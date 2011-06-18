<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" />
<title>我的网站</title>
<link href="templates/css/ui.tabs.css" rel="stylesheet" type="text/css" />
<link href="templates/css/css.css" rel="stylesheet" type="text/css" />

<script src="includes/lib/jquery/jquery.min.js" type="text/javascript"></script>

</head>
<body id="welcome_top">
<div id="top">
<div class="logo"><img height="37" src="templates/images/logo.gif"   width="42"  style="vertical-align:middle" />
<span class="title">MyOffice</span></div>

<div class="menu"><a target="frmView"  href="?go=welcome&do=right">桌面</a> | 
<a target="frmView"  href="?go=user&do=browse">用户</a>&nbsp;<a target="frmView"  href="?go=user&do=append" title="添加用户">+</a> | 
<a target="frmView"  href="?go=doc&do=browse">文章</a>&nbsp;<a target="frmView"  href="?go=doc&do=append" title="添加文章">+</a> | 
<a target="frmView"  href="?go=channel&do=browse">分类</a>&nbsp;<a target="frmView"  href="?go=channel&do=append" title="添加分类">+</a> | 
<a target="frmView"  href="?go=diary&do=browse">日记</a>&nbsp;<a target="frmView"  href="?go=diary&do=append" title="添加日记">+</a> | 
<a target="frmView"  href="?go=site&do=browse">网址</a>&nbsp;<a target="frmView"  href="?go=site&do=append" title="添加网址">+</a> | 
<a target="frmView"  href="?go=address&do=browse">通讯录</a>&nbsp;<a target="frmView"  href="?go=address&do=append" title="添加通讯录">+</a> | 
<a target="frmView"  href="?go=book&do=browse">帐本</a>&nbsp;<a target="frmView"  href="?go=book&do=append" title="添加帐本">+</a>
</div>
<div class="right">
<!--
<a href="#" onclick="javascript:history.go(-1);" ><img src="templates/images/undo.gif" alt="{lang back_off}" width="32" height="32" border="0" /></a>
<a href="#" onclick="javascript:history.go(1);"><img src="templates/images/redo.gif" alt="{lang go_ahead}" width="32" height="32" border="0" /></a>
<a href="#" onclick="top.frmView.location.reload();top.leftFrame.location.reload();" ><img src="templates/images/reload.gif" alt="刷新" width="32" height="32" border="0" style="vertical-align:middle" /></a>
<a href="?go=user&do=logout" target="_top"><img src="templates/images/exit.gif" alt="退出" width="32" height="32" border="0" style="vertical-align:middle"  /></a>-->
</div>                                                              
<div class="index_login">
欢迎登录：<?php echo $online->username?> <a href="?go=user&do=logout" target="_top">退出</a></div>         
</div>
<div style="clear:both"></div>

<script>

$(function(){
  $('a').each(function(){
      if($(this).attr('href').indexOf('?')!=-1 && $(this).attr('href').indexOf('welcome')==-1 && $(this).attr('href').indexOf('logout')==-1){
		
            $(this).click(function(){
                var url=$(this).attr('href');
				 var title=$(this).attr('title');				
                if(title=='') title=$(this).text();
                var type=$(this).attr('class');//YEMATree_A
                //alert(typeof top.frmView.addNewTab);
                var type=null;//类型(图标)
                top.frmView.addNewTab(url,title,type)
                return false;
          });
      }
  });
  
  
});
</script>
