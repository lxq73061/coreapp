<?php
define('GET_DATE',true);
include('header.tpl')?>

○<a href="?go=book&do=browse">帐本</a>&nbsp;
○<a href="?go=book&do=append" rel="facebox">添加帐本<img src="templates/images/add.gif" border="0" width="16" height="16" /></a><br>
<table cellspacing="0" cellpadding="0">
    <tr>
        <td> 账目总数：<?php echo $totals[total]; ?> 笔</td>
        <td></td>
    </tr>
    <tr>
        <td>资金余额：<?php echo $totals['amount']; ?>
            <?=$get['ccy']?></td>
    </tr>
</table>
<hr />

<form id="form1" name="form1" method="get" action="">
    <input type="hidden" name="go" value="book">
    <input type="hidden" name="do" value="browse">
    <label for="from"></label>
    起始日期：
    <input name="from" type="text" class="datepicker_input" id="datepickerFrom" size="10" value="<?=$get['from']?>" />
    终止日期：
    <input name="to" type="text" class="datepicker_input"  id="datepickerTo" size="10"  value="<?=$get['to']?>"/>
    &nbsp;货币：
    <select name="ccy" id="ccy">
        <option value="CNY" <?=set_select($get['ccy'],'CNY')?>>CNY</option>
        <option value="USD" <?=set_select($get['ccy'],'USD')?>>USD</option>
    </select>
    <input id="BtnOK" type="submit" value="查 询" name="BtnOK" />
</form>

<?php $ids = 'doc_id[]';?>
<script language="javascript">
var ids = '<?=$ids?>';
</script>
<form method="post" action="?go=book&do=group_remove&query=<?php echo urlencode($query) ?>">
    <table cellspacing="1" cellpadding="5" border="0" class="table0 width_box">
        <thead>
            <tr class="td0 c">
                <th scope="col">ID</th>
                <th scope="col">日期</th>
                <th scope="col">账户</th>
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
                    <?php echo $book->book_id; ?></td>
                <td>&nbsp;<?php echo $book->create_date; ?> <?php echo $book->create_time; ?></td>
                <td>&nbsp;<?php echo $items[$book->item]; ?></td>
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
                    <a href="?go=book&do=modify&book_id=<?php echo $book->book_id; ?>&query=<?php echo urlencode($query) ?>">修改</a>
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
                <td colspan="11">no data</td>
            </tr>
            <?php endif ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">&nbsp;
                    <input type="button" value="全选" onClick="select_all(this)">
                    <input type="button" value="反选" onClick="reverse_all(this);">
                    <input type="button" value="删除" onClick="return remove_selected(this);"></td>
            </tr>
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
 
</form>
<?php if($page['page']<$page['total']): ?>
<a href="?<?php $_GET['page']=$page['page']+1;echo http_build_query($_GET); ?>">下一页</a>&nbsp;
<?php endif; ?>
<?php if($page['page']>1): ?>
<a href="?<?php $_GET['page']=$page['page']-1;echo http_build_query($_GET); ?>">上一页</a>
<?php endif; ?>
</body></html>