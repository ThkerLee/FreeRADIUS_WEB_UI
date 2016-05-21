#!/bin/php
<?php include("inc/conn.php");
include_once("evn.php"); 
date_default_timezone_set('Asia/Shanghai');
 ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("报修登记")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<?php 
$ID  =$_REQUEST["ID"];
$db->query("delete from repairdisposal where ID='".$ID."'");
echo "<script language='javascript'>alert('"._("删除成功")."');window.history.go(-1);</script>";
?>
</head>
<body>

</body>
</html>

