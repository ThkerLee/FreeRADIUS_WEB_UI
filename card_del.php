#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">

</head>
<body>
<?php 
$ID=$_REQUEST["ID"];
if(is_array($ID)){
	foreach($ID as $vaID){
		$db->delete_new("card","ID='".$vaID."'");
	}
 }

echo "<script language='javascript'>alert('"._("删除成功")."');window.location.href='card_search.php';</script>";
?>
</body>
</html>

