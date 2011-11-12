<?php
define('GET_DATE',true);
include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
○<a href="?go=book&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">帐本列表</a>&nbsp;
○<a href="?go=book&do=append">添加帐本</a><br>

<form method="post" action="?go=book&do=<?php echo $_GET['do']; ?>&book_id=<?php echo $_GET['book_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加帐本' : '修改帐本'; ?></legend>

<label>日期：
  <input name="create_date" class="datepicker_input"  type="text" id="create_date" size="12" value="<?=$post['create_date']?>" /></label>
	<font color="red">*</font><?php if(isset($error['create_date'])): ?><font color="red"><?php echo $error['create_date']; ?></font><?php endif; ?><br>
<label>时间： 
  <input name="create_time" class="" type="text" size="12" value="<?=$post['create_time']?>" /></label>
	<font color="red">*</font><?php if(isset($error['create_time'])): ?><font color="red"><?php echo $error['create_time']; ?></font><?php endif; ?><br>
    
    
<label>账户： <select name="item" id="item">
	                <option value="1" <?=set_select($post['item'],1)?>>银行</option>
	                <option value="2" <?=set_select($post['item'],2)?>>支付宝</option>
	                <option value="3" <?=set_select($post['item'],3)?>>现金</option>
                    <option value="4" <?=set_select($post['item'],4)?>>信用卡</option>
	                </select></label>
	<font color="red">*</font><?php if(isset($error['item'])): ?><font color="red"><?php echo $error['item']; ?></font><?php endif; ?><br>
用途： 选择
                <select name="item_txt" id="item_txt">
                    <option value="" <?=set_select('',$post['item_txt'])?>>=不选=</option>
	                <?php foreach( $item_txts as $k=>$v ){?>
	                <option value="<?=$v?>" <?=set_select($v,$post['item_txt'])?>><?=$v?></option>
	               <?php }?>
            </select>
            或输入
            <input name="item_txt2" type="text" id="item_txt2" value="" size="8" />
	<?php if(isset($error['item_txt'])): ?><?php echo $error['item_txt']; ?><?php endif; ?><br>
    
    
<label>备注： <input type="text" name="remark" value="<?php echo $post['remark']; ?>"></label>
<?php if(isset($error['remark'])): ?><?php echo $error['remark']; ?><?php endif; ?><br>
<label>货币： 
  <select name="ccy" id="ccy">
	                <option value="CNY" <?=set_select($post['ccy'],'CNY')?>>CNY</option>
	                <option value="USD" <?=set_select($post['ccy'],'USD')?>>USD</option>
	                </select></label>
<?php if(isset($error['ccy'])): ?>
<font color="red"><?php echo $error['ccy']; ?></font><?php endif; ?>
	<br>
<label>金额： 
  <input type="text" name="amount" size="10" value="<?php echo $post['amount']; ?>">
  </label>
<?php if(isset($error['amount'])): ?>
<font color="red"><?php echo $error['amount']; ?></font><?php endif; ?>
<br>
<label> </label>
	方式：
	 <label for="otype1"><input id="otype1" name="otype" value="IN" type="radio" <?=set_radio($post['otype'],'IN')?> >收入</label>
     <label for="otype2"> <input id="otype2" name="otype" value="OUT"  type="radio"  <?=set_radio($post['otype'],'OUT')?> >支出</label>
      <?php if(isset($error['otype'])): ?><?php echo $error['otype']; ?></font><?php endif; ?>
 
	<br>
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>