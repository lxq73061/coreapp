
// 获取编辑器中HTML内容 
function getEditorHTMLContents(EditorName) { 
var oEditor = FCKeditorAPI.GetInstance(EditorName); 

return(oEditor.GetXHTML(true)); 
} 

// 获取编辑器中文字内容 
function getEditorTextContents(EditorName) { 
var oEditor = FCKeditorAPI.GetInstance(EditorName); 
return(oEditor.EditorDocument.body.innerText); 
} 

// 设置编辑器中内容 
function SetEditorContents(EditorName, ContentStr) { 
var oEditor = FCKeditorAPI.GetInstance(EditorName) ; 
oEditor.SetHTML(ContentStr) ; 
} 


//分类中翻译用
function tr(code,text){
	if(code=='en')return;
	var name;
		
	name=title+'['+code2id(code)+']';
	$('input[name="'+name+'"]').val('Translate...');
	$.post('./GTranslate.php?ajax=true',{'text':text,'code':code},function(d,s){		
		$('input[name="'+name+'"]').val(d);
	});
}
//分类中翻译用 描述
function trd(code){
	if(code=='en')return;
	var name;
	
	if($('textarea[name="'+txt_area+'[1]"]').size()==0){
		text = getEditorHTMLContents(''+txt_area+'[1]');
	}else{
		text =  $('textarea[name="'+txt_area+'[1]"]').val();
	}
		
	name=''+txt_area+'['+code2id(code)+']';
	//$('textarea[name="'+name+'"]').val('Translate...');
	$.post('./GTranslate.php?ajax=true',{'text':text,'code':code},function(d,s){	
		if($('textarea[name="'+txt_area+'[1]"]').size()==0){
			SetEditorContents(name,d);
		}else{

			$('textarea[name="'+name+'"]').val(d);
		}
		
		
	});
}
function code2id(code){
	for(i in languages_json){
		if(languages_json[i]['code']==code)return languages_json[i]['languages_id'];
	}
	return 1;
}
function id2code(id){
	for(i in languages_json){
		if(languages_json[i]['languages_id']==id)return languages_json[i]['code'];
	}
	return 'en';
}
function id2codes(id){
	for(i in languages_json){
		if(languages_json[i]['languages_id']==id)return languages_json[i]['name'];
	}
	return 'English';
}

var title;
var txt_area;

function init_translate(){
	
	if($('input[name^="pages_title"]').size()!=0){
		title = 'pages_title';
		txt_area = 'pages_html_text';
		
	}else if($('input[name^="categories_name"]').size()!=0){
		title = 'categories_name';
		txt_area = 'categories_description';
	}else if($('input[name^="products_name"]').size()!=0){
		
		title = 'products_name';
		txt_area = 'products_description';	
	}
	
	
	if($('input[name^="'+title+'"]').size() !=0 &&$('input[name^="'+title+'"]').attr('type')!='hidden'){
			$('input[name^="'+title+'"]').each(function(){
				
					var name=$(this).attr('name');
					var id=name.replace(''+title+'[','').replace(']','');	
					
					var codes = id2codes(id);
					if(id!=1){
						$(this).attr('title','双击翻译到'+codes);
					}
			});
		$('input[name^="'+title+'"]').dblclick(function(){
		
			var text = $('input[name="'+title+'[1]"]').val();
			var name=$(this).attr('name');
			var id=name.replace(''+title+'[','').replace(']','');	
			tr(id2code(id),text);
		});
		
		if($('textarea[name="'+txt_area+'[1]"]').size()==0){//FCK
				$('input[name^="'+txt_area+'"]').each(function(){
					
					var name=$(this).attr('name');
					var id=name.replace(''+txt_area+'[','').replace(']','');	
					if(id!=1){
						var codes = id2codes(id);
						var code = id2code(id);
						
						var button = "<input type=button name='' value='翻译到"+codes+"' onclick='trd(\""+code+"\")'>";
					
							var html = $(this).parent().html();
							html = '<table width=100%><tr>'+html+'<td></td><td>'+button+'</td></tr></table>';
							$(this).parent().html(html);
						
					}
					
				});
		}else{
			$('textarea[name^="'+txt_area+'"]').each(function(){
					var name=$(this).attr('name');
					var id=name.replace(''+txt_area+'[','').replace(']','');	
					if(id!=1){
						var codes = id2codes(id);
						var code = id2code(id);
						
						var button = "<input type=button name='' value='翻译到"+codes+"' onclick='trd(\""+code+"\")'>";
							$(this).after(button);
						
					}
					
			});
		}
	
	}
}