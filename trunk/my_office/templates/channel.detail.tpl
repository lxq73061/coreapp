<?php include('header.tpl')?>
<!--○<a href="?go=channel&<?php if(isset($_GET['query'])): ?><?=$_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">分类列表</a>&nbsp;
○<a href="?go=channel&do=append">添加分类</a><br>
-->
<div id="nav">
	<div class="left"><?=channel::get_nav($channel->channel_id)?></div>
	<div class="right"><a href="?go=channel&do=modify&channel_id=<?=$channel->channel_id?>&query=<?=urlencode($query)?>">[编辑]</a></div>
</div>

<div id="channel">
<dl class="doc">
		<dt><strong>文章列表</strong> </dt>
		<?php 
		 foreach( $docs as $c ) {?>
		 <dd><a title="" href="?go=doc&do=detail&doc_id=<?=$c->doc_id ?>"><?= $c->title ?>:<?= $c->hit ?></a><em>[<?= $c->name ?>] <?=$c->update_date?></em></dd>
		 <?php  } ?>
		 </dl>
 </div>
<?php $page = $page_doc;$pagename='page_doc'?>
<?php include('page.tpl')?>

<dl class="site">
		<dt><strong>网址列表</strong> </dt>
		<?php 
		 foreach( $sites as $c ) {?>
		 <dd><a title="" href="?go=site&do=detail&site_id=<?=$c->site_id ?>"><?= $c->title ?></a> <a href="<?= $c->url ?>" target="_blank">Go&raquo;</a> <em>[<?= $c->name ?>] <?=$c->update_date?></em></dd>
		 <?php  } ?>
		 </dl>
 </div>
<!-- 共<?=$page_doc['count']?>条，共<?=$page_doc['total']?>页
<?php if($page['page']<$page['total']): ?><a href="?<?php $_GET['page']=$page['page']+1;echo http_build_query($_GET); ?>">下一页</a>&nbsp;<?php endif; ?>
<?php if($page['page']>1): ?><a href="?<?php $_GET['page']=$page['page']-1;echo http_build_query($_GET); ?>">上一页</a><?php endif; ?>-->



  <?=related::get('channel',$channel->channel_id)?>
</body>
</html>