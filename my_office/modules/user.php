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
	* 数据定义 列表，编辑，添加时自动创建页面时用
	*/
	static public function get_table() {
		$tableinfo =  get_table('user');
		
		$keys = array('username','password','name','addr','tel','mobile','member_lv_id','grade','disabled');
		$FromName=array(
			'username'=>'用户名',
			'password'=>'密码',
			'name'=>'姓名',
			'grade'=>'管理级别',
			//'disabled'=>'状态',
			//'addr'=>'地址',
			//'tel'=>'电话',
			'mobile'=>'手机',
			'email'=>'邮箱',
			'remark'=>'备注',
			
			
		
		);
		
		//$FromType['member_lv_id']=array('select',self:: get_member_lv());
		$FromType['grade']=array('select',self::get_grades());
		//$FromType['disabled']=array('select',array('false'=>'启用','true'=>'停用'));
		//$FromMsg['member_lv_id']='会员级别';
		$FromMsg['username']='3-16个字符，英文字母、汉字、数字、下划线，不能全部是数字，且下划线不能作为起始和结尾字符';
		$FromMsg['password']='4-16个字符，英文字母、数字、下划线、半角符号';
		
		
		foreach($tableinfo as $k=>&$info){
			$info['FromType'] = $FromType[$k];
			$info['FromMsg'] = $FromMsg[$k];
			$info['FromName'] = $FromName[$k]?$FromName[$k]:$k;
			$info['FromView'] = in_array($k,$keys);
			$info['size']=substr($invo['Type'],0,7)=='varchar';
		}
		
		return $tableinfo;
		
	}
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 用户列表
	 */
	final static public function browse() {
		$TI =  self::get_table();
		$TI['password']['FromView']=0;
		
		
		if(isset($_POST['filter'])){
			unset($_POST['mprice']);
			unset($_POST['filter']);
			$url =	http_build_query($_POST);
			$url = str_replace('&amp;','&',$url);
			//header("Location: ?".$url);
			echo "<script>window.location.href='?".$url."'</script>";
			exit();
		}
		// 数据消毒
		$get = array(
			'page'  => isset ($_GET ['page']) ? $_GET ['page'] : '',
			'orderby'  => isset ($_GET ['orderby']) ? $_GET ['orderby'] : '',
			'ordertype'  => isset ($_GET ['ordertype']) && in_array($_GET ['ordertype'],array('ASC','DESC')) ? $_GET ['ordertype'] : 'DESC',
		);
		foreach($TI as $k=>$v){
			$get[$k] = isset ($_GET [$k]) ? $_GET [$k] : '';
		}
		if (get_magic_quotes_gpc()) {
			$get = stripslashes_deep($get);
		}

		// 获取数据
		$where = array();
		foreach($TI as $k=>$v){
			if($get[$k])
			if($k=='advance1'){
				
			}else
			$where[$k .' LIKE ?']= '%'.trim($get[$k])	.'%';
		}
		
		$online = front::online();
		
		 
		$page = array('page'=>$get['page'],'size'=>10);
		$other ['page'] = &$page;
		if($TI[$get['orderby']])
			$other [] = 'ORDER BY '.$get['orderby'].' '.$get['ordertype'];
		else
		$other [] = 'ORDER BY grade,user_id DESC';
		
		$dataArray = $users = self::selects (null, null, $where, $other, __CLASS__);
		$grades = self::get_grades();
		
		// 页面显示
		foreach (array('username') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		
		$query = $_SERVER['QUERY_STRING'];
		
		$ACT= array(
			'select'=>1,//快速选择
			'delete'=>$online->grade<2,
			'deletes'=>$online->grade<2,	
			'modify'=>1,
			'append'=>1,
		);
		if(isset($_GET['_filter'])){
			$filterArray = array();
			foreach($dataArray as $v){
				if($v->grade=='')
				$filterArray[$v->user_id]=$v->username;
				
			}
		front::view2 ('common/table.filter.tpl', compact ('users','get','online','page','query','TI','filterArray'));
		}else
		front::view2 (__CLASS__ . '.list.tpl', compact ('ACT','users','get','online','page','query','TI','dataArray'));
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
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 页面显示
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('user'));
	}
	
	/**
	 * 添加用户
	 */
	final static public function append() {
		$error = array ();
		$online = front::online();
		
		$TI =  self::get_table();
		
		// 数据消毒
		$post = array();
		foreach($TI as $k=>$info){
			 if($info['Extra']=='auto_increment')continue;
			if(isset($_POST[$info['Field']]))$post[$info['Field']]=$_POST[$info['Field']];
		}
		if (get_magic_quotes_gpc()) {
			$post = stripslashes_deep($post);
		}
		if($online->grade>2 ){
			$error = '无权限';
			front::view2 ( 'common/error.tpl', compact ('error'));
			return;
		}

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据验证
			if($online->grade >  $post['grade']){
				$error['grade'] ='等级设置错误';

			}
		
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
			}else{
				$post ['password'] = md5($post ['username'].md5($post ['password']));
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
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('TI', 'keys', 'post', 'error','online','notice','FromType','FromMsg','FromName'));
	}
	
	/**
	 * 修改用户
	 */
	final static public function modify() {
		$error = array ();
		$online = front::online();
		$TI =  self::get_table();
		// 获取数据
		$user = new self;
		$user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
		if(! is_numeric($user->user_id) || ! $user->select()) {
			$error = '该用户不存在';
			front::view2 ( 'common/error.tpl', compact ('error'));
			return;
		}
		//级别:1超级管理员/2管理员/3普通用户
		if($online->grade==3 && $user->user_id!=$online->user_id ){
			$error = '无权限';
			front::view2 ( 'common/error.tpl', compact ('error'));
			return;
		}
		if($online->grade==2 && $user->grade!=3 ){
			$error = '无权限';
			front::view2 ( 'common/error.tpl', compact ('error'));
			return;
		}
		
		$post = get_object_vars ($user);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			$post = array();
			foreach($TI as $k=>$info){
				 if($info['Extra']=='auto_increment')continue;
				if(isset($_POST[$info['Field']]))$post[$info['Field']]=$_POST[$info['Field']];
			}
			if (get_magic_quotes_gpc()) {
				$post = stripslashes_deep($post);
			}

			if($online->grade > $post['grade'] || ($online->user_id == $user->user_id && $online->grade != $post['grade'] )){
				$error['grade'] ='等级设置错误';

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
			if (strlen ($post ['password']) > 0){			
				$post ['password'] = md5($post ['username'].md5($post ['password']));
			
			}
			
			if (preg_match ('/^[1-3]$/i',$post ['grade']) === 0 ) {
				$error ['grade'] = '请选择级别';
			}
			if (strlen ($post['name']) === 0) {
				$error ['name'] = '请填写姓名';
			}
			if (preg_match ('/^[1-2]$/i',$post ['gender']) === 0 ) {
				//$error ['gender'] = '请选择性别';
			}
			if (strlen ($post ['mobile']) > 0 && preg_match ('/^1[0-9]{10}$/i',$post ['mobile']) === 0) {
				$error ['mobile'] = '请正确填写手机号';
			}
			if (strlen ($post ['email']) > 0 && ! filter_var ($post ['email'], FILTER_VALIDATE_EMAIL)) {
				$error ['email'] = '请正确填写邮箱';
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
		unset($post ['password']);
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('TI','post', 'error','online'));
	}
	
	/**
	 * 删除用户
	 */
	final static public function remove() {
		if(!self::user_level(2,__CLASS__,__FUNCTION__))return;
		$online = front::online();
		// 获取数据
		$user = new self;
		$user->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
		if(! is_numeric($user->user_id) || ! $user->select()) {
			$error = '该用户不存在';
			front::view2 ( 'common/error.tpl', compact ('error'));
			return;
		}
		if($online->user_id==$user->user_id||$user->grade==1){
			$error = '此用户不能删除';
			front::view2 ( 'common/error.tpl', compact ('error'));
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
		if(!self::user_level(2,__CLASS__,__FUNCTION__))return;
		$online = front::online();
		// 获取数据
		if(! isset($_POST['user_id']) || !is_array($_POST['user_id'])){
			$error = '该用户不存在';
			front::view2 ( 'common/error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		self::deletes(null,null,array('user_id'=>$_POST['user_id'],'user_id!=?'=>$online->user_id),null,__CLASS__);
		header ('Location: ?'.$_GET['query']);
	}
	
	public function get_grades(){
		return array('1'=>'高级管理员','2'=>'普通管理员',3=>'普通用户');	
	}
	/**
	 * 返回等级名称
	 */
	public function get_grade($grade) {
		$array = self::get_grades();
		return $array [$grade];
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