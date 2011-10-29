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
class address extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		header("Location: ./?go=".__CLASS__."&do=browse");
		//front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 网址列表
	 */
	final static public function browse() {

		// 数据消毒
		$get = array(
			'name' => isset ($_GET ['name']) ? $_GET ['name'] : '',
			'typeid'  => isset ($_GET ['typeid']) ? $_GET ['typeid'] : '',
			'email'  => isset ($_GET ['email']) ? $_GET ['email'] : '',
			'qq'  => isset ($_GET ['qq']) ? $_GET ['qq'] : '',
			'msn'  => isset ($_GET ['msn']) ? $_GET ['msn'] : '',
			'mobile'  => isset ($_GET ['mobile']) ? $_GET ['mobile'] : '',
			'office_phone'  => isset ($_GET ['office_phone']) ? $_GET ['office_phone'] : '',
			'home_phone'  => isset ($_GET ['home_phone']) ? $_GET ['home_phone'] : '',
			'remarks'  => isset ($_GET ['remarks']) ? $_GET ['remarks'] : '',
			'order'  => isset ($_GET ['order']) ? $_GET ['order'] : '',
			'page'  => isset ($_GET ['page']) ? $_GET ['page'] : '',
			'keyword'  => isset ($_GET ['keyword']) ? $_GET ['keyword'] : '',
			'limit'  => isset ($_GET ['limit']) ? $_GET ['limit'] : '20',
		);
		if(IN_WAP)$get['limit']=10;
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}

		// 获取数据
		$where = array();
		$online = front::online();
		$where['user_id'] = $online->user_id;
		
		if (strlen($get['keyword'])>0){
			$where []=array(
			'name LIKE ?' => '%'.$get['keyword'].'%',
			'email LIKE ?' => '%'.$get['keyword'].'%',
			'qq LIKE ?' => '%'.$get['keyword'].'%',
			'msn LIKE ?' => '%'.$get['keyword'].'%',
			'mobile LIKE ?' => '%'.$get['keyword'].'%',
			'office_phone LIKE ?' => '%'.$get['keyword'].'%',
			'home_phone LIKE ?' => '%'.$get['keyword'].'%',
			'remarks LIKE ?' => '%'.$get['keyword'].'%',			
			);
			
		}
		if ($get['typeid']){
			$where ['typeid'] = (int)$get['typeid'];
		}
		switch ($get['order']) {
			case 'address_id':
				$other = array('ORDER BY address_id');
				break;
			case 'name':
				$other = array('ORDER BY name');
				break;
			case 'name2':
				$other = array('ORDER BY name DESC');
				break;
			default:
				$other = array('ORDER BY address_id DESC');
				break;
		}
		$page = array('page'=>$get['page'],'size'=>$get['limit']);
		$other ['page'] = &$page;
		$addresss = self::selects (null, null, $where, $other, __CLASS__);
		foreach($addresss as &$v)
			foreach($v as &$vv)    $vv = mb_strcut($vv,0,15,'UTF-8');
			
		// 页面显示
		foreach (array('name') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		front::view2 (__CLASS__ . '.list.tpl', compact ('addresss','get','page','query'));
	}
	
	/**
	 * 网址详细
	 */
	final static public function detail() {

		// 获取数据
		$address = new self;
		$address->address_id = isset($_GET['address_id']) ? $_GET['address_id'] : null;
		if(! is_numeric($address->address_id) || ! $address->select()) {
			$error = '该通讯名不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		
		$meta_title = $address->name;
		// 页面显示
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('address','meta_title'));
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
			'name' => isset ($_POST ['name']) ? $_POST ['name'] : '',
			/*'url' => isset ($_POST ['url']) ? $_POST ['url'] : '',*/
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',		
			/*'content' => isset ($_POST ['content']) ? $_POST ['content'] : '',*/
			'email' => isset ($_POST ['email']) ? $_POST ['email'] : '',
			'qq' => isset ($_POST ['qq']) ? $_POST ['qq'] : '',
			'msn' => isset ($_POST ['msn']) ? $_POST ['msn'] : '',
			'mobile' => isset ($_POST ['mobile']) ? $_POST ['mobile'] : '',
			'office_phone' => isset ($_POST ['office_phone']) ? $_POST ['office_phone'] : '',
			'home_phone' => isset ($_POST ['home_phone']) ? $_POST ['home_phone'] : '',
			'remarks' => isset ($_POST ['remarks']) ? $_POST ['remarks'] : '',
			'user_id' => $online->user_id,
//			'create_date'=>date('Y-m-d',$time),
//			'create_time'=>date('H:i:s',$time),	
//			'update_date'=>date('Y-m-d',$time),
//			'update_time'=>date('H:i:s',$time),		
			
		);


		if (get_magic_quotes_gpc()) {
			$post = array_map ('stripslashes', $post);
		}

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据验证
			$length = (strlen ($post ['name']) + mb_strlen ($post ['name'], 'UTF-8')) /2;
			if ($length < 3 || $length > 200 //3-200个字符
			) {
				$error ['name'] = '通讯名至少3个字符,最多200个字符';
			} else {
				$count = self::selects('COUNT(*)', null, array('name'=>$post ['name']), null, array('column|table=address'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['name'] = '通讯名重复，请检查是否重复记录';
				}
			}
			$count = self::selects('COUNT(*)', null, array('url'=>$post ['url']), null, array('column|table=address'=>'COUNT(*)'));
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
			$address = new self;
			$address ->address_id = null;
			$address ->struct ($post);
			$address->insert ();
				
			header ('Location: ?go=address&do=browse');
			return;

		}

		// 页面显示
		foreach (array('name',/*'url',*/'typeid','email','qq','msn','mobile','office_phone','home_phone','remarks'/*'content'*/) as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 修改网址
	 */
	final static public function modify() {
		$error = array ();

		// 获取数据
		$address = new self;
		$address->address_id = isset($_GET['address_id']) ? $_GET['address_id'] : null;
		if(! is_numeric($address->address_id) || ! $address->select()) {
			$error = '该通讯名不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($address);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'name' => isset ($_POST ['name']) ? $_POST ['name'] : '',
			/*'url' => isset ($_POST ['url']) ? $_POST ['url'] : '',
			'content' => isset ($_POST ['content']) ? $_POST ['content'] : '',*/
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',
			'email' => isset ($_POST ['email']) ? $_POST ['email'] : '',
			'qq' => isset ($_POST ['qq']) ? $_POST ['qq'] : '',
			'msn' => isset ($_POST ['msn']) ? $_POST ['msn'] : '',
			'mobile' => isset ($_POST ['mobile']) ? $_POST ['mobile'] : '',
			'office_phone' => isset ($_POST ['office_phone']) ? $_POST ['office_phone'] : '',
			'home_phone' => isset ($_POST ['home_phone']) ? $_POST ['home_phone'] : '',
			'remarks' => isset ($_POST ['remarks']) ? $_POST ['remarks'] : '',	
//			'update_date'=>date('Y-m-d',$time),
//			'update_time'=>date('H:i:s',$time),		
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}

			// 数据验证
			$length = (strlen ($post ['name']) + mb_strlen ($post ['name'], 'UTF-8')) /2;
			if ($length < 3 || $length > 200 //3-200个字符
			) {
				$error ['name'] = '通讯名至少3个字符,最多200个字符';
			}else{
				$count = self::selects('COUNT(*)', null, array('name'=>$post ['name'],'typeid'=>$post ['typeid'],'address_id<>?'=>$address->address_id), null, array('column|table=address'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['name'] = '通讯名重复';
				}
				
				
			}
			$count = self::selects('COUNT(*)', null, array('url'=>$post ['url'],'address_id<>?'=>$address->address_id), null, array('column|table=address'=>'COUNT(*)'));
			if ($count > 0) {
				$error ['url'] = '通讯名重复';
			}

	
			if (! empty ($error)) {
				break;
			}

			$address->struct ($post);
			$address->update ();
			header ('Location: ?'.$_GET['query']);
			return;

		}
		$meta_title = $address->name;
		// 页面显示
		foreach (array('name','mobile','email','typeid','qq','msn','office_phone','home_phone','remarks'/*'url','content'*/) as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','meta_title'));
	}
	
	/**
	 * 删除网址
	 */
	final static public function remove() {

		// 获取数据
		$address = new self;
		$address->address_id = isset($_GET['address_id']) ? $_GET['address_id'] : null;
		if(! is_numeric($address->address_id) || ! $address->select()) {
			$error = '该通讯不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$address->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 群删网址
	 */
	final static public function group_remove() {

		// 获取数据
		if(! isset($_POST['address_id']) || !is_array($_POST['address_id'])){
			$error = '该通讯不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('address_id'=>$_POST['address_id']),null,__CLASS__);
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 返回网址分类名称
	 */
	public function get_typeid() {
	
		return channel::get_one($this->typeid,'name');
	}
	
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>