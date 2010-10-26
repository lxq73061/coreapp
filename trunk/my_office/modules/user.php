<?php
/**
 * 用户模块
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
class user extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 用户列表
	 */
	final static public function browse() {

		// 数据消毒
		$get = array(
			'username' => isset ($_GET ['username']) ? $_GET ['username'] : '',
			'grade'  => isset ($_GET ['grade']) ? $_GET ['grade'] : '',
			'order'  => isset ($_GET ['order']) ? $_GET ['order'] : '',
			'page'  => isset ($_GET ['page']) ? $_GET ['page'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}

		// 获取数据
		$where = array();
		if (strlen($get['username'])>0){
			$where ['username LIKE ?'] = '%'.$get['username'].'%';
		}
		if (strlen($get['grade'])>0){
			$where ['grade'] = (int)$get['grade'];
		}
		switch ($get['order']) {
			case 'user_id':
				$other = array('ORDER BY user_id');
				break;
			case 'username':
				$other = array('ORDER BY username');
				break;
			case 'username2':
				$other = array('ORDER BY username DESC');
				break;
			default:
				$other = array('ORDER BY user_id DESC');
				break;
		}
		$page = array('page'=>$get['page'],'size'=>10);
		$other ['page'] = &$page;
		$users = self::selects (null, null, $where, $other, __CLASS__);

		// 页面显示
		foreach (array('username') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		self::view (__CLASS__ . '.list.tpl', compact ('users','get','page','query'));
	}
	
	/**
	 * 用户详细
	 */
	final static public function detail() {

		// 获取数据
		$user = new self;
		$user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
		if(! is_numeric($user->user_id) || ! $user->select()) {
			$error = '该用户不存在';
			self::view (__CLASS__ . '.error.tpl', compact ('error'));
			return;
		}

		// 页面显示
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('user'));
	}
	
	/**
	 * 添加用户
	 */
	final static public function append() {
		$error = array ();

		// 数据消毒
		$post = array(
			'username' => isset ($_POST ['username']) ? $_POST ['username'] : '',
			'password' => isset ($_POST ['password']) ? $_POST ['password'] : '',
			'grade'  => isset ($_POST ['grade']) ? $_POST ['grade'] : '',
			'name' => isset ($_POST ['name']) ? $_POST ['name'] : '',
			'gender' => isset ($_POST ['gender']) ? $_POST ['gender'] : '',
			'mobile' => isset ($_POST ['mobile']) ? $_POST ['mobile'] : '',
			'email' => isset ($_POST ['email']) ? $_POST ['email'] : '',
			'url' => isset ($_POST ['url']) ? $_POST ['url'] : '',
			'remark' => isset ($_POST ['remark']) ? $_POST ['remark'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$post = array_map ('stripslashes', $post);
		}

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据验证
			$length = (strlen ($post ['username']) + mb_strlen ($post ['username'], 'UTF-8')) /2;
			if ($length < 3 || $length > 16 //3-16个字符
				|| preg_match ('/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]+$/u',$post ['username']) === 0 //英文字母、汉字、数字、下划线
				|| preg_match ('/^[0-9]+$/', $post ['username']) === 1 //不能全部是数字
				|| preg_match ('/^_|_$/', $post ['username']) === 1 //且下划线不能作为起始和结尾字符
			) {
				$error ['username'] = '请正确填写用户名';
			} else {
				$count = self::selects('COUNT(*)', null, array('username'=>$post ['username']), null, array('column|table=user'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['username'] = '用户名重复，请换一个用户名';
				}
			}
			if (strlen ($post ['password']) < 4 || strlen ($post ['password']) > 16 //4-16个字符
				|| preg_match ('/^[\x21-\x9e]+$/',$post ['password']) === 0 //，英文字母、数字、下划线、半角符号
			) {
				$error ['password'] = '请正确填写密码';
			}
			if (preg_match ('/^[1-3]$/i',$post ['grade']) === 0 ) {
				$error ['grade'] = '请选择级别';
			}
			if (strlen ($post['name']) === 0) {
				$error ['name'] = '请填写姓名';
			}
			if (preg_match ('/^[1-2]$/i',$post ['gender']) === 0 ) {
				$error ['gender'] = '请选择性别';
			}
			if (strlen ($post ['mobile']) > 0 && preg_match ('/^1[0-9]{10}$/i',$post ['mobile']) === 0) {
				$error ['mobile'] = '请正确填写手机号';
			}
			if (strlen ($post ['email']) > 0 && ! filter_var ($post ['email'], FILTER_VALIDATE_EMAIL)) {
				$error ['email'] = '请正确填写邮箱';
			}
			if (strlen ($post ['url']) > 0 && ! filter_var ($post ['url'], FILTER_VALIDATE_URL)) {
				$error ['url'] = '请正确填写网址';
			}
			$length = (strlen ($post ['remark']) + mb_strlen ($post ['remark'], 'UTF-8')) /2;
			if ($length > 100) {
				$error ['remark'] = '备注最多只能填写100个字符';
			}
			if (! empty ($error)) {
				break;
			}

			// 数据入库
			$user = new self;
			$user->user_id = null;
			$user->struct ($post);
			$user->insert ();
			header ('Location: ?go=user&do=browse');
			return;

		}

		// 页面显示
		foreach (array('username','mobile','email','url','remark') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 修改用户
	 */
	final static public function modify() {
		$error = array ();

		// 获取数据
		$user = new self;
		$user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
		if(! is_numeric($user->user_id) || ! $user->select()) {
			$error = '该用户不存在';
			self::view (__CLASS__ . '.error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($user);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$post = array(
				'username' => isset ($_POST ['username']) ? $_POST ['username'] : '',
				'password' => isset ($_POST ['password']) ? $_POST ['password'] : '',
				'grade'  => isset ($_POST ['grade']) ? $_POST ['grade'] : '',
				'name' => isset ($_POST ['name']) ? $_POST ['name'] : '',
				'gender' => isset ($_POST ['gender']) ? $_POST ['gender'] : '',
				'mobile' => isset ($_POST ['mobile']) ? $_POST ['mobile'] : '',
				'email' => isset ($_POST ['email']) ? $_POST ['email'] : '',
				'url' => isset ($_POST ['url']) ? $_POST ['url'] : '',
				'remark' => isset ($_POST ['remark']) ? $_POST ['remark'] : '',
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}

			// 数据验证
			$length = (strlen ($post ['username']) + mb_strlen ($post ['username'], 'UTF-8')) /2;
			if ($length < 3 || $length > 16 //3-16个字符
				|| preg_match ('/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]+$/u',$post ['username']) === 0 //英文字母、汉字、数字、下划线
				|| preg_match ('/^[0-9]+$/', $post ['username']) === 1 //不能全部是数字
				|| preg_match ('/^_|_$/', $post ['username']) === 1 //且下划线不能作为起始和结尾字符
			) {
				$error ['username'] = '请正确填写用户名';
			} else {
				$count = self::selects('COUNT(*)', null, array('username'=>$post ['username'],'user_id<>?'=>$user->user_id), null, array('column|table=user'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['username'] = '用户名重复，请换一个用户名';
				}
			}
			if (strlen ($post ['password']) > 0
				&& (strlen ($post ['password']) < 4 || strlen ($post ['password']) > 16 //4-16个字符
				|| preg_match ('/^[\x21-\x9e]+$/',$post ['password']) === 0 //，英文字母、数字、下划线、半角符号
			)) {
				$error ['password'] = '请正确填写密码';
			}
			if (preg_match ('/^[1-3]$/i',$post ['grade']) === 0 ) {
				$error ['grade'] = '请选择级别';
			}
			if (strlen ($post['name']) === 0) {
				$error ['name'] = '请填写姓名';
			}
			if (preg_match ('/^[1-2]$/i',$post ['gender']) === 0 ) {
				$error ['gender'] = '请选择性别';
			}
			if (strlen ($post ['mobile']) > 0 && preg_match ('/^1[0-9]{10}$/i',$post ['mobile']) === 0) {
				$error ['mobile'] = '请正确填写手机号';
			}
			if (strlen ($post ['email']) > 0 && ! filter_var ($post ['email'], FILTER_VALIDATE_EMAIL)) {
				$error ['email'] = '请正确填写邮箱';
			}
			if (strlen ($post ['url']) > 0 && ! filter_var ($post ['url'], FILTER_VALIDATE_URL)) {
				$error ['url'] = '请正确填写网址';
			}
			$length = (strlen ($post ['remark']) + mb_strlen ($post ['remark'], 'UTF-8')) /2;
			if ($length > 100) {
				$error ['remark'] = '备注最多只能填写100个字符';
			}
			if (! empty ($error)) {
				break;
			}

			// 数据入库
			if (strlen ($post ['password']) === 0) {
				unset($post ['password']);
			}
			$user->struct ($post);
			$user->update ();
			header ('Location: ?'.$_GET['query']);
			return;

		}

		// 页面显示
		foreach (array('username','mobile','email','url','remark') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 删除用户
	 */
	final static public function remove() {

		// 获取数据
		$user = new self;
		$user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
		if(! is_numeric($user->user_id) || ! $user->select()) {
			$error = '该用户不存在';
			self::view (__CLASS__ . '.error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$user->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 群删用户
	 */
	final static public function group_remove() {

		// 获取数据
		if(! isset($_POST['user_id']) || !is_array($_POST['user_id'])){
			$error = '该用户不存在';
			self::view (__CLASS__ . '.error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('user_id'=>$_POST['user_id']),null,__CLASS__);
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 返回等级名称
	 */
	public function get_grade() {
		$array = array (
			1 => '超级管理员',
			2 => '管理员',
			3 => '普通用户',
		);
		return $array [$this->grade];
	}
	
	/**
	 * 返回性别名称
	 */
	public function get_gender() {
		$array = array (
			1 => '男',
			2 => '女',
		);
		return $array [$this->gender];
	}
	
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>