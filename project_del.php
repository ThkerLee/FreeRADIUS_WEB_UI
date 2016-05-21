#!/bin/php
<?php 
include("inc/conn.php");  
require_once("evn.php");
echo "<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=utf-8\" \/>";

$ID=$_GET["ID"];
//在删除项目时间你判断些项目中是否存在有产品，和所在用户
$uNum =$db->select_count("userinfo","projectID='".$ID."'");
$uNum1=$db->select_count("productandproject","projectID='".$ID."'");
if($uNum>0 || $uNum1>0){
	echo "<script>alert(' ". _("此项目中存在产品或用户不能被删除") ." ');window.history.go(-1);</script>";
	exit;
}else{ 
	$db->delete_new("project","ID='$ID'");
	$db->delete_new("areaandproject","projectID='$ID'");
	//固定IP删除 
	 $db->delete_new("ip2ros",'projectID="'.$ID.'"');  
}
echo "<script>alert(' ". _("删除成功") ." ');window.location.href='project.php';</script>";

?>
