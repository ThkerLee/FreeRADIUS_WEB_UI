#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="userlog.csv"'); 
echo "类型,帐号,姓名,项目名,操作人员,操作时间,备注\n";  
require_once("../inc/conn.php");mysql_query("set names utf8"); //utf8
//********************************************设置保存的条件 
@$UserName       =$_REQUEST["UserName"];
@$startDateTime  =$_REQUEST["startDateTime"];
@$endDateTime    =$_REQUEST["endDateTime"];
@$projectID		   =$_REQUEST["projectID"];
@$name			     =$_REQUEST["name"];
@$address		     =$_REQUEST["address"];
@$type           =$_REQUEST["type"];
@$manager        =$_REQUEST["manager"];  
$sql=" projectID in (".$_SESSION["auth_project"].") ";
if($UserName){
	$sql .=" and account like '%".$UserName."%'";
}
if($manager){
	$sql .=" and operator like '%".$manager."%'";
}
if($type && $type!='0'){
    $type = $type - 1;
	$sql .=" and type = $type ";
}
if($startDateTime){
	$sql .=" and adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and adddatetime<'".$endDateTime."'";
}
if($projectID){
	$sql .=" and projectID='".$projectID."'";
} 
$result=$db->select_all("*","userlog",$sql);

if(is_array($result)){
	$row=2;
	foreach($result as $rs){
		//查询出用户的IP地址
		$rs1=$db->select_one("name","project","ID='".$rs["projectID"]."' ");
		if($rs['type']==0){
		   $str='新增';
		}else if($rs['type']==1){ 
		   $str='续费';
		}else if($rs['type']==2){ 
		   $str='修改';
		}else if($rs['type']==3){ 
		   $str='暂停';
		}else if($rs['type']==4){ 
		   $str='取消暂停';
		}else if($rs['type']==5){ 
		   $str='销户';
		}else if($rs['type']==6){ 
		   $str='设置停机';
		}else if($rs['type']==7){ 
		   $str='更改产品';
		}else if($rs['type']==8){ 
		   $str='删除用户';
		}else if($rs['type']==9){ 
		   $str='用户冲账';
		}else if($rs['type']==10){ 
		   $str='续费并更改产品';
		}else { 
		   $str='未知';
		} 	
		echo "'".$str.","."'".$rs["account"].",".$rs["name"].","."'".$rs1["name"].","."'".$rs["operator"].","."'".$rs["adddatetime"]."'".$rs["content"]."\n";   
	}
}
?>

