<?php include('common/header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
<style>
.data_advance{
	cursor:pointer;
}
</style>
<script>
$(document).ready(function(e) {
	$('.data_advance').click(function(){
		if(!$(this).find('input').size())return;
		var user_id = ($(this).find('input').val());
		//window.location.href = '?go=advancelog&do=modify&user_id='+goods_id;
		top.frmView.addNewTab('?go=advancelog&do=modify&user_id='+user_id,'用户预存款');
	});   
	
	$('.data_advance').hover(function(){
		if($(this).find('input').size())$(this).css('background','#FC3');
		else $(this).css('cursor','not-allowed');
	});
	$('.data_advance').mouseout(function(){
		$(this).css('background','');
	});
	 
});

</script>
<div class="division2">
<?php include('common/table.list.tpl')?>

</div>
</body>
</html>