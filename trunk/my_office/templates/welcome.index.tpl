<html>
<head>
<title>Welcome</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>
○<a href="?go=user&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">用户列表</a>
&nbsp;○<a href="?go=doc&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章列表</a>
&nbsp;○<a href="?go=channel&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">分类列表</a>
&nbsp; ○<a href="?go=user&do=logout">退出登录</a><br>
Welcome

</body>
</html>