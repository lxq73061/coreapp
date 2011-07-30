<?php
/**
 * 关联模块
 * 
 * @version 
 * @author steven.liao <lxq73061@gmail.com>
 
 现有表：
address（地址薄）
book（账本）
channel（分类）
diary（日记）
doc（文档）
site（网址）
user（用户）

现在我想增加一个功能，就是以上类似的表，可以相互关联。
比如：一篇文章，我可以关联2条网址，关联一篇日记
或者：一篇日记，可以关联1条网址，2篇文章这样


下面是关联表设计：

related_id,address,book,channel,diary,doc,site,user
1,0,0,0,1,1,1,0
2,0,0,0,0,1,2,0
3,0,0,0,2,2,3,0
4,0,0,0,2,2,0,0


related_id,source_type,target_type,source_id,target_id
1,doc,site,1,1
2,doc,site,1,2
'address','book','channel','diary','doc','site','user'

 */

/**
 * 导入(import)
 */
class_exists('core') or require_once 'core.php';

/**
 * 定义(define)
 */
class related extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		



		return;
	}
	
	/**
	 * 添加关系
	 */
	final static public function get($s_type,$s_id,$t_type=null,$t_id=null) {
		$online = front::online();
		$lists = self::selects(null, null, array('user_id'=>$online->user_id,'s_id'=>$s_id,'s_type'=>$s_type),array('ORDER BY related_id DESC'),array(null,'assoc|table=related'=>null));
		
		$lists2 = self::selects(null, null, array('user_id'=>$online->user_id,'t_id'=>$s_id,'t_type'=>$s_type),array('ORDER BY related_id DESC'),array(null,'assoc|table=related'=>null));
		if($lists2){
			foreach($lists2 as $k=>$v){
				$lists[]=array(
				'related_id' => $v['related_id'],
				's_type' => $v['t_type'],
				't_type' => $v['s_type'],
				's_id' => $v['t_id'],
				't_id' => $v['s_id'],
				'user_id' => $v['user_id'],
				);
			}
		}
		$types=array('address'=>'name','book'=>'concat_ws(\',\',create_date,item_txt,remark,ccy,amount,otype) as name','channel'=>'name','diary'=>'title as name','doc'=>'title as name','site'=>'title as name','user'=>'username as name');
		
		
		foreach($lists as $k=>$v){
			$name = $types[$v['t_type']];
			$t = self::selects($name, null, array('user_id'=>$online->user_id,$v['t_type'].'_id'=>$v['t_id']),array(),array('column|table='.$v['t_type']=>'name'));
			$lists[$k]['t_name'] = $t;
		}
		$lists2=array();
		foreach($lists as $k=>$v){
			$lists2[$v['t_type']][]=$v;
		}
		$lists = $lists2;
		$query = $_SERVER['QUERY_STRING'];
		front::view2 (__CLASS__ . '.' . 'detail.tpl', compact ('post','get', 'error','types','lists','query','s_type','s_id','t_type','t_id'));	
	}
	/**
	 * 添加关系
	 */
	final static public function append() {
		$error = array ();

		$online = front::online();
		$get = array(
				's_type' => isset ($_GET ['s_type']) ? $_GET ['s_type'] : '',
				't_type' => isset ($_GET ['t_type']) ? $_GET ['t_type'] : '',
				's_id'  => isset ($_GET ['s_id']) ? (int)$_GET ['s_id'] : '0',
				't_id' => isset ($_GET ['t_id']) ? (int)$_GET ['t_id'] : '0',							
			);	

	$s_list=null;
	if($get['s_type']=='channel'){
		$s_list = channel::get_channel_select(0,0,$get['s_id'],null,null);
	}elseif($get['s_type']=='address'){
		$s_lists = address::selects('address_id as id,name', null, array('user_id'=>$online->user_id),array('ORDER BY address_id DESC'),array('id','column|table=address'=>'name'));		
		if($s_lists)$s_list = make_option($s_lists,$get['s_id']);
	}elseif($get['s_type']=='book'){
		$s_lists = book::selects('book_id as id,concat_ws(\',\',create_date,item_txt,remark,ccy,amount,otype) as name', null, array('user_id'=>$online->user_id),array('ORDER BY create_date DESC,book_id DESC'),array('id','column|table=book'=>'name'));		
		if($s_lists)$s_list = make_option($s_lists,$get['s_id']);
	}elseif($get['s_type']=='diary'){
		$s_lists = diary::selects('diary_id as id,title as name', null, array('user_id'=>$online->user_id),array('ORDER BY diary_id DESC'),array('id','column|table=diary'=>'name'));		
		if($s_lists)$s_list = make_option($s_lists,$get['s_id']);	
		
	}elseif($get['s_type']=='doc'){
		$s_lists = doc::selects('doc_id as id,title as name', null, array('user_id'=>$online->user_id),array('ORDER BY doc_id DESC'),array('id','column|table=doc'=>'name'));		
		if($s_lists)$s_list = make_option($s_lists,$get['s_id']);
	}elseif($get['s_type']=='site'){
		$s_lists = site::selects('site_id as id,title as name', null, array('user_id'=>$online->user_id),array('ORDER BY site_id DESC'),array('id','column|table=site'=>'name'));		
		if($s_lists)$s_list = make_option($s_lists,$get['s_id']);
	}elseif($get['s_type']=='user'){
		$s_lists = user::selects('user_id as id,username as name', null, array('user_id'=>$online->user_id),array('ORDER BY user_id DESC'),array('id','column|table=user'=>'name'));		
		if($s_lists)$s_list = make_option($s_lists,$get['s_id']);
	}else{		
	}
	
	
	$t_list=null;
	if($get['t_type']=='channel'){
		$t_list = channel::get_channel_select(0,0,$get['t_id'],null,null);		
	}elseif($get['t_type']=='address'){
		$t_lists = address::selects('address_id as id,name', null, array('user_id'=>$online->user_id),array('ORDER BY address_id DESC'),array('id','column|table=address'=>'name'));		
		if($t_lists)$t_list = make_option($t_lists,$get['t_id']);
	}elseif($get['t_type']=='book'){
		$t_lists = book::selects('book_id as id,concat_ws(\',\',create_date,item_txt,remark,ccy,amount,otype) as name', null, array('user_id'=>$online->user_id),array('ORDER BY create_date DESC,book_id DESC'),array('id','column|table=book'=>'name'));		
		if($t_lists)$t_list = make_option($t_lists,$get['t_id']);
	}elseif($get['t_type']=='diary'){
		$t_lists = diary::selects('diary_id as id,title as name', null, array('user_id'=>$online->user_id),array('ORDER BY diary_id DESC'),array('id','column|table=diary'=>'name'));		
		if($t_lists)$t_list = make_option($t_lists,$get['t_id']);	
		
	}elseif($get['t_type']=='doc'){
		$t_lists = doc::selects('doc_id as id,title as name', null, array('user_id'=>$online->user_id),array('ORDER BY doc_id DESC'),array('id','column|table=doc'=>'name'));		
		if($t_lists)$t_list = make_option($t_lists,$get['t_id']);
	}elseif($get['t_type']=='site'){
		$t_lists = site::selects('site_id as id,title as name', null, array('user_id'=>$online->user_id),array('ORDER BY site_id DESC'),array('id','column|table=site'=>'name'));		
		if($t_lists)$t_list = make_option($t_lists,$get['t_id']);
	}elseif($get['t_type']=='user'){
		$t_lists = user::selects('user_id as id,username as name', null, array('user_id'=>$online->user_id),array('ORDER BY user_id DESC'),array('id','column|table=user'=>'name'));		
		if($t_lists)$t_list = make_option($t_lists,$get['t_id']);
	}else{		
	}

						// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {
				// 数据消毒
			$post = array(
				's_type' => isset ($_POST ['s_type']) ? $_POST ['s_type'] : '',
				't_type' => isset ($_POST ['t_type']) ? $_POST ['t_type'] : '',
				's_id'  => isset ($_POST ['s_id']) ? (int)$_POST ['s_id'] : '0',
				't_id' => isset ($_POST ['t_id']) ? (int)$_POST ['t_id'] : '0',			
				'user_id' => $online->user_id,		
			);		
			if(!$post['s_type'])$error['s_type']='请选择源类型';
			if(!$post['t_type'])$error['t_type']='请选择目标类型';
			if(!$post['s_id'])$error['s_id']='请选择源内容';
			if(!$post['t_id'])$error['t_id']='请选目标内容';
			
			if(!$error['t_id'])
			if($post['s_type'] == $post['t_type'] && $post['s_id'] == $post['t_id'])$error['t_id']='不能和自己关联';
			
			if(!$error['t_id']){
				$related_id = self::selects('related_id', null, 
				array('user_id'=>$online->user_id,
				's_id'=>$post['s_id'],
				's_type'=>$post['s_type'],
				't_id'=>$post['t_id'],
				't_type'=>$post['t_type'],			
				),
				null,array('column'=>'related_id'));
				if(!$related_id){
					$related_id = self::selects('related_id', null, 
					array('user_id'=>$online->user_id,
					's_id'=>$post['t_id'],
					's_type'=>$post['t_type'],
					't_id'=>$post['s_id'],
					't_type'=>$post['s_type'],			
					),
					null,array('column'=>'related_id'));
				}			
				if($related_id)$error['t_id']='目标内容已经关联，请重新选择';
			}
		
			if (! empty ($error)) {
				break;
			}		
			// 数据入库
			$related = new self;
			$related->related_id = null;
			$related->struct ($post);	
			$related->insert ();			
			$error = '添加成功';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		$types=array('address'=>'地址','book'=>'账本','channel'=>'分类','diary'=>'日志','doc'=>'文章','site'=>'网址','user'=>'用户');
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post','get', 'error','types','s_list','t_list'));
	}
	
	/**
	 * 删除网址
	 */
	final static public function remove() {

		// 获取数据
		$related = new self;
		$related->related_id = isset($_GET['related_id']) ? $_GET['related_id'] : null;
		if(! is_numeric($related->related_id) || ! $related->select()) {
			$error = '该关联不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}

		// 删除数据
		$related->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	
	
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>