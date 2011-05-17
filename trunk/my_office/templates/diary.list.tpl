<?php include('header.tpl')?>

○<a href="?go=diary&do=browse">日志列表</a>&nbsp;
○<a href="?go=diary&do=append">添加日志</a><br>

<form>
<input type="hidden" name="go" value="diary">
<input type="hidden" name="do" value="browse">
关键词：<input type="text" name="keyword" value="<?php echo $get['keyword']?>">&nbsp;
分类：<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['typeid']);
			?>
          </select>
排序：<select name="order">
	<option value=""></option>
	<option value="diary_id" <?php if($get['order'] === 'diary_id') echo 'selected'; ?>>日志ID↑</option>
    <option value="diary_id2" <?php if($get['order'] === 'diary_id2') echo 'selected'; ?>>日志ID↓</option>
    
</select>
<input type="submit" value="查询">
</form>
<?php $ids = 'diary_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>
<?php include('page.tpl')?>
<form method="post" action="?go=diary&do=group_remove&query=<?php echo urlencode($query) ?>">
<table border="0" cellpadding="5">
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
	<td><?php if($diary->diary_id<0): ?>&nbsp;<? else: ?><input type="checkbox" name="<?=$ids?>" value="<?php echo $diary->diary_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?php echo $diary->diary_id; ?></td>
	<td>&nbsp;<?php echo $diary->create_date; ?></td>
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
</form>
<?php include('page.tpl')?>
</body>
</html>