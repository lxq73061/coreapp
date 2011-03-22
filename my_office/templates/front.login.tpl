<html>
<head>
<title>登录</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<style>
BODY{
	background:#069;
	color:#FFF
}
fieldset{
	margin:15% 30%;
	border: #39C 3px solid
}
fieldset img{vertical-align:middle; border:1px solid #Fc0}
</style>
</head>
<body>

<form method="post" action="index.php?go=front&do=login">
<input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
<fieldset>
<legend>登录</legend>
<label>用户名：<input type="text" name="username" value="<?php echo isset($_POST['username'])?$_POST['username']:''; ?>" /></label>
	<?php if(isset($error['username'])): ?><font color="red"><?php echo $error['username']; ?></font><?php endif; ?><br />
<label>密　码：<input type="password" name="password" /></label>
	<?php if(isset($error['password'])): ?><font color="red"><?php echo $error['password']; ?></font><?php endif; ?><br />
<label>验证码：<input name="authcode" type="text" size="13" /></label><img src="index.php?go=front&do=authcode" />
	<?php if(isset($error['authcode'])): ?><font color="red"><?php echo $error['authcode']; ?></font><?php endif; ?><br />
<input type="submit" value="登录" />
</fieldset>
</form>

</body>
</html>