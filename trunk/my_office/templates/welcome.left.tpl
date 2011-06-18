<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<style>

.loading {
	background-image:url("templates/images/loading.gif");
	background-position:center center;
	background-repeat:no-repeat;
	height:200px;
}
</style>
<link href="templates/images/tree/menu.css" rel="stylesheet" type="text/css" />
<script src="templates/images/tree/menu.js" type="text/javascript"></script>
<script src="includes/lib/jquery/jquery.min.js" type="text/javascript"></script><!--
<script src="includes/lib/jquery/jquery-ui.min.js" type="text/javascript"></script>-->

</head>

<body>
                                                                            <td>
<form action="/" method="get" target="frmView" name="search_from" id="search_from" onSubmit="return submitQuery(this)">
<input type="hidden" name="do" value="browse">




<select name="go">
	<option value="address">通讯录</option>
	<option value="doc"  selected="selected" >文章</option>
	<option value="site" >网址</option>
    <option value="diary" >日志</option>
</select> 
<input name="limit" type="hidden" value="20" size="2"><!--条/页<br>-->

<select name="order">
<option value="doc_id" <?php if($get['order'] === 'doc_id') echo 'selected'; ?>>创建日期↑</option>
<option value="doc_id2" <?php if($get['order'] === 'doc_id2') echo 'selected'; ?>>创建日期↓</option>

<option value="date" <?php if($get['order'] === 'date') echo 'selected'; ?>>修改日期↑</option>
<option value="date2" <?php if($get['order'] === 'date2') echo 'selected'; ?>>修改日期↓</option>

<option value="hit" <?php if($get['order'] === 'hit') echo 'selected'; ?>>访问次数↑</option>
<option value="hit2" <?php if($get['order'] === 'hit2') echo 'selected'; ?>>访问次数↓</option>
</select><br>
<input name="keyword" type="text" id="keyword" value="" size="14"> <input name="" type="submit" value="搜索">
</form>
<script>
function submitQuery(f){
	//alert(f.action);
	var title ='搜索';
	//童装+:SDF&^%20 2
	//童装%2B%3ASDF%26^%2520+2
	//.replace('+','%2B').replace('&','%26').replace('?','%3F').replace('#','%23').replace('%','%25')
	//alert(escape(f.keyword.value));
	//alert(encodeURI(f.keyword.value));
	//alert(encodeURIComponent (f.keyword.value));
	
	var url = f.action + '?go=' + f.go.value + '&do='+ f.do.value + '&keyword=' + encodeURIComponent(f.keyword.value) + '&order=' + f.order.value + '&limit=' + f.limit.value;
	
	
		
		if(f.go.value == 'channel'){
			title +='分类';
		}else
		if(f.go.value == 'doc'){
			title +='文章';
		}else
		if(f.go.value == 'diary'){
			title +='日志';
		}else
		if(f.go.value == 'site'){
			title +='站点';
		}else
		if(f.go.value == 'address'){
			title +='通讯';
		}else{
			title +='';
		}
		title +=':'+f.keyword.value+'';
	top.frmView.addNewTab(url,title,null);
	return false;

}

function addw(a){
/*调整左栏宽度的函数*/
	var frame = window.parent.document.getElementById("info_show_frame");   	
	arr=frame.cols.split(",");   
	//for(i=0;i<arr.length;i++)alert(i+":"+arr[i]) ;  
	var iw = parseInt(arr[0]);	
	if(a == '+'){
		frame.cols= iw + 10 + ",12,*";
	}else{
		frame.cols= iw - 10 + ",12,*";
	}
}

function change_a(){
	 $('a').each(function(){		
		 // alert($(this).attr('href') + ' | ' + $(this).attr('href').indexOf('?'));
		  if($(this).attr('href').indexOf('?')!=-1){
			  
				$(this).click(function(){
					var url=$(this).attr('href');
					var title=$(this).text();
					var type=$(this).attr('class');//YEMATree_A
					//alert(typeof top.frmView.addNewTab);
					var type=null;//类型(图标)
					top.frmView.addNewTab(url,title,type)
					return false;
			  });
		  }
	  });
	  
}
  $(function(){

	  
	  
  });
   </script>
<hr />
  <input type="button" value="++" onClick="tree1.openall()" title="展开全部" alt="展开全部" />
  <input type="button" value="--" onClick="tree1.closeall()" title="折叠全部"  />
  <input type="button" value="&lt;&lt;" onClick="addw('-')" />
  <input type="button" value="&gt;&gt;" onClick="addw('+')" />
<?php echo $tree;?>


</body>
</html>