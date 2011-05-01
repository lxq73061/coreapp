<?php include('header.tpl')?>

○<a href="?go=address&do=browse">联系人列表</a>&nbsp;
○<a href="?go=address&do=append">新建联系人</a><br>

<form>
<input type="hidden" name="go" value="address">
<input type="hidden" name="do" value="browse">
关键词：
<input type="text" name="keyword" value="<?php echo $get['keyword']?>">&nbsp;
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
<?php $ids = 'address_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>
<?php include('page.tpl')?>
<form method="post" action="?go=address&do=group_remove&query=<?php echo urlencode($query) ?>">
    <table border="0" cellpadding="5">
    <tbody><table border="0" cellpadding="5">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>名称</th>
                <th>手机</th>
                <th>email</th>
                <th>qq</th>
                <th>msn</th>
                <th>办公电话</th>
                <th>住宅电话</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if($addresss):?>
            <?php foreach($addresss as $address): ?>
            <tr>
                <td><?php if($address->address_id<0): ?>
                    &nbsp;
                    <? else: ?>
                    <input type="checkbox" name="<?=$ids?>" value="<?php echo $address->address_id; ?>" />
                    <?php endif; ?></td>
                <td>&nbsp;<?php echo $address->name; ?></td>
                <td>&nbsp;<?php echo $address->mobile; ?></td>
                <td>&nbsp;<?php echo $address->email; ?></td>
                <td>&nbsp;<?php echo $address->qq; ?></td>
                <td>&nbsp;<?php echo $address->msn; ?></td>
                <td>&nbsp;<?php echo $address->office_phone; ?></td>
                <td>&nbsp;<?php echo $address->home_phone; ?></td>
                <td><?php echo $address->remarks; ?></td>
                <!--<td>&nbsp;<?php echo $address->get_typeid(); ?></td>-->
                <!--<td>&nbsp;<?php echo $address->url; ?></td>-->
                <td>&nbsp;<a href="?go=address&do=detail&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | 
                    &nbsp;
                    <?php if($address->address_id<0): ?>
                    修改
                    <? else: ?>
                    <a href="?go=address&do=modify&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>">修改</a>
                    <?php endif; ?>
                    |
                    <!--&nbsp;<?php if($address->address_id<0): ?>删除<? else: ?><a href="javascript:if(confirm('您确定要删除该通讯名吗？'))location='?go=address&do=remove&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>';void(0);">删除</a><?php endif; ?>-->
                    &nbsp;
                    <?php if($address->address_id<0): ?>
                    删除
                    <? else: ?>
                    <a href="?go=address&do=remove&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>" onclick="return  confirm('您确定要删除该日志吗？')">删除</a>
                    <?php endif; ?></td>
            </tr>
            <?php endforeach ?>
            <?php else: ?>
            <tr>
                <td colspan="10">无</td>
            </tr>
            <?php endif ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10">&nbsp;
                    <input type="button" value="全选" onclick="select_all(this)" />
                    <input type="button" value="反选" onclick="reverse_all(this);" />
                    <input type="button" value="删除" onclick="return remove_selected(this);" /></td>
            </tr>
        </tfoot>
    </table>
    
    </tbody>
    </table>
</form>
<?php include('page.tpl')?>
</body>
</html>