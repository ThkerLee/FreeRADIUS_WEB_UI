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
<title><? echo _("用户重建")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
$UserName=$_GET["UserName"];
//当前正在使用订单
$rs=$db->select_one("o.productID,u.ID,ar.orderID","userinfo as u,userattribute as ar,orderinfo as o","u.ID=ar.userID and ar.orderID=o.ID and u.UserName='".$UserName."'");
$userID=$rs["ID"];
$productID=$rs["productID"];
$orderID=$rs["orderID"];
//添加用户技术参数信息  正常产品添加
addUserParaminfo($userID,$productID);
//获取当前订单的到期时间  
$runRS=$db->select_one("enddatetime,begindatetime","userrun","orderID='".$orderID."' and userID='".$userID."'");
if($runRS){
 $beginTime=strtotime($runRS['begindatetime']);
 $endtime=strtotime($runRS['enddatetime']);
 $time=time();
 $userSQL=array("MAC"=>'',"NAS_IP"=>'',"VLAN"=>'');
 if($endtime>$time && $beginTime<$time ){//属于正在运行时间范围内的正常订单的 给予重建
	//判断订单时间与状态  并做修改  判断当用户订单正常不能拨号的情况下
	//订单表状态修改  
	$orderSQL=array("status"=>'1');
	$db->update_new("orderinfo","ID='".$orderID."'",$orderSQL);
	//拨号验证表
	$attrSQL=array("status"=>'1','stop'=>'0');
	$db->update_new("userattribute","orderID='".$orderID."' and userID='".$userID."'",$attrSQL);
	//运行表证表
	$runSQL=array("status"=>'1');
	$db->update_new("userrun","orderID='".$orderID."' and userID='".$userID."'",$runSQL);
	//删除用户MAC NAS VLAN 地址   
	$db->update_new("userinfo","ID='".$userID."'",$userSQL);
 }elseif($endtime<$time && $runRS['enddatetime']!="0000-00-00 00:00:00"){//时间到期  订单结束 
    $orderSQL=array("status"=>'4');
	$db->update_new("orderinfo","ID='".$orderID."'",$orderSQL);
	//拨号验证表
	$attrSQL=array("status"=>'4','stop'=>'1');
	$db->update_new("userattribute","orderID='".$orderID."' and userID='".$userID."'",$attrSQL);
	//运行表证表
	$runSQL=array("status"=>'4');
	$db->update_new("userrun","orderID='".$orderID."' and userID='".$userID."'",$runSQL);
   //删除用户MAC NAS VLAN 地址   
	$db->update_new("userinfo","ID='".$userID."'",$userSQL); 
 } 
	
} 
echo "<script>alert('"._("重建成功")."');window.location.href='user.php';</script>"; 
?>
</body>
</html>

