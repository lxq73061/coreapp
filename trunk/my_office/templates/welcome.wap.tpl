<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome</title>
</head>

<body>
<div>欢迎:<?php echo $online->username?> <a href="?go=user&do=logout" target="_top">退出</a> | <a href="#">3G</a></div>         

 <a href="?go=user&do=browse">用户</a> 
 <a href="?go=doc&do=browse">文章</a> 
 <a href="?go=channel&do=browse">分类</a> 
 <a href="?go=diary&do=browse">日记</a> 
 <a href="?go=site&do=browse">网址</a> 
 <a href="?go=address&do=browse">通讯录</a> 
 <a href="?go=book&do=browse">帐本</a><hr />
<form>
<input type="hidden" name="do" value="browse">


<input type="text" name="keyword" value="<?php echo $get['keyword']?>">&nbsp;
<select name="go">
	<option value="address">联系人</option>
	<option value="doc"  selected="selected" >文章</option>
	<option value="site" >收藏</option>
    <option value="diary" >日志</option>
</select>
<input type="submit" value="查询">
</form>
</body>
</html>
