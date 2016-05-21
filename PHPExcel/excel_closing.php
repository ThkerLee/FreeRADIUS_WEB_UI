#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="closing.csv"'); 
echo "帐号,密码,姓名,证件号码,手机号码,联系地址,帐户余额,开始时间,结束时间,项目编号,产品编号,开户人员,用户备注\n";  
require_once("../inc/conn.php");mysql_query("set names gb2312"); //utf8
//********************************************设置保存的条件
@$UserName        =$_REQUEST["UserName"];
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$projectID		    =$_REQUEST["projectID"]; 
@$name			      =$_REQUEST["name"];
@$address		      =$_REQUEST["address"];  
@$mobile			    =$_REQUEST["mobile"];
@$productID		    =$_REQUEST["productID"];
@$MAC			        =$_REQUEST["MAC"];
@$operator        =$_REQUEST["operator"];
@$receipt         =$_REQUEST["receipt"]; 
@$action          =$_REQUEST["action"];
@$sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and a.closing=1 and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")"; 

if($UserName) $sql .=" and u.UserName like '%".$UserName."%'"; 
if($name) $sql .=" and u.name like '%".$name."%'"; 
if($address) $sql .=" and u.address like '%".$address."%'"; 
if($mobile) $sql .=" and u.mobile like '%".$mobile."%'"; 
if($startDateTime) $sql .=" and u.adddatetime>='".$startDateTime."'"; 
if($endDateTime) $sql .=" and u.adddatetime<'".$endDateTime."'"; 
if($projectID) $sql .=" and u.projectID='".$projectID."'"; 
if($productID) $sql .=" and o.productID='".$productID."'"; 
if($MAC) $sql .=" and u.MAC='".$MAC."'"; 
if($operator) $sql .=" and u.zjry='".$operator."'"; 
if($receipt) 	$sql .=" and u.receipt like '%".$receipt."%'";  

if($_GET['action']=="userASC") $sql .=" order by u.UserName ASC "; 
else if($_GET['action']=="userDESC")$sql .=" order by u.UserName DESC ";
else if($_GET['action']=="nameASC")$sql .=" order by u.name ASC "; 
else if($_GET['action']=="nameDESC")$sql .=" order by u.name DESC "; 
else if($_GET['action']=="projectASC")$sql .=" order by u.projectID ASC "; 
else if($_GET['action']=="projectDESC")$sql .=" order by u.projectID DESC "; 
else if($_GET['action']=="productASC")$sql .=" order by p.name ASC "; 
else if($_GET['action']=="productDESC")$sql .=" order by p.name DESC "; 
else if($_GET['action']=="moneyASC")$sql .=" order by u.money ASC "; 
else if($_GET['action']=="moneyDESC")$sql .=" order by u.money DESC "; 
else if($_GET['action']=="IDASC")$sql .=" order by u.ID ASC ";  
else if($_GET['action']=="IDDESC")$sql .=" order by u.ID DESC ";  
else $sql .=" order by u.UserName DESC";  
$result=$db->select_all("u.*,u.ID as uID,u.remark as uremark,a.orderID,p.name as product_name","userinfo as u,userattribute as a,orderinfo as o,product as p ",$sql);
	
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

