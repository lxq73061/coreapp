<?php
/**
 * 网址模块
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
class site extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 网址列表
	 */
	final static public function browse() {

		// 数据消毒
		$get = array(
			'title' => isset ($_GET ['title']) ? $_GET ['title'] : '',
			'typeid'  => isset ($_GET ['typeid']) ? $_GET ['typeid'] : '',
			'order'  => isset ($_GET ['order']) ? $_GET ['order'] : '',
			'page'  => isset ($_GET ['page']) ? $_GET ['page'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}

		// 获取数据
		$where = array();
		$online = front::online();
		$where['user_id'] = $online->user_id;

		if (strlen($get['title'])>0){
			$where ['title LIKE ?'] = '%'.$get['title'].'%';
		}
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
		switch ($get['order']) {
			case 'site_id':
				$other = array('ORDER BY site_id');
				break;
			case 'title':
				$other = array('ORDER BY title');
				break;
			case 'title2':
				$other = array('ORDER BY title DESC');
				break;
			default:
				$other = array('ORDER BY site_id DESC');
				break;
		}
		$page = array('page'=>$get['page'],'size'=>10);
		$other ['page'] = &$page;
		$sites = self::selects (null, null, $where, $other, __CLASS__);

		// 页面显示
		foreach (array('title') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		self::view (__CLASS__ . '.list.tpl', compact ('sites','get','page','query'));
	}
	
	/**
	 * 网址详细
	 */
	final static public function detail() {

		// 获取数据
		$site = new self;
		$site->site_id = isset($_GET['site_id']) ? $_GET['site_id'] : null;
		if(! is_numeric($site->site_id) || ! $site->select()) {
			$error = '该网址不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		

		// 页面显示
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('site'));
	}
	
	/**
	 * 添加网址
	 */
	final static public function append() {
		$error = array ();

		$online = front::online();
		$time=time();
		// 数据消毒
		$post = array(
			'title' => isset ($_POST ['title']) ? $_POST ['title'] : '',
			'url' => isset ($_POST ['url']) ? $_POST ['url'] : '',
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',		
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
			$length = (strlen ($post ['title']) + mb_strlen ($post ['title'], 'UTF-8')) /2;
			if ($length < 3 || $length > 200 //3-200个字符
			) {
				$error ['title'] = '网站名至少3个字符,最多200个字符';
			} else {
				$count = self::selects('COUNT(*)', null, array('title'=>$post ['title']), null, array('column|table=site'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['title'] = '网站名重复，请检查是否重复记录';
				}
			}
			$count = self::selects('COUNT(*)', null, array('url'=>$post ['url']), null, array('column|table=site'=>'COUNT(*)'));
			if ($count > 0) {
				$error ['url'] = 'URL重复，请检查是否重复记录';
			}

			if ($post ['typeid'] === 0 ) {
				$error ['typeid'] = '请选择分类';
			}
	
			//$length = (strlen ($post ['content']) + mb_strlen ($post ['content'], 'UTF-8')) /2;
			//if ($length > 100) {
			//	$error ['content'] = '备注最多只能填写100个字符';
			//}
			if (! empty ($error)) {
				break;
			}

			// 数据入库
			$site = new self;
			$site ->site_id = null;
			$site ->struct ($post);
			$site->insert ();
				
			header ('Location: ?go=site&do=browse');
			return;

		}

		// 页面显示
		foreach (array('title','url','typeid','content') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 修改网址
	 */
	final static public function modify() {
		$error = array ();

		// 获取数据
		$site = new self;
		$site->site_id = isset($_GET['site_id']) ? $_GET['site_id'] : null;
		if(! is_numeric($site->site_id) || ! $site->select()) {
			$error = '该网址不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($site);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'title' => isset ($_POST ['title']) ? $_POST ['title'] : '',
			'url' => isset ($_POST ['url']) ? $_POST ['url'] : '',
			'content' => isset ($_POST ['content']) ? $_POST ['content'] : '',
			
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',		
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
				$error ['title'] = '网站名至少3个字符,最多200个字符';
			}else{
				$count = self::selects('COUNT(*)', null, array('title'=>$post ['title'],'typeid'=>$post ['typeid'],'site_id<>?'=>$site->site_id), null, array('column|table=site'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['title'] = '网站名重复';
				}
				
				
			}
			$count = self::selects('COUNT(*)', null, array('url'=>$post ['url'],'site_id<>?'=>$site->site_id), null, array('column|table=site'=>'COUNT(*)'));
			if ($count > 0) {
				$error ['url'] = '网址重复';
			}
			if ($post ['typeid'] === 0 ) {
				$error ['typeid'] = '请选择网址分类';
			}
	
			if (! empty ($error)) {
				break;
			}

			$site->struct ($post);
			$site->update ();
			header ('Location: ?'.$_GET['query']);
			return;

		}

		// 页面显示
		foreach (array('title','mobile','email','url','content') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 删除网址
	 */
	final static public function remove() {

		// 获取数据
		$site = new self;
		$site->site_id = isset($_GET['site_id']) ? $_GET['site_id'] : null;
		if(! is_numeric($site->site_id) || ! $site->select()) {
			$error = '该网址不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$site->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 群删网址
	 */
	final static public function group_remove() {

		// 获取数据
		if(! isset($_POST['site_id']) || !is_array($_POST['site_id'])){
			$error = '该网址不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('site_id'=>$_POST['site_id']),null,__CLASS__);
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 返回网址分类名称
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