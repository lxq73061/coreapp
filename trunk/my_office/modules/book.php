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

 
class book extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
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
	
		$where ['create_date >=?'] = $get['from'];
		$where ['create_date <=?'] = $get['to'];
		$where ['ccy'] = $get['ccy'];	
		

		$where['user_id'] = $online->user_id;
		$other=array('ORDER BY create_date ASC,create_time ASC,book_id ASC');
		$page = array('page'=>$get['page'],'size'=>20);
		$other ['page'] = &$page;	
		$books = self::selects (null, null, $where, $other, __CLASS__);
		

		$total =self::selects ('COUNT(*)',null,array('user_id'=>$online->user_id,'ccy'=>$get['ccy']),null,array('column|table=book'=>'COUNT(*)'));//得到账目总笔数
		$amount =self::selects ('SUM(amount)',null,array('user_id'=>$online->user_id,'ccy'=>$get['ccy']),null,array('column|table=book'=>'SUM(amount)'));//得到账目余额
		

		$total_amount =self::selects ('sum(amount),otype',null,$where,array('GROUP BY otype'),array('otype','column|table=book'=>'sum(amount)'));//得到支出金额合计
		
		$total_count =self::selects ('COUNT(*),otype',null,$where,array('GROUP BY otype'),array('otype','column|table=book'=>'COUNT(*)'));


		$totals=array(); //运用数组方式
		$totals['total']=$total;//得到账目总笔数值
		$totals['amount']=$amount;//得到资金余额值
		
		$totals['out_amount']=$total_amount['OUT'];//得到支出金额合计值
		$totals['in_amount']=$total_amount['IN'];//得到收入金额合计值
		$totals['total_out']=$total_count['OUT'];//得到支出交易笔数值
		$totals['total_in']=$total_count['IN'];//得到收入交易笔数值

		//PECHO($totals);
		
		// 页面显示
		foreach (array('item') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		front::view2 (__CLASS__ . '.list.tpl', compact ('books','get','page','query','total_items','totals'));//得到数组所有的变量值
	}
	
	/**
	 * 日志详细
	 */
	final static public function detail() {

		// 获取数据
		$book = new self;
		$book->book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;
		if(! is_numeric($book->book_id) || ! $book->select()) {
			$error = '该日志不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		

		// 页面显示
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('book'));
	}
	
	/**
	 * 添加日志
	 */
	final static public function append() {
	//pecho($_POST);
		$error = array ();

		$online = front::online();
		$time=time();
		// 数据消毒
	
		$online = front::online();
		//item_txts
	     $channels = self::selects('channel_', null, array('user_id'=>$online->user_id), array('ORDER BY sort ASC,channel_id DESC'),array('channel_id','assoc|table=channel'=>null));
		$item_txts = self::selects('item_txt', null, array('user_id'=>$online->user_id), array('GROUP BY item_txt'), array(null,'column|table=book'=>'item_txt'));	
		//$item_txts = self::selects('item_txt', null, null, null, array(null,'column'=>'item_txt'));	
		
		if(!$item_txts){
			$item_txts=array();
		}	
		foreach($item_txts as $k=>$v){
			
			if(!empty($v)){
				$item_txts[$k] = $v;
			}else{
				unset($item_txts[$k]);
			}
		}

		
		

		if (get_magic_quotes_gpc()) {
			$post = array_map ('stripslashes', $post);
		}

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {
			
			$post = array(
				'item' => isset ($_POST ['item']) ? $_POST ['item'] : '',
				'item_txt' => isset ($_POST ['item_txt']) ? $_POST ['item_txt'] : '',
				'remark' => isset ($_POST ['remark']) ? $_POST ['remark'] : '',
				'typeid'  => 0,		
				'ccy' => isset ($_POST ['ccy']) ? $_POST ['ccy'] : '',
				'net' => '0',
				'otype' => isset ($_POST ['otype']) ? $_POST ['otype'] : 'OUT',
				'amount' => isset ($_POST ['amount']) ? $_POST ['amount'] : '',
				'user_id' => $online->user_id,
				'create_date'=>isset ($_POST ['create_date']) ? $_POST ['create_date'] : '',
				'create_time'=>isset ($_POST ['create_time']) ? $_POST ['create_time'] : '',
				'update_date'=>date('Y-m-d',$time),
				'update_time'=>date('H:i:s',$time),	
			);
			
			
			// 数据验证
			if (!empty($_POST['item_txt2'])) {
				$post['item_txt'] = $_POST['item_txt2'];
			}
			

			$reg="/(\d{4})-(\d{1,2})-(\d{1,2})/";
			if (!empty($post ['create_date'])) {
				preg_match($reg,$post ['create_date'],$arr);				
				//checkdate ( int $month , int $day , int $year )
				if(!$arr || !checkdate($arr[2],$arr[3],$arr[1])){
					$error ['create_date'] = '日期格式不正确';
				}
			}else{
				$error ['create_date'] = '请输入日期';
			}

			if (empty($post ['item'])) {//account=content
				$post ['item'] = substr($post ['item'],0,15);
			}
			if($post['otype']=='IN'){				
				$post['amount']=abs($post['amount']);
			}else{
				$post['amount']=-abs($post['amount']);
			}
	

			if (! empty ($error)) {
				break;
			}

			// 数据入库
			$book = new self;
			$book ->book_id = null;
			$book ->struct ($post);
			$book_id = $book->insert ('','book_id');
			if($book_id<1){
				$error ['create_date'] = 'add fail';
				break;
			}

			self::update_statement_net($online->user_id,0,$post['ccy']);
			header ('Location: ?go=book&do=browse');
			return;

		}
		if(!$post['create_date'])$post['create_date'] = date('Y-m-d');
		if(!$post['create_time'])$post['create_time'] = '12:00:00';//date('H:i:s');
		if(!$post['item'])$post['item'] = 3;

		// 页面显示
		foreach (array('item','item_txt','typeid','remark','ccy','net','otype','amount') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','item_txts','otype'));
	}
	/**
     * 更新某个会员某个时间后所有帐目的小计
     * @param INT $uid
     * @param INT $date
     * @param STRING $ccy 货币 
     */

	function update_statement_net($uid,$date=0,$ccy='CNY')
	{

		$array = self::selects('*', null, array('user_id'=>$uid,'create_date'>=$date,'ccy'=>$ccy),array('ORDER BY create_date ASC,create_time ASC,book_id ASC'),array('book_id','assoc|table=book'=>null));

		$key=null;
		foreach($array as $k=>$v){
			$key===null?$array[$k]['net']=$v['net']:$array[$k]['net']=$array[$key]['net']+$v['amount'];
			$book = new self;
			$book->book_id =$v['book_id'];
			$book->net=$array[$k]['net'];
			$book->update ();
			$key=$k;
		}
			
	}
	/**
	 * 修改账本
	 */
	final static public function modify() {
		$error = array ();
		// 获取数据
		$book = new self;
		$book->book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;
		if(! is_numeric($book->book_id) || ! $book->select()) {
			$error = '该日志不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($book);
	
		$online = front::online();
		$item_txts = self::selects('item_txt', null, array('user_id'=>$online->user_id), array(' GROUP BY item_txt'), array(NULL,'column|table=book'=>'item_txt'));	
		if(!$item_txts){
			$item_txts=array();
		}
		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'item' => isset ($_POST ['item']) ? $_POST ['item'] : '',
			'item_txt' => isset ($_POST ['item_txt']) ? $_POST ['item_txt'] : '',
			'remark' => isset ($_POST ['remark']) ? $_POST ['remark'] : '',
			
			'ccy' => isset ($_POST ['ccy']) ? $_POST ['ccy'] : '',
			'net' => isset ($_POST ['net']) ? $_POST ['net'] : '0',
			'otype' => isset ($_POST ['otype']) ? $_POST ['otype'] : '',
			'amount' => isset ($_POST ['amount']) ? $_POST ['amount'] : '',
			'user_id' => $online->user_id,
			'create_date'=>isset ($_POST ['create_date']) ? $_POST ['create_date'] : '',
			'create_time'=>isset ($_POST ['create_time']) ? $_POST ['create_time'] : '',

			'update_date'=>date('Y-m-d',$time),
			'update_time'=>date('H:i:s',$time),		
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}
			if (!empty($_POST['item_txt2'])) {
				$post['item_txt'] = $_POST['item_txt2'];
			}

			// 数据验证
			if (empty($post ['item'])) {
				$post ['item'] = substr($post ['item'],0,15);
			}
			if($post['otype']=='IN'){				
				$post['amount']=abs($post['amount']);
			}else{
				$post['amount']=-abs($post['amount']);
			}
			
			$reg="/(\d{4})-(\d{1,2})-(\d{1,2})/";
			if (!empty($post ['create_date'])) {
				preg_match($reg,$post ['create_date'],$arr);				
				//checkdate ( int $month , int $day , int $year )
				if(!$arr || !checkdate($arr[2],$arr[3],$arr[1])){
					$error ['create_date'] = '日期格式不正确';
				}
			}else{
				$error ['create_date'] = '请输入日期';
			}
	
			if (! empty ($error)) {
				break;
			}
			

			$book->struct ($post);
			$book->update ();
			$online = front::online();
			self::update_statement_net($online->user_id,0,$post['ccy']);
			
			header ('Location: ?'.$_GET['query']);
			return;

		}

		// 页面显示
		foreach (array('item','item_txt','typeid','remark','ccy','net','otype','amount','create_date','create_time') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','item_txts','otype'));
	}
	
	/**
	 * 删除日志
	 */
	final static public function remove() {

		// 获取数据
		$book = new self;
		$book->book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;
		if(! is_numeric($book->book_id) || ! $book->select()) {
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
		if(! isset($_POST['book_id']) || !is_array($_POST['book_id'])){
			$error = '该日志不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('book_id'=>$_POST['book_id']),null,__CLASS__);
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