#!/bin/php
<?php
include("inc/scan_conn.php");  
//include_once("evn.php");

$accResult=$db->select_all("*","radacct","AcctStopTime='0000-00-00 00:00:00'");

if($accResult){
    foreach($accResult as $accKey=>$accRs){
                $AcctStartTime   =$accRs["AcctStartTime"];//上线开始时间
		$AcctSessionTime =$accRs["AcctSessionTime"];//在线时间
		$AcctInputOctets =$accRs["AcctInputOctets"];
		$AcctOutputOctets=$accRs["AcctOutputOctets"];		
		$UserName        =$accRs["UserName"];
		$userID          =getUserID($accRs["UserName"]);//得到用户编号
    $pRs=$db->select_one("o.*,o.ID as orderID,p.*,p.ID as productID","orderinfo as o,product as p","o.productID=p.ID and (o.status in (1,2) ) and o.userID='$userID' and  p.type='flow' "); //包流量用户
        if($pRs){
                         $productID   =$pRs["productID"];
		 	 $orderID     =$pRs["orderID"];
		 	 $periodValue =$pRs["period"];
			 $type        =$pRs["type"];
                         $pPrice       =$pRs["price"];
			 $creditline  =$pRs["creditline"];//信誉值
			 $capping     =$pRs["capping"];//封顶
			 $unitprice   =$pRs["unitprice"];
                         if($type=="flow"){
                         $periodValue=$periodValue*1024*1024;//流量的换算 KB，套餐总共的流量
                         $unitprice  =$unitprice/1024/1024;//这是以KB为单位的费率
                         $onlineData =$AcctInputOctets+$AcctOutputOctets;//讲流量的是又上传流量+下载流量的
			// $onlineDate =($onlineDate/8)*1024; //这里是把字节换算成KB   
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
    //echo "未超出限制"; 
	//只更新记录当天的		
	$db->query("update runinfo set stats='$onlineData' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime' "); 
	 //更新后 统计流量值计算费用 
	$nTotal      =$db->select_one("sum(stats) as useValue","runinfo","userID='$userID' and orderID='$orderID'");
	$nTotalStats =(int)$nTotal["useValue"];	

     $nowPrice= $pPrice/($periodValue/1024/1024) ;//单价 

	$balance =$pPrice-($nTotalStats/1024/1024*$nowPrice);//产品价格-流量*单价

	#echo "当前流量值：".$tTotalStats."<hr>更新流量值：".$nTotalStats ."<hr>".$onlineData."<hr>"; 
	#echo "update runinfo set stats='$onlineData' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime' "."<hr>"."update userattribute set balance='$balance' where userID='$userID' and orderID='$orderID' ";
        
	$db->query("update userrun set balance='$balance' where userID='$userID' and orderID='$orderID' ");//更改用户产品余额
        
}else{//超出限制后 
	  ### echo "超出限制";
	  //判断用户是否为限速用户 
	 if($pRs['limittype']==1){//限速 
	 //修改技术参数 
		if($tLimit['limit']!=1){//判断限速规则是否更改 1 已更改 0 未超速即未更改 
			//踢用户下线 
			###echo "踢用户下线 "; 
			include('inc/scan_down_line.php');
                                  //--------在t.php记录下线记录2014.03.17----------
                                    $file = fopen('t.php','a');
                                    $name="scan_flow_limit.php*流量计费超出限制";
                                    $time=date("Y-m-d H:i:s",time())."||";
                                    fwrite($file,$name.$time);
                                    fclose($file);
                            //-----------------------------------------------
			$db->query("update userrun set balance='0' where userID='$userID' and orderID='$orderID' ");//更改用户产品余额 
			addUserParaminfo($userID,$productID,$pRs["limitupbandwidth"],$pRs["limitdownbandwidth"]);
			//只更新记录当天的 
			$db->query("update runinfo set `limit`='1' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime' ");
		} 
	 }else if($pRs['limittype']==2){//停机 
			//$db->query("update userrun set status=4, balance='0',  where orderID='".$orderID."'");//更改用户产品余额 与 产品余额
                        $db->update_new("userrun","userID='".$userID."' and orderID=".$orderID."",array("status"=>4,"balance"=>0,"enddatetime"=>date("Y-m-d H:i:s",time())));//更新余额、状态结束时间
			$db->query("update orderinfo set status=4 where ID='".$orderID."'");
			//更新用户的属性条件,这是方便拨号时验证
			updateUserAttribute($userID,array("status"=>4,"stop"=>1)); 
			//加写订单日志						
			addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");	 
			//删除用户在网用户信息 即tmpusers 
			//$db->delete_new("tmpusers","userName='".$UserName."'");//删除在网用户信息 
			//把用户级踢下线的 
			###echo "停机 ：update userrun set balance='0' where userID='$userID' and orderID='$orderID' ";
			include('inc/scan_down_line.php');
                            //--------在t.php记录下线记录2014.03.17----------
                                    $file = fopen('t.php','a');
                                    $name="scan_flow_limit.php*流量计费停机";
                                    $time=date("Y-m-d H:i:s",time())."||";
                                    fwrite($file,$name.$time);
                                    fclose($file);
                            //-----------------------------------------------
 
     }else{//封顶
			 $eFlow=$tTotalStats-$periodValue;//超额流量=当期使用总流量-产品流量
			 if($type=="hour"){//小时用户 
				//用户余额
				$userRs = $db->select_one("money","userinfo","ID='".$userID."'");
				$userMoney=$userRs["money"]; 
				//计算超出的总金额
				$moneyTotalRs=$db->select_one("sum(price) as useValue","runinfo","userID='$userID' and orderID='$orderID' ");
				$moneyTotal=$moneyTotalRs["useValue"]; 
				$nowPrice=(float)($onlineData-$tRs["stats"])*$unitprice;//这是本次应当扣去的费用
				$price   =(float)$unitprice*$onlineData;//算出当次在线有所有费用 
				$db->query("update userinfo set money=money-$nowPrice where ID='$userID'");
				$db->query("update runinfo set stats='$onlineData',price='$price' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime'");  
		   	}else{
				$db->query("update runinfo set stats='$onlineData' where userID='$userID' and orderID='$orderID' and adddatetime='$AcctStartTime'"); 
				if($pRs['type']=='flow'){
					unset($balance);
					$balance=-($newPrice*$eFlow/1024/1024);//超额总费用=产品费率*产品流量  以M为单位
					echo $newPrice*$eFlow/1024/1024;
					if(abs($balance)<=$pRs['capping']){//未超过封顶金额 计费
						//更改用户产品封顶欠费 金额       
						$db->query("update userrun set balance='$balance' where userID='$userID' and orderID='$orderID' ");
					} else{//超过了  多该给封顶值
					   $balance=-$pRs['capping'];
					   //更改用户产品封顶欠费 金额     
					   $db->query("update userrun set balance='$balance' where userID='$userID' and orderID='$orderID' ");
					} 
				 }//end if  
			} //end $type='flow'
	 } //end $pRs['limittype'] 
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
    }//end foreach($accResult as $accKey=>$accRs){
}//end if($accResult){	
?>