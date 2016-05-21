#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="maturity.csv"'); 
echo "帐号,密码,姓名,证件号码,手机号码,联系地址,帐户余额,开始时间,结束时间,项目编号,产品编号,开户人员,用户备注\n";  
require_once("../inc/conn.php");mysql_query("set names gb2312"); //utf8 
//********************************************设置保存的条件
@$UserName        =$_REQUEST["UserName"];
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$projectID		    =$_REQUEST["projectID"]; 
@$name			      =$_REQUEST["name"];
@$address		      =$_REQUEST["address"];   
@$nowTime  = date("Y-m-d H:i:s",time());
@$sql="u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and r.enddatetime<'$nowTime' and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and r.enddatetime>'0000-00-00 00:00:00'";

if($UserName)  $sql .=" and u.UserName like '%".$UserName."%'";
if($name)  $sql .=" and u.name like '%".$name."%'"; 
if($address) $sql .=" and u.address like '%".$address."%'";
if($startDateTime) $sql .=" and u.adddatetime>='".$startDateTime."'";
if($endDateTime) $sql .=" and u.adddatetime<'".$endDateTime."'";
if($projectID) $sql .=" and u.projectID='".$projectID."'";  
$sql.=" order by r.enddatetime DESC";
$result=$db->select_all("u.*,a.*,u.remark as uremark,u.ID as uID,r.enddatetime","userinfo as u,userattribute as a,userrun as r",$sql);
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

