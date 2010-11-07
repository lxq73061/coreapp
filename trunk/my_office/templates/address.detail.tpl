<?php include('header.tpl')?>


○<a href="?<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">联系人列表</a>&nbsp;
○<a href="?go=address&do=append">新建联系人</a></ul>


<form method="post">
<fieldset>
<legend>详细</legend>

名称：<?php echo htmlspecialchars($address->name); ?><br>
	<br>
手机：<?php echo htmlspecialchars($address->mobile); ?><br>
	<br>   
email：<?php echo htmlspecialchars($address->email); ?><br>
	<br>  
qq：<?php echo htmlspecialchars($address->qq); ?><br>
	<br>  
msn：<?php echo htmlspecialchars($address->msn); ?><br>
	<br> 
办公电话：<?php echo htmlspecialchars($address->office_phone); ?><br>
	<br>      
住宅电话：<?php echo htmlspecialchars($address->home_phone); ?><br>
	<br>           
备注：<?php echo htmlspecialchars($address->remarks); ?><br>
	<br>
<!--  通讯名：<?php echo $address->url; ?><br>
	<br>-->
分　类：<?php echo $address->get_typeid(); ?><br>
	<br>
<!--内容：<?php echo htmlspecialchars($address->content); ?><br>
	<br>-->
</fieldset>
</form>

</body>
</html>