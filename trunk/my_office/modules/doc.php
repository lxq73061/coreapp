<?php
/**
 * 文章模块
 * 
 * @version 1.2.1
 * @author Z <602000@gmail.com>
 */

/**
 * 导入(import)
 */
class_exists('core') or require_once 'core.php';

/**
 * 定义(define)
 */
class doc extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 文章列表
	 */
	final static public function browse() {

		// 数据消毒
		$get = array(
			'keyword' => isset ($_GET ['keyword']) ? $_GET ['keyword'] : '',
			'typeid'  => isset ($_GET ['typeid']) ? $_GET ['typeid'] : '',
			'order'  => isset ($_GET ['order']) ? $_GET ['order'] : '',
			'page'  => isset ($_GET ['page']) ? $_GET ['page'] : '',
			'limit'  => isset ($_GET ['limit']) ? $_GET ['limit'] : '10',
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}

		// 获取数据
		$where = array();
		$online = front::online();
		$where['user_id'] = $online->user_id;
		if (strlen($get['keyword'])>0){
			$where []=array(
			'title LIKE ?' => '%'.$get['keyword'].'%',
			'content LIKE ?' => '%'.$get['keyword'].'%',
			'keyword LIKE ?' => '%'.$get['keyword'].'%',
			'copyfrom LIKE ?' => '%'.$get['keyword'].'%');
		}
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
	
		switch ($get['order']) {
			case 'doc_id':
				$other = array('ORDER BY doc_id');
				break;
			case 'date':
				$other = array('ORDER BY update_date');
				break;
			case 'date2':
				$other = array('ORDER BY update_date DESC');
				break;
			case 'hit':
				$other = array('ORDER BY hit');
				break;
			case 'hit2':
				$other = array('ORDER BY hit DESC');
				break;
				
			default:
				$other = array('ORDER BY doc_id DESC');
				break;
		}
		$page = array('page'=>$get['page'],'size'=>$get['limit']);
		$other ['page'] = &$page;
		$docs = self::selects (null, null, $where, $other, __CLASS__);

		// 页面显示
		foreach (array('title') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		
		self::view (__CLASS__ . '.list.tpl', compact ('docs','get','page','query'));
	}
	
	/**
	 * 文章详细
	 */
	final static public function detail() {

		// 获取数据
		$doc = new self;
		$doc->doc_id = isset($_GET['doc_id']) ? $_GET['doc_id'] : null;
		if(! is_numeric($doc->doc_id) || ! $doc->select()) {
			$error = '该文章不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		$doc->hit++;//访问次数
		$doc->update ();
		// 页面显示
		$query = $_SERVER['QUERY_STRING'];
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('doc','query'));
	}
	
	/**
	 * 添加文章
	 */
	final static public function append() {
		$error = array ();

		$online = front::online();
		$time=time();
		// 数据消毒
		$post = array(
			'title' => isset ($_POST ['title']) ? $_POST ['title'] : '',
			'copyfrom' => isset ($_POST ['copyfrom']) ? $_POST ['copyfrom'] : '',
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',
			'keyword' => isset ($_POST ['keyword']) ? $_POST ['keyword'] : '',
			'keyword_auto' => isset ($_POST ['keyword_auto']) ? $_POST ['keyword_auto'] : '',
			'content' => isset ($_POST ['content']) ? $_POST ['content'] : '',
			'user_id' => $online->user_id,
			'hit' => 0,
			'create_date'=>date('Y-m-d',$time),
			'create_time'=>date('H:i:s',$time),		
			'update_date'=>date('Y-m-d',$time),
			'update_time'=>date('H:i:s',$time),		
			
		);


		if (get_magic_quotes_gpc()) {
			$post = array_map ('stripslashes', $post);
		}

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据验证
			$length = (strlen ($post ['title']) + mb_strlen ($post ['title'], 'UTF-8')) /2;
			if ($length < 3 || $length > 200 //3-200个字符
			) {
				$error ['title'] = '文章名至少3个字符,最多200个字符';
			} else {
				$count = self::selects('COUNT(*)', null, array('title'=>$post ['title']), null, array('column|table=doc'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['title'] = '文章名重复，请换一个文章名';
				}
			}

			if ($post ['typeid'] === 0 ) {
				$error ['typeid'] = '请选择文章分类';
			}
			//if (strlen ($post['keyword']) === 0) {
			//	$error ['keyword'] = '请填写姓名';
			//}

			if($post['keyword_auto']==1){
				$post['keyword'] = self::get_keywords(strip_tags($post['title'].$post['content']));
			}
			unset($post ['keyword_auto']);
			
			//$length = (strlen ($post ['content']) + mb_strlen ($post ['content'], 'UTF-8')) /2;
			//if ($length > 100) {
			//	$error ['content'] = '备注最多只能填写100个字符';
			//}
			if (! empty ($error)) {
				break;
			}

			// 数据入库
			$doc = new self;
			$doc ->doc_id = null;
			$doc ->struct ($post);
			$doc->insert ('','doc_id');
			header ('Location: ?go=doc&do=modify&doc_id='.$doc->doc_id);
			//header ('Location: ?go=doc&do=browse');
			
			return;

		}

		// 页面显示
		foreach (array('title','copyfrom','typeid','keyword','keyword_auto','content') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 修改文章
	 */
	final static public function modify() {
		$error = array ();

		// 获取数据
		$doc = new self;
		$doc->doc_id = isset($_GET['doc_id']) ? $_GET['doc_id'] : null;
		if(! is_numeric($doc->doc_id) || ! $doc->select()) {
			$error = '该文章不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($doc);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'title' => isset ($_POST ['title']) ? $_POST ['title'] : '',
			'copyfrom' => isset ($_POST ['copyfrom']) ? $_POST ['copyfrom'] : '',
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',
			'keyword' => isset ($_POST ['keyword']) ? $_POST ['keyword'] : '',
			'keyword_auto' => isset ($_POST ['keyword_auto']) ? $_POST ['keyword_auto'] : '',
			'content' => isset ($_POST ['content']) ? $_POST ['content'] : '',
			'update_date'=>date('Y-m-d',$time),
			'update_time'=>date('H:i:s',$time),		
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}

			// 数据验证
			$length = (strlen ($post ['title']) + mb_strlen ($post ['title'], 'UTF-8')) /2;
			if ($length < 3 || $length > 200 //3-200个字符
			) {
				$error ['title'] = '文章名至少3个字符,最多200个字符';
			}
			if ($post ['typeid'] === 0 ) {
				$error ['typeid'] = '请选择文章分类';
			}

			if($post['keyword_auto']==1){
				$post['keyword'] = self::get_keywords(strip_tags($post['title'].$post['content']));
			}
			unset($post ['keyword_auto']);
					
			if (! empty ($error)) {
				break;
			}

			// 数据入库

			$doc->struct ($post);
			$doc->update ();
			header ('Location: ?'.$_GET['query']);
			//header ('Location: ?go=doc&do=modify&doc_id='.$doc->doc_id);
			return;

		}

		// 页面显示s
		foreach (array('title','mobile','email','url','content') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		
		$query = $_SERVER['QUERY_STRING'];
			
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','query'));
	}
	
	/**
	 * 删除文章
	 */
	final static public function remove() {

		// 获取数据
		$doc = new self;
		$doc->doc_id = isset($_GET['doc_id']) ? $_GET['doc_id'] : null;
		if(! is_numeric($doc->doc_id) || ! $doc->select()) {
			$error = '该文章不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$doc->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 群删文章
	 */
	final static public function group_remove() {

		// 获取数据
		if(! isset($_POST['doc_id']) || !is_array($_POST['doc_id'])){
			$error = '该文章不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('doc_id'=>$_POST['doc_id']),null,__CLASS__);
		header ('Location: ?'.$_GET['query']);
	}
	
	final static public function get_keywords($contents){
		$rows = strip_tags($contents);
		$arr = array(' ',' ',"\s", "\r\n", "\n", "\r", "\t", ">", "\"", "\"");
		$qc_rows = str_replace($arr, '', $rows);
		if(strlen($qc_rows)>2400){
		$qc_rows = substr($qc_rows, '0', '2400');
		}
		$contents = urlencode($contents);
		//pecho(("http://keyword.discuz.com/related_kw.html?title=$contents&ics=gbk&ocs=gbk"));
		$data = @implode('', file("http://keyword.discuz.com/related_kw.html?title=$contents&ics=gbk&ocs=gbk"));
		preg_match_all("/<kw>(.*)A\[(.*)\]\](.*)><\/kw>/",$data, $out, PREG_SET_ORDER);
		for($i=0;$i<5;$i++){
			$key=$key.$out[$i][2];
			if($out[$i][2])$key=$key.",";
		} 
		return $key; 
	}
	/**
	 * 返回文章分类名称
	 */
	public function get_typeid() {
		$array = channel::get_channel();
		//pecho($array);
		return $array [$this->typeid]['name'];
	}
	
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>