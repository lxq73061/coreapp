<html>
<head>
<title>文章例表</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>

○<a href="?go=doc&do=browse">文章列表</a>&nbsp;
○<a href="?go=doc&do=append">添加文章</a><br>

<form>
<input type="hidden" name="go" value="doc">
<input type="hidden" name="do" value="browse">
文章名：<input type="text" name="title" value="<?php echo $get['title']?>">
&nbsp;
分类：
<select name="typeid">
	<option value=""></option>
	<option value="1" <?php if($get['typeid'] === '1') echo 'selected'; ?>>超级管理员</option>
	<option value="2" <?php if($get['typeid'] === '2') echo 'selected'; ?>>管理员</option>
	<option value="3" <?php if($get['typeid'] === '3') echo 'selected'; ?>>普通文章</option>
</select>
排序：<select name="order">
	<option value=""></option>
	<option value="doc_id" <?php if($get['order'] === 'doc_id') echo 'selected'; ?>>文章ID↑</option>
	<option value="docname" <?php if($get['order'] === 'docname') echo 'selected'; ?>>文章名↑</option>
	<option value="docname2" <?php if($get['order'] === 'docname2') echo 'selected'; ?>>文章名↓</option>
</select>
<input type="submit" value="查询">
</form>

<form method="post" action="?go=doc&do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="1">
<thead>
	<tr><th>&nbsp;</th><th>ID</th><th>文章名</th>
	<th>分类</th>
	<th>创建日期</th>
	<th>更新日期</th><th>操作</th></tr>
</thead>
<tbody>
<?php foreach($docs as $doc): ?>
	<tr>
	<td><?php if($doc->doc_id<3): ?>&nbsp;<? else: ?><input type="checkbox" name="doc_id[]" value="<?php echo $doc->doc_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?php echo $doc->doc_id; ?></td>
	<td>&nbsp;<?php echo $doc->title; ?></td>
	<td>&nbsp;<?php echo $doc->get_typeid(); ?></td>
	<td>&nbsp;<?php echo $doc->create_date; ?>&nbsp;<?php echo $doc->create_time; ?></td>
	<td>&nbsp;<?php echo $doc->update_date; ?>&nbsp;<?php echo $doc->update_time; ?></td>
	<td>&nbsp;<a href="?go=doc&do=detail&doc_id=<?php echo $doc->doc_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
	&nbsp;<?php if($doc->doc_id<3): ?>修改<? else: ?><a href="?go=doc&do=modify&doc_id=<?php echo $doc->doc_id; ?>&query=<?php echo urlencode($query) ?>">修改</a><?php endif; ?> | 
	&nbsp;<?php if($doc->doc_id<3): ?>删除<? else: ?><a href="javascript:if(confirm('您确定要删除该文章吗？'))location='?go=doc&do=remove&doc_id=<?php echo $doc->doc_id; ?>&query=<?php echo urlencode($query) ?>';void(0);">删除</a><?php endif; ?></td>
	</tr>
<?php endforeach ?>
</tbody>
<tfoot>
	<tr><td colspan="7">&nbsp;<input type="button" value="全选" onClick="select_all(this)">
	<input type="button" value="反选" onClick="reverse_all(this);">
	<input type="button" value="删除" onClick="return remove_selected(this);"></td></tr>
</thead>
</table>
<script language="javascript">
function select_all(t){
	if(typeof t.form["doc_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["doc_id[]"];
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
	if(typeof t.form["doc_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["doc_id[]"];
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
	if(typeof t.form["doc_id[]"] == "undefined"){
		alert("请选中要操作的文章后再点删除");
		return false;
	}
	var arr = t.form["doc_id[]"];
	if(typeof arr.length == "undefined"){
		if(!arr.checked){
			alert("请选中要操作的文章后再点删除");
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
			alert("请选中要操作的文章后再点删除");
			return false;
		}
	}
	if(!confirm("您确定删除这些选中的文章吗")){
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