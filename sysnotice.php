#!/bin/php
<?php 
include("inc/scan_conn.php");  
include_once("evn.php"); 
 
if($_GET['ipaddress']){
        //从在线的IP地址，查找用户名
        $ipaddress = $_GET['ipaddress'];
		$rs  =$db->select_one("UserName","radacct","FramedIPAddress='".$ipaddress."' and AcctStopTime='0000-00-00 00:00:00'");
		$rs1 =$db->select_one("r.enddatetime","userattribute as a,userrun as r","a.userID=r.userID and a.orderID=r.orderID and a.UserName='".$rs["UserName"]."'");
		
		$days=floor( (strtotime($rs1["enddatetime"])-time() )/60/60/24);
		echo $days;
		
}

?>
