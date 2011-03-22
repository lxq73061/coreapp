// JavaScript Document
/******************************************************************************
*�������ƣ�Ŀ¼�� YEMA Tree 2.1.0 (Ŀ¼�� for ALL)
*�����ܣ���ȫ����Javascript�Ķ�̬����Ŀ¼
*��    �ߣ��ڶ�ˮ
*��ϵ��ʽ��
	QQ:			46163020
	msn:		yuenshui@hotmail.com
	Email:		yuenshui@gmail.com
	֧����վ:	www.yemaol.com
	�������⣬����������ϵ������
*����޸ģ�2006��09��04��
*�޸Ĵ�����4
*�������ڣ�2004��7��14��
*��    ע���˳���Ϊ���ʹ�õĴ��룬�������κ��������κκϷ�����;��
			���ڴ�������ɵ��κ���ʧ�����߲����κ����Ρ�
			��ʹ�ù����в����޸ĺ�ɾ����ע����Ϣ������׷���ַ���Ȩ�ķ������Ρ�
			����ַ���Ȩ�����Է������������޶ȵ����ߡ�
**********************************************   ����Ϊ�汾�޸���Ϣ
2006��09��04��
v2.1.0
�ö���ķ�ʽ��д�����д���
�����˽ڵ㲻���ȶ���bug
�����������IE��Firefox��Opera�������
����SaveNodeStatus��GetNodeStatus������������ʵ��������״̬���淽ʽ

2004��12��28��
v2.0.3
��Ҷ�ӽڵ������Ҳ��Ч�������������Ҫ����ֱ�Ӹ���js�ļ���
���Ӳ���ߵ�˫���¼���˫�����Ὣ�ýڵ����������������������

2004��12��09��
v2.0.1
�޸ļ�¼�Ľڵ���ʱ�����bug


2004��10��15�� 
v2.0 
���Ӹ��Ի�ͼ�깦�ܣ�
������Ӳ˵��Ĳ������裻
����ȫ��չ����ȫ���۵����ܣ�
*******************************************************************************/

