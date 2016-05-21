#!/bin/php
<?php 
date_default_timezone_set('Asia/Shanghai');
@include("inc/conn.php");
@include_once("fbegin.php"); 
@include_once("evn.php"); 
$ID  =$_REQUEST["ID"];
$db->query("delete from repair where ID='".$ID."'");
$db->query("delete from repairdisposal where repairID='".$ID."'");
echo "<script language='javascript'>alert('"._("删除成功")."');window.location.href='repair.php';</script>";
?>

