<?php include('header.tpl')?>
○<a href="?go=channel&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">分类列表</a>&nbsp;
○<a href="?go=channel&do=append">添加分类</a><br>

<form method="post" action="?go=channel&do=<?php echo $_GET['do']; ?>&channel_id=<?php echo $_GET['channel_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加分类' : '修改分类'; ?></legend>
<label>分类名称：<input name="name" type="text" class="input" id="name" value="<?php echo $post['name'];?>" size="40" /></label><font color="red">*</font><?php if(isset($error['name'])): ?><font color="red"><?php echo $error['name']; ?></font><?php endif; ?><br>
<label>所属分类：<select name="parent_id" id="parent_id">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['parent_id']);
			?>
          </select></label><br>
<label>分类排序：<input name="sort" type="text" class="input" id="sort" value="<?php echo $post['sort'];?>" size="25" /></label><br>
<label><input type="submit" value="提交"></label>
</fieldset>
</form>

</body>
</html>