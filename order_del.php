#!/bin/php
<?php include("inc/conn.php"); 
include("inc/loaduser.php");
require_once("evn.php");
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("订单撤消")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jsdate.js" type="text/javascript"></script>
</head>
<body>
<?php 
$ID      =$_REQUEST["ID"];
$oRs     =$db->select_one("userID","orderinfo","ID='".$ID."'");
$userID  =$oRs["userID"];
$orderID =$ID;
$name    =getUserName($userID);
//作废订单
cancelOrder($userID,$orderID,11,_("订单撤消"),$name) ;//11 订单撤销 
echo "<script language='javascript'>alert('"._("订单撤消成功")."');window.location.href='order.php';</script>"; 	

?>
</body>
</html>

