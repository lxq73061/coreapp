<?php
require("GTranslate.php");
$translate_string = "<strong>Das</strong> ist <font color=\"red\">wunderschön</font>";
 try{
       $gt = new Gtranslate;
	$gt->setRequestType('curl');
	echo "Translating [$translate_string] German to English => ".$gt->german_to_english($translate_string)."<br/>";
	
echo "Translating [$translate_string] German to English => ".$gt->german_to_chinese($translate_string)."<br/>";
} catch (GTranslateException $ge)
 {
       echo $ge->getMessage();
 }

?>
