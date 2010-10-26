<html>
<head>
<title><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加文章' : '修改文章'; ?></title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>
○<a href="?go=doc&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章列表</a>&nbsp;
○<a href="?go=doc&do=append">添加文章</a><br>

<form method="post" action="?go=doc&do=<?php echo $_GET['do']; ?>&doc_id=<?php echo $_GET['doc_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加文章' : '修改文章'; ?></legend>
<label>文章名： <input type="text" name="title" value="<?php echo $post['title']; ?>"></label>
	<font color="red">*</font><?php if(isset($error['title'])): ?><font color="red"><?php echo $error['title']; ?></font><?php endif; ?><br>
<label>来　源： <input type="text" name="copyfrom" value="<?php echo $post['copyfrom']; ?>"></label><br>
<label>分　类：
<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['typeid']);
			?>
          </select>
	<font color="red">*</font>
	<?php if(isset($error['typeid'])): ?><font color="red"><?php echo $error['typeid']; ?></font><?php endif; ?></label><br>

<label>关键词： 
  <input type="text" name="keyword" value="<?php echo $post['keyword']; ?>"></label>
<?php if(isset($error['keyword'])): ?>
<font color="red"><?php echo $error['keyword']; ?></font><?php endif; ?>
	<br>
	<label> </label>
	关键词：
	<label><input type="radio" name="keyword_auto" value="1" <?php if($post['keyword_auto'] === '1') echo 'checked'; ?>>
    是</label>
	<label><input name="keyword_auto" type="radio" value="2" checked <?php if($post['keyword_auto'] === '2') echo 'checked'; ?>>
	  否</label>
	<font color="red">*</font>
	<?php if(isset($error['keyword_auto'])): ?><font color="red"><?php echo $error['keyword_auto']; ?></font><?php endif; ?>
	</label>
	<br>
<label>内　容： 
  <textarea name="content"><?php echo $post['content']; ?></textarea></label>
	<?php if(isset($error['content'])): ?><font color="red"><?php echo $error['content']; ?></font><?php endif; ?>
	<br>
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>