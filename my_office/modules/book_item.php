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

 
class book_item extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
			return self::browse();
	}
	
	/**
	 * 日志列表
	 */
	final static public function browse() {

		// 数据消毒
		$get = array(
			'from' => isset ($_GET ['from']) ? $_GET ['from'] : date('Y-m-d',strtotime('-1 month')),
			'to' => isset ($_GET ['to']) ? $_GET ['to'] : date('Y-m-d',strtotime('-0 day')),
			'ccy' => isset ($_GET ['ccy']) ? $_GET ['ccy'] : 'CNY',
			'page' => isset ($_GET ['page']) ? $_GET ['page'] : '',
		);
		
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}
		
		$online = front::online();
		//self::update_statement_net($online->user_id,0,$get['ccy']);
		// 获取数据
		$where = array();
	
	

		$where['user_id'] = $online->user_id;
		$other=array('ORDER BY book_item_id ASC');
		$page = array('page'=>$get['page'],'size'=>20);
		$other ['page'] = &$page;	
		$books = self::selects (null, null, $where, $other, __CLASS__);
		$item_types = self::get_items();
		$query = $_SERVER['QUERY_STRING'];
		front::view2 (__CLASS__ . '.list.tpl', compact ('books','get','page','query','item_types'));//得到数组所有的变量值
	}
	
	/**
	 * 日志详细
	 */
	final static public function detail() {

		// 获取数据
		$book = new self;
		$book->book_item_id = isset($_GET['book_item_id']) ? $_GET['book_item_id'] : null;
		if(! is_numeric($book->book_item_id) || ! $book->select()) {
			$error = '该信息不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		return;

		// 页面显示
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('book'));
	}
	
	/**
	 * 添加日志
	 */
	final static public function append() {
	
		$item_types = self::get_items();
		$error = array ();

		$online = front::online();
		$time=time();
		// 数据消毒
	
		$online = front::online();
	
		

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {
			
			$post = array(
			'item' => isset ($_POST ['item']) ? $_POST ['item'] : '',
			'info' => isset ($_POST ['info']) ? $_POST ['info'] : '',
			'user_id' => $online->user_id	
			);
			
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}


			if (empty($post ['info'])) {//account=content
				$post ['info'] = substr($post ['info'],0,15);
			}
			

			if (! empty ($error)) {
				break;
			}

			// 数据入库
			$book = new self;
			$book ->book_item_id = null;
			$book ->struct ($post);
			$book_item_id = $book->insert ('','book_item_id');
			if($book_item_id<1){
				$error ['create_date'] = 'add fail';
				break;
			}

			header ('Location: ?go=book_item&do=browse');
			return;

		}
		if(!$post['create_date'])$post['create_date'] = date('Y-m-d');
		if(!$post['create_time'])$post['create_time'] = '12:00:00';//date('H:i:s');
		if(!$post['item'])$post['item'] = 3;

		// 页面显示
		foreach (array('item','item_txt','typeid','remark','ccy','net','otype','amount') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','item_txts','otype','item_types'));
	}
	
	/**
	 * 修改账本
	 */
	final static public function modify() {
		$item_types = self::get_items();
		
		$error = array ();
		// 获取数据
		$book = new self;
		$book->book_item_id = isset($_GET['book_item_id']) ? $_GET['book_item_id'] : null;
		if(! is_numeric($book->book_item_id) || ! $book->select()) {
			$error = '该信息不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($book);
	
		$online = front::online();
		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'item' => isset ($_POST ['item']) ? $_POST ['item'] : '',
			'info' => isset ($_POST ['info']) ? $_POST ['info'] : '',
			'user_id' => $online->user_id	
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}
			
			// 数据验证
			if (empty($post ['info'])) {
				$post ['info'] = substr($post ['info'],0,255);
			}
	
			if (! empty ($error)) {
				break;
			}
			

			$book->struct ($post);
			$book->update ();
			$online = front::online();
			header ('Location: ?'.$_GET['query']);
			return;

		}
		

		// 页面显示
		foreach (array('info') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error', 'item_types'));
	}
	
	/**
	 * 删除日志
	 */
	final static public function remove() {

		// 获取数据
		$book = new self;
		$book->book_item_id = isset($_GET['book_item_id']) ? $_GET['book_item_id'] : null;
		if(! is_numeric($book->book_item_id) || ! $book->select()) {
			$error = '该日志不存在';
			front::view2 ('error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$book->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 群删日志
	 */
	final static public function group_remove() {

		// 获取数据
		if(! isset($_POST['book_item_id']) || !is_array($_POST['book_item_id'])){
			$error = '该日志不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('book_item_id'=>$_POST['book_item_id']),null,__CLASS__);
		header ('Location: ?'.$_GET['query']);
	}
	
	/**
	 * 返回日志分类名称
	 */
	public function get_items() {
		 $items=array('1'=>'银行','2'=>'虚拟账户','3'=>'现金','4'=>'负债' ,'5'=>'债权');
		return $items;
	}
	
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>