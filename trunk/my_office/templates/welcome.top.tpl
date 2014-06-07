<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="favicon.ico" />

<title>我的网站</title>

<script src="skin/js/jquery-1.9.1.min.js"></script>
<script src="skin/js/js.js?20131006"></script>
<script src="skin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="skin/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="templates/css/ui.tabs.css" rel="stylesheet" type="text/css" />
<link href="skin/css/css.css" rel="stylesheet" type="text/css" />

<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <script src="skin/bootstrap/js/respond.src.js"></script>
<![endif]-->

<!--[if IE 6]>
<link href="skin/css/css-ie6.css" rel="stylesheet">
<![endif]-->
<!--[if IE 7]>
<link href="skin/css/css-ie6.css" rel="stylesheet">
<![endif]-->


</head>
<body id="welcome_top">
<div id="top">
<div class="logo" onclick="top.midFrame.Submit_onclick()"><img height="37" src="skin/images/logo.gif"   width="42"  style="vertical-align:middle" />
<span class="title"></span></div>






<ul class="menu">
<?php if($online->grade<3):?> 
    
<?php else:?>

<?php endif?>   
<li><a target="frmView"  href="?go=welcome&do=right">桌面</a> | 
<li><a target="frmView" id="user_browse"  href="?go=user&do=browse">用户</a>
    <a target="frmView" id="user_append" href="?go=user&do=append" title="添加用户">+</a></li>
 <li><a target="frmView" id="doc_browse" href="?go=doc&do=browse">文章</a>
    <a target="frmView" id="doc_append" href="?go=doc&do=append" title="添加文章">+</a></li>
 <li><a target="frmView" id="channel_browse"  href="?go=channel&do=browse">分类</a>
    <a target="frmView" id="channel_append"  href="?go=channel&do=append" title="添加分类">+</a></li>
 <li><a target="frmView" id="diary_browse"  href="?go=diary&do=browse">日记</a>
    <a target="frmView" id="diary_append"  href="?go=diary&do=append" title="添加日记">+</a></li>
 <li><a target="frmView" id="site_browse"  href="?go=site&do=browse">网址</a>
    <a target="frmView" id="site_append"  href="?go=site&do=append" title="添加网址">+</a></li>
 <li><a target="frmView" id="address_browse"  href="?go=address&do=browse">通讯录</a>
    <a target="frmView" id="address_append"  href="?go=address&do=append" title="添加通讯录">+</a></li>
 <li><a target="frmView" id="book_browse"  href="?go=book&do=browse">帐本</a>
	<a target="frmView" id="book_append"  href="?go=book&do=append" title="添加帐本">+</a></li>


   

</ul>
<div class="right " >

</div>                                                              
<div class="index_login visible-lg">
欢迎登录：<a href="?go=user&do=modify_info" title="修改密码"><?php echo $online->username?></a> ( <?php if($online->grade==1):?>高级管理员
<?php elseif($online->grade==2):?>管理员
<?php else:?>
 普通用户
<?php endif?>)<a href="?go=user&do=logout" target="_top">退出</a></div>         
</div>
<div style="clear:both"></div>

<script>

$(function(){
 change_a();
});
</script>



