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

   $db->delete_new("accept","ID='".$ID."'");
echo"<script>alert('". _("删除成功")."');window.history.go(-1);</script>";



//查询项目集合
?>

</body>
</html>