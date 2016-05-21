#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="upcoming.csv"'); 
echo "帐号,密码,姓名,证件号码,手机号码,联系地址,帐户余额,开始时间,结束时间,项目编号,产品编号,开户人员,用户备注\n";  
require_once("../inc/conn.php");mysql_query("set names gb2312"); 
//********************************************设置保存的条件
@$UserName        =$_REQUEST["UserName"];
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$projectID		    =$_REQUEST["projectID"]; 
@$name			      =$_REQUEST["name"];
@$address		      =$_REQUEST["address"]; 
@$enddays         =$_REQUEST["enddays"];
@$enddays         =($enddays)?$enddays:15;
@$page            =$_REQUEST["page"];   
$nowTime        = date("Y-m-d H:i:s",time());
$upcomingTime   = date('Y-m-d H:i:s',strtotime("$nowTime +$enddays day")); 
@$sql=" u.ID=r.userID and r.enddatetime<'$upcomingTime' and att.userID =u.ID and att.userID=r.userID and r.enddatetime>='$nowTime' and r.enddatetime > now() and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")"; 
if($UserName)  $sql .=" and u.UserName like '%".$UserName."%'";
if($name)  $sql .=" and u.name like '%".$name."%'"; 
if($address) $sql .=" and u.address like '%".$address."%'";
if($startDateTime) $sql .=" and u.adddatetime>='".$startDateTime."'";
if($endDateTime) $sql .=" and u.adddatetime<'".$endDateTime."'";
if($projectID) $sql .=" and u.projectID='".$projectID."'";  
 

// 根据用户分组,查询出用户最后执行的订单编号
 $orderStr=0; 
  $pg =$db->select_all("max(orderID) as maxOrderID","userrun","0=0 group by userID ");
  if($pg){ 
    if(is_array($pg)){
    	foreach($pg as $p){
    	  $orderStr .=$p["maxOrderID"].",";
    	} 
      $orderStr = substr($orderStr,0,-1);
    }
  } 
$sql .= " and r.orderID in(".$orderStr.") ";
$sql .="  ORDER BY u.ID  desc";  
 
$result=$db->select_all("u.*,att.*,u.remark as uremark,u.ID as uID","userinfo as u,userrun as r,userattribute as att",$sql); 
if(is_array($result)){ 
	foreach($result as $rs){
		//查询出用户的IP地址
		$rs1=$db->select_one("Value","radreply","UserName='".$rs["UserName"]."' and Attribute='Framed-IP-Address'");
		$rs2=$db->select_one("*","userrun","orderID='".$rs["orderID"]."'");
		$rs3=$db->select_one("p.name,p.ID","orderinfo as o,product as p","o.ID='".$rs["orderID"]."' and p.ID=o.productID");
	  $rs4=$db->select_one("operator","orderinfo","userID='".$rs["uID"]."' order by ID ASC ");
		echo "'".$rs["UserName"].","."'".$rs["password"].",".$rs["name"].","."'".$rs["cardid"].","."'".$rs["mobile"].",".$rs["address"].",".$rs["money"].","."'".$rs2["begindatetime"].","."'".$rs2["enddatetime"].",".$rs["projectID"].",".$rs3["ID"].",".$rs4["operator"].",".trim($rs["uremark"])."\n";
	} 
}   
?>

