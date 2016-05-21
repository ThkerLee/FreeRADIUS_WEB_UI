#!/bin/php
<?php    
include("inc/conn.php");  
include_once("evn.php");  
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />"; 
//************************************************
//这是扫描的用户停机恢复的情况
//条件是结束时间和开始时间不能为0,且此订单的状态为5（停机状态），表示此用户都设置过停机过了的，如查运行只以当天时间天于恢复时间则表示可以正常恢复了
// 
$action = $_REQUEST["action"];
$userID = $_REQUEST["ID"]; 
$manager =!empty($_SESSION["manager"])?$_SESSION["manager"]:"SYSTEM_SCAN";
$Mname  = getUserName($userID);
//子母账号暂停
$child=$db->select_all('ID','userinfo','Mname="'.$Mname.'"');//该帐号下的所有子账号 
$child[]['ID']=$userID; 
$nowdatetime=date("Y-m-d",time()); 
$nowTime    =date("Y-m-d H:Ii:s",time());  
if($action=="pause"){ //暂停  
$stoptime    =isset($_REQUEST["stoptime"])?$_REQUEST["stoptime"]:$nowdatetime;
$restoretime =isset($_REQUEST["restoretime"])?$_REQUEST["restoretime"]:"0000-00-00 00:00:00";  
unset($userID);
   if(is_array($child)){
	 	foreach($child  as $val){
			foreach($val as $userID){//母账号ID和母账号下的子账号ID                            
		    		 $stopRs=$db->select_one("userID,orderID,status","userrun","userID='$userID' order by status asc");  
					if($stopRs['status']==0){//等待运行订单
					   unset($stopRs);
					   $stopRs=$db->select_one("userID,orderID,status","userrun","userID='$userID' and (status=4 or status=1)  order by status asc");
					}  
					 $userID  =$stopRs["userID"];
					 $UserName=getUserName($userID);
					 $orderID =$stopRs["orderID"];
					 $rs =$db->select_one("enddatetime","userrun","userID='$userID' and orderID='$orderID'");
			   if(count($child)==1){//无子账号 可以有弹出框
				   if( mysqlShowTime($rs['enddatetime'])< $nowTime){
					  echo "<script>alert('"._("该用户订单已结束或为上线计时用户，不允许暂停")."');window.history.go(-1);</script>";
					  exit;
					} 
					if(mysqlShowTime($stoptime) <=  $nowTime){
					   //更新用户属性表1=停机，0=正常
					   updateUserAttribute($userID,array("pause"=>1,"status"=>5,"stop"=>1)); 
					   //设置订单
                                           //------------------------------------------------------------------------------------------------------------
                                           //重新定制时间2014.03.07修改
                                           $Re=$db->select_one("*","userrun","userID='".$userID."' order by ID DESC limit 0,1 "); //2014.07.04号修改order by ID DESC limit 0,1 不加limit 会导致暂停用户时 查询结束时间不准。
                                           $enddatetime    =$Re["enddatetime"];
                                            $timeDiff =mysqlDatediff($restoretime,date("Y-m-d"));//计算用户停机的天数，eg:只要时间过夜及为一天不分时分秒，即使当天23：59：59 到次日 00：00：01也视为一天
                                            $EndDate  =mysqlGteDate($enddatetime,$timeDiff,"day");  //重新更新结束时间 +天
                                            //------------------------------------------------------------------------------------------------------------------------
					   $db->update_new("orderinfo","ID='".$orderID."'",array("status"=>5));
					   $db->update_new("userrun","userID='".$userID."' and status=1 and orderID ='".$orderID."'",array("status"=>5,"stopdatetime"=>$nowdatetime,"restoredatetime"=>$restoretime,"enddatetime"=>$EndDate));//恢复运行表信息  
  
					   include('inc/scan_down_line.php');
                                           //--------在t.php记录下线记录2014.03.17----------
                                           $file = fopen('t.php','a');
                                            $name="pause.php*1点击用户暂停";
                                            $time=date("Y-m-d H:i:s",time())."||";
                                            fwrite($file,$name.$time);
                                            fclose($file);
                                            //-----------------------------------------------
					}else{
					  $db->update_new("userrun","userID='".$userID."' and orderID='".$orderID."' ",array("stopdatetime"=>$stoptime ,"restoredatetime"=>$restoretime));//恢复运行表信息  
					} 
					//记录操作记录,3表示暂停用户
                                        //直接点暂停用户的才更新记录，在停机保号页面设置用户停机时间以添加过了2014.03.10-------------------------------------------------
                                        if($restoretime == "0000-00-00 00:00:00"){ //
                                            addUserLogInfo($userID,3,_("暂停用户"),getName($userID),'',$manager );//"SYSTEM_SCAN" 以前管理员用户名 这是点击用户暂停
                                        }  else {
                                          
                                             addUserLogInfo($userID,6,_("恢复时间：".$restoretime.""),getName($userID)); //这是用户停机保号
                                       
                                        }
					
					//把用户级踢下线的
			   }else{//有子母账号关心  
			  	 if( mysqlShowTime($rs['enddatetime']) > $nowTime){ 
			  	 		if(mysqlShowTime($stoptime) <= $nowTime){ 
					      updateUserAttribute($userID,array("pause"=>1,"status"=>5,"stop"=>1)); //更新用户属性表1=停机，0=正常 
					      $db->update_new("orderinfo","ID='".$orderID."'",array("status"=>5));//设置订单
					      $db->update_new("userrun","userID='".$userID."' and status=1 and orderID='".$orderID."'",array("status"=>5,"stopdatetime"=>$nowdatetime,"restoredatetime"=>$restoretime));//恢复运行表信息 
					      include('inc/scan_down_line.php');//把用户级踢下线的
                                              //--------在t.php记录下线记录2014.03.17----------
                                           $file = fopen('t.php','a');
                                            $name="pause.php*2点击用户暂停";
                                            $time=date("Y-m-d H:i:s",time())."||";
                                            fwrite($file,$name.$time);
                                            fclose($file);
                                            //-----------------------------------------------
				      }else{
					      $db->update_new("userrun","userID='".$userID."' and orderID='".$orderID."' ",array("stopdatetime"=>$stoptime ,"restoredatetime"=>$restoretime));//恢复运行表信息  
					    }
					  //记录操作记录,3表示暂停用户
					  addUserLogInfo($userID,3,_("暂停用户"),getName($userID),'',$manager );//"SYSTEM_SCAN" 以前管理员用户名 
					}
			   } // end count($child)
			} //end 子母账号ID
		} //end foreach
	 }//end if  
	 echo "<script>alert('"._("操作成功")."');window.location.href='user.php'</script>"; 
}
if($action=="restore"){ //恢复
  //判断子母账号 
  $childVal=$db->select_one('Mname','userinfo','ID="'.$userID.'"');
  $mName =$childVal['Mname'];
  $mID   =$db->select_one('ID','userinfo','account="'.$mName.'"');//母账号的ID
  if($mName!=''){//子账号 
    //判断母账号是否暂停或停机  母账号暂停或停机不允许恢复
    //判断母账号是否暂停
    $mom=$db->select_one('status,pause,stop','userattribute','userID="'.$mID['ID'].'" ');//非子账号 
    if(($mom['status']==5 && $mom['pause']==1) || ($mom['status']==4 && $mom['stop']==1)  ){//暂停 或 停机
		 echo "<script>alert('"._("母账号").$mName._("暂停或停机，请确认母账号正常")."');window.location.href='user.php';</script>";
		 exit;
	 }else{//母账号正常
	  //恢复   restartUser($userID);//恢复
		$restoreRs=$db->select_one("*","userrun","(status=5 or status=1) and userID='".$userID."'");//有一个异常情况 当status=1 但验证表pause=1暂停时未能恢复，
		$userID         =$restoreRs["userID"];
		$orderID	    	=$restoreRs["orderID"];
		$stopdatetime   =$restoreRs["stopdatetime"];
		$enddatetime    =$restoreRs["enddatetime"]; 
		//重新定制时间
		$timeDiff =mysqlDatediff(date("Y-m-d"),$stopdatetime);//计算用户停机的天数，eg:只要时间过夜及为一天不分时分秒，即使当天23：59：59 到次日 00：00：01也视为一天
		$EndDate  =mysqlGteDate($enddatetime,$timeDiff,"day");  //重新更新结束时间 +天
		// 上面函数已处理 $EndDate  =date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($EndDate))."+ 23 hours 59 minutes  59 seconds "));  
		$sql=array(
		"enddatetime"=>$EndDate,
		"stopdatetime"=>"0000-00-00 00:00:00",
		"restoredatetime"=>"0000-00-00 00:00:00",
		"status"=>1
		); 
		if($restoreRs["status"]=='5'){
		   $db->update_new("userrun","userID='".$userID."' and (status=5 or status=1)",$sql);//恢复运行表信息   status=1 主要用于修改 订单正常运行1 但pause=1 异常情况下的修改 
		} 
		$db->update_new("orderinfo","ID='".$orderID."'",array("status"=>1));//恢复订单信息 
		//更新用户属性表1=停机，0=正常
		updateUserAttribute($userID,array("pause"=>0,"status"=>1,"stop"=>0));  
		//记录操作记录,3表示暂停用户,4表示恢复
		addUserLogInfo($userID,4,_("用户恢复"),getName($userID),'',$manager);//$_SERVER['REQUEST_URI']  
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
	} 
  }else{//母账号
   if(is_array($child )){
	   foreach($child as $cID){
		  foreach($cID as $userID){
		  //restartUser($userID);//恢复 
			$restoreRs=$db->select_one("*","userrun","(status=5  or status=1)and userID='".$userID."'");
			//print_r($restoreRs);
			$userID         =$restoreRs["userID"];
			$orderID		    =$restoreRs["orderID"];
			$stopdatetime   =$restoreRs["stopdatetime"];
			$enddatetime    =$restoreRs["enddatetime"];
                        $orderenddatetime = $restoreRs["orderenddatetime"];
			//重新定制时间
		  $timeDiff =mysqlDatediff(date("Y-m-d"),$stopdatetime);//计算用户停机的天数，eg:只要时间过夜及为一天不分时分秒，即使当天23：59：59 到次日 00：00：01也视为一天
		  $EndDate  =mysqlGteDate($orderenddatetime,$timeDiff,"day");  //重新更新结束时间 +天
      $sql=array(
                        "orderenddatetime"=> $EndDate,
			"enddatetime"=>$EndDate,
			"stopdatetime"=>"0000-00-00 00:00:00",
			"restoredatetime"=>"0000-00-00 00:00:00",
			"status"=>1
			); 
			if($restoreRs["status"]=='5'){//正常停机
		      $db->update_new("userrun","userID='".$userID."' and (status=5 or status=1)",$sql);//恢复运行表信息 status=1 主要用于修改 订单正常运行1 但pause=1 异常情况下的修改 
	    }
			$db->update_new("userrun","userID='".$userID."' and status=5",$sql);//恢复运行表信息	
			$db->update_new("orderinfo","ID='".$orderID."'",array("status"=>1));//恢复订单信息  
			updateUserAttribute($userID,array("pause"=>0,"status"=>1,"stop"=>0));//更新用户属性表1=停机，0=正常  
			addUserLogInfo($userID,4,_("用户恢复"),getName($userID),'',$manager);//$_SERVER['REQUEST_URI'] //记录操作记录,3表示暂停用户,4表示恢复   
		  //查询该订单的开始结束时间不为0的等待运行订单
	    $waitOrder=$db->select_all("begindatetime,enddatetime,orderID","userrun","status=0 and begindatetime != '00-00-00 00:00:00' and enddatetime !='00-00-00 00:00:00' and userID= '".$userID."'")  ;  
		 if($waitOrder){ //重新设定用户等待运行订单的时间
		    foreach($waitOrder as $val){
			  $newBeginTime  =mysqlGteDate($val['begindatetime'],$timeDiff,"day");  //重新更新等待运行订单开始时间 +天
	      $newEndTime    =mysqlGteDate($val['enddatetime'],$timeDiff,"day");   //重新更新等待运行订单结束时间 +天
	      
			  $orderID      = $val["orderID"];
			  $sql=array(
			        "begindatetime"=>$newBeginTime,
		          "enddatetime"=>$newEndTime 
		      ); 
	 		   $db->update_new("userrun","userID='".$userID."' and orderID = '".$orderID."'",$sql);//恢复运行表信息   status=1 主要用于修改 订单正常运行1 但pause=1 异常情况下的修改  
			}
		  }
		  } 
		} 
   } //end is_array
  }  
echo "<script>alert('"._("操作成功")."');window.location.href='user.php'</script>"; 
}
 
?>
