<?php include('header.tpl')?>

○<a href="?go=book&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">帐本列表</a>&nbsp;
○<a href="?go=book&do=append">添加帐本</a><br>

<form method="post" action="?go=book&do=<?php echo $_GET['do']; ?>&book_id=<?php echo $_GET['book_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加帐本' : '修改帐本'; ?></legend>
<!--<label>文章名： <input type="text" name="title" value="<?php echo $post['title']; ?>"></label>
	<font color="red">*</font><?php if(isset($error['title'])): ?><font color="red"><?php echo $error['title']; ?></font><?php endif; ?><br>-->
<!--<label>来　源： <input type="text" name="copyfrom" value="<?php echo $post['copyfrom']; ?>"></label><br>-->
<label>账户： <select name="item" id="item">
	                <option value="1" <?=set_select($book['item'],1)?>>银行</option>
	                <option value="2" <?=set_select($book['item'],2)?>>支付宝</option>
	                <option value="3" <?=set_select($book['item'],3)?>>现金</option>
	                </select></label>
	<font color="red">*</font><?php if(isset($error['item'])): ?><font color="red"><?php echo $error['item']; ?></font><?php endif; ?><br>
    
    
<label>用途： 选择
	                <select name="item_txt" id="item_txt">
                    <option value="" <?=set_select('',$book['item_txt'])?>>=不选=</option>
	                <?php foreach( $item_txts as $k=>$v ){?>
	                <option value="<?=$v?>" <?=set_select($v,$book['item_txt'])?>><?=$v?></option>
	               <?php }?>
                </select>
                或输入
                <input name="item_txt2" type="text" id="item_txt2" value="" size="8" /></label>
	<?php if(isset($error['item_txt'])): ?><?php echo $error['item_txt']; ?><?php endif; ?><br>
    
    
<label>备注： <input type="text" name="remark" value="<?php echo $post['remark']; ?>"></label>
<?php if(isset($error['remark'])): ?><?php echo $error['remark']; ?><?php endif; ?><br>
<label>分类：
<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	channel::get_channel_select(0,0,$post['typeid']);
			?>
          </select>
	<?php if(isset($error['typeid'])): ?><?php echo $error['typeid']; ?><?php endif; ?></label><br>

<label>货币： 
  <select name="ccy" id="ccy">
	                <option value="CNY" <?=set_select($book['ccy'],'CNY')?>>CNY</option>
	                <option value="USD" <?=set_select($book['ccy'],'USD')?>>USD</option>
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
	<label><input id="ccy1" name="ccy2" value="0" type="radio" <?=set_radio($book['otype']<=0,true)?> >
                  <label for="ccy1"> 收入</label>
           <input id="ccy2" name="ccy2" value="1"  type="radio"  <?=set_radio($book['otype']<0,false)?> >
                  <label for="ccy2">  支出
	<?php if(isset($error['otype'])): ?><?php echo $error['otype']; ?></font><?php endif; ?>
 </label>
	<br>
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>