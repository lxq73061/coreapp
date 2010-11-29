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
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 日志列表
	 */
	final static public function browse() {
	
		self::calculation();
		// 数据消毒
		$get = array(
			'item' => isset ($_GET ['item']) ? $_GET ['item'] : '',
			'typeid'  => isset ($_GET ['typeid']) ? $_GET ['typeid'] : '',
			'item_txt' => isset ($_GET ['item_txt']) ? $_GET ['item_txt'] : '',
			'remark' => isset ($_GET ['remark']) ? $_GET ['remark'] : '',
			'ccy' => isset ($_GET ['ccy']) ? $_GET ['ccy'] : '',
			'net' => isset ($_GET ['net']) ? $_GET ['net'] : '',
			'otype' => isset ($_GET ['otype']) ? $_GET ['otype'] : '',
			'amount' => isset ($_GET ['amount']) ? $_GET ['amount'] : '',
			'user_id' => isset ($_GET ['user_id']) ? $_GET ['user_id'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}

		// 获取数据
		$where = array();
		if (strlen($get['item'])>0){
			$where ['item LIKE ?'] = '%'.$get['item'].'%';
		}
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
/*		switch ($get['order']) {
			case 'book_id':
				$other = array('ORDER BY book_id');
				break;
			case 'item':
				$other = array('ORDER BY item');
				break;
			case 'item2':
				$other = array('ORDER BY item DESC');
				break;
			default:
				$other = array('ORDER BY book_id DESC');
				break;
		}*/
		
		$other=array('ORDER BY create_date ASC,create_time ASC,book_id ASC');
		$page = array('page'=>$get['page'],'size'=>10);
		$other ['page'] = &$page;	
		$books = self::selects (null, null, $where, $other, __CLASS__);
		/*$otype = self::selects('otype', null, array('user_id'=>$online->user_id), array(' GROUP BY otype'), array('assoc|table=book'=>NULL));	
		if(!$item_txts){
			$item_txts=array();
		}*/
		
	  //账目总笔数：资金余额  支出交易笔数     收入交易笔数：   //支出金额合计：    收入金额合计

//$mdb_book2 =self::selects ('COUNT(*)','mdb_'.__CLASS__,null,null,array('column'=>'COUNT(*)'));

//
		$total_items =self::selects ('COUNT(*)',null,null,null,array('column|table=book'=>'COUNT(*)'));//得到账目总笔数
		//$total_in =self::selects ('amount(*)',null,null,null,array('column|table=book'=>'amount(*)'));
//pecho($total_in);
		//$out_amount =self::selects ('otype(*)',null,null,null,array('column|table=book'=>'otype(*)'));
		$where=array();
		$where['otype']='IN';
		$total_amount_in =self::selects ('sum(amount)',null,$where,null,array('column|table=book'=>'sum(amount)'));//得到支出金额合计
		//pecho($total_amount);
		$where['otype']='OUT';
		$total_amount_out =self::selects ('sum(amount)',null,$where,null,array('column|table=book'=>'sum(amount)'));//得到收入金额合计
		

		$total_out =self::selects ('COUNT(*)',null,array('otype'=>'OUT'),null,array('column|table=book'=>'COUNT(*)'));//得到支出交易笔数
		$total_in =self::selects ('COUNT(*)',null,array('otype'=>'IN'),null,array('column|table=book'=>'COUNT(*)'));//得到收入交易笔数

		
	/*	$fund_balance = '1';
		$amount_in = '2';
		$amount_out ='3';
		$total_in ='4';
		$total_out ='5';*/
		
		$totals=array(); //运用数组方式
		$totals['total']=$total_items;//得到账目总笔数值
		$totals['amount']=$total_amount_in-$total_amount_out;//得到资金余额值
		$totals['out_amount']=$total_amount_out;//得到支出金额合计值
		$totals['in_amount']=$total_amount_in;//得到收入金额合计值
		$totals['total_out']=$total_out;//得到支出交易笔数值
		$totals['total_in']=$total_in;//得到收入交易笔数值
		
		//pecho($totals);

		

		//$total_amount['otype']['amount'] = ($total_amount['IN']['amount']-$total_amount['OUT']['amount']);

		//$total =self::selects (null,'user',null,null,array('user_id','otype'=>'OUT')); 
	 //pecho($total_amount);

		// 页面显示
		foreach (array('item') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		
		//$a = compact ('books');
		//$b = array('books'=>$books);
		
		
		self::view (__CLASS__ . '.list.tpl', compact ('books','get','page','query','total_items','totals'));//得到数组所有的变量值
		//self::view (__CLASS__ . '.list.tpl', array('books'=>$books,'get'=>$get,'page'=>$page,'query'=>$query,'totals'=>$totals));
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
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		

		// 页面显示
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('book'));
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
		$item_txts = self::selects('item_txt', null, array('user_id'=>$online->user_id), array(' GROUP BY item_txt'), array('assoc|table=book'=>NULL));	
		if(!$item_txts){
			$item_txts=array();
		}	
		$post = array(
/*			'book_date' => isset ($_POST ['book_date']) ? $_POST ['book_date'] : '',
*/			'item' => isset ($_POST ['item']) ? $_POST ['item'] : '',
			'item_txt' => isset ($_POST ['item_txt']) ? $_POST ['item_txt'] : '',
			'remark' => isset ($_POST ['remark']) ? $_POST ['remark'] : '',
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',		
			'ccy' => isset ($_POST ['ccy']) ? $_POST ['ccy'] : '',
			'net' => isset ($_POST ['net']) ? $_POST ['net'] : '',
			'otype' => isset ($_POST ['otype']) ? $_POST ['otype'] : '',
			'amount' => isset ($_POST ['amount']) ? $_POST ['amount'] : '',
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
			if (!empty($_POST['item_txt2'])) {
				$post['item_txt'] = $_POST['item_txt2'];
			}
			
			
			if ($_POST['ccy2']){
				$post['otype'] = 'IN';
			}else{
			    $post['otype'] ='OUT';
	
			}

			/*$a = otype;
			$b = net;
			function Sum()
			{
				global $a, $b;
			
				$b = $a + $b;
			}
			
			Sum();
			echo $b;*/

			/*if (empty($post ['book_date'])) {//account=content
				$post ['book_date'] = date('Y-m-d');
			}*/

			if (empty($post ['item'])) {//account=content
				$post ['item'] = substr($post ['item'],0,15);
			}
			if ($post ['typeid'] === 0 ) {//使用默认分类
				$error ['typeid'] = '请选择分类';
			}
	

			if (! empty ($error)) {
				break;
			}
/*echo __LINE__;	*/
			// 数据入库
			$book = new self;
			$book ->book_id = null;
			$book ->struct ($post);
			$book->insert ();
			/*echo __LINE__;	*/
			header ('Location: ?go=book&do=browse');
			return;

		}

		// 页面显示
		foreach (array('item','item_txt','typeid','remark','ccy','net','otype','amount') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','item_txts','otype'));
	}

				/**
	 * UPDATE NET
*/      

	function calculation($create_time='')
	{
		  $online = front::online();
		//按日期读取列表
		//循环列表，本次余额＝将（本次里金额）+上次余款，
		//将本次的余额数据写回数据库		
		$sql="SELECT net FROM mdb_book WHERE 1 ORDER BY create_date DESC,id DESC";	
		//$item_txts = self::selects($sql, null, null, null, array('assoc'=>NULL));	
		$array = self::selects('*', null, array('user_id'=>$online->user_id),array('ORDER BY create_date ASC,create_time ASC,book_id ASC'),array('book_id','assoc|table=book'=>null));
		//$array = self::selects($sql);	
		//$array=array();
		$key=null;
		foreach($array as $k=>$v){
		//echo $key;
			$amount =$v['amount'];//
			$net=$array[$key]['net'];						
/*			$array[$k]['net']=$net-$amount;*/					
/*			if($v['otype']=='IN')
			{
			$array[$key]['net'];==$net-$amount;
			}else{
			$array[$key]['net'];==$net+$amount;
			}*/if($v['otype']=='IN')
			{
				$array[$k]['net']=$net+$amount;
			}else{
				$array[$k]['net']=$net-$amount;
			}		
			
			$book = new self;
			$book->book_id =$v['book_id'];
			$book->net=$array[$k]['net'];
			$book->update ();
			$key=$k;
		}
		//pecho($array);
		
	}

		//$sql="SELECT net FROM mdb_book WHERE 1 ORDER BY create_date DESC,id DESC";	

	/**
	 * 修改日志
	 */
	final static public function modify() {
		$error = array ();
	//在选择不选中添加字句、不需要在或输入添加-->
		// 获取数据
		$book = new self;
		$book->book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;
		if(! is_numeric($book->book_id) || ! $book->select()) {
			$error = '该日志不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($book);
		//$item_txts=array(1,2);
		
/*		if($ccy2==0){//支出
		$amount= - abs($amount);
		$otype ='OUT';
	}else{
		$amount=  abs($amount);
		$otype ='IN';
	}*/
		$online = front::online();
		//item_txts
		$item_txts = self::selects('item_txt', null, array('user_id'=>$online->user_id), array(' GROUP BY item_txt'), array('assoc|table=book'=>NULL));	
		if(!$item_txts){
			$item_txts=array();
		}
			
		

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$time = time();
			$post = array(
			'item' => isset ($_POST ['item']) ? $_POST ['item'] : '',
/*			'book_date' => isset ($_POST ['book_date']) ? $_POST ['book_date'] : '',
*/			'item_txt' => isset ($_POST ['item_txt']) ? $_POST ['item_txt'] : '',
			'remark' => isset ($_POST ['remark']) ? $_POST ['remark'] : '',
			
			'ccy' => isset ($_POST ['ccy']) ? $_POST ['ccy'] : '',
			'net' => isset ($_POST ['net']) ? $_POST ['net'] : '',
			'otype' => isset ($_POST ['otype']) ? $_POST ['otype'] : '',
			'amount' => isset ($_POST ['amount']) ? $_POST ['amount'] : '',
			'user_id' => $online->user_id,
			'typeid'  => isset ($_POST ['typeid']) ? $_POST ['typeid'] : '',		
			'update_date'=>date('Y-m-d',$time),
			'update_time'=>date('H:i:s',$time),		
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}
			if (!empty($_POST['item_txt2'])) {
				$post['item_txt'] = $_POST['item_txt2'];
			}
			
			/*if (empty($post ['book_date'])) {//account=content
				$post ['book_date'] = date('Y-m-d');
			}elseif(strtotime($post ['book_date'])==0){
				$error ['book_date'] = '日期不正确';
			}*/
			// 数据验证
			if (empty($post ['item'])) {
				$post ['item'] = substr($post ['item'],0,15);
			}
			
			if ($_POST['ccy2']){
				$post['otype'] = 'OUT';
			}else{
			    $post['otype'] ='IN';
	
			}

			if ($post ['typeid'] === 0 ) {
				$error ['typeid'] = '请选择日志分类';
			}
	
			if (! empty ($error)) {
				break;
			}

			$book->struct ($post);
			$book->update ();
			header ('Location: ?'.$_GET['query']);
			return;

		}

		// 页面显示
		foreach (array('item','item_txt','typeid','remark','ccy','net','otype','amount') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','item_txts','otype'));
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
			self::view ('error.tpl', compact ('error'));
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
			self::view ( 'error.tpl', compact ('error'));
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