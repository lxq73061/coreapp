


<?php

?>

<div id="navigation"> 
  <?php
function format_date(){}
$tree .= "tree1.addnode(100000000, 0, \"资料\",\"\",\"\",\"#\",\"\");\n";
$tree .= "tree1.addnode(200000000, 0, \"记事\",\"\",\"\",\"#\",\"\");\n";
$tree .= "tree1.addnode(300000000, 0, \"网址\",\"\",\"\",\"#\",\"\");\n";
$tree .= "tree1.addnode(400000000, 0, \"联系人\",\"\",\"\",\"#\",\"\");\n";

foreach($channels as $v){
	$v['title'] = $v['name'];
	$orderids[] = $v['channel_id'];
    $v['parent_id']==0?$v['parent_id']=100000000:0;	
    //$v[title] = str_replace('"','',$v[title]);
	$tree .= "tree1.addnode($v[channel_id], $v[parent_id], \"$v[title]\",\"\",\"\",\"./?go=channel&do=detail&channel_id=$v[channel_id]\",\"frmView\");\n";
};
$orderids ? $orderid = max($orderids) : $orderid=0;
foreach($docs as $v){
	$orderid++;
    $v['typeid']==0?$v['typeid']=100000000:0;
    $v[title] = str_replace('"','',$v[title]);
    $tree .= "tree1.addnode($orderid, $v[typeid], \"$v[title]\",\"\",\"\",\"./?go=doc&do=detail&doc_id=$v[doc_id]\",\"frmView\");\n";

};

foreach($sites as $v){
	$orderid++;
    $v['typeid']==0?$v['typeid']=300000000:0;
    $v[title] = str_replace('"','',$v[title]);
	$tree .= "tree1.addnode($orderid, $v[typeid], \"$v[title]\",\"e1.gif\",\"\",\"./?go=site&do=detail&site_id=$v[site_id]\",\"frmView\");\n";

};

foreach($adds as $v){
	$orderid++;
    $v['typeid']==0?$v['typeid']=400000000:0;
    $v[name] = str_replace('"','',$v[name]);
	$tree .= "tree1.addnode($orderid, $v[typeid], \"$v[name]\",\"usergroupicon.gif\",\"\",\"./?go=address&do=detail&address_id=$v[address_id]\",\"frmView\");\n";

};


foreach($diarys as $v){
	$orderid++;
    
    $v[title] = str_replace('"','',$v[title]);
    $title = addslashes ($v['title']);
    $title = trim(strip_tags($title));
    $title = preg_replace("@\s@",' ',$title);
    $title = mb_strcut($title,0,30,'UTF-8');
//	$title = format_date('Ymd',$blog[create_time]);
//	$content = strip_tags($blog['content']);
//	$content = preg_replace('/[\s]+/is','',$content );
//	$content = mb_strcut($content,0,30,'gb2312');
	$v['typeid']==0?$v['typeid']=200000000:0;
	$title .= ' '.$content;
	$tree .= "tree1.addnode($orderid, $v[typeid], \"$title\",\"b.gif\",\"\",\"./?go=diary&do=detail&diary_id=$v[diary_id]\",\"frmView\");\n";

};
?>
  <script language="javascript" type="text/javascript">
	var tree1 = new YEMATree("tree1", "templates/images/tree/", "usericon.gif", "My Office", "", "");
	<?=$tree?>
	setTimeout("write_tree();",0);
function write_tree()	{

	document.getElementById('loading').style.display='none';
	tree1.write();
	change_a();
	
}
	
</script>
  <div id="usetime"></div>
  <div id="loading" class="loading"></div>
</div>

</p>
<!--<div  id='rightmenu'>
  <div id="div_ty">
    <div class="div_tyk3">
      <div class="div_tyk2">
        <div class="div_tyk">
          <div class="div_tyc">
            <ul>
              <li><a   href="#">折叠/展开</a></li>
              <li style="height:1px; overflow:hidden; background:#CCCCCC; margin:0px 3px; padding:0px;" ></li>   
              <hr>
              <li><a   href="view_1.php">刷新</a></li>
              <li><a   href="admin/admin_channel.php" target="frmView">新建目录</a></li>
              <li><a   href="admin/admin_note.php" target="frmView">新建资料</a></li>
              <li><a   href="admin/admin_blog.php" target="frmView">新建日记</a></li>
              <li><a   href="admin/admin_site.php" target="frmView">新建网站</a></li>
              <li><a   href="#">删除</a></li>
              <li><a   href="#">重命名</a></li>
              <hr>
              <?php					
					if ($_SESSION["adminid"] ==1){
					?>
              <li><a   href="admin/admin_vhost.php" target="frmView">主机管理</a></li>
              <hr>
              <li><a   href="admin/admin_user.php" target="frmView">用户管理</a></li>
              <?php
					}
					?>
              <li><a   href="admin/admin_user.php?id=<?php echo  $_SESSION["uid"]?>" target="frmView">更改密码</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>-->
<script   language="javascript">   
  <!--   
//var menu   =  document.getElementById("rightmenu");  
//var menu_a =  menu.getElementsByTagName('a');
//document.oncontextmenu   =   function(e){
//e   =   window.event   ||   e;   
//x   =   e.x   ||   e.layerX;   
//y   =   e.y   ||   e.layerY;   
//menu.style.left   =   x   +   "px";   
//menu.style.top     =   y   +   "px";
//
//menu.style.display   =   "block";   
//return   false;   
//}   
//document.onclick   =   function(){   
//  menu.style.display   =   "none"; 
// 
//} 
//document.onmousedown = function(){
//	menu_a[6].disabled='disabled';
//	menu_a[7].disabled='disabled';
//}
//var x = document.getElementById('navigation');
//if (x){
//	var aa =  x.getElementsByTagName('a');
//	if(aa) {
//	for(var i=0;i<aa.length;i++){	
//		aa[i].oncontextmenu = function(e){			
//			var s_url=this.href;
//			if (s_url.search("note") != -1){	
//				var s_type = "note";	
//			}else if(s_url.search("site") != -1){
//				var s_type = "site";	
//			}else if(s_url.search("adds") != -1){
//				var s_type = "adds";	
//			}else if(s_url.search("blog") != -1){
//				var s_type = "blog";	
//			}else if(s_url.search("channel") != -1){
//				var s_type = "channel";	
//			}else{
//				var s_type = "";	
//			}									
//			if(s_type){
//				var params=s_url.split('id=');//得到当前链接的ID
//				var s_id =params[1];
//				menu_a[6].href="admin/admin_"+s_type+".php?action=del&id="+s_id;
//				menu_a[7].href="admin/admin_"+s_type+".php?action=cname&id="+s_id;
//				menu_a[6].target="frmView";
//				menu_a[7].target="frmView";				
//				menu_a[6].disabled = '';
//				menu_a[7].disabled = '';					
//				
//			}	
//		}	
//	}
//	}	
//}
  //-->   
  </script>
 