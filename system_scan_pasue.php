#!/bin/php
<?php 
include("inc/scan_conn.php");  
include_once("evn.php");  
//************************************************
//这是扫描的用户停机恢复的情况
//条件是结束时间和开始时间不能为0,且此订单的状态为5（停机状态），表示此用户都设置过停机过了的，如查运行只以当天时间天于恢复时间则表示可以正常恢复了
//
$nowdatetime=date("Y-m-d H:i:s",time());
$restoreResult=$db->select_all("*","userrun","stopdatetime<>'0000-00-00 00:00:00' and restoredatetime<>'0000-00-00 00:00:00' and status=5 and restoredatetime<='".$nowdatetime."'");

if(!empty($restoreResult)){
	foreach($restoreResult as $restoreRs){
		$userID         =$restoreRs["userID"];
		$orderID		=$restoreRs["orderID"];
		$stopdatetime   =$restoreRs["stopdatetime"];
		$restoredatetime=$restoreRs["restoredatetime"];
		$enddatetime    =$restoreRs["enddatetime"];
		
		//重新定制时间
		$timeDiff =abs( strtotime(date("Y-m-d H:i:s"))- strtotime($stopdatetime) );//计算用户停机的秒数
		$EndDate = date('Y-m-d H:i:s',strtotime($enddatetime)+$timeDiff); //重新更新结束时间
		$sql=array(
			"enddatetime"=>$EndDate,
			"stopdatetime"=>"0000-00-00 00:00:00",
			"restoredatetime"=>"0000-00-00 00:00:00",
			"status"=>1
		);
		echo $userID;
		$db->update_new("userrun","userID='".$userID."' and status=5",$sql);//恢复运行表信息	
		$db->update_new("orderinfo","ID='".$orderID."'",array("status"=>1));//恢复订单信息
		
		//更新用户属性表1=停机，0=正常
		updateUserAttribute($userID,array("pause"=>0,"status"=>1));
		
		//记录操作记录,3表示暂停用户,4表示恢复
		addUserLogInfo($userID,4,$_SERVER['REQUEST_URI']);
	}	
}


//*****************
//这是扫描应该停机的信息
//条件是针对正常运行的订单，如果设置了停机时间并且如果停机时间小于现在时间则表示为现在应该是停机状态

$stopResult=$db->select_all("*","userrun","stopdatetime<>'0000-00-00 00:00:00' and status=1 and stopdatetime<='".$nowdatetime."'");
if(!empty($stopResult)){
	foreach($stopResult as $stopRs){
		$userID  =$stopRs["userID"];
		$UserName=getUserName($userID);
		$orderID =$stopRs["orderID"];
		//更新用户属性表1=停机，0=正常
		updateUserAttribute($userID,array("pause"=>1,"status"=>5));
		
		//设置订单
		$db->update_new("orderinfo","ID='".$orderID."'",array("status"=>5));
		$db->update_new("userrun","userID='".$userID."' and status=1",array("status"=>5));//恢复运行表信息
		//记录操作记录,3表示暂停用户
		addUserLogInfo($userID,3,$_SERVER['REQUEST_URI']);

		//把用户级踢下线的
		include('inc/scan_down_line.php');
                //--------在t.php记录下线记录2014.03.17----------
               $file = fopen('t.php','a');
               $name="system_scan_pasue.php*用户停机恢复";
               $time=date("Y-m-d H:i:s",time())."||";
               fwrite($file,$name.$time);
               fclose($file);
        //-----------------------------------------------	                    
	}
}


?>
