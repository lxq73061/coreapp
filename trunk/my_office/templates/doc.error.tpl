<html>
<head>
<title>用户详细</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>

○<a href="?<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章列表</a>&nbsp;
○<a href="?go=doc&do=append">添加文章</a></ul>

<?php echo $error; ?>

</body>
</html>