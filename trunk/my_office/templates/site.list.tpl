<?php include('header.tpl')?>

○<a href="?go=site&do=browse">网址列表</a>&nbsp;
○<a href="?go=site&do=append">添加网址</a><br>

<form>
<input type="hidden" name="go" value="site">
<input type="hidden" name="do" value="browse">
网址名：<input type="text" name="sitename" value="<?php echo $get['sitename']?>">&nbsp;
分类：<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['typeid']);
			?>
          </select>
排序：<select name="order">
	<option value=""></option>
	<option value="site_id" <?php if($get['order'] === 'site_id') echo 'selected'; ?>>网址ID↑</option>
	<option value="title" <?php if($get['order'] === 'title') echo 'selected'; ?>>网址名↑</option>
	<option value="title2" <?php if($get['order'] === 'title2') echo 'selected'; ?>>网址名↓</option>
</select>
<input type="submit" value="查询">
</form>

<form method="post" action="?go=site&do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="1">
<thead>
	<tr><th>&nbsp;</th><th>ID</th>
	  <th>网站名</th>
	  <th>分类</th>
	  <th>URL</th><th>&nbsp;</th><th>操作</th></tr>
</thead>
<tbody>
<?php if($sites):?>
<?php foreach($sites as $site): ?>
	<tr>
	<td><?php if($site->site_id<3): ?>&nbsp;<? else: ?><input type="checkbox" name="site_id[]" value="<?php echo $site->site_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?php echo $site->site_id; ?></td>
	<td>&nbsp;<?php echo $site->title; ?></td>
	<td>&nbsp;<?php echo $site->get_typeid(); ?></td>
	<td>&nbsp;<?php echo $site->url; ?></td>
	<td>&nbsp;</td>
	<td>&nbsp;<a href="?go=site&do=detail&site_id=<?php echo $site->site_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
	&nbsp;<?php if($site->site_id<0): ?>修改<? else: ?><a href="?go=site&do=modify&site_id=<?php echo $site->site_id; ?>&query=<?php echo urlencode($query) ?>">修改</a><?php endif; ?> | 
	&nbsp;<?php if($site->site_id<0): ?>删除<? else: ?><a href="javascript:if(confirm('您确定要删除该网址吗？'))location='?go=site&do=remove&site_id=<?php echo $site->site_id; ?>&query=<?php echo urlencode($query) ?>';void(0);">删除</a><?php endif; ?></td>
	</tr>

<?php endforeach ?>
<?php else: ?> 
	<tr>
	<td colspan="7">无</td>
	</tr><?php endif ?> 
</tbody>
<tfoot>
	<tr><td colspan="7">&nbsp;<input type="button" value="全选" onClick="select_all(this)">
	<input type="button" value="反选" onClick="reverse_all(this);">
	<input type="button" value="删除" onClick="return remove_selected(this);"></td></tr>
</thead>
</table>
<script language="javascript">
function select_all(t){
	if(typeof t.form["site_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["site_id[]"];
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
	if(typeof t.form["site_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["site_id[]"];
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
	if(typeof t.form["site_id[]"] == "undefined"){
		alert("请选中要操作的网址后再点删除");
		return false;
	}
	var arr = t.form["site_id[]"];
	if(typeof arr.length == "undefined"){
		if(!arr.checked){
			alert("请选中要操作的网址后再点删除");
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
			alert("请选中要操作的网址后再点删除");
			return false;
		}
	}
	if(!confirm("您确定删除这些选中的网址吗")){
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