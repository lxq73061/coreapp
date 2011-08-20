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
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl');
	}
	
	/**
	 * 分类列表
	 */
	final static public function browse() {

		// 数据消毒
/*		$get = array(
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
		}*/
		$query = $_SERVER['QUERY_STRING'];
	
		front::view2 (__CLASS__ . '.list.tpl', compact ('channels','get','page','query'));
	}
	
	/**
	 * 分类详细
	 */
	final static public function detail() {
		// 数据消毒
		$get = array(
			'page_doc'  => isset ($_GET ['page_doc']) ? $_GET ['page_doc'] : '',
			'page_site'  => isset ($_GET ['page_site']) ? $_GET ['page_site'] : '',
			'page_address'  => isset ($_GET ['page_address']) ? $_GET ['page_address'] : '',
			'page_diary'  => isset ($_GET ['page_diary']) ? $_GET ['page_diary'] : '',
			
		);
		if (get_magic_quotes_gpc()) {
			$get = array_map ('stripslashes', $get);
		}
		
		// 获取数据
		$channel = new self;
		$channel->channel_id = isset($_GET['channel_id']) ? $_GET['channel_id'] : null;
		if(! is_numeric($channel->channel_id) || ! $channel->select()) {
			$error = '该分类不存在';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		
		$online = front::online();
		// 获取数据
		$where = array();
		
		if (strlen($get['typeid'])>0){
			$where ['typeid'] = (int)$get['typeid'];
		}
		
		$path = ','.str_pad($channel->channel_id,5,'0',STR_PAD_LEFT);
		

		
		$channels =  self::selects('channel_id',null,array('path like ?'=>'%'.$path.'%'),array(),array(null,'column|table=channel'=>'channel_id'));
	 
//		$tablepre = self::init('prefix_search');
//		$sql = "SELECT a.*,b.name  FROM {$tablepre}doc as a LEFT JOIN {$tablepre}channel as b  on a.typeid = b.channel_id  
//		WHERE a.user_id='$online->user_id' AND  b.channel_id IN(
//			SELECT channel_id FROM {$tablepre}channel WHERE path like'%$path%'
//		)";
//		
//		$docs = self::selects($sql,null,true,$other);

		$page_doc = array('page'=>$get['page_doc'],'size'=>100);
		$page_site = array('page'=>$get['page_site'],'size'=>100);
		$page_address = array('page'=>$get['page_address'],'size'=>100);
		$page_diary = array('page'=>$get['page_diary'],'size'=>100);
		
		$class_arr=self::get_channel();
		//pecho($class_arr);
		
		$docs = self::selects('*',null,array('user_id'=>$online->user_id,'typeid'=>$channels),array('ORDER BY create_date DESC','page'=>&$page_doc),
		array(null,'|table=doc'=>''));
		
		$sites = self::selects('*',null,array('user_id'=>$online->user_id,'typeid'=>$channels),array('ORDER BY create_date DESC','page'=>&$page_site),array(null,'|table=site'=>''));
		$addresss = self::selects('*',null,array('user_id'=>$online->user_id,'typeid'=>$channels),array('ORDER BY create_date DESC','page'=>&$page_address),array(null,'|table=address'=>''));
		$diarys = self::selects('*',null,array('user_id'=>$online->user_id,'typeid'=>$channels),array('ORDER BY create_date DESC','page'=>&$page_diary),array(null,'|table=diary'=>''));
		
		
		foreach($docs as &$v)$v->name = $class_arr[$v->typeid]['name'];
		foreach($sites as &$v)$v->name = $class_arr[$v->typeid]['name'];
		foreach($addresss as &$v)$v->name = $class_arr[$v->typeid]['name'];
		foreach($diarys as &$v)$v->name = $class_arr[$v->typeid]['name'];
			
		
		

		
		
		
		$query = $_SERVER['QUERY_STRING'];
		// 页面显示
		front::view2 (__CLASS__ . '.' . __FUNCTION__.'.tpl', compact ('docs','sites','addresss','diarys','channel',
		'page_doc','page_site','page_address','page_diary',
		'query'));
	}
	
	/**
	 * 添加分类
	 */
	final static public function append() {
		$error = array ();

		$online = front::online();


		// 表单处理
		while (isset ($_SERVER ['REQUEST_METHOD']) && $_SERVER ['REQUEST_METHOD'] === 'POST') {
		
			// 数据消毒
			$post = array(
				'name' => isset ($_POST ['name']) ? $_POST ['name'] : '',
				'parent_id' => isset ($_POST ['parent_id']) ? (int)$_POST ['parent_id'] : '0',
				'component'  => isset ($_POST ['component']) ? $_POST ['component'] : '',
				'sort' => isset ($_POST ['sort']) ? (int)$_POST ['sort'] : '0',			
				'user_id' => $online->user_id,		
			);
	
	
			if (get_magic_quotes_gpc()) {
				$post = array_map ('stripslashes', $post);
			}		
		
		
		
			// 数据验证
			$length = (strlen ($post ['name']) + mb_strlen ($post ['name'], 'UTF-8')) /2;
			if ($length ==0) {
				$error ['name'] = '分类名不能为空';
			} else {
				$count = self::selects('COUNT(*)', null, array('name'=>$post ['name'],'parent_id'=>$post ['parent_id']), null, array('column|table=channel'=>'COUNT(*)'));
				if ($count > 0) {
					$error ['name'] = '分类名重复，请换一个分类名';
				}
				if($post ['parent_id']){
					$component = self::selects('component', null, array('channel_id'=>$post ['parent_id']), null, array('column|table=channel'=>'component'));
					$post['component'] = $component;
				}else{
					//顶级分类，可以任意指定
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
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
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
			front::view2 ( 'error.tpl', compact ('error'));
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
				}else{
					$parent_path = self::selects('path',null,array('channel_id'=>$post['parent_id']),null,array('column'=>'path'));
					if(strstr($parent_path,self::make_path($channel->channel_id))){
						$error ['name'] = '不能指定为自己的下级';						
					}
					if($post ['parent_id']){
						$component = self::selects('component', null, array('channel_id'=>$post ['parent_id']), null, array('column|table=channel'=>'component'));
						$post['component'] = $component;
					}else{
						//顶级分类，可以任意指定
					}
		
					
				}
			}
			if (! empty ($error)) {
				break;
			}

			// 数据入库
			
			$channel->struct ($post);
			$channel->update ();
			self::update_path($channel->channel_id);
			//exit();
			//header ('Location: ?'.$_GET['query']);
			header ('Location: ?'.$_GET['query']);
			
			return;

		}

		// 页面显示
//		foreach (array('title','mobile','email','url','content') as $value) {
//			$post [$value] = htmlspecialchars ($post [$value]);
//		}
		front::view2 (__CLASS__ . '.' . 'form.tpl', compact ('post', 'error'));
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
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}
		
		//分类下不能有数据
		$count = self::selects('COUNT(*)', null, array('typeid'=>$channel->channel_id), null, array('column|table=doc'=>'COUNT(*)'));
		
		if ($count > 0) {
			$error = '分类下还有数据不能删除';
			front::view2 ( 'error.tpl', compact ('error'));
			return;
		}else{
			$count = self::selects('COUNT(*)', null, array('parent_id'=>$channel->channel_id), null, array('column|table=channel'=>'COUNT(*)'));
			if ($count > 0) {
				$error = '分类下还有分类不能删除';
				front::view2 ( 'error.tpl', compact ('error'));
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
		$adds = self::selects('address_id,typeid,name', null, array('user_id'=>$online->user_id),array('ORDER BY typeid ASC,address_id DESC'),array('address_id','assoc|table=address'=>null));
		$diarys = self::selects('diary_id,typeid,title,create_date,create_time,update_date,update_time', null, array('user_id'=>$online->user_id),array('ORDER BY typeid ASC,diary_id DESC'),array('diary_id','assoc|table=diary'=>null));
		
		return	front::view2 ( __CLASS__ . '.' .'tree.tpl', compact ('channels','docs','sites','adds','diarys'),null,false);
	}
	/**
	 * 群删分类
	 */
	final static public function group_remove() {

//		// 获取数据
//		if(! isset($_POST['channel_id']) || !is_array($_POST['channel_id'])){
//			$error = '该分类不存在';
//			front::view2 ( 'error.tpl', compact ('error'));
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
		$channels = self::selects('channel_id,name,parent_id,sort,path,component', null, array('user_id'=>$online->user_id),array('ORDER BY sort ASC,channel_id DESC'),array('channel_id','assoc|table=channel'=>null));
		
		return $channels;
}
	/**
	 * 返回分类名称
	 */

function get_channel_table($m,$id)
{
	return self::get_channel_select($m,$id,NULL,NULL,NULL,'table');
/*	$parent_id ='parent_id';
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
	return $str;*/
	
}
/**
*级别,当前父ID,父ID,分类ID,前缀,类型
*/
function get_channel_select($m,$id,$p_id,$c_id=NULL,$c_type=NULL,$pLineType='',$type='option')
{	
	static $class_arr;
	//global $class_arr;
	$parent_id ='parent_id';
	$name ='name';
	$class_id='channel_id';
	$sort='sort';
	$type_name ='component';

	if(!$class_arr)$class_arr=self::get_channel();

	$c_path = self::make_path($c_id);
	foreach($class_arr as $k=>$v){	
		if($v[$parent_id]==$id && ($c_type==NULL || $c_type==$v[$type_name])){
			$childrenArray[]=$v;
		}		
	}

	if($type=='check') return sizeof($childrenArray);//仅判断是否有下级时用.
	
	if($childrenArray_length = sizeof($childrenArray))
	foreach($childrenArray as $k=>$v){
			$pChildrenExists = $ChildrenExists;//上一个是否有下级
			$ChildrenExists =self::get_channel_select(NULL,$v[$class_id],NULL,NULL,$c_type,NULL,'check');
			//$ChildrenExists = NodeExists($childrenArray[$k][$class_id]);
			if($ChildrenExists) {//有下级
				if($k == $childrenArray_length - 1) {
					$NodeType = "┗&nbsp;";//
					$LineType = "&nbsp;&nbsp;";//
				}else {
					if($pChildrenExists)
					$NodeType = "┝&nbsp;";
					else
					$NodeType = "┡&nbsp;";//
					
					$LineType = "┆&nbsp;";//
				}
			}else { //无下级
				if($k == $childrenArray_length - 1) {
						if($pChildrenExists)
						$NodeType = "┕&nbsp;";
						else
						$NodeType = "┗&nbsp;";//YEMATreeLeaf
				}else {
					if($pChildrenExists)
						$NodeType = "┢&nbsp;";
					else
						$NodeType = "┣&nbsp;";//YEMATreeLeafEnd
				}
					
			}
			if($type=='option'){
				if($v[$class_id]==$p_id){
					$html .= "        <option value=\"".$v[$class_id]."\" selected=\"selected\">".$pLineType.$NodeType.$v[$name]."</option>\n";
				}else{
					$html .= "        <option value=\"".$v[$class_id]."\" ".(strstr($v['path'],$c_path)?'disabled':'').">".$pLineType.$NodeType.$v[$name]."</option>\n";
				}
			}else{//table
				$html .= "<tr>\n";
				$html .= "	  <td>".$pLineType.$NodeType."<a href=\"?go=channel&do=detail&amp;channel_id=".$v[$class_id]."\">".$v[$name]."</a></td>\n";
				$html .= "	  <td><div align=\"center\">".$v[$sort]."</div></td>\n";
				$html .= "	  <td><div align=\"center\"><a href=\"?go=channel&do=modify&amp;channel_id=".$v[$class_id]."\">修改</a>";
				$html .= " <a href=\"?go=channel&do=remove&amp;channel_id=".$v[$class_id]."&query=go=channel\" onclick=\"return  confirm('您确定要删除？')\">删除</a>";
				$html .= "</div></td>\n";
				$html .= "	</tr>\n";
		
			}
			$ChildrenExists =self::get_channel_select($m+1,$v[$class_id],$p_id,$c_id,$c_type,$pLineType.$LineType,$type);
			$html .=$ChildrenExists;
			
	}
	return $html;
	
}
	//更新某个分类的path信息
	function update_path($id){
						
		$id=intval($id);
		
		$parent_id = self::selects('parent_id', null, array('channel_id'=>$id),array(),array('column|table=channel'=>'parent_id'));
		//$channel = self::selects('parent_id', null, array('channel_id'=>$id),array(),array('column|table=channel'=>1));


		//$sql = "SELECT channel_id,parent_id,path FROM channel WHERE channel_id = (SELECT parent_id FROM channel WHERE channel_id=$id) ";
		//$channel = self::selects($sql,null,true,array('ORDER BY sort ASC,channel_id DESC'),array('assoc'=>null));
       // $channel = self::selects('channel_id,parent_id,path', null, array('channel_id'=>$parent_id),array(),array('assoc'=>null));
		$channel = self::selects('channel_id,parent_id,path', null, array('channel_id'=>$parent_id),array(),array('assoc|table=channel'=>null));

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




		//$pinfo = self::selects(null, null, array('channel_id'=>$pid),array(),array('assoc|table=channel'=>null));
		$path = $pinfo['path'];
		$channel = new channel;
		$channel->channel_id = $pid;
		$channel->select();	
		
		//$path = preg_replace('/[0]+/is','',$pinfo['path']);//当前id也在path里。		
		$path = trim($channel->path,',');
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
		$pinfos = self::selects(null, null, array('channel_id'=>explode(',',$path)),array(),array(null,'assoc|table=channel'=>null));

		foreach($pinfos as $k=>$v){
			if($v['channel_id']!=$pid)
			$nav .= '<a href="?go=channel&do=detail&channel_id='.$v['channel_id'].'">'.$v['name'].'</a>&raquo;';
			else
			$nav .= '<strong>'.$v['name'].'</strong>';
		}
		return $nav;
	}
	function make_path($id){
		return str_pad($id,5,'0',STR_PAD_LEFT);
	}
}

/**
 * 执行(execute)
 */
//user::stub () and user::main ();
?>