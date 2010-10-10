<html>
<head>
<title>登录</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>

<form method="post" action="?do=login">
<input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
<fieldset>
<legend>登录</legend>
<label>用户名：<input type="text" name="username" value="<?php echo isset($_POST['username'])?$_POST['username']:''; ?>" /></label>
	<?php if(isset($error['username'])): ?><font color="red"><?php echo $error['username']; ?></font><?php endif; ?><br />
<label>密　码：<input type="password" name="password" /></label>
	<?php if(isset($error['password'])): ?><font color="red"><?php echo $error['password']; ?></font><?php endif; ?><br />
<label>验证码：<input type="text" name="authcode" /></label><img src="?do=authcode" />
	<?php if(isset($error['authcode'])): ?><font color="red"><?php echo $error['authcode']; ?></font><?php endif; ?><br />
<input type="submit" value="登录" />
</fieldset>
</form>

</body>
</html>