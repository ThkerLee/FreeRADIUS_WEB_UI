#!/bin/php
<?php include("inc/conn.php"); 
include_once("evn.php");  ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统管理组")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<body>
<?php 
$ID=$_GET["ID"];
if($ID == 1){
    echo"<script>alert('". _("系统账号不能删除")."');window.location.href='manager.php';</script>";
    exit();
}  else {
   $db->delete_new("manager","ID='".$ID."'");
echo"<script>alert('". _("删除成功")."');window.history.go(-1);</script>";
}


//查询项目集合
?>

</body>
</html>