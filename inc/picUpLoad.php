#!/bin/php
<?php
//include_once("conn.php");/
if ( file_exists("/etc/LANG" ) ) {
  $lang = trim(file_get_contents("/etc/LANG"));
} else {
  $lang = "zh_CN";
}
//$lang = "zh_CN";
//$lang = "en_US";
putenv("LANG={$lang}");
setlocale(LC_ALL,'$lang');
bindtextdomain("greetings", "../locale/");  
textdomain("greetings"); 
@include_once("inc/ajax_js.php");


  $Prve=strtolower($_SERVER['HTTP_REFERER']);
  $Onpage=strtolower($_SERVER["HTTP_HOST"]);
  $Onnym=strpos($Prve,$Onpage);
   if(!$Onnym) exit("<script language='javascript'>alert(\""._("非法操作")."！\");history.go(-1);</script>");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("上传文件");?></title>
<style type="text/css">
body {font:12px Tahoma;font-family:"宋体";margin:0px;}
body,form,ul,li,p,dl,dd,dt,h,td,th,h3{padding:0;font-size:12px;color:#444;}
.table  {width:250px;}
</style>
<script type="text/javascript">
function checkform(){
  var strs=document.upform.file.value;
  if(strs==""){
      alert("<? echo _("请选择要上传的图片");?>!");
	  return false;     
  }  
  var n1=strs.lastIndexOf('.')+1;
  var fileExt=strs.substring(n1,n1+3).toLowerCase()
  if(fileExt!="jpg"&&fileExt!="gif"&&fileExt!="jpeg"&&fileExt!="bmp"&&fileExt!="png"){
	  alert("<? echo _("目前系统仅支持jpg、gif、bmp、png后缀图片上传");?>!");
	  return false;
  }
  document.upform.oldFileName.value=window.parent.document.getElementById('<?=$_GET["upFileID"]?>').value;
}
</script>
</head>
<body>
<form action="picUpFile.php?upFileFoler=<?=$_GET["upFileFoler"]?>&upFileID=<?=$_GET["upFileID"]?>&viewID=<?=$_GET["viewID"]?>" method="post" enctype="multipart/form-data" name="upform" onSubmit="return checkform();">
<input type="hidden" name="oldFileName" id="oldFileName">
<input name="file" id="file" type="file" class="input" />
<input name="Submit" type="submit" class="button" value="<? echo _("上 传");?>" />
</form>
</body> 
</html>
