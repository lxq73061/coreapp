<html>
<head>
<title>Welcome</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>
○<a href="?go=user&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">用户</a>&nbsp;
○<a href="?go=doc&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章</a>&nbsp;
○<a href="?go=channel&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">分类</a>&nbsp; 
○<a href="?go=diary&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">日记</a>&nbsp; 
○<a href="?go=site&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">网址</a>&nbsp;
○<a href="?go=address&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">通讯录</a>&nbsp;

○<a href="?go=user&do=logout">退出</a><br>
Welcome <?php echo $online->username?>!
</body>
</html>