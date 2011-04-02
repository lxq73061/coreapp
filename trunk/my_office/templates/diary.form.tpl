<?php
define('GET_DATE',true);
include('header.tpl')?>
○<a href="?go=<?=$_GET['go']?>&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">日记列表</a>&nbsp;
○<a href="?go=<?=$_GET['go']?>&do=append">添加日记</a><br>

<form method="post" action="?go=<?=$_GET['go']?>&do=<?php echo $_GET['do']; ?>&diary_id=<?php echo $_GET['diary_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加日记' : '修改日记'; ?></legend>

<label>日期： <input type="text" id="datepicker" readonly="readonly" name="diary_date" value="<?php echo $post['diary_date']; ?>"></label>
<!-- 我修改 -->

	<font color="red">*</font><?php if(isset($error['diary_date'])): ?><font color="red"><?php echo $error['diary_date']; ?></font><?php endif; ?><br>
<label>标题： <input type="text" name="title" value="<?php echo $post['title']; ?>"></label>
	<font color="red">*</font><?php if(isset($error['title'])): ?><font color="red"><?php echo $error['title']; ?></font><?php endif; ?><br>
<label>心情： <input type="text" name="mood" value="<?php echo $post['mood']; ?>"></label><?php if(isset($error['mood'])): ?><font color="red"><?php echo $error['mood']; ?></font><?php endif; ?><br>
<label>天气： <input type="text" name="weather" value="<?php echo $post['weather']; ?>"></label><?php if(isset($error['weather'])): ?><font color="red"><?php echo $error['weather']; ?></font><?php endif; ?><br>
   <textarea name="content" style="DISPLAY: none"><?php echo $post['content']; ?></textarea>
<iframe id=content___Frame src="/includes/lib/fckeditor/editor/fckeditor.html?InstanceName=content&Toolbar=Default" frameborder=0 width=95% scrolling=no height=500>	</iframe>
 
	<br>
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>