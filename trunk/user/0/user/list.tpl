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
排序：<select name="order">
	<option value=""></option>
	<option value="user_id" <?php if($get['order'] === 'user_id') echo 'selected'; ?>>用户ID↑</option>
	<option value="username" <?php if($get['order'] === 'username') echo 'selected'; ?>>用户名↑</option>
	<option value="username2" <?php if($get['order'] === 'username2') echo 'selected'; ?>>用户名↓</option>
</select>
<input type="submit" value="查询">
</form>

<form method="post" action="?do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="1">
<thead>
	<tr><th>&nbsp;</th><th>ID</th><th>用户名</th><th>等级</th><th>姓名</th><th>性别</th><th>操作</th></tr>
</thead>
<tbody>
<?php foreach($users as $user): ?>
	<tr>
	<td><?php if($user->user_id<3): ?>&nbsp;<? else: ?><input type="checkbox" name="user_id[]" value="<?php echo $user->user_id; ?>"><?php endif; ?></td>
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
<tfoot>
	<tr><td colspan="7">&nbsp;<input type="button" value="全选" onclick="select_all(this)">
	<input type="button" value="反选" onclick="reverse_all(this);">
	<input type="button" value="删除" onclick="return remove_selected(this);"></td></tr>
</thead>
</table>
<script language="javascript">
function select_all(t){
	if(typeof t.form["user_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["user_id[]"];
	if(typeof arr.length == "undefined"){
		arr.checked = true;
		return true;
	}
	for(i=0;i<arr.length;i++){
		arr[i].checked = true;
	}
	return true;
}
function reverse_all(t){
	if(typeof t.form["user_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["user_id[]"];
	if(typeof arr.length == "undefined"){
		arr.checked = ! arr.checked;
		return true;
	}
	for(i=0;i<arr.length;i++){
		arr[i].checked = ! arr[i].checked;
	}
	return true;
}
function remove_selected(t){
	if(typeof t.form["user_id[]"] == "undefined"){
		alert("请选中要操作的用户后再点删除");
		return false;
	}
	var arr = t.form["user_id[]"];
	if(typeof arr.length == "undefined"){
		if(!arr.checked){
			alert("请选中要操作的用户后再点删除");
			return false;
		}
	}else{
		ret = false;
		for(i=0;i<arr.length;i++){
			if(arr[i].checked){
				ret = true;
				break;
			}
		}
		if(!ret){
			alert("请选中要操作的用户后再点删除");
			return false;
		}
	}
	if(!confirm("您确定删除这些选中的用户吗")){
		return false;
	}
	t.form.submit();
	return true;
}
</script>
</form>
<?php if($page['page']<$page['total']): ?><a href="?<?php $_GET['page']=$page['page']+1;echo http_build_query($_GET); ?>">下一页</a>&nbsp;<?php endif; ?>
<?php if($page['page']>1): ?><a href="?<?php $_GET['page']=$page['page']-1;echo http_build_query($_GET); ?>">上一页</a><?php endif; ?>

</body>
</html>