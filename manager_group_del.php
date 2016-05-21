#!/bin/php
<?php include("inc/conn.php");  
include_once("evn.php"); ?>
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
$result=$db->select_all("*","manager","manager_groupID='".$ID."'");
if(!empty($result)){
	echo "<script language='javascript'>alert('"._("此组下面存在会员")."');window.history.go(-1);</script>";
	exit;
}else{
	$db->delete_new("managergroup","ID='".$ID."'");
	echo "<script language='javascript'>alert('"._("删除成功")."');window.location.href='manager_group.php';</script>";
}
?>

</body>
</html>

