<?php
define('GET_DATE',true);
include('header.tpl')?>

<?php include('welcome.wap.head.tpl')?>

<div class="division">
<a href="?go=book_item&do=browse" class="sysiconBtn list " >账户管理</a>
<a href="?go=book&do=browse" class="sysiconBtn list">帐本列表</a>&nbsp;
<a href="?go=book&do=append" class="sysiconBtn addorder addproduct" rel="facebox">添加帐本</a>&nbsp;
<a href="?go=book&do=import" class="sysiconBtn addorder addproduct" rel="facebox">网银导入</a>&nbsp;

<br>
<table cellspacing="0" cellpadding="0">
    <tr>
        <th> 账目总数：</th>
        <td><?php echo $totals[total]; ?> 笔</td>
    </tr>
    <tr>
        <th>资金余额：</th><td><?php echo $totals['amount']; ?>
            <?=$get['ccy']?></td>
    </tr>
</table>
<hr />

<div class="clearfix note" style="border:none;"><form id="form1" name="form1" method="get" action="">
    <input type="hidden" name="go" value="book">
    <input type="hidden" name="do" value="browse">
    <label for="from"></label>
    日期：
    <input name="from" type="text" class="datepicker_input" id="datepickerFrom" size="10" value="<?=$get['from']?>" />
   ~
    <input name="to" type="text" class="datepicker_input"  id="datepickerTo" size="10"  value="<?=$get['to']?>"/>
账户：
    <select name="book_item_id" id="book_item_id">
                    <option value="" <?=set_select('',$get['item_txt'])?>>=不选=</option>
	                <?php foreach( $book_items as $k=>$v ){?>
	                <option value="<?=$k?>" <?=set_select($k,$get['book_item_id'])?>><?=$v?></option>
	               <?php }?>
            </select> 
往来：
                <select name="opposite" id="item_txt">
                  <option value="" <?=set_select('',$get['opposite'])?>>=不选=</option>
	                <?php foreach( $opposites as $k=>$v ){?>
	                <option value="<?=$v?>" <?=set_select($v,$get['opposite'])?>><?=$v?></option>
	               <?php }?>
            </select>
用途：
    <select name="item_txt" id="item_txt">
                    <option value="" <?=set_select('',$get['item_txt'])?>>=不选=</option>
	                <?php foreach( $item_txts as $k=>$v ){?>
	                <option value="<?=$v?>" <?=set_select($v,$get['item_txt'])?>><?=$v?></option>
	               <?php }?>
      </select>
      
货币：
    <select name="ccy" id="ccy">
  <?php foreach( $ccys as $k=>$v ){?>
	   <option value="<?=$k?>" <?=set_select($get['ccy'],$k)?>><?=$v?></option>
  <?php }?> 
    </select>
               

            
            

                
   <input id="BtnOK" class="sysiconBtnNoIcon" type="submit" value="查 询" name="BtnOK" />
</form></div>

<?php $ids = 'book_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>
<?php include('page.tpl')?>
<form method="post" action="?go=book&do=group_remove&query=<?php echo urlencode($query) ?>">
    <table cellspacing="1" cellpadding="5" border="0" class=" width_box gridlist">
        <thead>
            <tr class="td0 c">
                <th scope="col">ID</th>
                <th scope="col">日期</th>
                <th scope="col">账户</th>
                <th scope="col">往来</th>
                <th scope="col">用途</th>
                <th scope="col">备注</th>
                <th scope="col">货币</th>
                <th scope="col">收入</th>
                <th scope="col">支出</th>
                <th scope="col">余额</th>
                <th scope="col">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php $items=array('1'=>'银行','2'=>'支付宝','3'=>'现金','4'=>'信用卡');?>
            <?php if($books):?>
            <?php foreach($books as $book): ?>
            <?php $class == 'td2' ? $class  = 'td2alt':$class  = 'td2';?>
            <tr class="<?=$class?> r">
                <td><input type="checkbox" name=<?php echo $ids ?> value="<?php echo $book->book_id; ?>">
                     <a href="?go=book&do=detail&book_id=<?php echo $book->book_id; ?>"><?php echo $book->book_id; ?></a></td>
                <td>&nbsp;<?php echo date('H:i m/d/y',strtotime($book->create_date .' '. $book->create_time)); ?></td>
                <td nowrap="nowrap">&nbsp;<?php echo $book_items[$book->book_item_id]; ?></td>
                <td><?php echo $book->opposite; ?></td>
                <td>&nbsp;<?php echo $book->item_txt; ?></td>
                <td>&nbsp;<?php echo $book->remark; ?></td>
                <td>&nbsp;<?php echo $book->ccy; ?></td>
                <td>&nbsp;
                    <?=($book->otype=='IN')?$book->amount:''?></td>
                <td>&nbsp;
                    <?=($book->otype=='OUT')?$book->amount:''?></td>
                <td>&nbsp;<?php echo $book->net; ?></td>
                <td><!--&nbsp;<a href="?go=book&do=detail&book_id=<?php echo $book->book_id; ?>&query=<?php echo urlencode($query) ?>">详细</a> | --> 
                    &nbsp;
                    <?php if($book->book_id<0): ?>
                    修改
                    <? else: ?>
                    <a href="?go=book&do=modify&book_id=<?php echo $book->book_id; ?>&query=<?php echo urlencode($query) ?>" rel="facebox">修改</a>
                    <?php endif; ?>
                    | 
                    
                    &nbsp;
                    <?php if($book->book_id<0): ?>
                    删除
                    <? else: ?>
                    <a href="?go=book&do=remove&book_id=<?php echo $book->book_id; ?>&query=<?php echo urlencode($query) ?>" onclick="return  confirm('您确定要删除该记录吗？')">删除</a>
                    <?php endif; ?></td>
            </tr>
            <?php endforeach ?>
            <?php else: ?>
            <tr>
                <td colspan="12">no data</td>
            </tr>
            <?php endif ?>
        </tbody>
        <tfoot>
            <tr>                <td colspan="11">&nbsp;
                
<b class="submitBtn"><button onClick="select_all(this)" type="button"><span class="iconbutton">全选</span></button></b>
<b class="submitBtn"><button onClick="reverse_all(this);" type="button"><span class="iconbutton">反选</span></button></b>
<b class="submitBtn"><button onClick="return remove_selected(this);" type="button"><span class="iconbutton deletebutton">删除</span></button></b>
</td>
            </tr>
                </thead>
            
    </table>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <th> 支出交易笔数：</th>
            <td><?php echo $totals['total_out']; ?></td>
            <th>收入交易笔数：</th>
            <td><?php echo $totals['total_in']; ?></td>
      </tr>
        <tr>
            <th>支出金额合计：</th>
            <td><?php echo $totals['out_amount']; ?></td>
            <th>收入金额合计：</th>
            <td><?php echo $totals['in_amount']; ?></td>
        </tr>
    </table>
    <p>&nbsp;</p>
 
</form>
<?php include('page.tpl')?></div>

</body></html>