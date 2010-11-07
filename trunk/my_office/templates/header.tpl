<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo get_title($_GET); ?></title>
</head>

<body>
○<a href="?go=user&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">用户</a>&nbsp;
○<a href="?go=doc&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章</a>&nbsp;
○<a href="?go=channel&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">分类</a>&nbsp; 
○<a href="?go=diary&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">日记</a>&nbsp; 
○<a href="?go=site&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">网址</a>&nbsp; 
○<a href="?go=address&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">通讯录</a>&nbsp;


○<a href="?go=user&do=logout">退出</a><br>
<hr />