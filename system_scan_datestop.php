#!/bin/php
<?php  
include("inc/conn.php");
include_once("evn.php"); 
 
include("inc/scan_conn.php"); 
$result1=$db->select_all("*","userrun","status=1 and enddatetime<'".date("Y-m-d H:i:s",time())."' and enddatetime>'0000-00-00 00:00:00'");
if($result1){
	foreach($result1 as $key1=>$rs1){//循环到正常订单时间到期的用户、
		$userID  =$rs1["userID"];//此运行订单的用户编号 
		$UserName=getUserName($userID);
		$orderID =$rs1["orderID"];//运行的订单编号 
		//replace order type
		$db->query("update userrun set status=4 where orderID='".$orderID."'");
		$db->query("update orderinfo set status=4 where ID='".$orderID."'");
		//add order operator loginfo
		addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志
		//①判断用户余额是否存在还未运行完的订单 ￥status=0
		$waitOrderRs =$db->select_one("*","userrun","userID='".$userID."' and status=0");//status=0,表示用户还存在未完成的订单	
		
		if($waitOrderRs){//表示用户存在等待运行的订单，此时直接进行等待的订单，
			$begindatetime=$waitOrderRs["begindatetime"];
			$orderRs=$db->select_one("o.*,p.*","orderinfo as o,product as p","o.productID=p.ID and o.ID='".$waitOrderRs["orderID"]."'");
			$productID   =$orderRs["productID"];
			//replace user param info
			addUserParaminfo($userID,$productID);
			//replace user running time 直接运行等待的订单
			updateUserRuntime($waitOrderRs["ID"],$productID,1,$begindatetime);		
			
			//更改用户属性的中的正在使用的产品编号
			updateUserAttribute($userID,array("orderID"=>$waitOrderRs["orderID"]));									
			
		}else{//表示用户没有等待运行的订单,则查出当前的订单信息
			$orderRs=$db->select_one("o.productID,p.price","orderinfo as o,product as p","o.productID=p.ID and o.ID='".$orderID."'");//查询出订单与产品信息
			$userRs =$db->select_one("money","userinfo","ID='".$userID."'");
			
			$productID   =$orderRs["productID"];
			$productPrice=$orderRs["price"];//product price
			$money       =$userRs["money"];//user surplus money	
			
			if(($money-$productPrice)>=0){//this step first ,this suffice term
				//add new order 
				$orderID=addOrder($userID,$productID,1,"SYSTEM_systemScanOrder");
				
				addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志
				//replace user param info
				addUserParaminfo($userID,$productID);
				//replace user running time 
				addUserRuntime($userID,$productID,$orderID,1,1);//立即执行,立即计时	
							
				//更改用户属性的中的正在使用的产品编号
				updateUserAttribute($userID,array("orderID"=>$orderID));	
				
			}else{//当余额不够,则直接结束订单
				$db->query("update userrun set status=4 where orderID='".$orderID."'");
				$db->query("update orderinfo set status=4 where ID='".$orderID."'");
				
				//更新用户的属性条件,这是方便拨号时验证
				updateUserAttribute($userID,array("status"=>4,"stop"=>1));
				
				//加写订单日志						
				addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");	 
				

				
				//把用户级踢下线的
				include('inc/scan_down_line.php');
                                     //--------在t.php记录下线记录2014.03.17----------
                                        $file = fopen('t.php','a');
                                        $name="system_scan_datestop.php*当余额不够,则直接结束订单";
                                        $time=date("Y-m-d H:i:s",time())."||";
                                        fwrite($file,$name.$time);
                                        fclose($file);
                                   //-----------------------------------------------
				
			}//end money 
		}// end is user have a wait order 	
	}//end foreach 
}//end if $result1

//第二步是已经使用了信誉度的订单表了
$result2=$db->select_all("*","userrun","status=2 and enddatetime<'".date("Y-m-d H:i:s",time())."'");
if($result2){
	foreach($result2 as $key2=>$rs2){
		$db->query("update userrun set status=3 where orderID='".$orderID."'");
		$db->query("update orderinfo set status=3 where ID='".$orderID."'");	
		//更新用户的属性条件
		updateUserAttribute($userID,array("status"=>3));
		addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志		
	}
}


