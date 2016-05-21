#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("限速规则")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jquery.js" type="text/javascript"></script>
</head>
<?php 
if($_GET["action"]=="del"){
	$db->delete_new("speedrule","ID='".$_GET["ID"]."'");
	echo "<script>alert('". _("删除成功") . " ');window.location.href='speedrule.php';</script>";
}
?>
<body>

</body>
</html>

