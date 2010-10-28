<?php include('header.tpl')?>

○<a href="?go=diary&do=browse">日志列表</a>&nbsp;
○<a href="?go=diary&do=append">添加日志</a><br>

<form>
<input type="hidden" name="go" value="diary">
<input type="hidden" name="do" value="browse">
日志名：<input type="text" name="diaryname" value="<?php echo $get['diaryname']?>">&nbsp;
分类：<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['typeid']);
			?>
          </select>
排序：<select name="order">
	<option value=""></option>
	<option value="diary_id" <?php if($get['order'] === 'diary_id') echo 'selected'; ?>>日志ID↑</option>
	<option value="title" <?php if($get['order'] === 'title') echo 'selected'; ?>>日志名↑</option>
	<option value="title2" <?php if($get['order'] === 'title2') echo 'selected'; ?>>日志名↓</option>
</select>
<input type="submit" value="查询">
</form>

<form method="post" action="?go=diary&do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="1">
<thead>
	<tr><th>&nbsp;</th><th>ID</th>
	  <th>日期</th>
	  <th>标题</th>
	  <th>分类</th>
	  <th>心情</th>
	  <th>天气</th><th>操作</th></tr>
</thead>
<tbody>
<?php if($diarys):?>
<?php foreach($diarys as $diary): ?>
	<tr>
	<td><?php if($diary->diary_id<3): ?>&nbsp;<? else: ?><input type="checkbox" name="diary_id[]" value="<?php echo $diary->diary_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?php echo $diary->diary_id; ?></td>
	<td>&nbsp;<?php echo $diary->diary_date; ?></td>
	<td><?php echo $diary->title; ?></td>
	<td>&nbsp;<?php echo $diary->get_typeid(); ?></td>
	<td>&nbsp;<?php echo $diary->mood; ?></td>
	<td><?php echo $diary->weather; ?></td>
	<td>&nbsp;<a href="?go=diary&do=detail&diary_id=<?php echo $diary->diary_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
	&nbsp;<?php if($diary->diary_id<0): ?>修改<? else: ?><a href="?go=diary&do=modify&diary_id=<?php echo $diary->diary_id; ?>&query=<?php echo urlencode($query) ?>">修改</a><?php endif; ?> | 
	&nbsp;<?php if($diary->diary_id<0): ?>删除<? else: ?><a href="?go=diary&do=remove&diary_id=<?php echo $diary->diary_id; ?>&query=<?php echo urlencode($query) ?>" onclick="return  confirm('您确定要删除该日志吗？')">删除</a><?php endif; ?></td>
	</tr>

<?php endforeach ?>
<?php else: ?> 
	<tr>
	<td colspan="8">无</td>
	</tr><?php endif ?> 
</tbody>
<tfoot>
	<tr><td colspan="8">&nbsp;<input type="button" value="全选" onClick="select_all(this)">
	<input type="button" value="反选" onClick="reverse_all(this);">
	<input type="button" value="删除" onClick="return remove_selected(this);"></td></tr>
</thead>
</table>
<script language="javascript">
function select_all(t){
	if(typeof t.form["diary_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["diary_id[]"];
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
	if(typeof t.form["diary_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["diary_id[]"];
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
	if(typeof t.form["diary_id[]"] == "undefined"){
		alert("请选中要操作的日志后再点删除");
		return false;
	}
	var arr = t.form["diary_id[]"];
	if(typeof arr.length == "undefined"){
		if(!arr.checked){
			alert("请选中要操作的日志后再点删除");
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
			alert("请选中要操作的日志后再点删除");
			return false;
		}
	}
	if(!confirm("您确定删除这些选中的日志吗")){
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