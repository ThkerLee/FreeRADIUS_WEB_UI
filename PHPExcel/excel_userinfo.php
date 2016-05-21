#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="userinfo.csv"'); 
echo "帐号,密码,姓名,证件号码,手机号码,联系地址,帐户余额,IP地址,开始时间,结束时间,区域编号,区域名称,项目编号,项目名称,产品编号,产品名称,开户人员,用户备注\n";  
require_once("../inc/conn.php"); 
//********************************************设置保存的条件
@$UserName        =$_REQUEST["UserName"];
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$areaID		      =$_REQUEST["areaID"];
@$projectID		    =$_REQUEST["projectID"];
@$productID		    =$_REQUEST["productID"];
@$name			      =$_REQUEST["name"];
@$address		      =$_REQUEST["address"];
@$action          =$_REQUEST["action"];
@$sql="o.userID=u.ID and u.ID = o.userID and at.orderID =o.ID and  u.projectID in (".$_SESSION["auth_project"].") ";
if($UserName)  $sql .=" and u.UserName like '%".$UserName."%'";
if($name)  $sql .=" and u.name like '%".$name."%'"; 
if($address) $sql .=" and u.address like '%".$address."%'";
if($startDateTime) $sql .=" and u.adddatetime>='".$startDateTime."'";
if($endDateTime) $sql .=" and u.adddatetime<'".$endDateTime."'";
if($areaID)  $sql .=" and u.areaID ='".$areaID."'"; 
if($projectID) $sql .=" and u.projectID='".$projectID."'";
if($productID) $sql .=" and o.productID='".$productID."'";
if($action=="userASC") $sql .=" order by u.UserName ASC ";
else if($action=="userDESC")  $sql .=" order by u.UserName DESC ";
else if($action=="nameASC") $sql .=" order by u.name ASC "; 
else if($action=="nameDESC") $sql .=" order by u.name DESC "; 
else if($action=="projectASC") $sql .=" order by u.projectID ASC "; 
else if($action=="projectDESC")  $sql .=" order by u.projectID DESC "; 
else if($action=="productASC") $sql .=" order by p.name ASC "; 
else if($action=="productDESC")  $sql .=" order by p.name DESC "; 
else if($action=="moneyASC") $sql .=" order by u.money ASC "; 
else if($action=="moneyDESC") $sql .=" order by u.money DESC "; 
else if($action=="IDASC")$sql .=" order by u.ID ASC ";  
else if($action=="IDDESC") $sql .=" order by u.ID DESC ";  
else $sql .=" order by u.ID DESC";  
$result=$db->select_all("u.*,at.*,o.*,u.remark as uremark,u.ID as uID","userinfo as u,userattribute as at,orderinfo as o",$sql);
if(is_array($result)){ 
	foreach($result as $rs){
		//查询出用户的IP地址
		$rs1=$db->select_one("Value","radreply","UserName='".$rs["UserName"]."' and Attribute='Framed-IP-Address'");
		$rsBegin=$db->select_one("*","userrun","userID='".$rs["uID"]."' order by orderID asc limit 0,1"); 
		$rsEnd=$db->select_one("*","userrun","userID='".$rs["uID"]."'  order by orderID desc limit 0,1");
		$rs3=$db->select_one("p.name,p.ID","orderinfo as o,product as p","o.ID='".$rs["orderID"]."' and p.ID=o.productID");
	    $rs4=$db->select_one("operator","orderinfo","userID='".$rs["uID"]."' order by ID ASC ");
	    $rs5=$db->select_one("name","project","ID='".$rs["projectID"]."' order by ID ASC ");
	    $rs6=$db->select_one("name","area","ID='".$rs["areaID"]."' order by ID ASC ");
	  
		echo "'".$rs["UserName"].","."'".$rs["password"].",".$rs["name"].","."'".$rs["cardid"].","."'".$rs["mobile"].",".$rs["address"].",".$rs["money"].","."'".$rs1["Value"].","."'".$rsBegin["begindatetime"].","."'".$rsEnd["enddatetime"].",".$rs["areaID"].",".$rs6["name"].",".$rs["projectID"].",".$rs5["name"].",".$rs3["ID"].",".$rs3["name"].",".$rs4["operator"].",".trim($rs["uremark"])."\n";
	} 
}   
?>

