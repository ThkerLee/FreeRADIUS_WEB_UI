#!/bin/php
<?php 
//header("content-type:text/html; charset=utf-8");
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="project.csv"'); 
require_once("../inc/conn.php");mysql_query("set names utf8");
echo "编号,项目名,所属区域,起始IP,结束IP,Nas设备,MTU值,用户数,安装费用,覆盖用户数,开通日期\n";
$name=$_REQUEST["name"];
$manRs=$db->select_one("*","manager","manager_account='".$_SESSION['manager']."'"); 
$_SESSION["auth_area"] =empty($record["manager_area"])?"0":$record["manager_area"];
$_SESSION["auth_area"] =empty($manRs['manager_area'])?"0":$manRs['manager_area'];
$sql=" 0=0 ";
if($name){
	$sql .=" and name like '%".$name."%'";
}
$sql .=" and p.ID in (".$_SESSION["auth_project"] .") and ap.areaID in(".$_SESSION["auth_area"].")";
$result=$db->select_all("distinct(p.ID),p.*","project as p, areaandproject as ap",$sql);
if(is_array($result)){
		foreach($result as $key=>$rs){
		$num=$db->select_count("userinfo","projectID='".$rs["ID"]."' and gradeID in (". $_SESSION["auth_gradeID"].")");
		$area=$db->select_one("a.name","area as a,areaandproject as ap","a.ID=ap.areaID and ap.projectID=".$rs["ID"]);

                
   echo $rs['ID'].",".$rs['name'].",".$area['name'].",".$rs['beginip'].",".$rs['endip'].",".deviceShow($rs["device"]).",".$rs["mtu"].",".$num.",".$rs["installcharge"].",".$rs["description"].",".$rs["addtime"]."\n";
}

                }