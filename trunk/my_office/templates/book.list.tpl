<?php include('header.tpl')?>

○<a href="?go=book&do=browse">帐本</a>&nbsp;
○<a href="?go=book&do=append">添加帐本</a><br>

<form>
<table cellspacing="0" cellpadding="0">
	        <tr>
	            <td> 账目总笔数：<?php echo $totals[total]; ?></td>
	            <td></td>
            </tr>
	        <tr>
	            <td>资金余额：<?php echo $totals['amount']; ?></td>
            </tr>
        </table>
        <hr />
<!--<input type="hidden" name="go" value="book">
<input type="hidden" name="do" value="browse">
帐本名：<input type="text" name="title" value="<?php echo $get['book']?>">
&nbsp;
分类：
<select name="typeid">
	<option value="1" <?php if($get['typeid'] === '1') echo 'selected'; ?>>超级管理员</option>
	<option value="2" <?php if($get['typeid'] === '2') echo 'selected'; ?>>管理员</option>
	<option value="3" <?php if($get['typeid'] === '3') echo 'selected'; ?>>普通帐本</option>
</select>
排序：<select name="order">
	<option value=""></option>
	<option value="book_id" <?php if($get['order'] === 'book_id') echo 'selected'; ?>>帐本ID↑</option>
	<option value="bookname" <?php if($get['order'] === 'bookname') echo 'selected'; ?>>帐本名↑</option>
	<option value="bookname2" <?php if($get['order'] === 'bookname2') echo 'selected'; ?>>帐本名↓</option>
</select>
<input type="submit" value="查询">
--></form>
<?php $book_id = 'book_id[]';?>
<form method="post" action="?go=book&do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="1">
<thead>
	<tr><th>&nbsp;</th><th>ID</th>
	<th>分类</th>
	<th>账户</th>
    <th>用途</th>
    <th>备注</th>
    <th>货币</th>
    <th>收入</th>
	<th>支出</th>
    <th>余额</th><th>操作</th></tr>
</thead>
<tbody>
<?php if($books):?>
<?php foreach($books as $book): ?>
	<tr>
	<td><?php if($book->book_id<3): ?>&nbsp;<? else: ?><input type="checkbox" name=<?php echo $book_id ?> value="<?php echo $book->book_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?php echo $book->book_id; ?></td>
    <td>&nbsp;<?php echo $book->get_typeid(); ?></td>
    <td>&nbsp;<?php echo $book->item; ?></td>	
    <td>&nbsp;<?php echo $book->item_txt; ?></td>
    <td>&nbsp;<?php echo $book->remark; ?></td>
    <td>&nbsp;<?php echo $book->ccy; ?></td>
<!--        <td>&nbsp;<?php echo $book->otype; ?></td>
-->	<td>&nbsp;<?php 
	if($book->otype=='IN')
	{
	echo $book->amount;
	}
	?></td>
	
<td>&nbsp;<?php
    if($book->otype=='OUT')
    {
    echo $book->amount;
    }
?></td>
<!--	<td>&nbsp;<?=$v['otype']=='IN'? $v['amount']:''?></td>
	<td>&nbsp;<?=$v['otype']!='IN'? $v['amount']:''?></td>-->
    <td>&nbsp;<?php echo $book->net; ?></td>
<!--	<td><?=$v['otype']!='IN'? $v['amount']:''?></td>
-->	
	<!--<td>&nbsp;<?php echo $book->create_date; ?>&nbsp;<?php echo $book->create_time; ?></td>
	<td>&nbsp;<?php echo $book->update_date; ?>&nbsp;<?php echo $book->update_time; ?></td>-->
	
    
   <td>&nbsp;<a href="?go=book&do=detail&book_id=<?php echo $book->book_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
	&nbsp;<?php if($book->book_id<0): ?>修改<? else: ?><a href="?go=book&do=modify&book_id=<?php echo $book->book_id; ?>&query=<?php echo urlencode($query) ?>">修改</a><?php endif; ?> | 
    
    &nbsp;<?php if($book->book_id<0): ?>删除<? else: ?><a href="?go=book&do=remove&book_id=<?php echo $book->book_id; ?>&query=<?php echo urlencode($query) ?>" onclick="return  confirm('您确定要删除该日志吗？')">删除</a><?php endif; ?></td>
    
	</tr>
<?php endforeach ?>

<?php else: ?>
	<tr>
	<td colspan="11">no data</td>
	</tr>
<?php endif ?>
</tbody>
<tfoot>
	<tr><td colspan="7">&nbsp;<input type="button" value="全选" onClick="select_all(this)">
	<input type="button" value="反选" onClick="reverse_all(this);">
	<input type="button" value="删除" onClick="return remove_selected(this);"></td></tr>
</thead>
</table>
<table cellspacing="0" cellpadding="0">
        <tr>
            <td> 支出交易笔数：<?php echo $totals['total_out']; ?></td>
            <td>&nbsp; 收入交易笔数：<?php echo $totals['total_in']; ?></td>
            <td></td>
        </tr>
        <tr>
            <td>支出金额合计：<?php echo $totals['out_amount']; ?></td>
            <td>&nbsp; 收入金额合计：<?php echo $totals['in_amount']; ?></td>
        </tr>
</table>
<p>&nbsp;</p>
<script language="javascript">
var book_id = '<?php echo $book_id?>';
function select_all(t){
	if(typeof t.form[book_id] == "undefined"){
		return false;
	}
	var arr = t.form[book_id];
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
	if(typeof t.form[book_id] == "undefined"){
		return false;
	}
	var arr = t.form[book_id];
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
	if(typeof t.form[book_id] == "undefined"){
		alert("请选中要操作的项目后再点删除");
		return false;
	}
	var arr = t.form[book_id];
	if(typeof arr.length == "undefined"){
		if(!arr.checked){
			alert("请选中要操作的项目后再点删除");
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
			alert("请选中要操作的项目后再点删除");
			return false;
		}
	}
	if(!confirm("您确定删除这些选中的项目吗")){
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