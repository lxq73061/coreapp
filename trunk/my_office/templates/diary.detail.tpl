<?php include('header.tpl')?>


○<a href="?<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">日记列表</a>&nbsp;
○<a href="?go=diary&do=append">添加日记</a></ul>


<form method="post">
<fieldset>
<legend>详细</legend>

标　题：<?php echo htmlspecialchars($diary->title); ?><br>
	<br>
心　情：<?php echo $diary->mood; ?><br>
	<br>
天　气：<?php echo $diary->weather; ?><br>
	<br>
    
分　类：<?php echo $diary->get_typeid(); ?><br>
	<br>
内　容：<?php echo htmlspecialchars($diary->content); ?><br>
	<br>
</fieldset>
</form>

</body>
</html>