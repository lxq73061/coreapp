<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
<script>
var status = false;
var timer = null;
function check(){
	if($.trim($('#doc_edit_content').html())=='')return false;
	if(status) return true;
	timer = setTimeout("alert('net connect error!')",15000);
	$.get('/?go=welcome&do=online',function(d,s){
		clearTimeout(timer);
		if(d=='online'){
			 status = true;	
			 $('input[name="content"]').val($('#doc_edit_content').html());
			 $('#doc_form').submit();
		}else{
			alert("网络连接失败，请保存好你的数据!");
		}
	});
		
	return false;
	
}
function quick(){
	if($('#subbtn').is(":hidden")){
		$('#doc_edit_content').css('border','1px solid #FF9900');
		$('#doc_edit_content').css('padding','10px');
		$('#doc_edit_content').attr('contenteditable',true);
		$('#resetbtn').show();
		$('#subbtn').show();
		if($.trim($('#doc_edit_content').html())==''){
			$('#doc_edit_content').html("在此输入要编辑的内容。");
		}
	}else{
		$('#doc_edit_content').css('border','');
		$('#doc_edit_content').css('padding','');
		$('#doc_edit_content').attr('contenteditable',false);
		$('#subbtn').hide();
		$('#resetbtn').hide();
		if($.trim($('#doc_edit_content').html())=='在此输入要编辑的内容。'){
			$('#doc_edit_content').html("");
		}
	}
}

</script>

<div id="nav">
	<div class="left"><?=channel::get_nav($doc->typeid)?></div>
	<div class="right"><a href="#"  onclick="quick()" >[快速编辑]</a> <a href="?go=doc&do=modify&doc_id=<?=$doc->doc_id?>&query=<?php echo urlencode($query) ?>">[编辑]</a> <a href="?go=doc&do=append&query=<?php echo urlencode($query) ?>">[新建]</a></div>
</div>
<div class="doc_content_box">
    <div class="doc_content">
        <h1 class="title"><?php echo  htmlspecialchars($doc->title); ?></h1>
        <h4 class="copyfrom">来源：<?php echo  $doc->copyfrom; ?></h4>
        <h4 class="keywords">关键词：<?php echo  htmlspecialchars($doc->keyword); ?></h4>   
        <form id="doc_form" method="post" action="?go=doc&do=modify&doc_id=<?php echo $_GET['doc_id']; ?>&quick=true&query=<?php echo urlencode('go=doc&do=detail&doc_id='.$_GET['doc_id']); ?>" onsubmit="return check(false)">
        <div id="doc_edit_content"><?php echo ($doc->content);?>
        </div>
       <input name="content" type="hidden" >
       <input type="submit" value="保存修改" id="subbtn" style="display:none">
       <input type="button" value="取消" id="resetbtn" onclick="quick()" style="display:none">
        </form>
        <h4 class="create_time">创建日期：<?=$doc->create_date;  ?> <?=$doc->create_time;  ?></h4>   
        <h4 class="update_time">最后更新：<?=$doc->update_date;  ?> <?=$doc->update_time;  ?></h4>   
        <h4 class="update_time">访问次数：<?=$doc->hit;  ?></h4>   
       
    </div>
</div>

<?php include('doc.detail.remark.tpl');?>
<?=related::get('doc',$doc->doc_id)?>
</body>
</html>