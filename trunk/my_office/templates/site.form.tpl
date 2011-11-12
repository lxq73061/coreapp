<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
○<a href="?go=<?=$_GET['go']?>&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">网址列表</a>&nbsp;
○<a href="?go=<?=$_GET['go']?>&do=append">添加网址</a><br>

<form method="post" action="?go=<?=$_GET['go']?>&do=<?php echo $_GET['do']; ?>&site_id=<?php echo $_GET['site_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加网址' : '修改网址'; ?></legend>
<label>名称： <input type="text" name="title" value="<?php echo $post['title']; ?>"></label>
	<font color="red">*</font><?php if(isset($error['title'])): ?><font color="red"><?php echo $error['title']; ?></font><?php endif; ?><br>
<label>网址： <input type="text" name="url" value="<?php echo $post['url']; ?>"></label><font color="red">*</font><?php if(isset($error['url'])): ?><font color="red"><?php echo $error['url']; ?></font><?php endif; ?><br>
<label>分　类：
<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	echo channel::get_channel_select(0,0,$post['typeid'],0,'site');
			?>
          </select>
	<font color="red">*</font>
	<?php if(isset($error['typeid'])): ?><font color="red"><?php echo $error['typeid']; ?></font><?php endif; ?></label><br>

<label>内　容： 
  <textarea name="content"><?php echo $post['content']; ?></textarea></label>
	<?php if(isset($error['content'])): ?><font color="red"><?php echo $error['content']; ?></font><?php endif; ?>
	<br>
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>