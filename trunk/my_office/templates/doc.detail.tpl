<?php include('header.tpl')?>
<?php if(IN_WAP):?>
<a href="/">Home</a><br />
<?php endif?>
<div id="nav">
	<div class="left"><?=channel::get_nav($doc->typeid)?></div>
	<div class="right"><a href="?go=doc&do=modify&doc_id=<?=$doc->doc_id?>&query=<?php echo urlencode($query) ?>">[编辑]</a> <a href="?go=doc&do=append&query=<?php echo urlencode($query) ?>">[新建]</a></div>
</div>
<div class="doc_content_box">
    <div class="doc_content">
        <h1 class="title"><?php echo  htmlspecialchars($doc->title); ?></h1>
        <h4 class="copyfrom">来源：<?php echo  $doc->copyfrom; ?></h4>
        <h4 class="keywords">关键词：<?php echo  htmlspecialchars($doc->keyword); ?></h4>   
        
        <?php echo ($doc->content);?>
        <h4 class="create_time">创建日期：<?=$doc->create_date;  ?> <?=$doc->create_time;  ?></h4>   
        <h4 class="update_time">最后更新：<?=$doc->update_date;  ?> <?=$doc->update_time;  ?></h4>   
        <h4 class="update_time">访问次数：<?=$doc->hit;  ?></h4>   
       
    </div>
</div>

<?php include('doc.detail.remark.tpl');?>
<?=related::get('doc',$doc->doc_id)?>
</body>
</html>