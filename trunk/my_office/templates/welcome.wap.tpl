<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome</title>
</head>

<body>
○<a href="?go=user&do=browse">用户</a> 
○<a href="?go=doc&do=browse">文章</a> 
○<a href="?go=channel&do=browse">分类</a> 
○<a href="?go=diary&do=browse">日记</a> 
○<a href="?go=site&do=browse">网址</a> 
○<a href="?go=address&do=browse">通讯录</a> 
○<a href="?go=book&do=browse">帐本</a><hr />

<form>
<input type="hidden" name="do" value="browse">


<input type="text" name="keyword" value="<?php echo $get['keyword']?>">&nbsp;
<select name="go">
	<option value="address" selected="selected" >address</option>
	<option value="doc" >doc</option>
	<option value="site" >site</option>
    <option value="diary" >diary</option>
</select>
<input type="submit" value="查询">
</form>
</body>
</html>
