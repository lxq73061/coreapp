<div class="page_nav">

       Total:<?=$page['count']?>  Page:<?=$page['page']?>/<?=$page['total']?> 
      <?php if($page['page']>1): ?>
    <a href="?<?php $_GET['page']=$page['page']-1;echo http_build_query($_GET); ?>">Previous</a>
    <?php endif; ?> 
    <?php if($page['page']<$page['total']): ?>
    <a href="?<?php $_GET['page']=$page['page']+1;echo http_build_query($_GET); ?>">Next</a>&nbsp;
    <?php endif; ?>

</div>
