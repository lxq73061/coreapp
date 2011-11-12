<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
○<a href="?go=user&do=browse">用户列表</a>&nbsp;
○<a href="?go=user&do=append">添加用户</a><br>

<form>
<input type="hidden" name="go" value="user">
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
<?php $ids = 'user_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>
<?php include('page.tpl')?>
<form method="post" action="?go=user&do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="0" cellpadding="5">
<thead>
	<tr><th>&nbsp;</th><th>ID</th><th>用户名</th><th>等级</th><th>姓名</th><th>性别</th><th>操作</th></tr>
</thead>
<tbody>
<?php if(count($users)): ?>
<?php foreach($users as $user): ?>
	<tr>
	<td><?php if($online->grade!=1 || $online->user_id==$user->user_id): ?>&nbsp;<? else: ?><input type="checkbox" name="<?=$ids?>" value="<?php echo $user->user_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?php echo $user->user_id; ?></td>
	<td>&nbsp;<?php echo $user->username; ?></td>
	<td>&nbsp;<?php echo $user->get_grade(); ?></td>
	<td>&nbsp;<?php echo $user->name; ?></td>
	<td>&nbsp;<?php echo $user->get_gender(); ?></td>
	<td>&nbsp;<a href="?go=user&do=detail&user_id=<?php echo $user->user_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
	&nbsp;<?php if($online->grade==1 || $online->user_id==$user->user_id || ($online->grade==2 && $user->grade==3)): ?><a href="?go=user&do=modify&user_id=<?php echo $user->user_id; ?>&query=<?php echo urlencode($query) ?>">修改</a><? else: ?>修改<?php endif; ?> | 
	&nbsp;<?php if($online->grade==1 && $online->user_id!=$user->user_id ): ?><a href="javascript:if(confirm('您确定要删除该用户吗？'))location='?go=user&do=remove&user_id=<?php echo $user->user_id; ?>&query=<?php echo urlencode($query) ?>';void(0);">删除</a><? else: ?>删除<?php endif; ?></td>
	</tr>
<?php endforeach ?>
<?php else: ?>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
<?php endif ?>
</tbody>
<tfoot>
	<tr><td colspan="7">&nbsp;<input type="button" value="全选" onClick="select_all(this)">
	<input type="button" value="反选" onClick="reverse_all(this);">
	<input type="button" value="删除" onClick="return remove_selected(this);"></td></tr>
</thead>
</table>
</form>
<?php include('page.tpl')?>
</body>
</html>