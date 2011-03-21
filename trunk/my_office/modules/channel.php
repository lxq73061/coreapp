<?php
/**
 * 分类模块
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
class channel extends core {
	
	/**
	 * 默认动作
	 */
	final static public function index() {
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 分类列表
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
		if (strlen($get['title'])>0){
			$where ['title LIKE ?'] = '%'.$get['title'].'%';
		}
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
		$other = array('ORDER BY sort');
		
		$page = array('page'=>$get['page'],'size'=>10);
		$other ['page'] = &$page;
		$channels = self::selects (null, null, $where, $other, __CLASS__);

		// 页面显示
		foreach (array('title') as $value) {
			$get [$value] = htmlspecialchars ($get [$value]);
		}
		$query = $_SERVER['QUERY_STRING'];
		self::view (__CLASS__ . '.list.tpl', compact ('channels','get','page','query'));
	}
	
	/**
	 * 分类详细
	 */
	final static public function detail() {
		// 数据消毒
		$get = array(
			'page'  => isset ($_GET ['page']) ? $_GET ['page'] : '',
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}
		
		// 获取数据
		$channel = new self;
		$channel->channel_id = isset($_GET['channel_id']) ? $_GET['channel_id'] : null;
		if(! is_numeric($channel->channel_id) || ! $channel->select()) {
			$error = '该分类不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		
		$online = front::online();
		// 获取数据
		$where = array();
		
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
		$other = array('ORDER BY sort');
		
		$page = array('page'=>$get['page'],'size'=>100);
		$other ['page'] = &$page;
		//$channels = self::selects (null, null, $where, $other, __CLASS__);
		$path = ','.str_pad($channel->channel_id,5,'0',STR_PAD_LEFT);
		$tablepre = self::init('prefix_search');
		$sql = "SELECT a.*,b.name  FROM {$tablepre}doc as a LEFT JOIN {$tablepre}channel as b  on a.typeid = b.channel_id  
		WHERE a.user_id='$online->user_id' AND  b.channel_id IN(
			SELECT channel_id FROM {$tablepre}channel WHERE path like'%$path%'
		)";
		
		$docs = self::selects($sql,null,true,$other);

		// 页面显示
		self::view (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('docs','channel','page'));
	}
	
	/**
	 * 添加分类
	 */
	final static public function append() {
		$error = array ();

		$online = front::online();
		// 数据消毒
		$post = array(
			'name' => isset ($_POST ['name']) ? $_POST ['name'] : '',
			'parent_id' => isset ($_POST ['parent_id']) ? $_POST ['parent_id'] : '',
			'component'  => isset ($_POST ['component']) ? $_POST ['component'] : '',
			'sort' => isset ($_POST ['sort']) ? $_POST ['sort'] : '0',			
			'user_id' => $online->user_id,		
		);


		if (get_magic_quotes_gpc()) {
			$post = array_map ('stripslashes', $post);
		}

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据验证
			$length = (strlen ($post ['name']) + mb_strlen ($post ['name'], 'UTF-8')) /2;
			if ($length ==0) {
				$error ['name'] = '分类名不能为空';
			} else {
				$count = self::selects('COUNT(*)', null, array('name'=>$post ['name'],'parent_id'=>$post ['parent_id']), null, array('column|table=channel'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['name'] = '分类名重复，请换一个分类名';
				}
			}

			if (! empty ($error)) {
				break;
			}

			// 数据入库
			$channel = new self;
			$channel->channel_id = null;
			$channel->struct ($post);	
			$channel->insert ();
			self::update_path($channel->channel_id);
			header ('Location: ?go=channel&do=browse');
			return;

		}

		// 页面显示
		foreach (array('title','copyfrom','typeid','keyword','keyword_auto','content') as $value) {
			$post [$value] = htmlspecialchars ($post [$value]);
		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 修改分类
	 */
	final static public function modify() {
		$error = array ();

		// 获取数据
		$channel = new self;
		$channel->channel_id = isset($_GET['channel_id']) ? $_GET['channel_id'] : null;
		if(! is_numeric($channel->channel_id) || ! $channel->select()) {
			$error = '该分类不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		$post = get_object_vars ($channel);

		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {

			// 数据消毒
			$post = array(
				'name' => isset ($_POST ['name']) ? $_POST ['name'] : '',
				'parent_id' => isset ($_POST ['parent_id']) ? $_POST ['parent_id'] : '',
				'component'  => isset ($_POST ['component']) ? $_POST ['component'] : '',
				'sort' => isset ($_POST ['sort']) ? $_POST ['sort'] : '0',			
	
			);
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}

			// 数据验证
			$length = (strlen ($post ['name']) + mb_strlen ($post ['name'], 'UTF-8')) /2;
			if ($length ==0) {
				$error ['name'] = '分类名不能为空';
			} else {
				$count = self::selects('COUNT(*)', null, array('name'=>$post ['name'],'parent_id'=>$post ['parent_id'],'channel_id<>?'=>$channel->channel_id), null, array('column|table=channel'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['name'] = '分类名重复，请换一个分类名';
				}
			}
			if (! empty ($error)) {
				break;
			}

			// 数据入库
			
			$channel->struct ($post);
			$channel->update ();
			self::update_path($channel->channel_id);
			exit();
			header ('Location: ?'.$_GET['query']);
			return;

		}

		// 页面显示
//		foreach (array('title','mobile','email','url','content') as $value) {
//			$post [$value] = htmlspecialchars ($post [$value]);
//		}
		self::view (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
	}
	
	/**
	 * 删除分类
	 */
	final static public function remove() {

		// 获取数据
		$channel = new self;
		$channel->channel_id = isset($_GET['channel_id']) ? $_GET['channel_id'] : null;
		if(! is_numeric($channel->channel_id) || ! $channel->select()) {
			$error = '该分类不存在';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}
		
		//分类下不能有数据
		$count = self::selects('COUNT(*)', null, array('typeid'=>$channel->channel_id), null, array('column|table=doc'=>'COUNT(*)'));
		
		if ($count > 0) {
			$error = '分类下还有数据不能删除';
			self::view ( 'error.tpl', compact ('error'));
			return;
		}else{
			$count = self::selects('COUNT(*)', null, array('parent_id'=>$channel->channel_id), null, array('column|table=channel'=>'COUNT(*)'));
			if ($count > 0) {
				$error = '分类下还有分类不能删除';
				self::view ( 'error.tpl', compact ('error'));
				return;
			}
		}
						

		// 删除数据
		$channel->delete ();
		header ('Location: ?'.$_GET['query']);
	}
	final static public function tree() {
		$online = front::online();
		$channels = self::get_channel();
		$docs = self::selects('doc_id,typeid,title,create_date,create_time,update_date,update_time,hit', null, array('user_id'=>$online->user_id),array('ORDER BY typeid ASC,doc_id DESC'),array('doc_id','assoc|table=doc'=>null));
		$sites = self::selects('site_id,typeid,title,create_date,create_time,update_date,update_time', null, array('user_id'=>$online->user_id),array('ORDER BY typeid ASC,site_id DESC'),array('site_id','assoc|table=site'=>null));
		$adds = self::selects('add_id,typeid,who', null, array('user_id'=>$online->user_id),array('ORDER BY typeid ASC,add_id DESC'),array('add_id','assoc|table=add'=>null));
		$diarys = self::selects('diary_id,typeid,title,create_date,create_time,update_date,update_time', null, array('user_id'=>$online->user_id),array('ORDER BY typeid ASC,diary_id DESC'),array('diary_id','assoc|table=diary'=>null));

		self::view ( __CLASS__ . '.' .'tree.tpl', compact ('channels','docs','sites','adds','diarys'));
	}
	/**
	 * 群删分类
	 */
	final static public function group_remove() {

//		// 获取数据
//		if(! isset($_POST['channel_id']) || !is_array($_POST['channel_id'])){
//			$error = '该分类不存在';
//			self::view ( 'error.tpl', compact ('error'));
//			return;
//		}
//
//		// 删除数据
//		self::deletes(null,null,array('channel_id'=>$_POST['channel_id']),null,__CLASS__);
//		header ('Location: ?'.$_GET['query']);
	}
/**
* 取分类数据
*/	
function get_channel(){
		$online = front::online();
		$class_arr=array();
		$channels = self::selects('channel_id,name,parent_id,sort', null, array('user_id'=>$online->user_id),array('ORDER BY sort ASC,channel_id DESC'),array('channel_id','assoc|table=channel'=>null));
		
		return $channels;
}
	/**
	 * 返回分类名称
	 */

function get_channel_table($m,$id)
{
	$parent_id ='parent_id';
	$name ='name';
	$class_id='channel_id';
	$sort='sort';
	$class_arr=self::get_channel();
	if($id=="") $id=0;
	$n = str_pad('',$m,'-',STR_PAD_RIGHT);
	$n = str_replace("-","&nbsp;&nbsp;",$n);
	foreach($class_arr as $k=>$v){
		if($v['parent_id']==$id){
		$str .= "<tr>\n";
		$str .= "	  <td>".$n."|--<a href=\"?go=channel&do=detail&amp;channel_id=".$v[$class_id]."\">".$v[$name]."</a></td>\n";
		$str .= "	  <td><div align=\"center\">".$v[$sort]."</div></td>\n";
		$str .= "	  <td><div align=\"center\"><a href=\"?go=channel&do=modify&amp;channel_id=".$v[$class_id]."\">修改</a>";
		$str .= " <a href=\"?go=channel&do=remove&amp;channel_id=".$v[$class_id]."&query=go=channel\">删除</a>";
		$str .= "</div></td>\n";
		$str .= "	</tr>\n";
		$str .=	self::get_channel_table($m+1,$v[$class_id]);
		}		
	}
	return $str;
	
}

function get_channel_select($m,$id,$index)
{	
	//global $class_arr;
	$parent_id ='parent_id';
	$name ='name';
	$class_id='channel_id';
	$sort='sort';
	
	$class_arr=self::get_channel();
	$n = str_pad('',$m,'-',STR_PAD_RIGHT);
	$n = str_replace("-","&nbsp;&nbsp;",$n);
	foreach($class_arr as $k=>$v){
	
		if($v[$parent_id]==$id){
			if($v[$class_id]==$index){
				echo "        <option value=\"".$v[$class_id]."\" selected=\"selected\">".$n."|--".$v[$name]."</option>\n";
			}else{
				echo "        <option value=\"".$v[$class_id]."\">".$n."|--".$v[$name]."</option>\n";
			}
			self::get_channel_select($m+1,$v[$class_id],$index);
			
		}
		
	}
	
}
		//更新某个分类的path信息
		function update_path($id){
						
		$id=intval($id);
		
		$parent_id = self::selects('parent_id', null, array('channel_id'=>$id),array(),array('column'=>'parent_id'));
		//$channel = self::selects('parent_id', null, array('channel_id'=>$id),array(),array('column|table=channel'=>1));


		//$sql = "SELECT channel_id,parent_id,path FROM channel WHERE channel_id = (SELECT parent_id FROM channel WHERE channel_id=$id) ";
		//$channel = self::selects($sql,null,true,array('ORDER BY sort ASC,channel_id DESC'),array('assoc'=>null));
        $channel = self::selects('channel_id,parent_id,path', null, array('channel_id'=>$parent_id),array(),array('assoc'=>null));

		if($channel['path']){//有上级，并且上级设置了path					
			$path  = $channel['path'].','. str_pad($id,5,'0',STR_PAD_LEFT);
		}elseif($channel['channel_id']){//上级分类有，但没有设置path时
			 self::update_path($channel['channel_id']);
			 self::update_path($id);
			 return;
		}else{
			$path = ','.str_pad($id,5,'0',STR_PAD_LEFT);
		}
		
		$channel = new self;
		$channel->channel_id=$id;
		$channel->path = $path;
		return $channel->update ();
	}
	//得到导航路径，根据分类ID
	function get_nav($pid){
		if(!$pid){
			return;
		}
		//$sql = "SELECT * FROM channel WHERE channel_id = ".$pid;
		//$pinfo = self::selects($sql,null,true,null,array('assoc'=>null));
		$pinfo = self::selects(null, null, array('channel_id'=>$pid),array(),array('assoc'=>null));
	
		
		//$path = preg_replace('/[0]+/is','',$pinfo['path']);//当前id也在path里。
		$path = $pinfo['path'];
		$path = trim($path,',');
		if(!$path){
			self::update_path($pid);
			return self::get_nav($pid);
		}
		$paths = explode(',',$path);
		$paths = array_map('intval',$paths);
		//pecho($pid);
		//pecho($paths);
	
		//if($path[count($path)]!=$pid)$path.=','.$pid;
		//$sql = "SELECT * FROM channel WHERE channel_id IN($path)";
		//$pinfos = self::selects($sql,null,true,null,array(null,'assoc'=>null));
		$pinfos = self::selects(null, null, array('channel_id'=>explode(',',$path)),array(),array(null,'assoc'=>null));

		foreach($pinfos as $k=>$v){
			if($v['channel_id']!=$pid)
			$nav .= '<a href="?go=channel&do=detail&channel_id='.$v['channel_id'].'">'.$v['name'].'</a>&raquo;';
			else
			$nav .= '<strong>'.$v['name'].'</strong>';
		}
		return $nav;
	}
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>