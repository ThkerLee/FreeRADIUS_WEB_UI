#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("删除产品")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
$ID=$_GET["ID"];
//在删除产品时间你判断些项目中是否存在有在用户
$uNum=$db->select_count("orderinfo","productID='".$ID."'");
if($uNum>0){
	echo "<script>alert('". _("此项产品中存在用户不能被删除"). " ');window.history.go(-1);</script>";
	exit;
}else{
	$db->delete_new("product","ID='".$ID."'");//删除产品
	$db->delete_new("productandproject","productID='".$ID."'");//删除产品与项目关系信息
}
echo "<script>alert('". _("删除成功"). " ');window.location.href='product.php';</script>";
?>
</body>
</html>