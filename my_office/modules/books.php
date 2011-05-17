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
class books extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 网址列表
	 */
	final static public function browse() {

		// 数据消毒
		$get = array(
			'account' => isset ($_GET ['account']) ? $_GET ['account'] : '',
			'typeid'  => isset ($_GET ['typeid']) ? $_GET ['typeid'] : '',
			'use' => isset ($_GET ['use']) ? $_GET ['use'] : '',
			'remarks' => isset ($_GET ['remarks']) ? $_GET ['remarks'] : '',
			'currency' => isset ($_GET ['currency']) ? $_GET ['currency'] : '',
			'income' => isset ($_GET ['income']) ? $_GET ['income'] : '',
			'expenditure' => isset ($_GET ['expenditure']) ? $_GET ['expenditure'] : '',
			'balance' => isset ($_GET ['balance']) ? $_GET ['balance'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}

		// 获取数据
		$where = array();
		if (strlen($get['account'])>0){
			$where ['account LIKE ?'] = '%'.$get['account'].'%';
		}
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
		switch ($get['order']) {
			case 'books_id':
				$other = array('ORDER BY books_id');
				break;
			case 'account':
				$other = array('ORDER BY account');
				break;
			case 'account2':
				$other = array('ORDER BY account DESC');
				break;
			default:
				$other = array('ORDER BY books_id DESC');
				break;
		}
		$page = array('page'=>$get['page'],'size'=>10);
		$other ['page'] = &$page;
		$bookss = self::selects (null, null, $where, $other, __CLASS__);

		// 页面显示
		foreach (array('name') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		front::view2 (__CLASS__ . '.list.tpl', compact ('bookss','get','page','query'));
	}
	
	/**
	 * 网址详细
	 */
	final static public function detail() {

		// 获取数据
		$books = new self;
		$books->books_id = isset($_GET['books_id']) ? $_GET['books_id'] : null;
		if(! is_numeric($books->books_id) || ! $books->select()) {
			$error = '该通讯名不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		

		// 页面显示
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('books'));
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
			'books_date' => isset ($_POST ['books_date']) ? $_POST ['books_date'] : '',
			'account' => isset ($_POST ['account']) ? $_POST ['account'] : '',
			'use' => isset ($_POST ['use']) ? $_POST ['use'] : '',
			'remarks' => isset ($_POST ['remarks']) ? $_POST ['remarks'] : '',
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',		
			'currency' => isset ($_POST ['currency']) ? $_POST ['currency'] : '',
			'income' => isset ($_POST ['income']) ? $_POST ['income'] : '',
			'expenditure' => isset ($_POST ['expenditure']) ? $_POST ['expenditure'] : '',
			'balance' => isset ($_POST ['balance']) ? $_POST ['balance'] : '',
			/*'user_id' => $online->user_id,*/
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
			$length = (strlen ($post ['account']) + mb_strlen ($post ['account'], 'UTF-8')) /2;
			if ($length < 3 || $length > 200 //3-200个字符

			) {
				$error ['account'] = '通讯名至少3个字符,最多200个字符';
			} else {
				$count = self::selects('COUNT(*)', null, array('account'=>$post ['account']), null, array('column|table=books'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['account'] = '通讯名重复，请检查是否重复记录';
				}
			}
			$count = self::selects('COUNT(*)', null, array('url'=>$post ['url']), null, array('column|table=books'=>'COUNT(*)'));
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
			$books = new self;
			$books ->books_id = null;
			$books ->struct ($post);
			$books->insert ();
				
			header ('Location: ?go=books&do=browse');
			return;

		}

		// 页面显示
		foreach (array('account','use','typeid','remarks','currency','income','expenditure','balance'/*'content'*/) as $value) {
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
		$books = new self;
		$books->books_id = isset($_GET['books_id']) ? $_GET['books_id'] : null;
		if(! is_numeric($books->books_id) || ! $books->select()) {
			$error = '该通讯名不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($books);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'account' => isset ($_POST ['account']) ? $_POST ['account'] : '',
			'books_date' => isset ($_POST ['books_date']) ? $_POST ['books_date'] : '',
			'use' => isset ($_POST ['use']) ? $_POST ['use'] : '',
			'remarks' => isset ($_POST ['remarks']) ? $_POST ['remarks'] : '',
			
			'currency' => isset ($_POST ['currency']) ? $_POST ['currency'] : '',
			'income' => isset ($_POST ['income']) ? $_POST ['income'] : '',
			'expenditure' => isset ($_POST ['expenditure']) ? $_POST ['expenditure'] : '',
			'balance' => isset ($_POST ['balance']) ? $_POST ['balance'] : '',	
			'update_date'=>date('Y-m-d',$time),
			'update_time'=>date('H:i:s',$time),		
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}

			// 数据验证
			$length = (strlen ($post ['account']) + mb_strlen ($post ['account'], 'UTF-8')) /2;
			if ($length < 3 || $length > 200 //3-200个字符
			) {
				$error ['account'] = '通讯名至少3个字符,最多200个字符';
			}else{
				$count = self::selects('COUNT(*)', null, array('account'=>$post ['account'],'typeid'=>$post ['typeid'],'books_id<>?'=>$books->books_id), null, array('column|table=books'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['name'] = '通讯名重复';
				}
				
				
			}
			$count = self::selects('COUNT(*)', null, array('url'=>$post ['url'],'books_id<>?'=>$books->books_id), null, array('column|table=books'=>'COUNT(*)'));
			if ($count > 0) {
				$error ['url'] = '通讯名重复';
			}
			if ($post ['typeid'] === 0 ) {
				$error ['typeid'] = '请选择通讯分类';
			}
	
			if (! empty ($error)) {
				break;
			}

			$books->struct ($post);
			$books->update ();
			header ('Location: ?'.$_GET['query']);
			return;

		}

		// 页面显示
		foreach (array('account','use','typeid','remarks','currency','income','expenditure','balance') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 删除网址
	 */
	final static public function remove() {

		// 获取数据
		$books = new self;
		$books->books_id = isset($_GET['books_id']) ? $_GET['books_id'] : null;
		if(! is_numeric($books->books_id) || ! $books->select()) {
			$error = '该通讯不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$books->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 群删网址
	 */
	final static public function group_remove() {

		// 获取数据
		if(! isset($_POST['books_id']) || !is_array($_POST['books_id'])){
			$error = '该通讯不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('books_id'=>$_POST['books_id']),null,__CLASS__);
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