<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
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

<?php $page = $page_doc;$pagename='page_doc'?>
<?php include('page.tpl')?>
<dl class="diary">
		<dt><strong>记事列表</strong> </dt>
		<?php 
		 foreach( $diarys as $c ) {?>
		 <dd><a title="" href="?go=diary&do=detail&diary_id=<?=$c->diary_id ?>"><?= $c->title ?></a>  <em>[<?= $c->name ?>] <?=$c->update_date?></em></dd>
		 <?php  } ?>
		 </dl>

<?php $page = $page_diary;$pagename='page_diary'?>
<?php include('page.tpl')?>

<dl class="site">
		<dt><strong>网址列表</strong> </dt>
		<?php 
		 foreach( $sites as $c ) {?>
		 <dd><a title="" href="?go=site&do=detail&site_id=<?=$c->site_id ?>"><?= $c->title ?></a> <a href="<?= $c->url ?>" target="_blank">Go&raquo;</a> <em>[<?= $c->name ?>] <?=$c->update_date?></em></dd>
		 <?php  } ?>
		 </dl>

<?php $page = $page_site;$pagename='page_site'?>
<?php include('page.tpl')?>


<dl class="address">
		<dt><strong>联系人列表</strong> </dt>
		<?php 
		 foreach( $addresss as $c ) {?>
		 <dd><a title="" href="?go=address&do=detail&address_id=<?=$c->address_id ?>"><?= $c->title ?></a> <em>[<?= $c->name ?>] <?=$c->update_date?></em></dd>
		 <?php  } ?>
		 </dl>

<?php $page = $page_address;$pagename='page_address'?>
<?php include('page.tpl')?>

 </div>
 



  <?=related::get('channel',$channel->channel_id)?>
</body>
</html>