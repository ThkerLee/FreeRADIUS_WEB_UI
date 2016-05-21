#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="normalinfo.csv"'); 
echo "帐号,密码,姓名,证件号码,手机号码,联系地址,帐户余额,开始时间,结束时间,项目编号,产品编号,开户人员,用户备注\n";  
require_once("../inc/conn.php");mysql_query("set names gb2312"); //utf8
//********************************************设置保存的条件
@$UserName        =$_REQUEST["UserName"];
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$projectID		    =$_REQUEST["projectID"];
@$productID		    =$_REQUEST["productID"];
@$name			      =$_REQUEST["name"];
@$address		      =$_REQUEST["address"];
@$action          =$_REQUEST["action"];
@$MAC             =$_REQUEST["MAC"];
@$mobile          =$_REQUEST["mobile"]; 
$nowTime  = date("Y-m-d H:i:s",time());
$sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and (r.enddatetime >'".$nowTime."' or r.enddatetime = '0000-00-00 00:00:00'  )and r.userID=u.ID and r.orderID=o.ID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")  and r.enddatetime!='0000-00-00 00:00:00'  and r.begindatetime !='0000-00-00 00:00:00' "; 

if($UserName)  $sql .=" and u.UserName like '%".$UserName."%'";
if($name)  $sql .=" and u.name like '%".$name."%'"; 
if($address) $sql .=" and u.address like '%".$address."%'";
if($startDateTime) $sql .=" and u.adddatetime>='".$startDateTime."'";
if($endDateTime) $sql .=" and u.adddatetime<'".$endDateTime."'";
if($projectID) $sql .=" and u.projectID='".$projectID."'";
if($productID) $sql .=" and o.productID='".$productID."'";
if($mobile) $sql .=" and u.mobile like '%".$mobile."%'"; 
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

$result=$db->select_all("DISTINCT u.ID,u.remark as uremark,u.address,u.password,u.mobile,u.UserName,u.cardid,u.name,u.projectID,u.mobile,u.money,a.orderID,p.name as product_name,p.ID as PID,r.begindatetime,r.enddatetime,o.operator","userinfo as u,userattribute as a,orderinfo as o,product as p,userrun as r",$sql);
if(is_array($result)){
		foreach($result as $key=>$rs){   
		 echo "'".$rs["UserName"].","."'".$rs["password"].",".$rs["name"].","."'".$rs["cardid"].","."'".$rs["mobile"].",".$rs["address"].",".$rs["money"].","."'".$rs["begindatetime"].","."'".$rs["enddatetime"].",".$rs["projectID"].",".$rs["PID"].",".$rs	["operator"].",".trim($rs["uremark"])."\n";  
		}
} 
?>

