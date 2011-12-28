<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>


<?php if(IN_WAP):?>
<?php include('page.tpl')?>
<hr />
<?php foreach($addresss as $address): ?>
<a href="?go=address&do=detail&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>"><?php echo $address->name; ?></a> 
<?php 
$contact =  $address->mobile; 
if(!$contact) $contact = $address->office_phone; 
if(!$contact) $contact =  $address->home_phone;
if(!$contact) $contact =  $address->email;
if(!$contact) $contact =  $address->qq?'QQ:'.$address->qq:'';
 echo $contact;
 ?>
<br />
<?php endforeach ?>

<?php else:?>
<div class="division">
<a href="?go=address&do=browse" class="sysiconBtn list">联系人列表</a>&nbsp;
<a href="?go=address&do=append" class="sysiconBtn addorder addproduct">新建联系人</a><br>

<form>
<input type="hidden" name="go" value="address">
<input type="hidden" name="do" value="browse">
关键词：
<input type="text" name="keyword" value="<?php echo $get['keyword']?>">&nbsp;
分类：<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
             <?php
            echo channel::get_channel_select(0,0,$get['typeid'],0,'address');
			?>
          </select>
排序：<select name="order">
	<option value=""></option>
	<option value="address_id" selected="selected" <?php if($get['order'] === 'address_id') echo 'selected'; ?>>通讯ID↑</option>
	<option value="name" <?php if($get['order'] === 'name') echo 'selected'; ?>>通讯名↑</option>
	<option value="name2" <?php if($get['order'] === 'name2') echo 'selected'; ?>>通讯名↓</option>
</select>
   <input id="BtnOK" class="sysiconBtnNoIcon" type="submit" value="查 询" name="BtnOK" />
</form>
<?php $ids = 'address_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>
<?php include('page.tpl')?>
<form method="post" action="?go=address&do=group_remove&query=<?php echo urlencode($query) ?>">
   <table border="0" cellpadding="5" class="gridlist">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>名称</th>
                <th>分组</th>
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
                <td>&nbsp;<a href="?go=address&do=detail&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>"><?php echo $address->name; ?></a></td>
                 <td>&nbsp;<?php echo $address->get_typeid(); ?></td>
                <td>&nbsp;<?php echo $address->mobile; ?></td>
                <td>&nbsp;<?php echo $address->email; ?></td>
                <td>&nbsp;<?php echo $address->qq; ?></td>
                <td>&nbsp;<?php echo $address->msn; ?></td>
                <td>&nbsp;<?php echo $address->office_phone; ?></td>
                <td>&nbsp;<?php echo $address->home_phone; ?></td>
                <td><?php echo $address->remarks; ?></td>
                <!--<td>&nbsp;<?php echo $address->get_typeid(); ?></td>-->
                <!--<td>&nbsp;<?php echo $address->url; ?></td>-->
                <td nowrap="nowrap">&nbsp;
               
                  <?php if($address->address_id<0): ?>
                     <img style="width:15px;height:16px;background-position:0 -133px;" class="imgbundle" src="templates/images/transparent.gif">
                    <? else: ?>
                    <a href="?go=address&do=modify&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>"> <img style="width:15px;height:16px;background-position:0 -133px;" class="imgbundle" src="templates/images/transparent.gif"></a>
                    <?php endif; ?>


                    &nbsp;
                    
                    <?php if($address->address_id<0): ?>
                    <img src="templates/images/transparent.gif" alt="删除" class="imgbundle" style="width:15px;height:15px;background-position:0 -226px;">
                    <? else: ?>
                    <a href="?go=address&do=remove&address_id=<?php echo $address->address_id; ?>&query=<?php echo urlencode($query) ?>" onclick="return  confirm('您确定要删除该联系人吗？')"><img src="templates/images/transparent.gif" alt="删除" class="imgbundle" style="width:15px;height:15px;background-position:0 -226px;"></a>
                    <?php endif; ?></td>
            </tr>
            <?php endforeach ?>
            <?php else: ?>
            <tr>
                <td colspan="11">无</td>
            </tr>
            <?php endif ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11">                     
<b class="submitBtn"><button onClick="select_all(this)" type="button"><span class="iconbutton">全选</span></button></b>
<b class="submitBtn"><button onClick="reverse_all(this);" type="button"><span class="iconbutton">反选</span></button></b>
<b class="submitBtn"><button onClick="return remove_selected(this);" type="button"><span class="iconbutton deletebutton">删除</span></button></b>
</td>
            </tr>
        </tfoot>
    </table>
</form>
<?php endif?>
<?php include('page.tpl')?>
</div>
</body>
</html>