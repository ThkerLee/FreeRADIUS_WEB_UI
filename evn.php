<?php
if ( file_exists("/etc/LANG" ) ) {
  $lang = trim(file_get_contents("/etc/LANG"));
} else {
  $lang = "zh_CN";
}
//$lang = "zh_CN";
//$lang = "en_US";
putenv("LANG={$lang}");
setlocale(LC_ALL,'$lang');
bindtextdomain("greetings", "./locale/");  
textdomain("greetings"); 
@include_once("inc/ajax_js.php"); 
?>
