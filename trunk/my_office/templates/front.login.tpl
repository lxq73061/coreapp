<html>
<head>
<title>登录</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<style>

html,body{height:100%;font-family:"宋体", arial;background:url(./templates/images/install_backimg.jpg) top right no-repeat;min-height:600px;min-width:1000px;overflow:hidden;}
p, div, table, td, select, input, textarea {
    color: #3C3C3C;
    font-size: 12px;
}
/*ie6 only*/

.leftform{float:left;width:42%;height:100%;background:url(./templates/images/install_logo.jpg) 45% 38% no-repeat;}
.rightform{float:left;width:58%;_width:57%;background:url(./templates/images/install_lines.jpg) left 46% no-repeat;height:100%;}
.alterform li{line-height:20px;list-style:none;}
.dataForm li{line-height:20px;list-style:none;padding-left:20px;}

.installtitle,.dataTitle,.dataTitle2{color:#2E6CD2;font-size:14px;font-weight:bold;}


.conLeftForm{padding-top:75%;width:200px;padding-left:24.5%;}

.conRightForm{padding-top:30%;padding-left:58px;_padding-top:21%;}
.dataForm span{display:-moz-inline-box !important;display:inline-block;width:78px;}
.inputstyle,.inputstyle_2{padding-bottom:5px;}
.inputstyle_2{vertical-align:top;}
.step_1{padding-top:10px;}
.zhushi{margin-left:112px;width:210px;margin-bottom:12px;color:#797979;}

.bottomclass{position:absolute;width:100%;left:0;bottom:0;height:35px;background:url(./templates/images/bottomBg.gif) repeat-x;min-width:1000px;line-height:35px;}
.bottomclass p{color:#fff;padding-left:20px;line-height:35px;}
.bottomclass p a{color:#fff;}
.bottomclass p a font{color:Yellow;}

.inp_L1,.inp_L2{background:url(./templates/images/bg_x.gif) no-repeat}
.inp_L1{ width:67px; height:23px; background-position:-4px -4px; border:0; color:#464646; line-height:23px}
.inp_L2{ width:67px; height:23px; background-position:-4px -30px; border:0; color:#464646; line-height:23px}
 

fieldset{border:none;
	/*margin:15% 30%;
	border: #39C 3px solid*/
}
fieldset legend{
	color:#2E6CD2;
	background:url(./templates/images/step_1.gif) no-repeat;
	font-size:14px;
	font-weight:800;
	padding-left:20px;
}
fieldset img{vertical-align:middle; border:1px solid #Fc0}
label{
	line-height:25px
}
</style>
</head>
<body>


	        <div class="leftform">

		        <div class="conLeftForm">
        
		        </div>
	        </div>
            <div class="rightform">
                <div class="conRightForm">
                    <div class="dataForm">
<form method="post" action="index.php?go=front&do=login">
<input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
<fieldset>
<legend>登录</legend>
<label>用户名：<input type="text" name="username" value="<?php echo isset($_POST['username'])?$_POST['username']:''; ?>" /></label>
	<?php if(isset($error['username'])): ?><font color="red"><?php echo $error['username']; ?></font><?php endif; ?><br />
<label>密　码：<input type="password" name="password" /></label>
	<?php if(isset($error['password'])): ?><font color="red"><?php echo $error['password']; ?></font><?php endif; ?><br />
<label>验证码：<input name="authcode" type="text" size="13" /></label><img src="index.php?go=front&do=authcode" />
	<?php if(isset($error['authcode'])): ?><font color="red"><?php echo $error['authcode']; ?></font><?php endif; ?><br />
<input type="submit" value="登录" class="inp_L1"/>
</fieldset>
</form>                        
                    </div>
                </div>

            </div>
            <div class="bottomclass">
                
            </div>



</body>
</html>