<div class="pag pagTop clearfix">
<?php 
$intTotal   = $page['count'];
$intShowNum = $page['size'];
if(!$pagename)$pagename='page';
$aPageDatas = Pager ( $intTotal , $intShowNum ,5,$pagename);

    
?>
<?=show_pagenav($aPageDatas,$pagename)?>
</div>
