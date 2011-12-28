<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
<?php if(IN_WAP):?>
<?php include('page.tpl')?>
<hr />
<?php foreach($docs as $doc): ?>
<a href="?go=doc&do=detail&doc_id=<?=$doc->doc_id; ?>&query=<?=urlencode($query) ?>"><?=$doc->title; ?></a>[<a href="?go=channel&do=detail&channel_id=<?=$doc->typeid; ?>"><?=$doc->get_typeid(); ?></a>]<br />
<?php endforeach ?>

<?php else:?>
<div class="division">
<a href="?go=doc&do=browse" class="sysiconBtn list">文章列表</a>&nbsp;
<a href="?go=doc&do=append" class="sysiconBtn addorder addproduct">添加文章</a><br>

<form>
<input type="hidden" name="go" value="doc">
<input type="hidden" name="do" value="browse">
关键词：<input type="text" name="keyword" value="<?=$get['keyword']?>">
&nbsp;

排序：<select name="order">

<option value="doc_id2" <?php if($get['order'] === 'doc_id2') echo 'selected'; ?>>创建日期↓</option>
<option value="date2" <?php if($get['order'] === 'date2') echo 'selected'; ?>>修改日期↓</option>
<option value="hit2" <?php if($get['order'] === 'hit2') echo 'selected'; ?>>访问次数↓</option>
<option value="last_remark2" <?php if($get['order'] === 'last_remark') echo 'selected'; ?>>最后回复↓</option>


</select>
<input id="BtnOK" class="sysiconBtnNoIcon" type="submit" value="查 询" name="BtnOK" />
</form>
<?php $ids = 'doc_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>

<?php include('page.tpl')?>
<form method="post" action="?go=doc&do=group_remove&query=<?=urlencode($query) ?>">
<table border="0" cellpadding="5" cellspacing="0" class="gridlist">
<thead>
	<tr><th>&nbsp;</th><th>ID</th><th>文章名</th>
	<th>分类</th>
	<th>访问</th>
	<th>更新日期</th>
	<th>回复日期</th>
	<th>操作</th></tr>
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
	<td><?=$doc->last_remark; ?></td>
	<td>&nbsp;&nbsp;<?php if(!$doc->doc_id): ?>修改<? else: ?><a href="?go=doc&do=modify&doc_id=<?=$doc->doc_id; ?>&query=<?=urlencode($query) ?>">修改</a><?php endif; ?> | 
	  &nbsp;<?php if(!$doc->doc_id): ?>删除<? else: ?><a href="?go=doc&do=remove&doc_id=<?=$doc->doc_id; ?>&query=<?=urlencode($query) ?>" onclick="if(!confirm('您确定要删除该文章吗？'))return false;void(0);">删除</a><?php endif; ?></td>
	</tr>
<?php endforeach ?>
</tbody>
<tfoot>
	<tr><td colspan="8"><b class="submitBtn">
	  <button onclick="select_all(this)" type="button"><span class="iconbutton">全选</span></button>
    </b> <b class="submitBtn">
    <button onclick="reverse_all(this);" type="button"><span class="iconbutton">反选</span></button>
    </b> <b class="submitBtn">
    <button onclick="return remove_selected(this);" type="button"><span class="iconbutton deletebutton">删除</span></button>
    </b></td></tr>
</thead>
</table>

</form>
<?php include('page.tpl')?>

<?php endif?>
</body>
</html>