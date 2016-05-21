#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="repair.csv"'); 
echo "帐号,工单类别,说明,开始时间,结束时间,登记人员,当前状态\n";  
require_once("../inc/conn.php");mysql_query("set names gb2312"); //utf8 
//********************************************设置保存的条件 
@$UserName     =$_REQUEST["UserName"];
@$starDateTime =$_REQUEST["startDateTime"];
@$endDateTime  =$_REQUEST["endDateTime"];
@$status 	     =$_REQUEST["status"];
@$type         =$_REQUEST["type"];
@$operator     =$_REQUEST["operator"];
$sql="r.UserName=u.UserName and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") ";
if($UserName)$sql .=" and r.UserName like '%".$UserName."%'";
if($startDateTime)$sql .=" and r.startdatetime>='".$startDateTime."'";
if($endDateTime)$sql .=" and r.startdatetime<'".$endDateTime."'";
if($status)$sql .=" and r.status='".$status."'";
if($type) $sql .=" and r.type ='".$type."'";
if($operator)$sql .=" and r.operator='".$operator."'";
$sql .=" order by r.ID desc";
$result=$db->select_all("r.*","repair as r,userinfo as u",$sql); 
	
if(is_array($result)){
	 foreach($result as $key=>$rs){
			if($rs["status"]=="1")$status ="订单申请中";
			else if($rs["status"]=="2")$status="订单处理中";
	    else if($rs["status"]=="3")$status="订单处理完成";
			if($rs["type"]=="1")$type=_("报装");
			else if($rs["type"]=="2") $type=_("报修");
			else if($rs["type"]=="3")$type=_("其他");
	    echo "'".$rs["UserName"].","."'". $type.",".trim($rs["reason"]).","."'".$rs["startdatetime"].","."'".$rs["enddatetime"].",".$rs["operator"].",".$status."\n"; 
   }
}			








  
?>