function YEMATree(pInstanceName, pPath, pIcon, pContent, pTarget, pUrl) {
	this.instancename = pInstanceName;
	this.Tree = new Array();
	this.Path = pPath;
	this.Target = pTarget;
	this.len = 0;
	
	document.writeln("<div class='YEMATreeHead'><img src='" + this.Path + pIcon + "' align=top border=0>");
	if(pUrl != null && pUrl != "") {
		document.write("<a href='" + pUrl + "' target='" + this.Target + "'>");
	}
	document.writeln(pContent + "</a></div>");
	document.writeln("<div id='YEMATree_" + this.instancename + "' class='YEMATreeCase'></div>");
	
	
	this.addnode = function (pNodeID, pParentID, pCaption, pIconClose, pIconOpen, pUrl, pTarget) {
		this.Tree[this.Tree.length] = new Array(pNodeID, pParentID, pCaption, pIconClose, pIconOpen, pUrl, pTarget);
	} // end funciton addnode
	
	this.write = function () {
		var DivCase = document.getElementById("YEMATree_" + this.instancename);
		DivCase.innerHTML = this.Fetch(0);
		this.NodeStatus = this.GetNodeStatus();
		this.Initialize();
	} // end funciton write
	
	this.Initialize = function () {
		for(var i = 0; i < this.NodeStatus.length; i++) {
			if(this.NodeExists(this.NodeStatus[i])) {
				this.ChangeStatus(this.NodeStatus[i]);
			}//end if
		}//end for
	} // end funciton Initialize
	
	this.closeall = function() {
		for(var i = 0; i < this.len; i++) {
			var Item = document.getElementById("YEMATree_" + this.instancename + "_Item_" + this.Tree[i][0]);
			if(Item.className == "YEMATreeNodeOpen" || Item.className == "YEMATreeNodeEndOpen") {
				this.Node_OnClick(this.Tree[i][0]);
			}
		}
	} // end funciton closeall
	
	this.openall = function() {
		for(var i = 0; i < this.len; i++) {
			var Item = document.getElementById("YEMATree_" + this.instancename + "_Item_" + this.Tree[i][0]);
			if(Item.className == "YEMATreeNode" || Item.className == "YEMATreeNodeEnd") {
				this.Node_OnClick(this.Tree[i][0]);
			}
		}
	} // end funciton openall

	this.Fetch = function (pNodeID) {
		var childrenArray = new Array();
		var NodeHTML = "<table border='0' cellspacing='0' cellpadding='0'>\n";
		var NodeType = "";
		var LineType = "";
		var NodeContent = "";
		var NodeIcon = "";
		var NodeElent = "";
		var CaptionOver = "";
		var CaptionOut = "";
		this.len = this.Tree.length;
		
		for(var i = 0; i < this.len; i++) {
			if(this.Tree[i][1] == pNodeID) childrenArray[childrenArray.length] = this.Tree[i];
		}
		
		for(var i = 0; i < childrenArray.length; i++) {
			var ChildrenExists = this.NodeExists(childrenArray[i][0]);
			if(ChildrenExists) {
				if(i == childrenArray.length - 1) {
					NodeType = "YEMATreeNodeEnd";
					LineType = "YEMATreeLineEnd";
				}
				else {
					NodeType = "YEMATreeNode";
					LineType = "YEMATreeLine";
				}
				
				if(childrenArray[i][3] == null || childrenArray[i][3] == "")
					childrenArray[i][3] = "close.gif";
				if(childrenArray[i][4] == null || childrenArray[i][4] == "")
					childrenArray[i][4] = "open.gif";
					
			}
			else {
				if(i == childrenArray.length - 1) {
					NodeType = "YEMATreeLeaf";
				}
				else {
					NodeType = "YEMATreeLeafEnd";
				}
				
				if(childrenArray[i][3] == null || childrenArray[i][3] == "")
					childrenArray[i][3] = "e.gif";
			}
			NodeElent = this.instancename + ".Node_OnClick(\"" + childrenArray[i][0] + "\")";
			CaptionOver = this.instancename + ".Caption_OnOver(this)";
			CaptionOut = this.instancename + ".Caption_OnOut(this)";
			
			if(childrenArray[i][5] != null && childrenArray[i][5] != "") {
				NodeContent = "<a href='" + childrenArray[i][5] + "' onclick='" + this.instancename + ".Anthor_OnClick()' class=YEMATree_A target='" + childrenArray[i][6] + "'>" + childrenArray[i][2] + "</a>";
			}
			else {
				NodeContent = "<a href=# class=YEMATree_A>" + childrenArray[i][2] + "</a>";
			}
			
			NodeIcon = "<img src='" + this.Path + childrenArray[i][3] + "' width=16 height=17 id='YEMATree_" + this.instancename + "_Icon_" + childrenArray[i][0] + "' width=17 align=absmiddle border=0>";
			NodeHTML += "<tr><td id='YEMATree_" + this.instancename + "_Item_" + childrenArray[i][0] + "' valign='middle' class='" + NodeType + "' onclick='" + (ChildrenExists ? NodeElent : '') + "'>" + NodeIcon;
			NodeHTML += "<span onMouseOver='" + CaptionOver + "' onMouseOut='" + CaptionOut + "' valign='bottom' class='YEMATreeNodeCaption'>" + NodeContent + "</span></td></tr>\n";
			NodeHTML += "<tr id='YEMATree_" + this.instancename + "_Case_" + childrenArray[i][0] + "' ondblclick='" + NodeElent + "'";
			NodeHTML += " style='display:none'><td class=" + LineType + ">" + (ChildrenExists ? this.Fetch(childrenArray[i][0]) : '') + "</td></tr>\n";
		} // end for
		NodeHTML += "</table>\n";
		return NodeHTML;
	} // end funciton Fetch
	
	this.NodeExists = function (pNodeID) {
		for(var i = 0; i < this.len; i++) {
			if(this.Tree[i][1] == pNodeID) return true;
		}
		return false;
	} // end funciton NodeExists
	
	this.Anthor_OnClick = function () {
		if(window.event) window.event.cancelBubble = true;
		return false;
	}
	
	this.Node_OnClick = function (pNodeID) {
		this.ChangeStatus(pNodeID);
		this.SaveStatus(pNodeID);
	} // end funciton Node_OnClick
	
	this.Caption_OnOver = function (obj) {
		obj.className = "YEMATreeNodeCaptionOver";
	} // end funciton Caption_OnOver
	
	this.Caption_OnOut = function (obj) {
		obj.className = "YEMATreeNodeCaption";
	} // end funciton Caption_OnOut
	
	this.ChangeStatus = function (pNodeID) {
		var Node = document.getElementById("YEMATree_" + this.instancename + "_Case_" + pNodeID);
		var Item = document.getElementById("YEMATree_" + this.instancename + "_Item_" + pNodeID);
		var Icon = document.getElementById("YEMATree_" + this.instancename + "_Icon_" + pNodeID);
		var i;
		
		for(i = 0; i < this.len; i++) {
			if(this.Tree[i][0]==pNodeID) {
				break;
			}//end if
		}//end for
		
		if(Node.style.display == "") {
			Node.style.display = "none";
			Icon.src = this.Path + this.Tree[i][3];
		}
		else {
			Node.style.display = "";
			Icon.src = this.Path + this.Tree[i][4];
		}
		
		switch (Item.className) {
			case "YEMATreeNode":
				Item.className	= "YEMATreeNodeOpen";
				break;
			case "YEMATreeNodeOpen":
				Item.className	= "YEMATreeNode";
				break;
			case "YEMATreeNodeEnd":
				Item.className	= "YEMATreeNodeEndOpen";
				break;
			case "YEMATreeNodeEndOpen":
				Item.className	= "YEMATreeNodeEnd";
				break;
		}//end switch
	} // end funciton ChangeStatus
	
	this.SaveStatus = function (pNodeID) {
		if(!this.checkCookieExist("YEMATree_" + this.instancename) || this.NodeStatus.length == 0) {
			this.SaveAddNode(pNodeID);
			return ;
		}
		
		for(var i = 0; i < this.NodeStatus.length; i++) {
			if(this.NodeStatus[i] == pNodeID) {
				this.SaveDelNode(i);
				return ;
			}
		}
		this.SaveAddNode(pNodeID);
		return ;
	} // end funciton SaveStatus
	
	this.SaveAddNode = function (pNodeID) {
		this.NodeStatus[this.NodeStatus.length] = pNodeID;
		this.SaveNodeStatus();
	} // end funciton SaveAddNode
	
	this.SaveDelNode = function (ID) {
		var temp = new Array();
		for(var i = 0; i < this.NodeStatus.length; i++) {
			if(i != ID) {
				temp[temp.length] = this.NodeStatus[i];
			}
		}
		this.NodeStatus = temp;
		this.SaveNodeStatus();
	} // end funciton SaveDelNode
	
	this.GetNodeStatus = function() {
		var StatusString = this.checkCookieExist("YEMATree_" + this.instancename) ? this.getCookie("YEMATree_" + this.instancename) : '';
		if(StatusString.length == 0) {
			return new Array();
		}
		return StatusString.split(",");
	} // end funciton GetNodeStatus
	
	this.SaveNodeStatus = function () {
		var StatusString = this.NodeStatus.join(",");
		this.saveCookie("YEMATree_" + this.instancename, StatusString, 1000);
	} // end funciton SaveNodeStatus
	
	this.saveCookie = function (pName, pValue, pExpires, pPath, pDomain, pSecure) {
		var strCookie = pName + "=" + pValue;
		if (pExpires) {
			var curTime = new Date();
			curTime.setTime(curTime.getTime() + pExpires*24*60*60*1000);
			strCookie += "; expires=" + curTime.toGMTString();
		}
		
		strCookie += (pPath) ? "; path=" + pPath : ""; 
		strCookie += (pDomain) ? "; domain=" + pDomain : "";
		strCookie += (pSecure) ? "; secure" : "";
		
		document.cookie = strCookie;
	} // end funciton saveCookie
	
	this.getCookie = function (pName) {
		var strCookies = document.cookie;
		var cookieName = pName + "=";  // Cookie����
		var valueBegin, valueEnd;
		
		valueBegin = strCookies.indexOf(cookieName);
		if (valueBegin == -1) return null;
		valueEnd = strCookies.indexOf(";", valueBegin);
		if (valueEnd == -1) valueEnd = strCookies.length;
		
		return strCookies.substring(valueBegin + cookieName.length, valueEnd);
	} // end function getCookie
	
	this.checkCookieExist = function (pName) {
		return (this.getCookie(pName)) ? true : false;
	} // end function checkCookieExist
} // end class YEMATree
