<html>
<head>
<title>用户例表</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>

○<a href="?do=browse">用户列表</a>&nbsp;
○<a href="?do=append">添加用户</a><br>

<form>
<input type="hidden" name="do" value="browse">
用户名：<input type="text" name="username" value="<?php echo $get['username']?>">&nbsp;
等级：<select name="grade">
	<option value=""></option>
	<option value="1" <?php if($get['grade'] === '1') echo 'selected'; ?>>超级管理员</option>
	<option value="2" <?php if($get['grade'] === '2') echo 'selected'; ?>>管理员</option>
	<option value="3" <?php if($get['grade'] === '3') echo 'selected'; ?>>普通用户</option>
</select>
<input type="submit" value="查询">
</form>

<table border="1">
<thead>
	<tr><th>&nbsp;</th><th>ID</th><th>用户名</th><th>等级</th><th>姓名</th><th>性别</th><th>操作</th></tr>
</thead>
<tbody>
<?php foreach($users as $user): ?>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;<?php echo $user->user_id; ?></td>
	<td>&nbsp;<?php echo $user->username; ?></td>
	<td>&nbsp;<?php echo $user->get_grade(); ?></td>
	<td>&nbsp;<?php echo $user->name; ?></td>
	<td>&nbsp;<?php echo $user->get_gender(); ?></td>
	<td>&nbsp;<a href="?do=detail&user_id=<?php echo $user->user_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
	&nbsp;<?php if($user->user_id<3): ?>修改<? else: ?><a href="?do=modify&user_id=<?php echo $user->user_id; ?>&query=<?php echo urlencode($query) ?>">修改</a><?php endif; ?> | 
	&nbsp;<?php if($user->user_id<3): ?>删除<? else: ?><a href="javascript:if(confirm('您确定要删除该用户吗？'))location='?do=remove&user_id=<?php echo $user->user_id; ?>&query=<?php echo urlencode($query) ?>';void(0);">删除</a><?php endif; ?></td>
	</tr>
<?php endforeach ?>
</tbody>
</table>
<?php if($page['page']<$page['total']): ?><a href="?<?php $_GET['page']=$page['page']+1;echo http_build_query($_GET); ?>">下一页</a>&nbsp;<?php endif; ?>
<?php if($page['page']>1): ?><a href="?<?php $_GET['page']=$page['page']-1;echo http_build_query($_GET); ?>">上一页</a><?php endif; ?>

</body>
</html>