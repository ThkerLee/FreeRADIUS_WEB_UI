#!/bin/php
<?php 
include("inc/conn.php");  
require_once("evn.php");
echo "<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=utf-8\" \/>";

$ID=$_GET["ID"];
//在删除项目时间你判断些项目中是否存在有产品，和所在用户 
$uNum0=$db->select_count("productandproject","areaID='".$ID."'");
$uNum1=$db->select_count("userinfo","areaID='".$ID."'");
 
if($uNum0>0 || $uNum1>0){
	echo "<script>alert(' ". _("此区域中存在项目或用户不能被删除") ." ');window.location.href='area.php';</script>";
	exit;
}else{ 
	$db->delete_new("area","ID='$ID'"); 
}
echo "<script>alert(' ". _("删除成功") ." ');window.location.href='area.php';</script>";
 
?>