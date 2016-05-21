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
<title><? echo _("用户下线")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
$UserName=$_REQUEST["UserName"];
//根据用户查数据库,得到NAS IP,用户的SESSION  ID 
$row=$db->select_one("*","radacct","AcctStopTime='0000-00-00 00:00:00' and UserName='".$UserName."'");
$SessionID =$row['AcctSessionId'];
$NAS_IP    =trim($row['NASIPAddress']);
$tmp       =explode("-",$SessionID);
$FramedIPAddress = $row['FramedIPAddress'];
if($tmp[2]){
	$SessionID=$tmp[1]."-".$tmp[2];
}else{
	$SessionID=$tmp[1];
}

$nas_rs=$db->select_one("*","nas","ip='".$NAS_IP."'");
$http_port=$nas_rs["ports"];
$share_passwd=$nas_rs["secret"];

//print_r($row);
//echo "<hr>";
//print_r($nas_rs);
//echo "<hr>";
//获取供应商信息  
$dRs=$db->select_one("p.device","userinfo as u,project as p","u.projectID=p.ID and u.UserName='".$UserName."'");
$GroupName  = $dRs['GroupName'];
$Vender     = $dRs['device'];	

//echo "Vender=".$Vender;
/*
 *  注释掉了以前的下线函数调用方法
 *
 */
//if($NAS_IP){
//    echo "$SessionID,$NAS_IP,$http_port,$Vender,$UserName,$share_passwd";
//		close_port($SessionID,$NAS_IP,$http_port,$Vender,$UserName,$share_passwd);
//}	





/*
 * 为Linux-Radius添加的下线的代码
 * echo $row['FramedIPAddress']."<hr>";
 */
if( $NAS_IP ) {
  $SessionID = $row['AcctSessionId'];
    //echo "$SessionID,$NAS_IP,$http_port,$Vender,$UserName,$share_passwd";
		//close_port($SessionID, $NAS_IP, $Vender, $UserName, $share_passwd);
  down_user_linux_radius($UserName, $FramedIPAddress, $NAS_IP, $http_port, $share_passwd, $SessionID, $Vender);
}	 
/*
//获取NAS的管理端口
$a_radius = $db->select_all("*","nas","");
if(is_array($a_radius)){
	foreach($a_radius as $a_rs){
		if($a_rs['ip'] == $NAS_IP){
			$http_port    =$a_rs['ports'];
			$share_passwd =$a_rs['secret'];
		}
	
	}
} */


echo "<script>alert('下线成功');window.location.href='operate_online.php';</script>";
?>

</body>
</html>

