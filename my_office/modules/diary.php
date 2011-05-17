<?php
/**
 * 日志模块
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
class diary extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		self::browse();
		//front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 日志列表
	 */
	final static public function browse() {

		// 数据消毒
		$get = array(
			'keyword' => isset ($_GET ['keyword']) ? $_GET ['keyword'] : '',
			'typeid'  => isset ($_GET ['typeid']) ? $_GET ['typeid'] : '',
			'order'  => isset ($_GET ['order']) ? $_GET ['order'] : '',
			'page'  => isset ($_GET ['page']) ? $_GET ['page'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}
		$online = front::online();
		// 获取数据
		$where = array();
		$where['user_id'] = $online->user_id;
		if (strlen($get['keyword'])>0){
			$where []=array(
			'title LIKE ?' => '%'.$get['keyword'].'%',
			'content LIKE ?' => '%'.$get['keyword'].'%');
		}
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
		switch ($get['order']) {
			case 'diary_id':
				$other = array('ORDER BY diary_id');
				break;
			case 'diary_id2':
				$other = array('ORDER BY diary_id DESC');
				break;
			default:
				$other = array('ORDER BY diary_id DESC');
				break;
		}
		$page = array('page'=>$get['page'],'size'=>10);
		$other ['page'] = &$page;
		$diarys = self::selects (null, null, $where, $other, __CLASS__);
		foreach($diarys as &$v){
			$v->title = strip_tags($v->title);
			$v->content = strip_tags($v->content);
			
		}
		// 页面显示
		foreach (array('title') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		front::view2 (__CLASS__ . '.list.tpl', compact ('diarys','get','page','query'));
	}
	
	/**
	 * 日志详细
	 */
	final static public function detail() {

		// 获取数据
		$diary = new self;
		$diary->diary_id = isset($_GET['diary_id']) ? $_GET['diary_id'] : null;
		if(! is_numeric($diary->diary_id) || ! $diary->select()) {
			$error = '该日志不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		

		// 页面显示
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('diary'));
	}
	
	/**
	 * 添加日志
	 */
	final static public function append() {
		$error = array ();

		$online = front::online();
		$time=time();
		// 数据消毒
		$post = array(
			'diary_date' => isset ($_POST ['diary_date']) ? $_POST ['diary_date'] : '',
			'title' => isset ($_POST ['title']) ? $_POST ['title'] : '',
			'mood' => isset ($_POST ['mood']) ? $_POST ['mood'] : '',
			'weather' => isset ($_POST ['weather']) ? $_POST ['weather'] : '',
			'content' => isset ($_POST ['content']) ? $_POST ['content'] : '',
			'user_id' => $online->user_id,
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
			
			if (empty($post ['diary_date'])) {//title=content
				$post ['diary_date'] = date('Y-m-d');
			}
			
			if (empty($post ['title'])) {//title=content
				$post ['title'] = substr(strip_tags($post['content']),0,15);
			}else{
				$post ['title'] = strip_tags($post['title']);
			}
			
			if(empty($post['title'])){
				$error ['title'] = '标题不能为空';
			}
			if (! empty ($error)) {
				break;
			}

			// 数据入库
			$diary = new self;
			$diary ->diary_id = null;
			$diary ->struct ($post);
			$diary->insert ();
				
			header ('Location: ?go=diary&do=browse');
			return;

		}

		// 页面显示
		foreach (array('title','url','typeid','content') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 修改日志
	 */
	final static public function modify() {
		$error = array ();

		// 获取数据
		$diary = new self;
		$diary->diary_id = isset($_GET['diary_id']) ? $_GET['diary_id'] : null;
		if(! is_numeric($diary->diary_id) || ! $diary->select()) {
			$error = '该日志不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($diary);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'title' => isset ($_POST ['title']) ? $_POST ['title'] : '',
			'diary_date' => isset ($_POST ['diary_date']) ? $_POST ['diary_date'] : '',
			'mood' => isset ($_POST ['mood']) ? $_POST ['mood'] : '',
			'weather' => isset ($_POST ['weather']) ? $_POST ['weather'] : '',
			'content' => isset ($_POST ['content']) ? $_POST ['content'] : '',
			'update_date'=>date('Y-m-d',$time),
			'update_time'=>date('H:i:s',$time),		
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}
			
			if (empty($post ['diary_date'])) {//title=content
				$post ['diary_date'] = date('Y-m-d');
			}elseif(strtotime($post ['diary_date'])==0){
				$error ['diary_date'] = '日期不正确';
			}
			// 数据验证
			if (empty($post ['title'])) {
				$post ['title'] = substr(strip_tags($post['content']),0,15);
			}else{
				$post ['title'] = strip_tags($post['title']);
			}
			if(empty($post['title'])){
				$error ['title'] = '标题不能为空';
			}

			if (! empty ($error)) {
				break;
			}

			$diary->struct ($post);
			$diary->update ();
			header ('Location: ?'.$_GET['query']);
			return;

		}

		// 页面显示
		foreach (array('title','mobile','email','url','content') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 删除日志
	 */
	final static public function remove() {

		// 获取数据
		$diary = new self;
		$diary->diary_id = isset($_GET['diary_id']) ? $_GET['diary_id'] : null;
		if(! is_numeric($diary->diary_id) || ! $diary->select()) {
			$error = '该日志不存在';
			front::view2 ('error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$diary->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 群删日志
	 */
	final static public function group_remove() {

		// 获取数据
		if(! isset($_POST['diary_id']) || !is_array($_POST['diary_id'])){
			$error = '该日志不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('diary_id'=>$_POST['diary_id']),null,__CLASS__);
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 返回日志分类名称
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