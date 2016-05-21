#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php"); 
echo "<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=utf-8\" \/>";

$ID=$_GET["ID"];
//在删除项目时间你判断些项目中是否存在有产品，和所在用户
$uNum =$db->select_count("project","ippoolID='".$ID."'");
 if($uNum>0 ){
	echo "<script>alert('" . _("此地址池被项目绑定不允许删除") . " ');window.history.go(-1);</script>";
	exit;
}else{ 
	$db->delete_new("ippool","ID='$ID'"); 
}
echo "<script>alert('" . _("删除成功") . " ');window.location.href='ippool.php';</script>";

?>
