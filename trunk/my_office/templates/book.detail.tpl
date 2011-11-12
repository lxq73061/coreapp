<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
○<a href="?<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">帐本列表</a>&nbsp;
○<a href="?go=book&do=append">添加帐本</a></ul>


<form method="post">
<fieldset>
<legend>账户详细</legend>

账  户：<?php echo htmlspecialchars($book->item); ?><br>
	<br>
用	途：<?php echo htmlspecialchars($book->item_txt); ?><br>
	<br>
备 注：<?php echo htmlspecialchars($book->remark); ?><br>
	<br>
货	币：<?php echo htmlspecialchars($book->ccy); ?><br>
	<br>
收	入：<?php if($book->otype=='IN')
    {
    	echo $book->amount;
    } ?><br>
	<br>
支	出：<?php if($book->otype=='OUT')
        {
        	echo $book->amount;
        } ?><br>
	<br>
金	额：<?php echo htmlspecialchars($book->net); ?><br>
</fieldset>
</form>
 <?=related::get('book',$book->book_id)?>
</body>
</html>