<?php include('header.tpl')?>


○<a href="?<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章列表</a>&nbsp;
○<a href="?go=doc&do=append">添加文章</a></ul>


<form method="post">
<fieldset>
<legend>用户详细</legend>

标　题：<?php echo htmlspecialchars($doc->title); ?><br>
	<br>
来　源：<?php echo $doc->copyfrom; ?><br>
	<br>
分　类：<?php echo $doc->get_typeid(); ?><br>
	<br>
关键词：<?php echo htmlspecialchars($doc->keyword); ?><br>
	<br>
内容：<?php echo htmlspecialchars($doc->content); ?><br>
	<br>
</fieldset>
</form>

</body>
</html>