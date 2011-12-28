<?php if(IN_WAP):?>
<div><a href="/">Home</a> 欢迎:<?php echo $online->username?> <a href="?go=user&do=logout" target="_top">退出</a> <?php echo date('Y-m-d H:i:s')?></div>   
<?php endif?>