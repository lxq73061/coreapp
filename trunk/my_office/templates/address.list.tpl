<?php include('header.tpl')?>

○<a href="?go=address&do=browse">联系人列表</a>&nbsp;
○<a href="?go=address&do=append">新建联系人</a><br>

<form>
<input type="hidden" name="go" value="address">
<input type="hidden" name="do" value="browse">
通讯名：
<input type="text" name="addressname" value="<?php echo $get['addressname']?>">&nbsp;
分类：<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['typeid']);
			?>
          </select>
排序：<select name="order">
	<option value=""></option>
	<option value="address_id" selected="selected" <?php if($get['order'] === 'address_id') echo 'selected'; ?>>通讯ID↑</option>
	<option value="name" <?php if($get['order'] === 'name') echo 'selected'; ?>>通讯名↑</option>
	<option value="name2" <?php if($get['order'] === 'name2') echo 'selected'; ?>>通讯名↓</option>
</select>
<input type="submit" value="查询">
</form>

<form method="post" action="?go=address&do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="1">
<thead>
	<tr><th>&nbsp;</th><th>ID</th>
	  <th>名称</th>
	  <th>手机</th>
      <th>email</th>
      <th>qq</th>
      <th>msn</th>
      <th>办公电话</th>
      <th>住宅电话</th>
      <th>备注</th><th>&nbsp;</th><th>操作</th></tr>
</thead>
<tbody>
<?php if($addresss):?>
<?php foreach($addresss as $address): ?>
	<tr>
	<td><?php if($address->address_id<3): ?>&nbsp;<? else: ?><input type="checkbox" name="address_id[]" value="<?php echo $address->address_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?php echo $address->address_id; ?></td>
	<td>&nbsp;<?php echo $address->name; ?></td>
    <td>&nbsp;<?php echo $address->mobile; ?></td>
    <td>&nbsp;<?php echo $address->email; ?></td>
    <td>&nbsp;<?php echo $address->qq; ?></td>
    <td>&nbsp;<?php echo $address->msn; ?></td>
    <td>&nbsp;<?php echo $address->office_phone; ?></td>
    <td>&nbsp;<?php echo $address->home_phone; ?></td>
    <td>&nbsp;<?php echo $address->remarks; ?></td>
	<!--<td>&nbsp;<?php echo $address->get_typeid(); ?></td>-->
	<!--<td>&nbsp;<?php echo $address->url; ?></td>-->
	<td>&nbsp;</td>
	<td>&nbsp;<a href="?go=address&do=detail&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
	&nbsp;<?php if($address->address_id<0): ?>修改<? else: ?><a href="?go=address&do=modify&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>">修改</a><?php endif; ?> | 
	&nbsp;<?php if($address->address_id<0): ?>删除<? else: ?><a href="javascript:if(confirm('您确定要删除该通讯名吗？'))location='?go=address&do=remove&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>';void(0);">删除</a><?php endif; ?></td>
	</tr>

<?php endforeach ?>
<?php else: ?> 
	<tr>
	<td colspan="12">无</td>
	</tr><?php endif ?> 
</tbody>
<tfoot>
	<tr><td colspan="12">&nbsp;<input type="button" value="全选" onClick="select_all(this)">
	<input type="button" value="反选" onClick="reverse_all(this);">
	<input type="button" value="删除" onClick="return remove_selected(this);"></td></tr>
</thead>
</table>
<script language="javascript">
function select_all(t){
	if(typeof t.form["address_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["address_id[]"];
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
	if(typeof t.form["address_id[]"] == "undefined"){
		return false;
	}
	var arr = t.form["address_id[]"];
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
	if(typeof t.form["address_id[]"] == "undefined"){
		alert("请选中要操作的通讯人后再点删除");
		return false;
	}
	var arr = t.form["address_id[]"];
	if(typeof arr.length == "undefined"){
		if(!arr.checked){
			alert("请选中要操作的通讯人后再点删除");
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
			alert("请选中要操作的通讯名后再点删除");
			return false;
		}
	}
	if(!confirm("您确定删除这些选中的通讯名吗")){
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