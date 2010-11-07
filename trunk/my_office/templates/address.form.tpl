<?php include('header.tpl')?>

○<a href="?go=<?=$_GET['go']?>&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">联系人列表</a>&nbsp;
○<a href="?go=<?=$_GET['go']?>&do=append">新建联系人</a><br>

<form method="post" action="?go=<?=$_GET['go']?>&do=<?php echo $_GET['do']; ?>&address_id=<?php echo $_GET['address_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加通讯人' : '修改通讯人'; ?></legend>
<label>名称： <input type="text" name="name" value="<?php echo $post['name']; ?>"></label>
	<font color="red">*</font><?php if(isset($error['name'])): ?><font color="red"><?php echo $error['name']; ?></font><?php endif; ?><br>

<label>手机： <input type="text" name="mobile" value="<?php echo $post['mobile']; ?>"></label>
	<?php if(isset($error['mobile'])): ?><font color="red"><?php echo $error['mobile']; ?></font><?php endif; ?><br>
<label>email： <input type="text" name="email" value="<?php echo $post['email']; ?>"></label>
	</font><?php if(isset($error['email'])): ?><font color="red"><?php echo $error['email']; ?></font><?php endif; ?><br>
<label>qq： <input type="text" name="qq" value="<?php echo $post['qq']; ?>"></label>
	<?php if(isset($error['qq'])): ?><font color="red"><?php echo $error['qq']; ?></font><?php endif; ?><br>
<label>msn： <input type="text" name="msn" value="<?php echo $post['msn']; ?>"></label>
	</font><?php if(isset($error['msn'])): ?><font color="red"><?php echo $error['msn']; ?></font><?php endif; ?><br>
<label>办公电话： <input type="text" name="office_phone" value="<?php echo $post['office_phone']; ?>"></label>
	</font><?php if(isset($error['office_phone'])): ?><font color="red"><?php echo $error['office_phone']; ?></font><?php endif; ?><br>
<label>住宅电话： <input type="text" name="home_phone" value="<?php echo $post['home_phone']; ?>"></label>
	</font><?php if(isset($error['home_phone'])): ?><font color="red"><?php echo $error['home_phone']; ?></font><?php endif; ?><br>
<label>备注： <input type="text" name="remarks" value="<?php echo $post['remarks']; ?>"></label>
	<?php if(isset($error['remarks'])): ?><font color="red"><?php echo $error['remarks']; ?></font><?php endif; ?><br>


<!--<label>通讯名： <input type="text" name="url" value="<?php echo $post['url']; ?>"></label>
<font color="red">*</font><?php if(isset($error['url'])): ?><font color="red"><?php echo $error['url']; ?></font><?php endif; ?><br>-->
<label>分　类：
<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['typeid']);
			?>
          </select>
	<font color="red">*</font>
	<?php if(isset($error['typeid'])): ?><font color="red"><?php echo $error['typeid']; ?></font><?php endif; ?></label><br>

<!--<label>内　容： 
  <textarea name="content"><?php echo $post['content']; ?></textarea></label>
	<?php if(isset($error['content'])): ?><font color="red"><?php echo $error['content']; ?></font><?php endif; ?>
	<br>-->
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>