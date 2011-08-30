
<div id="comment" class="m_5">
	<?php if ($doc->remarks):?>  
	 <ul class="clr">
	<?php foreach($doc->remarks as $key=>$v):?>
    
		<li>
			
			
<p>  <?=$v['content']?>
          <span style="float:right"><?=$v['create_date']?> <?=$v['create_time']?><?php if ($online->user_id ==$doc->user_id ):?>
            <a href="/?go=doc_remark&do=remove&doc_remark_id=<?=$v['doc_remark_id']?>&doc_id=<?=$doc->doc_id?>&query=<?php echo urlencode($query) ?>" title="删除">
            <font color="">[x]</font></a>&nbsp;<?php endif;?></span>
</p>            
          </li>
		
	 <?php endforeach;?>
	 </ul>
	 <?php else:?>
	 <!--No Comment !-->
  <?php endif;?>
</div>  


<?php if ($online->user_id):?>	
<?php if(!IN_WAP):?>
<link href="/templates/images/face/ubb.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/templates/images/face/faces.js"></script>		
<script>
$(function(){
	if(document.all){
		var pv = $('#content').attr('placeholder');
		$('#content').val(pv);
		$('#content').css("color","#999");
		$('#content').click(function(){
			
			if($(this).val() ==pv){
				$(this).val("");
				$(this).css("color","#000");
			}else{
				
			}
		});	
	}

});
</script>
<?php endif?>
<div id="submit_comment" class="m_5">
<p><a name="comment"></a></p>
<p>&nbsp;</p>
<form  method="post" action="/?go=doc_remark&do=append&query=<?php echo urlencode($query) ?>" onsubmit="return check_comment(this);">
<a style="cursor:pointer" id="face" onclick="showFace(this.id,'content');"><img src="/templates/images/face/facelist.gif" align="absmiddle" /></a>      <textarea name="content" id="content" cols="60" rows="4" style="width:100%" placeholder="我想说两句"></textarea>
<input type="submit" value="发送"  /><br />
<input name="doc_id" type="hidden" value="<?=$doc->doc_id?>" />

</form>
</div>
<?php endif;?>
<DIV id="append_parent"></DIV>