<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
<div id="nav">
	<div class="left"><?=channel::get_nav($site->typeid)?></div>
	<div class="right"><a href="?go=site&do=modify&site_id=<?=$site->site_id?>&query=<?php echo urlencode($query) ?>">[编辑]</a> <a href="?go=site&do=append&query=<?php echo urlencode($query) ?>">[新建]</a></div>
</div>
<div class="doc_content_box">

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
 </div>
</body>
</html>