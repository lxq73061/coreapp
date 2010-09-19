<html>
<head>
<title>用户详细</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>

<ul>
<li><a href="?<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">用户列表</a></li>
<li><a href="?do=append">添加用户</a></li>
</ul>


<form method="post">
<fieldset>
<legend>用户详细</legend>
用户名：<?php echo htmlspecialchars($user->username); ?><br>
	<br>
密　码：******<br>
	<br>
等　级：<?php echo $user->get_grade(); ?><br>
	<br>
姓　名：<?php echo htmlspecialchars($user->name); ?><br>
	<br>
性　别：<?php echo $user->get_gender(); ?><br>
	<br>
手机号：<?php echo $user->mobile; ?><br>
	<br>
邮　箱：<?php echo $user->email; ?><br>
	<br>
网　址：<?php echo $user->url; ?><br>
	<br>
备　注：<?php echo htmlspecialchars($user->remark); ?><br>
	<br>
</fieldset>
</form>

</body>
</html>