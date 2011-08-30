<?php include('header.tpl')?>
<?php if(IN_WAP):?>
<a href="/">Home</a><br />
<?php endif?>
○<a href="?go=doc&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章列表</a>&nbsp;
○<a href="?go=doc&do=append">添加文章</a><br>
<script>
var status = false;
var timer = null;
function check(){
	if(status) return true;
	timer = setTimeout("alert('net connect error!')",15000);
	$.get('/?go=welcome&do=online',function(d,s){
		clearTimeout(timer);
		if(d=='online'){
			 status = true;		
			 $('#doc_form').submit();
		}else{
			alert("网络连接失败，请保存好你的数据!");
		}
	});
		
	return false;
	
}

</script>
<form id="doc_form" method="post" action="?go=doc&do=<?php echo $_GET['do']; ?>&doc_id=<?php echo $_GET['doc_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>" onsubmit="return check(false)">
<fieldset>
<legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加文章' : '修改文章:<a href="?go=doc&do=detail&doc_id='.$post['doc_id'].'">'.$post['title'].'</a>'; ?></legend>
<label>文章名： <input type="text" class="input"  name="title" value="<?php echo $post['title']; ?>"></label>
	<font color="red">*</font><?php if(isset($error['title'])): ?><font color="red"><?php echo $error['title']; ?></font><?php endif; ?><br>
<label>来　源： <input type="text" class="input"  name="copyfrom" value="<?php echo $post['copyfrom']; ?>"></label><br>
<label>分　类：
<select name="typeid" id="typeid">
            <option value="0">-----顶级分类-----</option>
          <?=channel::get_channel_select(0,0,$post['typeid'],0,'doc')?>
          </select>  
	<font color="red">*</font>
	<?php if(isset($error['typeid'])): ?><font color="red"><?php echo $error['typeid']; ?></font><?php endif; ?></label><br>
    
<!--<label>加密：<input type="checkbox"  value="1" name="encrypt"> </label><br>    
-->    

<label>关键词： 
  <input type="text" class="input"  name="keyword" value="<?php echo $post['keyword']; ?>"></label>
<?php if(isset($error['keyword'])): ?>
<font color="red"><?php echo $error['keyword']; ?></font><?php endif; ?>
	<br>
<div style="display:none">	<label> </label>
	<input type="checkbox" checked="checked" value="1" name="auto_keywords"> 自动提取关键词
	<font color="red">*</font></div>
	 
	</label>
	<br>
<label>
<?php if(IN_WAP){?>
  <textarea name="content" ><?php echo $post['content']; ?></textarea>
<?php }else{?>
  <textarea name="content" style="DISPLAY: none"><?php echo $post['content']; ?></textarea>
<iframe id=content___Frame src="./includes/lib/fckeditor/editor/fckeditor.html?InstanceName=content&Toolbar=Default" frameborder=0 width=95% scrolling=no height=500>	</iframe>
<?php }?>
      
  
  </label>
	<?php if(isset($error['content'])): ?><font color="red"><?php echo $error['content']; ?></font><?php endif; ?>
	<br>
<input type="submit" value="提交">
</fieldset>
</form>

</body>
</html>