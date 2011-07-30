<div class="relateds">
<fieldset>
<legend>关联详细 <a href="?go=related&do=append&s_type=<?=$s_type?>&s_id=<?=$s_id?>&t_type=&t_id=">添加</a></legend>

<?php foreach($lists as $key=>$val):?>
	<dl>
<dt><?=$key?>:</dt>
	<?php foreach($val as $k=>$v):?>
	<dd><a target="_blank" href="?go=<?=$v['t_type']?>&do=detail&<?=$v['t_type']?>_id=<?=$v['t_id']?>"><?=$v['t_name']?></a> 
    &nbsp;<a href="?go=related&do=remove&related_id=<?=$v['related_id']?>&query=<?php echo urlencode($query) ?>" onclick="return  confirm('您确定要删除该关联吗？')">删除</a></dd>
	<?php endforeach?>
</dl>
    <hr />
    

<?php endforeach?>

</fieldset></div>