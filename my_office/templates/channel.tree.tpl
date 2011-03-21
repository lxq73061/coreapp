<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<link href="templates/images/tree/menu.css" rel="stylesheet" type="text/css" />
<script src="templates/images/tree/menu.js" type="text/javascript"></script>
<style>
body {
	SCROLLBAR-FACE-COLOR: #5b84c4;
	SCROLLBAR-HIGHLIGHT-COLOR: #d2def2;
	SCROLLBAR-3DLIGHT-COLOR: #d2def2;
	SCROLLBAR-ARROW-COLOR: #fff;
	SCROLLBAR-TRACK-COLOR: #d2def2;
	SCROLLBAR-DARKSHADOW-COLOR: #d2def2;
}
.loading {
	background-image:url("templates/images/loading.gif");
	background-position:center center;
	background-repeat:no-repeat;
	height:200px;
}
</style>
</head>

<body>
<p>
  <input type="button" value="++" onClick="tree1.openall()" title="展开全部" alt="展开全部" />
  <input type="button" value="--" onClick="tree1.closeall()" title="折叠全部"  />
  <input type="button" value="&lt;&lt;" onClick="addw('-')" />
  <input type="button" value="&gt;&gt;" onClick="addw('+')" />
<div id="navigation">
  <?php
function format_date(){}

foreach($channels as $c){
	$c['title'] = $c['name'];
	$orderid = $c['channel_id'];
	$tree .= "tree1.addnode($c[channel_id], $c[parent_id], \"$c[title]\",\"\",\"\",\"./?go=channel&do=detail&channel_id=$c[channel_id]\",\"frmView\");\n";
};

foreach($docs as $notes){
	$orderid++;
    $tree .= "tree1.addnode($orderid, $notes[typeid], \"$notes[title]\",\"\",\"\",\"./?go=doc&do=detail&doc_id=$notes[doc_id]\",\"frmView\");\n";

};

foreach($sites as $site){
	$orderid++;
	$tree .= "tree1.addnode($orderid, $site[typeid], \"$site[sitename]\",\"e1.gif\",\"\",\"./?go=site&do=detail&site_id=$site[site_id]\",\"frmView\");\n";

};

foreach($adds as $add){
	$orderid++;

		$tree .= "tree1.addnode($orderid, $add[typeid], \"$add[who]\",\"e1.gif\",\"\",\"./?go=add&do=detail&add_id=$add[add_id]\",\"frmView\");\n";

};


foreach($diarys as $blog){
	$orderid++;
	$title = format_date('Ymd',$blog[create_time]);
	$content = strip_tags($blog['content']);
	$content = preg_replace('/[\s]+/is','',$content );
	$content = mb_strcut($content,0,30,'gb2312');
	
	$title .= ' '.$content;
	$tree .= "tree1.addnode($orderid, 5, \"$title\",\"b.gif\",\"\",\"./?go=diary&do=detail&diary_id=$blog[diary_id]\",\"frmView\");\n";

};
?>
  <script language="javascript" type="text/javascript">
	var tree1 = new YEMATree("tree1", "templates/images/tree/", "usericon.gif", "My Office", "", "");
	<?=$tree?>
	setTimeout("write_tree();",1);
function write_tree()	{

	document.getElementById('loading').style.display='none';
	tree1.write();
	
}
	
</script>
  <div id="usetime"></div>
  <div id="loading" class="loading"></div>
</div>
<SCRIPT>
function addw(a){
/*调整左栏宽度的函数*/
	var frame = window.parent.document.getElementById("info_show_frame");   	
	arr=frame.cols.split(",");   
	//for(i=0;i<arr.length;i++)alert(i+":"+arr[i]) ;  
	var iw = parseInt(arr[0]);	
	if(a == '+'){
		frame.cols= iw + 10 + ",12,*";
	}else{
		frame.cols= iw - 10 + ",12,*";
	}
}
</SCRIPT>
</p>
<div  id='rightmenu'>
  <div id="div_ty">
    <div class="div_tyk3">
      <div class="div_tyk2">
        <div class="div_tyk">
          <div class="div_tyc">
            <ul>
              <li><a   href="#">折叠/展开</a></li>
              <!--<li style="height:1px; overflow:hidden; background:#CCCCCC; margin:0px 3px; padding:0px;" ></li>   -->
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
</div>
<script   language="javascript">   
  <!--   
var menu   =  document.getElementById("rightmenu");  
var menu_a =  menu.getElementsByTagName('a');
document.oncontextmenu   =   function(e){
e   =   window.event   ||   e;   
x   =   e.x   ||   e.layerX;   
y   =   e.y   ||   e.layerY;   
menu.style.left   =   x   +   "px";   
menu.style.top     =   y   +   "px";

menu.style.display   =   "block";   
return   false;   
}   
document.onclick   =   function(){   
  menu.style.display   =   "none"; 
 
} 
document.onmousedown = function(){
	menu_a[6].disabled='disabled';
	menu_a[7].disabled='disabled';
}
var x = document.getElementById('navigation');
if (x){
	var aa =  x.getElementsByTagName('a');
	if(aa) {
	for(var i=0;i<aa.length;i++){	
		aa[i].oncontextmenu = function(e){			
			var s_url=this.href;
			if (s_url.search("note") != -1){	
				var s_type = "note";	
			}else if(s_url.search("site") != -1){
				var s_type = "site";	
			}else if(s_url.search("adds") != -1){
				var s_type = "adds";	
			}else if(s_url.search("blog") != -1){
				var s_type = "blog";	
			}else if(s_url.search("channel") != -1){
				var s_type = "channel";	
			}else{
				var s_type = "";	
			}									
			if(s_type){
				var params=s_url.split('id=');//得到当前链接的ID
				var s_id =params[1];
				menu_a[6].href="admin/admin_"+s_type+".php?action=del&id="+s_id;
				menu_a[7].href="admin/admin_"+s_type+".php?action=cname&id="+s_id;
				menu_a[6].target="frmView";
				menu_a[7].target="frmView";				
				menu_a[6].disabled = '';
				menu_a[7].disabled = '';					
				
			}	
		}	
	}
	}	
}
  //-->   
  </script>
</body>
</html>