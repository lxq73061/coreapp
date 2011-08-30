<?php if(!$get['noheader']):?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo get_title($_GET); ?></title>
<link rel="stylesheet" type="text/css" href="templates/css/css.css">
<script src="templates/js/js.js"></script>
<script src="templates/js/datepicker/jquery-1.4.4.js"></script>
<?php 
//引入时间
if(defined('GET_DATE')){
?>
<link rel="stylesheet" href="templates/js/datepicker/themes/base/jquery.ui.all.css">

<script src="templates/js/datepicker/ui/jquery.ui.core.js"></script>
<script src="templates/js/datepicker/ui/jquery.ui.widget.js"></script>
<script src="templates/js/datepicker/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="templates/js/datepicker/demos.css">
<script>
$(function($) {
	
$(".datepicker_input").datepicker({
			prevText:"",
			nextText:"",
			dateFormat:"yy-mm-dd"});
});
</script>
<?php 
	}
?>

</head>
<body>
<?php endif?>

