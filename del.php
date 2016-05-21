#!/bin/php
<?php 
session_start(); 
    $UserName=getUserName($ID);  
	$rs=$db->select_one("*","userinfo","ID='".$ID."'");
	$projectID=$rs['projectID'];
	$pj=$db->select_one("device","project","ID='".$projectID."'");   
	//删除用户拨号认证即订单相关信息 
	$db->delete_new("userattribute","userID='".$ID."'");//删除用户相关属性
	$db->delete_new("userrun","userID='".$ID."'");//删除用户运行订单
	$db->delete_new("orderinfo","userID='".$ID."'");//删除用户订单表
	
	//获取删除账号的开户代理商
	$addUserManager = $db->select_one("operator","userbill","`type` = '0' and userID = '".$ID ."'");   
	$manager = $addUserManager['operator']; 
	//把用户踢下线 radacct nas userinfo
	include('inc/scan_down_line.php');
          //--------在t.php记录下线记录2014.03.17----------
                  $file = fopen('t.php','a');
                  $name="del.php*删除用户";
                  $time=date("Y-m-d H:i:s",time())."||";
                  fwrite($file,$name.$time);
                  fclose($file);
          //-----------------------------------------------
	
	addUserLogInfo($ID,8,_("删除用户"),getName($ID));	//$_SERVER['REQUEST_URI']
	$db->delete_new("userinfo","ID='".$ID."'");//删除用户
	$db->delete_new("userbill","userID='".$ID."'");//删除用户帐单记录
	$db->delete_new("radreply","userID='".$ID."'");//删除用户技术参数表
	$db->delete_new("radcheck","UserName='".$UserName."'");//删除用户密码
	$db->delete_new("orderrefund","userID='".$ID."'");//删除用户退费订单表 
	$db->delete_new("credit","userID='".$ID."'");//删除用户财务信息
	$ros=$db->select_one("*",'ip2ros','projectID="'.$projectID.'"');
	if($ros){  
	  //print_r($ros);print_r($rs['MAC']);exit;
	  delarp_from_ros($ros['rosipaddress'], $ros['rosusername'], $ros['rospassword'],$rs['account']);
	}     
	$manInfo = $db->select_one("addusernum","manager","manager_account='".$manager."'");
	$num =$manInfo['addusernum'] - 1;
	if($num<0) $num =0; 
	$db->update_new("manager","manager_account='".$manager."'",array("addusernum"=>$num ));  