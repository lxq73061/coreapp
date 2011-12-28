// JavaScript Document
function select_all(t){
	if(typeof t.form[ids] == "undefined"){
		return false;
	}
	var arr = t.form[ids];
	if(typeof arr.length == "undefined"){
		arr.checked = true;
		return true;
	}
	for(i=0;i<arr.length;i++){
		arr[i].checked = true;
	}
	return true;
}
function reverse_all(t){
	if(typeof t.form[ids] == "undefined"){
		return false;
	}
	var arr = t.form[ids];
	if(typeof arr.length == "undefined"){
		arr.checked = ! arr.checked;
		return true;
	}
	for(i=0;i<arr.length;i++){
		arr[i].checked = ! arr[i].checked;
	}
	return true;
}
function remove_selected(t){
	if(typeof t.form[ids] == "undefined"){
		alert("请选中要操作的项目后再点删除");
		return false;
	}
	var arr = t.form[ids];
	if(typeof arr.length == "undefined"){
		if(!arr.checked){
			alert("请选中要操作的项目后再点删除");
			return false;
		}
	}else{
		ret = false;
		for(i=0;i<arr.length;i++){
			if(arr[i].checked){
				ret = true;
				break;
			}
		}
		if(!ret){
			alert("请选中要操作的项目后再点删除");
			return false;
		}
	}
	if(!confirm("您确定删除这些选中的项目吗")){
		return false;
	}
	t.form.submit();
	return true;
}

function change_a(){
	 $('a').each(function(){		
		 // alert($(this).attr('href') + ' | ' + $(this).attr('href').indexOf('?'));
		  if($(this).attr('href').indexOf('?')!=-1 || $(this).attr('href').indexOf('://')!=-1){
			  if($(this).attr('href').indexOf('://')!=-1){
					$(this).attr('target','_blank');
			  }else{
					$(this).click(function(){
						var url=$(this).attr('href');
						var title=$(this).text();
						top.frmView.addNewTab(url,title,null)
						return false;
				  });
			  }
		  }
	  });
	  
}