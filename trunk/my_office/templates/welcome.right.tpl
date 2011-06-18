
<script src="includes/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="includes/lib/jquery/jquery.cookie.js" type="text/javascript"></script>


  <link href="templates/css/ui.tabs.css" rel="stylesheet" type="text/css" />
<link href="templates/css/css.css" rel="stylesheet" type="text/css" />                                                                        
        <div id="tabs">
            <ul class="ui-tabs-nav">
              <!--  <li class="ui-tabs-selected"><a href="#tabs-1"><span>文章</span></a></li>             -->
            </ul>
        </div>     
                                                                                  
        <div id="contents">
             <!-- <div id="tabs-1" class="ui-tabs-panel"><iframe src="?go=doc&do=browse" width="100%" frameborder="0"></iframe></div>          -->    
        </div>     
		
<script>
var icon_channel = '<img width="16" height="17" border="0" align="absmiddle" src="templates/images/tree/open.gif">';
var icon_doc = '<img width="16" height="17" border="0" align="absmiddle" src="templates/images/tree/e.gif">';

var icon_diary = '<img width="16" height="17" border="0" align="absmiddle" src="templates/images/tree/b.gif">';
var icon_site = '<img width="16" height="17" border="0" align="absmiddle" src="templates/images/tree/e1.gif">';
var icon_address = '<img width="16" height="17" border="0" align="absmiddle" src="templates/images/tree/usergroupicon.gif">';


  $(document).ready(function(){
	  init();
	    //$("#tabs",top.topFrame.document).tabs();
  });
var current=0;  
var current_tabs=[];
  

function init(){
	if($.cookie('current_tabs')){
		objString = $.cookie('current_tabs');
		objString = JSON.parse(objString);
		if(objString)current_tabs=objString;
		//alert(current_tabs.length);
	}
	for(i in current_tabs){
		addNewTab(current_tabs[i][1],current_tabs[i][0],null);
	}
	$('#tabs .ui-tabs-nav li a').live("click", function(){
		swtichtab($(this));
	});	 
	  
	$('#tabs .ui-tabs-nav li a').live("dblclick", function(){
		removetab($(this));
	});	 
	  
	
 }
function swtichtab(obj){
		$('.ui-tabs-nav li').removeClass('ui-tabs-selected');
		obj.parent().addClass('ui-tabs-selected');
		
		var target =obj.attr('href');//#tabs-2
		$('.ui-tabs-panel').addClass('ui-tabs-hide');
		$(target).removeClass('ui-tabs-hide');
		showiframe($(target+" iframe"));
		return false;	
}
function removetab(obj){
	
	var target =obj.attr('href');	
	$(target).remove();
	obj.remove();
	
	target = target.replace('#tabs-','');
	//alert(target);
	var current_tabs2=[];
	for(i in current_tabs){
		//alert(current_tabs[i][2]+":"+target);
		if(current_tabs[i][2]-0!=target-0)	current_tabs2[current_tabs2.length]=current_tabs[i];		
	}
	current_tabs = current_tabs2;
	
	if(current_tabs.length<20){		
		var objString = JSON.stringify(current_tabs); //JSON 数据转化成字符串
		$.cookie('current_tabs',objString);
	}
	$('#tabs ul li a').each(function(){
		if($(this).attr('href')) swtichtab($(this));
	});
}
function showiframe(obj){	
	//obj.height( obj.contents().height() + 40); 
	obj.height(document.body.clientHeight-(55+$('#tabs').height()));
}

function addNewTab(url,title,type){
	if(url.indexOf('logout')!=-1)return;
	if(type==null){
		//./?go=address&do=detail&address_id=50
		//根据URL判断type
		if(url.indexOf('go=channel')!=-1){
			type=icon_channel;
		}else
		if(url.indexOf('go=doc')!=-1){
			type=icon_doc;
		}else
		if(url.indexOf('go=diary')!=-1){
			type=icon_diary;
		}else
		if(url.indexOf('go=site')!=-1){
			type=icon_site;
		}else
		if(url.indexOf('go=address')!=-1){
			type=icon_address;
		}else{
			type='';
		}
		
	}
	
	//如果存在,则	
	current_sub = check_current(current_tabs,url,title);
	if(current_sub && $('#tabs ul li a#nav-'+current_tabs[i][2]).size() > 0){
			swtichtab($('#tabs ul li a#nav-'+current_tabs[i][2]));
			return;			
	}
	//if(title.length>10)title = title.substring(0,10);
	//var tabsid = $('#tabs ul li').size()+1;
	 tabsid = ++current;
	//alert(title.length);
	var newTab = '<li><a href="#tabs-'+tabsid+'" id="nav-'+tabsid+'" title="'+title+'"><span>'+type+' '+(clen(title)>10?title.substring(0,10)+'...':title)+'</span></a></li>'; 
	var newTabs = '<div id="tabs-'+tabsid+'" class="ui-tabs-panel"><iframe src="'+url+'" width="100%" frameborder="0"></iframe></div>';
	$('#tabs ul').append(newTab);
	$('#contents').append(newTabs);
	if(!current_sub)current_tabs[current_tabs.length]=[title,url,tabsid];
	if(current_tabs.length<20){		
		var objString = JSON.stringify(current_tabs); //JSON 数据转化成字符串
		$.cookie('current_tabs',objString);
	}
	swtichtab($('#tabs ul li:last a'));
	
}
function check_current(current_tabs,url,title){
	//alert(current_tabs);
	

	
	for(i in current_tabs){
		if(current_tabs[i][1]==url && current_tabs[i][0]==title)	return  current_tabs[i];		
	}
	return null;
}

function clen(string){
  var   len   =   0;   
  for(i=0;i<string.length;i++)   
  {   
          if(string.charCodeAt(i)>256)   
          {   
                  len   +=   2;   
          }   
          else   
          {   
                  len++;   
          }   
 }   
 return len;
}

//function addEventHandler(event,ui){
//var li = $(ui.tab).parent();
//$('<img src="close.gif"/>').appendTo(li).hover(function(){
//var img = $(this);
//img.attr('src','close_hover2.png');
//},
//function(){
//var img = $(this);
//img.attr('src','close.png');
//}
//)
//.click(function(){ //关闭按钮,关闭事件绑定
//var li = $(ui.tab).parent();
//var index = $('#tabs li').index(li.get(0));
//$("#tabs").tabs("remove",index);
//tabCounter--;
//}); 

</script>