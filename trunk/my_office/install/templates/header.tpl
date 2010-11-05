<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>" />
<title><?php echo $lang[myoffice_install_guide]?></title>
<link href="install/templates/css/install.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" charset="utf-8"></script>
</head>

<body>

<div id="box">
    <div class="box_top"></div>
    <div class="main">

        <div class="top">
            <h1 title="<?php echo $lang[myoffice_install_guide]?>"><a href="http://myoffice.shopex.cn" target="_blank"></a></h1>
             <p><?php echo $lang[welcome_install_myoffice]?></p>
        </div>

        <h2>
            <span class="num">0<?php echo $step_num?>.</span>
            <span class="title"><?php echo $step_name?></span>
            <span class="title_intro"><?php echo $step_desc?></span>
        </h2>
        <div class="step<?php echo $step_num?>"></div>
        <form action="install.php?do=<?php echo $doing?>" method="POST" id="post_form">