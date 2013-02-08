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
			
			'item_txt' => isset ($_GET ['item_txt']) ? $_GET ['item_txt'] : null,
			'opposite' => isset ($_GET ['opposite']) ? $_GET ['opposite'] : null,
			'book_item_id' => isset ($_GET ['book_item_id']) ? $_GET ['book_item_id'] : null,
			
			'page' => isset ($_GET ['page']) ? $_GET ['page'] : '',
		);
		
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}
		
		$online = front::online();
		
		$ccys = book::get_ccy();
		$item_types = book_item::get_items();
		$book_items = self::selects ('book_item_id,item,info', '#@__book_item', array('user_id' => $online->user_id), array('ORDER BY book_item_id ASC'), array('book_item_id','assoc'=>null));
		$opposites = self::selects('opposite', null, array('user_id'=>$online->user_id), array('GROUP BY opposite'), array(null,'column|table=book'=>'opposite'));	
		$item_txts = self::selects('item_txt', null, array('user_id'=>$online->user_id), array('GROUP BY item_txt'), array(null,'column|table=book'=>'item_txt'));
		
		

	
		// 获取数据
		$where = array();
	
		$where ['create_date >=?'] = $get['from'];
		$where ['create_date <=?'] = $get['to'];
		$where ['ccy'] = $get['ccy'];
		
		if(!empty($get['opposite']))
		$where ['opposite'] = $get['opposite'];
		
		if(!empty($get['item_txt']))
		$where ['item_txt'] = $get['item_txt'];
		
		if(!empty($get['book_item_id']))
		$where ['book_item_id'] = $get['book_item_id'];

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
					
		foreach($book_items as $k=>$v)$book_items[$k]=$item_types[$v['item']].'『'.$v['info'].'』';
		front::view2 (__CLASS__ . '.list.tpl', compact ('books','get','page','query','total_items','totals','item_types','book_items','ccys','opposites','item_txts'));//得到数组所有的变量值
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
	
		$item_types = book_item::get_items();
		$ccys = book::get_ccy();
		$error = array ();

		$online = front::online();
		$time=time();
		// 数据消毒
	
		
		$item_txts = self::selects('item_txt', null, array('user_id'=>$online->user_id), array('GROUP BY item_txt'), array(null,'column|table=book'=>'item_txt'));	
		
		$opposites = self::selects('opposite', null, array('user_id'=>$online->user_id), array('GROUP BY opposite'), array(null,'column|table=book'=>'opposite'));	
		if(!$item_txts){
			$item_txts=array();
		}
		
		
		$book_items = self::selects ('book_item_id,item,info', '#@__book_item', array('user_id' => $online->user_id), array('ORDER BY book_item_id ASC'), array('book_item_id','assoc'=>null));
		
	

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {
			
			$post = array(
				'item' => isset ($_POST ['item']) ? $_POST ['item'] : '',
				'item_txt' => isset ($_POST ['item_txt']) ? $_POST ['item_txt'] : '',
				'remark' => isset ($_POST ['remark']) ? $_POST ['remark'] : '',
				'opposite' => isset ($_POST ['opposite']) ? $_POST ['opposite'] : '',
				'book_item_id' => isset ($_POST ['book_item_id']) ? $_POST ['book_item_id'] : '',
				
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
			if (!empty($_POST['opposite2'])) {
				$post['opposite'] = $_POST['opposite2'];
			}
			if($post['book_item_id']){
				$post['item']  = $book_items[$post['book_item_id']]['item'];
				
			}
			
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
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
		//if(!$post['item'])$post['item'] = 3;

		// 页面显示
		foreach (array('item','item_txt','typeid','remark','ccy','net','otype','amount') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','item_txts','opposites','otype','item_types','book_items','ccys'));
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
		$item_types = book_item::get_items();
		$ccys = book::get_ccy();
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
		$book_items = self::selects ('book_item_id,item,info', '#@__book_item', array('user_id' => $online->user_id), array('ORDER BY book_item_id ASC'), array('book_item_id','assoc'=>null));
		$opposites = self::selects('opposite', null, array('user_id'=>$online->user_id), array('GROUP BY opposite'), array(null,'column|table=book'=>'opposite'));	
	
		
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
				'opposite' => isset ($_POST ['opposite']) ? $_POST ['opposite'] : '',
				'book_item_id' => isset ($_POST ['book_item_id']) ? $_POST ['book_item_id'] : '',
				
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
			if (!empty($_POST['opposite2'])) {
				$post['opposite'] = $_POST['opposite2'];
			}
			if($post['book_item_id']){
				$post['item']  = $book_items[$post['book_item_id']]['item'];
				
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
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error','item_txts','otype','item_types','book_items','opposites','ccys'));
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
	public function get_ccy(){
		$ccy = array(
	  "CNY"=>'人民币(CNY)',
	  "HKD"=>'港元(HKD)',
	  "TWD"=>'新台币(TWD)',
	  "USD"=>'美元(USD)',
	  "EUR"=>'欧元(EUR)',
	  "JPY"=>'日元(JPY)',
	  "GBP"=>'英镑(GBP)',
	  "CAD"=>'加拿大元(CAD)',
	  "RUB"=>'俄国卢布(RUB)',
	  "AUD"=>'澳大利亚元(AUD)',
	  "KRW"=>'韩圆(KRW)',
	  "MOP"=>'澳门元(MOP)',
	  "UZS"=>'乌兹别克斯苏姆(UZS)',
	  "INR"=>'印度卢比(INR)',
	  "YER"=>'也门里亚尔(YER)',
	  "KWD"=>'科威特第纳尔(KWD)',
	  "KZT"=>'哈萨克斯坦坚戈(KZT)',
	  "HUF"=>'匈牙利福林(HUF)',
	  "SCR"=>'塞舌尔卢比(SCR)',
	  "MUR"=>'毛里求斯卢比(MUR)',
	  "BGN"=>'保加利亚新列弗(BGN)',
	  "PYG"=>'巴拉圭瓜拉尼(PYG)',
	  "COP"=>'哥伦比亚比索(COP)',
	  "LKR"=>'斯里兰卡卢比(LKR)',
	  "UYU"=>'乌拉圭比索(UYU)',
	  "TTD"=>'特立尼达和多巴哥元(TTD)',
	  "LVL"=>'拉脱维亚拉特(LVL)',
	  "VND"=>'越南盾(VND)',
	  "NGN"=>'尼日利亚奈拉(NGN)',
	  "RSD"=>'塞尔维亚第纳尔(RSD)',
	  "EGP"=>'埃及镑(EGP)',
	  "CRC"=>'哥斯达黎加科朗(CRC)',
	  "AED"=>'阿联酋迪拉姆(AED)',
	  "UGX"=>'乌干达先令(UGX)',
	  "EEK"=>'爱沙尼亚克朗(EEK)',
	  "LAK"=>'老挝基普(LAK)',
	  "MMK"=>'缅甸缅元(MMK)',
	  "KHR"=>'柬埔寨瑞尔(KHR)',
	  "BYR"=>'白俄罗斯卢布(BYR)',
	  "BZD"=>'伯利兹元(BZD)',
	  "ETB"=>'埃塞俄比亚比尔(ETB)',
	  "GTQ"=>'危地马拉格查尔(GTQ)',
	  "IQD"=>'伊拉克第纳尔(IQD)',
	  "IRR"=>'伊朗里尔斯(IRR)',
	  "MYR"=>'马来西亚林吉特(MYR)',
	  "HRK"=>'克罗地亚库纳(HRK)',
	  "BRL"=>'巴西雷亚尔(BRL)',
	  "UAH"=>'乌克兰格里夫尼亚(UAH)',
	  "THB"=>'泰铢(THB)',
	  "ZAR"=>'南非兰特(ZAR)',
	  "PGK"=>'巴布亚新几内亚基那(PGK)',
	  "CLP"=>'智利比索(CLP)',
	  "MAD"=>'摩洛哥迪拉姆(MAD)',
	  "SVC"=>'萨尔瓦多科朗(SVC)',
	  "PLN"=>'波兰兹罗提(PLN)',
	  "SGD"=>'新加坡元(SGD)',
	  "SYP"=>'叙利亚镑(SYP)',
	  "LBP"=>'黎巴嫩镑(LBP)',
	  "ANG"=>'荷兰安替兰盾(ANG)',
	  "TND"=>'突尼斯第纳尔(TND)',
	  "XOF"=>'非洲金融共同体法郎(XOF)',
	  "JOD"=>'约旦第纳尔(JOD)',
	  "IDR"=>'印度尼西亚盾(IDR)',
	  "KES"=>'肯尼亚先令(KES)',
	  "SEK"=>'瑞典克朗(SEK)',
	  "MDL"=>'摩尔多瓦列伊(MDL)',
	  "QAR"=>'卡塔尔里亚尔(QAR)',
	  "PKR"=>'巴基斯坦卢比(PKR)',
	  "RON"=>'罗马尼亚列伊(RON)',
	  "SKK"=>'斯洛伐克克朗(SKK)',
	  "HNL"=>'洪都拉斯拉伦皮拉(HNL)',
	  "VEF"=>'委内瑞拉强势玻利瓦(VEF)',
	  "BHD"=>'巴林第纳尔(BHD)',
	  "NPR"=>'尼泊尔卢比(NPR)',
	  "JMD"=>'牙买加元(JMD)',
	  "ILS"=>'以色列新谢克尔(ILS)',
	  "OMR"=>'阿曼里亚尔(OMR)',
	  "NAD"=>'纳米比亚元(NAD)',
	  "DZD"=>'阿尔及利亚第纳尔(DZD)',
	  "ISK"=>'冰岛克朗(ISK)',
	  "BDT"=>'孟加拉塔卡(BDT)',
	  "BOB"=>'玻利维亚诺(BOB)',
	  "BND"=>'文莱元(BND)',
	  "DKK"=>'丹麦克朗(DKK)',
	  "ARS"=>'阿根廷比索(ARS)',
	  "NIO"=>'尼加拉瓜金科多巴(NIO)',
	  "CZK"=>'捷克克郎(CZK)',
	  "KYD"=>'开曼元(KYD)',
	  "FJD"=>'斐济元(FJD)',
	  "MVR"=>'马尔代夫拉菲亚(MVR)',
	  "SAR"=>'沙特里亚尔(SAR)',
	  "PHP"=>'菲律宾比索(PHP)',
	  "CHF"=>'瑞士法郎(CHF)',
	  "NOK"=>'挪威克朗(NOK)',
	  "LTL"=>'立陶宛立特(LTL)',
	  "TRY"=>'新土耳其里拉(TRY)',
	  "SLL"=>'塞拉利昂利昂(SLL)',
	  "MKD"=>'马其顿戴代纳尔(MKD)',
	  "BWP"=>'博茨瓦纳普拉(BWP)',
	  "MXN"=>'墨西哥比索(MXN)',
	  "PEN"=>'秘鲁新索尔(PEN)',
	  "DOP"=>'多米尼加比索(DOP)',
	  "NZD"=>'新西兰元(NZD)',
	  "TZS"=>'坦桑尼亚先令(TZS)',
	  "ZMK"=>'赞比亚克瓦查(ZMK)');	
	  return $ccy ;
	}
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>