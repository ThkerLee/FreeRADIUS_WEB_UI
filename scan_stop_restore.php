#!/bin/php
<?php 
/*
include("inc/scan_conn.php");  
include_once("evn.php"); 
//************************************************
//这是扫描的用户停机恢复的情况
//条件是结束时间和开始时间不能为0,且此订单的状态为5（停机状态），表示此用户都设置过停机过了的，如查运行只以当天时间天于恢复时间则表示可以正常恢复了
$nowdatetime=date("Y-m-d H:i:s",time());
$restoreResult=$db->select_all("*","userrun","stopdatetime<>'0000-00-00 00:00:00' and restoredatetime<>'0000-00-00 00:00:00' and status=5 and restoredatetime<='".$nowdatetime."'"); 
if($restoreResult)
{
	foreach($restoreResult as $restoreRs)
	{
		$userID         =$restoreRs["userID"];
		$orderID		    =$restoreRs["orderID"];
		$stopdatetime   =$restoreRs["stopdatetime"];
		$restoredatetime=$restoreRs["restoredatetime"];
		$enddatetime    =$restoreRs["enddatetime"]; 
		//重新定制时间-----------------------------------------------------------------2014.03.07修改
		//$timeDiff =abs( strtotime(date("Y-m-d"))- strtotime($stopdatetime) );//计算用户停机的秒数
		//$EndDate = date('Y-m-d',strtotime($enddatetime)+$timeDiff); //重新更新结束时间 
		//$EndDate  =date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($EndDate))."+ 23 hours 59 minutes  59 seconds ")); 
                //重新定制时间2014.03.07修改
		$timeDiff =mysqlDatediff(date("Y-m-d"),$stopdatetime);//计算用户停机的天数，eg:只要时间过夜及为一天不分时分秒，即使当天23：59：59 到次日 00：00：01也视为一天
		$EndDate  =mysqlGteDate($enddatetime,$timeDiff,"day");  //重新更新结束时间 +天
		$sql=array(
			"enddatetime"=>$EndDate,
			"stopdatetime"=>"0000-00-00 00:00:00",
			"restoredatetime"=>"0000-00-00 00:00:00",
			"status"=>1
		); 
		$db->update_new("userrun","userID='".$userID."' and status=5",$sql);//恢复运行表信息	
		$db->update_new("orderinfo","ID='".$orderID."'",array("status"=>1));//恢复订单信息 
		//更新用户属性表1=停机，0=正常
		updateUserAttribute($userID,array("pause"=>0,"status"=>1,"stop"=>0)); 
		//记录操作记录,3表示暂停用户,4表示恢复  
	  addUserLogInfo($userID,4,$_SERVER['REQUEST_URI'],$name,$surplusMoney,$operator=""); 
          
          //-------------------------2014.03.07添加------------------------------------------------------------
          		//查询该订单的开始结束时间不为0的等待运行订单
	    $waitOrder=$db->select_all("begindatetime,enddatetime,orderID","userrun","status=0 and begindatetime != '00-00-00 00:00:00' and enddatetime !='00-00-00 00:00:00' and userID= '".$userID."'")  ; 
		
		//重新设定用户等待运行订单的时间
		if($waitOrder){ 
		    foreach($waitOrder as $val){
		     $newBeginTime  =mysqlGteDate($val['begindatetime'],$timeDiff,"day");  //重新更新等待运行订单开始时间 +天
	             $newEndTime    =mysqlGteDate($val['enddatetime'],$timeDiff,"day");   //重新更新等待运行订单结束时间 +天
	       //$newBeginTime = date("Y-m-d",strtotime($val['begindatetime']) + $timeDiff);
			   // $newEndTime   = date("Y-m-d",strtotime($val['enddatetime']) + $timeDiff);
			  $orderID      = $val["orderID"];
			  $sql=array(
			        "begindatetime"=>$newBeginTime,
		          "enddatetime"=>$newEndTime 
		      ); 
	 		   $db->update_new("userrun","userID='".$userID."' and orderID = '".$orderID."'",$sql);//恢复运行表信息   status=1 主要用于修改 订单正常运行1 但pause=1 异常情况下的修改 
		
			}
		}
         //-------------------------2014.03.07添加------------------------------------------------------------
	}	
} 
----------------------2014.03.07取消设置了暂停时间----------
//*****************
//这是扫描应该停机的信息
//条件是针对正常运行的订单，如果设置了停机时间并且如果停机时间小于现在时间则表示为现在应该是停机状态 
$stopResult=$db->select_all("*","userrun","stopdatetime<>'0000-00-00 00:00:00' and restoredatetime<>'0000-00-00 00:00:00'  and status=1 and stopdatetime<='".$nowdatetime."'"); 
if($stopResult)
{
	foreach($stopResult as $stopRs)
	{
		$userID  =$stopRs["userID"];
		$UserName=getUserName($userID);
		$orderID =$stopRs["orderID"];
		//更新用户属性表1=停机，0=正常
		updateUserAttribute($userID,array("pause"=>1,"status"=>5,"stop"=>1)); 
		//设置订单
		$db->update_new("orderinfo","ID='".$orderID."'",array("status"=>5));
		$db->update_new("userrun","userID='".$userID."' and status=1",array("status"=>5));//恢复运行表信息
		//记录操作记录,3表示暂停用户
		addUserLogInfo($userID,3,$_SERVER['REQUEST_URI'],"SYSTEM_SCAN"); 
		//把用户级踢下线的
		include('inc/scan_down_line.php'); 
	}
} */
?>
