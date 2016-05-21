#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="subjects.csv"'); 
echo "科目名称,金额,添加人员,添加时间,备注\n";   
require_once("../inc/conn.php");mysql_query("set names gb2312"); //utf8
//********************************************设置保存的条件 
@$name			      =$_REQUEST["name"]; 
@$sql .="ID!=''";
if($name)	$sql .=" and name like '%".$name."%'";
@$sql .=" order by ID DESC";
$result=$db->select_all("*","finance",$sql,20);
if(is_array($result)){ 
	foreach($result as $rs){
		//查询出用户的IP地址
	 	echo "'".$rs["name"].","."'".$rs["money"].",".$rs["operator"].","."'".$rs["adddatetime"].","."'".trim($rs["remark"])."\n"; 
	} 
}   
?>

