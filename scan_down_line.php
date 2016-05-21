#!/bin/php
<?php  
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
//获取供应商信息  
$dRs=$db->select_one("p.device","userinfo as u,project as p","u.projectID=p.ID and u.UserName='".$UserName."'");
$GroupName  = $dRs['GroupName'];
$Vender     = $dRs['device'];	
//echo "Vender=".$Vender;
if($NAS_IP){
		//close_port($SessionID,$NAS_IP,$http_port,$Vender,$UserName,$share_passwd);
		$SessionID =$row['AcctSessionId'];
		down_user_linux_radius($UserName, $FramedIPAddress, $NAS_IP, $http_port, $share_passwd, $SessionID, $Vender);
		
}

//获取NAS的管理端口
/*$a_radius = $db->select_all("*","nas","");
if(is_array($a_radius)){
	foreach($a_radius as $a_rs){
		if($a_rs['ip'] == $NAS_IP){
			$http_port    =$a_rs['ports'];
			$share_passwd =$a_rs['secret'];
		}
		//获取供应商信息  
		$dRs=$db->select_one("p.device","userinfo as u,project as p","u.projectID=p.ID and u.UserName='".$UserName."'");
		$GroupName  = $dRs['GroupName'];
		$Vender     = $dRs['device'];	
		//echo "Vender=".$Vender;
		if($NAS_IP){
				close_port($SessionID,$NAS_IP,$http_port,$Vender,$UserName,$share_passwd);
		}		
	}
}*/ 

?>


