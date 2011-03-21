<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的网站</title>

<style type="text/css">
body,td,th {
	font-size: 12px;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
#top .title {
color:#FF9900;
font-family:"方正大标宋简体";
font-size:24px;
}#top a {
color:#FFFFFF;
font-size:14px;
}.index_login, .index_login a {
font-family:"宋体";
font-size:12px;
text-align:right;
}
</style>
</head>
<body>
<div id="top">
    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tbody>
            <tr>
                <td background="templates/images/bg_top.jpg"><table cellspacing="0" cellpadding="0" width="100%" border="0">

                        <tbody>
                            <tr>
                                <td width="30" height="64">&nbsp;</td>
                                <td width="68"><img height="50" src="templates/images/logo.gif" 
            width="53" /></td>
                                <td width="935"><table cellspacing="0" cellpadding="0" width="100%" border="0">
                                        <tbody>
                                            <tr>
                                                <td width="100%"><table cellspacing="0" cellpadding="0" width="100%" border="0">
                                                        <tbody>

                                                            <tr>
                                                                <td width="10%" height="40" class="title">MyOffice</td>
                                                                <td width="90%" 
                        align="left" ><table width="100%">
                                                                        <tr>
                                                                            <td>
   ○<a target="frmView"  href="?go=user&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">用户</a>&nbsp;
○<a target="frmView"  href="?go=doc&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">文章</a>&nbsp;
○<a target="frmView"  href="?go=channel&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">分类</a>&nbsp; 
○<a target="frmView"  href="?go=diary&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">日记</a>&nbsp; 
○<a target="frmView"  href="?go=site&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">网址</a>&nbsp;
○<a target="frmView"  href="?go=address&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">通讯录</a>&nbsp;
○<a target="frmView"  href="?go=book&<?php if(isset($_GET['query'])): ?><?php echo $_GET['query']; ?><?php else: ?>do=browse<?php endif; ?>">帐本</a>&nbsp;
<br></td>
                                                                            <td>
<form action="/?go=doc&do=browse" method="get" target="frmView" name="search_from" id="search_from">
<input type="hidden" name="go" value="doc">
<input type="hidden" name="do" value="browse">
&nbsp; &nbsp;
<input name="keyword" type="text" id="keyword" value="" size="18">
显示：
<input name="limit" type="text" value="20" size="2">

条
<select name="order">
<option value="doc_id" <?php if($get['order'] === 'doc_id') echo 'selected'; ?>>创建日期↑</option>
<option value="date" <?php if($get['order'] === 'date') echo 'selected'; ?>>修改日期↑</option>
<option value="hit" <?php if($get['order'] === 'hit') echo 'selected'; ?>>访问次数↑</option>
    
</select>
<input name="" type="submit" value=搜索>
</form>
                                                                                </td>

                                                                            <td></td>
                                                                            <td><table border="0" align="center" cellpadding="0" cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr><!--
                                                                                            <td class="textButton" onclick="javascript:history.go(-1);" align="middle" width="55" height="22"><a href="#"><img src="templates/images/undo.gif" alt="{lang back_off}" width="32" height="32" border="0" /></a></td>
                                                                                            <td class="textButton" onclick="javascript:history.go(1);" align="middle" width="55" height="22"><a href="#"><img src="templates/images/redo.gif" alt="{lang go_ahead}" width="32" height="32" border="0" /></a></td>-->
                                                                                            <td class="textButton"  onclick="top.frmView.location.reload();top.leftFrame.location.reload();" align="middle" width="55" height="22"><a href="#"><img src="templates/images/reload.gif" alt="{lang refresh}" width="32" height="32" border="0" /></a></td>
                                                                                            <td align="middle" width="55" height="22"><a href="?go=user&do=logout" target="_top"><img src="templates/templates/images/exit.gif" alt="{lang exit}" width="32" height="32" border="0" /></a></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table></td>

                                                                        </tr>
                                                                        <tr>
                                                                          <td>&nbsp;</td>
<td><div class="index_login">
欢迎登录：<?php echo $online->username?></div>&nbsp;</td>
                                                                          <td>&nbsp;</td>
                                                                          <td>&nbsp;</td>
                                                                        </tr>

                                                                    </table></td>
                                                            </tr>
                                                        </tbody>
                                                    </table></td>
                                            </tr>
                                        </tbody>
                                    </table></td>
                            </tr>
                        </tbody>

                    </table></td>
            </tr>
            <tr>
                <td height="2"></td>
            </tr>
        </tbody>
    </table>
</div>
<div style="clear:both"></div>
