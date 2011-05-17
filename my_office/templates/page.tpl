<div class="pag pagTop clearfix">
<?php 
$intTotal   = $page['count'];
$intShowNum = $page['size'];
$aPageDatas = Pager ( $intTotal , $intShowNum );
        
?>
<?=show_pagenav($aPageDatas)?>
</div>
