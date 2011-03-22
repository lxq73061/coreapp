<?php include('header.tpl')?>

○<a href="?go=doc&do=browse">文章列表</a>&nbsp;
○<a href="?go=doc&do=append">添加文章</a><br>

<form>
<input type="hidden" name="go" value="doc">
<input type="hidden" name="do" value="browse">
关键词：<input type="text" name="keyword" value="<?=$get['keyword']?>">
&nbsp;

排序：<select name="order">
	<option value="doc_id" <?php if($get['order'] === 'doc_id') echo 'selected'; ?>>创建日期↑</option>
	<option value="date" <?php if($get['order'] === 'date') echo 'selected'; ?>>修改日期↑</option>
	<option value="hit" <?php if($get['order'] === 'hit') echo 'selected'; ?>>访问次数↑</option>
</select>
<input type="submit" value="查询">
</form>
<?php $ids = 'doc_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>
<form method="post" action="?go=doc&do=group_remove&query=<?=urlencode($query) ?>">
<table border="0" cellpadding="5" cellspacing="0">
<thead>
	<tr><th>&nbsp;</th><th>ID</th><th>文章名</th>
	<th>分类</th>
	<th>访问</th>
	<th>更新日期</th><th>操作</th></tr>
</thead>
<tbody>
<?php foreach($docs as $doc): ?>
	<tr>
	<td><?php if($doc->doc_id<0): ?>&nbsp;<? else: ?><input type="checkbox" name=<?=$ids ?> value="<?=$doc->doc_id; ?>"><?php endif; ?></td>
	<td>&nbsp;<?=$doc->doc_id; ?></td>
	<td>&nbsp;<a href="?go=doc&do=detail&doc_id=<?=$doc->doc_id; ?>&query=<?=urlencode($query) ?>"><?=$doc->title; ?></a></td>
	<td>&nbsp;<a href="?go=channel&do=detail&channel_id=<?=$doc->typeid; ?>"><?=$doc->get_typeid(); ?></a></td>
	<td>&nbsp;<?=$doc->hit; ?></td>
	<td>&nbsp;<?=$doc->update_date; ?>&nbsp;<?=$doc->update_time; ?></td>
	<td>&nbsp;&nbsp;<?php if(!$doc->doc_id): ?>修改<? else: ?><a href="?go=doc&do=modify&doc_id=<?=$doc->doc_id; ?>&query=<?=urlencode($query) ?>">修改</a><?php endif; ?> | 
	&nbsp;<?php if(!$doc->doc_id): ?>删除<? else: ?><a href="?go=doc&do=remove&doc_id=<?=$doc->doc_id; ?>&query=<?=urlencode($query) ?>" onclick="if(!confirm('您确定要删除该文章吗？'))return false;void(0);">删除</a><?php endif; ?></td>
	</tr>
<?php endforeach ?>
</tbody>
<tfoot>
	<tr><td colspan="7">&nbsp;<input type="button" value="全选" onClick="select_all(this)">
	<input type="button" value="反选" onClick="reverse_all(this);">
	<input type="button" value="删除" onClick="return remove_selected(this);"></td></tr>
</thead>
</table>

</form>
<?php if($page['page']<$page['total']): ?><a href="?<?php $_GET['page']=$page['page']+1;echo http_build_query($_GET); ?>">下一页</a>&nbsp;<?php endif; ?>
<?php if($page['page']>1): ?><a href="?<?php $_GET['page']=$page['page']-1;echo http_build_query($_GET); ?>">上一页</a><?php endif; ?>
</body>
</html>