//********************************************************************************
//*********针对计时，计流量的用户
$accResult=$db->select_all("*","radacct","AcctStopTime='0000-00-00 00:00:00'");
if($accResult){
	foreach($accResult as $accKey=>$accRs){//view online user 
		$AcctStartTime   =$accRs["AcctStartTime"];//上线开始时间
		$AcctSessionTime =$accRs["AcctSessionTime"];//在线时间
		$AcctInputOctets =$accRs["AcctInputOctets"];
		$AcctOutputOctets=$accRs["AcctOutputOctets"];		
		$userID          =getUserID($accRs["UserName"]);//得到用户编号
		
		
		//但是只查询出是计时，计流量的用户
		 $pRs=$db->select_one("o.*,o.ID as orderID,p.*,p.ID as productID","orderinfo as o,product as p","o.productID=p.ID and (o.status in (1,2) ) and o.userID='$userID' and (p.type='hour' or p.type='flow')");
		 if($pRs){//判断此用户是否是符合要求
		 	 $productID   =$pRs["productID"];
		 	 $orderID     =$pRs["orderID"];
		 	 $periodValue =$pRs["period"];
			 $type        =$pRs["type"];
			 $creditline  =$pRs["creditline"];//信誉值
			 $capping     =$pRs["capping"];//封顶
			 $unitprice   =$pRs["unitprice"];
			 if($type=="hour"){
			 	$periodValue=$periodValue*3600;//换成秒
				$unitprice  =$unitprice/3600;//秒为单位的费率
				$onlineData =$AcctSessionTime;//计时
			 }else if($type=="flow"){
			 	$periodValue=$periodValue*1024;//流量的换算 KB
				$unitprice  =$unitprice/1024;//这是以KB为单位的费率
				$onlineData =$AcctInputOctets+$AcctOutputOctets;//讲流量的是又上传流量+下载流量的
			 }	
			 /**
			  *第一步操作判断用户是否超出了用户限制 
			  *
			  */				  
			 //统计当前用户的总值
			 $tTotal      =$db->select_one("sum(stats) as useValue","runinfo","userID='$userID' and orderID='$orderID'");
			 $tTotalStats =$tTotal["useValue"];
			 $tRs         =$db->select_one("*","runinfo","userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime'");//查询用户的此订单的当前运行记录
			 if($tRs){
				if($tTotalStats<$periodValue){//还没有超出限制
					$db->query("update runinfo set stats='$onlineData' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime' ");//只更新记录当天的						
				}else{//超出限制后
					//计算超出的总金额
					 $moneyTotalRs=$db->select_one("sum(price) as useValue","runinfo","userID='$userID' and orderID='$orderID' ");
					 $moneyTotal=$moneyTotalRs["useValue"];
					 if($capping<$moneyTotal){//表示达到封顶金额，不开始计费
					 	$db->query("update runinfo set stats='$onlineData' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime'");//只更新记录当天的
					 }else{
						//***********这里是要算出每次扣费操作，要总价格减去，之前扣过的费用得到本次应该扣去的费用
						$nowPrice=($onlineData-$tRs["stats"])*$unitprice;//这是本次应当扣去的费用
						$price   =$unitprice*$onlineData;//算出当次在线有所有费用
						$db->query("update runinfo set stats='$onlineData',price='$price' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime'");
					 }
				}
			 }else{//当不存在，表示此用户第一次上线的
			 	$sql=array(
					"userID"=>$userID,
					"orderID"=>$orderID,
					"stats"=>$onlineData,
					"price"=>0,
					"adddatetime"=>$AcctStartTime
				);
			 	$db->insert_new("runinfo",$sql);
			 }// end $tRs	
			  	 
		 }//end $pRs	
		 
		 /**
		  * 第二步是判断用户是否离线了
		  * 
		  */
		  user_is_offline($userID);  
		  
	}//end foreach
}
?>
