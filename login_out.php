#!/bin/php
<?php 
@ session_start();
include("inc/scan_conn.php");
date_default_timezone_set('Asia/Shanghai');
 ?> 
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统登录")?></title>
</head>
<body>
<?php 
$nowdatetime=date("Y-m-d H:i:s",time());
$db->query("update loginlog set logoutdatetime='".$nowdatetime."' where name='".$_SESSION["manager"]."' and logoutdatetime='0000-00-00 00:00:00' "); 
unset($_SESSION["manager"]);
unset($_SESSION["managerID"]);
unset($_SESSION["permision"]);
echo "<script language='javascript'>alert('" . _("成功登出") ." ');window.parent.location.href='login.php';</script>";
exit;
?>

</body>
</html>

