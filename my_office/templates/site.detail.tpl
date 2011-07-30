<?php include('header.tpl')?>


○<a href="?<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">网址列表</a>&nbsp;
○<a href="?go=site&do=append">添加网址</a></ul>


<form method="post">
<fieldset>
<legend>详细</legend>

标　题：<?php echo htmlspecialchars($site->title); ?><br>
	<br>
网址：<?php echo $site->url; ?><br>
	<br>
分　类：<?php echo $site->get_typeid(); ?><br>
	<br>
内容：<?php echo htmlspecialchars($site->content); ?><br>
	<br>
</fieldset>
</form>
 <?=related::get('site',$site->site_id)?>
</body>
</html>