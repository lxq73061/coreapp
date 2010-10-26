<html>
<head>
<title><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加用户' : '修改用户'; ?></title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>

○<a href="?go=user&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">用户列表</a>&nbsp;
○<a href="?go=user&do=append">添加用户</a><br>


<form method="post" action="?go=user&do=<?php echo $_GET['do']; ?>&user_id=<?php echo $_GET['user_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加用户' : '修改用户'; ?></legend>
<label>用户名： <input type="text" name="username" value="<?php echo $post['username']; ?>"></label>
	<font color="red">*</font><?php if(isset($error['username'])): ?><font color="red"><?php echo $error['username']; ?></font><?php endif; ?><br>
　　　　 3-16个字符，英文字母、汉字、数字、下划线，不能全部是数字，且下划线不能作为起始和结尾字符。
	<br>
<label>密　码： <input type="password" name="password" value=""></label>
	<?php if(isset ($_GET ['do']) && $_GET ['do'] === 'append'): ?><font color="red">*</font><?php else: ?>(不填表示不修改密码)<?php endif; ?>
	<?php if(isset($error['password'])): ?><font color="red"><?php echo $error['password']; ?></font><?php endif; ?><br>
　　　　 4-16个字符，英文字母、数字、下划线、半角符号。
	<br>
<label>等　级：
	<select name="grade">
	<option value="1" <?php if($post['grade'] === '1') echo 'selected'; ?>>超级管理员</option>
	<option value="2" <?php if($post['grade'] === '2') echo 'selected'; ?>>管理员</option>
	<option value="3" <?php if($post['grade'] === '3') echo 'selected'; ?>>普通用户</option>
	</select></label>
	<font color="red">*</font>
	<?php if(isset($error['grade'])): ?><font color="red"><?php echo $error['grade']; ?></font><?php endif; ?>
	<br>
<label>姓　名： <input type="text" name="name" value="<?php echo $post['name']; ?>"></label>
	<font color="red">*</font>
	<?php if(isset($error['name'])): ?><font color="red"><?php echo $error['name']; ?></font><?php endif; ?>
	<br>
<label>性　别：
	<label><input type="radio" name="gender" value="1" <?php if($post['gender'] === '1') echo 'checked'; ?>>男</label>
	<label><input type="radio" name="gender" value="2" <?php if($post['gender'] === '2') echo 'checked'; ?>>女</label>
	<font color="red">*</font>
	<?php if(isset($error['gender'])): ?><font color="red"><?php echo $error['gender']; ?></font><?php endif; ?>
	</label><br>
<label>手机号： <input type="text" name="mobile" value="<?php echo $post['mobile']; ?>"></label>
	<?php if(isset($error['mobile'])): ?><font color="red"><?php echo $error['mobile']; ?></font><?php endif; ?>
	<br>
<label>邮　箱：
	<input type="text" name="email" value="<?php echo $post['email']; ?>">
	<?php if(isset($error['email'])): ?><font color="red"><?php echo $error['email']; ?></font><?php endif; ?>
	</label><br>
<label>网　址： <input type="text" name="url" value="<?php echo $post['url']; ?>"></label>
	<?php if(isset($error['url'])): ?><font color="red"><?php echo $error['url']; ?></font><?php endif; ?>
	<br>
<label>备　注： <textarea name="remark"><?php echo $post['remark']; ?></textarea></label>
	<?php if(isset($error['remark'])): ?><font color="red"><?php echo $error['remark']; ?></font><?php endif; ?>
	<br>
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>