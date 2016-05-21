#!/bin/php
<?php 
include_once("../evn.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("上传文件");?></title>
</head>
<style type="text/css">
<!--
body{margin:0;margin-left:auto;margin-right:auto;background:#efefef;}
-->
</style>
<body>
<?php
$uptypes=array('image/jpg','image/jpeg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png',"jpg","gif","png","bmp");//上传文件类型
$max_file_size=2000000; //上传文件大小限制, 单位BYTE
$systemPath="../images/";//系统设置上传文件的路径
 $upFileFoler=$_GET["upFileFoler"];//这是得到用户设置的上传的文件夹
 $upFileID=$_GET["upFileID"];//返回文件名的ID号
 $viewID=$_GET["viewID"];
 $oldFileName=$_POST["oldFileName"];//返回现在文件名
 $destination_folder=$upFileFoler."/";//."/".date("Y-m",time())."/"; //和用户相组合成上传文件的文件夹以日期识别

//$authnum=rand()%100000;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{ 
   if (!is_uploaded_file($_FILES["file"]["tmp_name"])){//是否存在文件
       echo "<script language=javascript>alert('"._("请先选择你要上传的文件！")."');history.go(-1);</script>";
       exit();
   }
   $file = $_FILES["file"];
   if($max_file_size < $file["size"]){//检查文件大小
       echo "<script language=javascript>alert('"._("文件大小不能超过2M！")."');history.go(-1);</script>";
       exit();
   }
   $pinfo=pathinfo($file["name"]);//返回一个上传文件的信息
   $ftype=$pinfo['extension'];	
   if(!in_array($ftype, $uptypes)){//检查文件类型
       echo _("文件类型不符!").$file["type"];
       exit();
   }
   if(!file_exists($systemPath.$upFileFoler)){ //判断用户在此文件夹的是否存在的
       mkdir($systemPath.$upFileFoler);
   }   
   if(!file_exists($systemPath.$destination_folder)){ //判断系统设置在此文件夹的是否存在的
       mkdir($systemPath.$destination_folder);
   }
   
   // $destination = $systemPath.$destination_folder.time().".".$ftype;//服务器上传文件路径
   //2014.07.28修改将上传图片名字为固定
  if($upFileID == "picTopLeftID"){
   $destination = $systemPath.$destination_folder."picTopLeft".".".$ftype;//服务器上传文件路径   
  }elseif ($upFileID == "picTopRight") {
    $destination = $systemPath.$destination_folder."picTopRight".".".$ftype;//服务器上传文件路径    
    }elseif ($upFileID == "picBottomLeft") {
     $destination = $systemPath.$destination_folder."picBottomLeft".".".$ftype;//服务器上传文件路径    
    }elseif ($upFileID == "picBottomRight") {
     $destination = $systemPath.$destination_folder."picBottomRight".".".$ftype;//服务器上传文件路径  
    }elseif ($upFileID == "picLogin") {
      $destination = $systemPath.$destination_folder."picLogin".".".$ftype;//服务器上传文件路径   
    }
   
   
   
   
   
   
   if (file_exists($destination) && $overwrite != true){ 
       echo "<script language=javascript>alert(\""._("同名文件已经存在了！")."\");history.go(-1);</script>";
       exit();
   }
   if(!move_uploaded_file ($file["tmp_name"], $destination)){ 
       echo "<script language=javascript>alert('"._("移动文件出错！")."');history.go(-1);</script>";
       exit();
   }
   $pinfo=pathinfo($destination);//返回一个服务器关联数组包含有 path 的信息
   $fname=$pinfo[basename];//得到上传到服务器的地址
   $picture_name = $destination_folder.$fname;//这是返回到文本框的路径
   
   if(!empty($oldFileName)){
   		@unlink($systemPath.$oldFileName);
   }
   echo "<font size=2>文件名：[ '$fname' ]"._("上传成功")." <a href='javascript:window.history.go(-1);'>"._("重新上传")."</a></font>";
   
   // " 宽度:".$image_size[0];
   // " 长度:".$image_size[1];
   // "<br> 大小:".$file["size"]." bytes";

}
?>
<script language="javascript">
<!--
window.parent.document.getElementById("<?=$viewID?>").innerHTML="<img src='images/<?=$picture_name?>' width=90 height=60 >"
window.parent.document.getElementById("<?=$upFileID?>").value="<?=$picture_name?>";
-->
</script>

</body>
</html>