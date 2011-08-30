//显示表情菜单
function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}
var menuTimer =null;
var basepath ='/templates/images/face/';
//层定位的代码
function showmenu(obj1,obj2,state,location){ 
    var btn=document.getElementById(obj1);
    var obj=document.getElementById(obj2);
    var h=btn.offsetHeight;
    var w=btn.offsetWidth;
    var x=btn.offsetLeft;
    var y=btn.offsetTop;
    while(btn=btn.offsetParent){y+=btn.offsetTop;x+=btn.offsetLeft;}
    
    var hh=obj.offsetHeight;
    var ww=obj.offsetWidth;
    var xx=obj.offsetLeft;//style.left;
    var yy=obj.offsetTop;//style.top;
    var obj2state=state.toLowerCase();
    var obj2location=location.toLowerCase();
    
    var showx,showy;

    if(obj2location=="left" || obj2location=="l" || obj2location=="top" || obj2location=="t" || obj2location=="u" || obj2location=="b" || obj2location=="r" || obj2location=="up" || obj2location=="right" || obj2location=="bottom"){
        if(obj2location=="left" || obj2location=="l"){showx=x-ww;showy=y;}
        if(obj2location=="top" || obj2location=="t" || obj2location=="u"){showx=x;showy=y-hh;}
        if(obj2location=="right" || obj2location=="r"){showx=x+w;showy=y;}
        if(obj2location=="bottom" || obj2location=="b"){showx=x;showy=y+h;}
    }else{ 
        showx=xx;showy=yy;
    }
    obj.style.left=showx+"px";
    obj.style.top=showy+"px";
    if(state =="hide"){
        menuTimer =setTimeout("hiddenmenu('"+ obj2 +"')", 500);
    }else{
        clearTimeout(menuTimer);
        obj.style.visibility ="visible";
    }
}
function hiddenmenu(psObjId){
    document.getElementById(psObjId).style.visibility ="hidden";
} 
function insertContent(target, texts) {
	var obj = document.getElementById(target);
	checkFocus(target);
	if(!isUndefined(obj.selectionStart)) {
		var opn = obj.selectionStart + 0;
		obj.value = obj.value.substr(0, obj.selectionStart) + texts + obj.value.substr(obj.selectionEnd);
	} else if(document.selection && document.selection.createRange) {
		var sel = document.selection.createRange();
		sel.text = texts;
		//sel.moveStart('character', -strlen(texts));
	} else {
	obj.value += texts;
	}
}
function checkFocus(target) {
	var obj = target;
	document.getElementById(obj).focus();
}
function showFace(showid, target) {
	var div = document.getElementById('face_bg');
	if(div) {
		div.parentNode.removeChild(div);
	}
	div = document.createElement('div');
	div.id = 'face_bg';
	div.style.position = 'absolute';
	div.style.left = div.style.top = '0px';
	div.style.width = '100%';
	div.style.height = document.body.scrollHeight + 'px';
	div.style.zIndex = 10000;
	div.style.display = 'none';
	div.style.opacity = 0;
	div.onclick = function() {
		document.getElementById(showid+'_menu').style.display = 'none';
		document.getElementById('face_bg').style.display = 'none';
	}
	document.getElementById('append_parent').appendChild(div);
	if(document.getElementById(showid + '_menu') != null) {
		document.getElementById(showid+'_menu').style.display = '';
	} else {
		var faceDiv = document.createElement("div");
		faceDiv.id = showid+'_menu';
		faceDiv.className = 'facebox';
		faceDiv.style.position = 'absolute';
		var faceul = document.createElement("ul");
		for(i=1; i<31; i++) {
			var faceli = document.createElement("li");
			faceli.innerHTML = '<img src="'+basepath+i+'.gif" onclick="insertFace(\''+showid+'\','+i+', \''+ target +'\')" style="cursor:pointer; position:relative;" />';
			faceul.appendChild(faceli);
		}
		faceDiv.appendChild(faceul);
		document.getElementById('append_parent').appendChild(faceDiv)
	}
	//定位菜单
	showmenu('face',showid+'_menu','show','bottom')
	div.style.display = '';
}
//插入表情
function insertFace(showid, id, target) {
	var faceText = '[em:'+id+':]';
	if(target != null) {
		insertContent(target, faceText);
	}
	document.getElementById(showid+'_menu').style.display = 'none';
	document.getElementById('face_bg').style.display = 'none';
}
function check_comment(f){

	if(f.content.value == $('#content').attr('placeholder')) f.content.value='';
	if(f.content.value=='')return false;
}

