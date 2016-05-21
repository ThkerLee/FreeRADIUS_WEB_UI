#!/bin/php
<?php 
include("inc/excel_conn.php");
require_once("ros_static_ip.php");  
MysqlBegin();//开始事务定义
$sql_a = true;  $sql_b = true;	$sql_c = true;	$sql_d = true;	$sql_e = true;	$sql_f = true;	$sql_g = true;	$sql_h = true;	$sql_i = true;	$sql_j = true;  $sql_k = true;	$sql_l = true;	$sql_m = true;	$sql_n = true;	$sql_o = true;	$sql_p = true; $sql_q = true; $sql_r = true; $sql_s=true; $sql_t =true; $sql_u =true; $sql_v = true; $sql_w=true; $sql_x = true;  $sql_y = true;  $sql_z = true;  $sql_aa = true;  $sql_bb = true;  $sql_cc = true; $sql_dd = true; $sql_ee = true; $sql_ff = true; $sql_gg = true; $sql_hh = true; 
$sql_ii = true; $sql_jj= true;  $sql_kk = true;  $sql_ll= true; $sql_mm= true;    $sql_nn = true;$sql_oo = true;$sql_pp = true;$sql_qq = true;$sql_rr = true;$sql_wait_aa = true ;$sql_wait_bb = true; $sql_wait_cc=true;
	//信用额度
$credit_a = true;$credit_b = true;$credit_c = true;$credit_d = true;$credit_e = true;$credit_f = true;$credit_g = true;$credit_h = true;$credit_i = true;
$notice_a = true; $notice_b =true; 
$result1=$db->select_all("*","userrun","status=1 and enddatetime<'".date("Y-m-d H:i:s",time())."' and enddatetime>'0000-00-00 00:00:00'");  
 //if(!$result1) $sql_a = false; 
 if($result1){ 
	foreach($result1 as $key1=>$rs1){//循环到正常订单时间到期的用户、 
		$userID  =$rs1["userID"];//此运行订单的用户编号 
		$UserName=getUserName($userID);
		$orderID =$rs1["orderID"];//运行的订单编号
		$balRs   =$db->select_one('balance,p.type','userrun as u,orderinfo as o ,product as p',"p.ID = o.productID and u.orderID = o.ID and u.userID = o.userID  and u.orderID='".$orderID."' and u.userID='".$userID."'");//得到当前订单的封顶金
		//if(!$balRs) $sql_b =false;
		if($balRs["type"]!="netbar_hour"){
		   $balRs['balance']=(float)$balRs['balance'];
	     $balances=number_format($balRs['balance'], 2, '.', ''); 
	    if($balRs['balance']){ 
		     if($balRs['balance']<0){//封顶了，即欠费
		   	    // 直接扣除用户余额，在用户表里，不能体现在财务表
			     $sql_rs_err = $db->query("update userinfo set money=money+$balances where  ID='".$userID."'");
			     if(!$sql_rs_err) $sql_c = false;
		      } 
		  } 
		}else{
			$sql_rs_err = $db->query("update userinfo set money=0 where ID='".$userID."'"); 
			if(!$sql_rs_err) $sql_c =false;
		}
		//replace order type
		$sql_rs_err = $db->query("update userrun set status=4 where orderID='".$orderID."'");
		if(!$sql_rs_err) $sql_d =false;
		$sql_rs_err = $db->query("update orderinfo set status=4 where ID='".$orderID."'"); 
		if(!$sql_rs_err) $sql_e =false;
		$sql_rs_eer = updateUserAttribute($userID,array("orderID"=>$orderID,"stop"=>1,"status"=>4)); 
		if(!$sql_rs_err) $sql_oo =false;
		//add order operator loginfo
		$sql_f = addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志
		//①判断用户余额是否存在还未运行完的订单 ￥status=0
		$waitOrderRs =$db->select_one("*","userrun","userID='".$userID."' and status=0 order by orderID asc limit 0,1");//status=0,表示用户还存在未完成的订单 
	//	if(!$waitOrderRs) $sql_g = false;	
		//echo "3添加成<hr>";  
		if($waitOrderRs){//表示用户存在等待运行的订单，此时直接进行等待的订 
		   $begindatetime=$waitOrderRs["begindatetime"];
		   $enddatetime  =$waitOrderRs["enddatetime"]; 
		   if($begindatetime > "00-00-00 00:00:00" &&  $enddatetime> "00-00-00 00:00:00" ){
		    //非上线计时用户
		    if(strtotime($begindatetime)<=time() && strtotime($enddatetime)>time() ){//等待运行订单在正常使用范围内则直接运行
		  ///执行等待运行订单 
		    /**************** satart begin waitorder   begin*****************/ 
		    	$orderRs=$db->select_one("o.*,p.*","orderinfo as o,product as p","o.productID=p.ID and o.ID='".$waitOrderRs["orderID"]."'"); 
			    //if(!$orderRs) $sql_h = false;
			    $productID   =$orderRs["productID"]; 
		     	//replace user param info
		        $sql_i = addUserParaminfo($userID,$productID,'','',"userAdd");
		    	//replace user running time 直接运行等待的订单 
		    	$sql_rs_eer = $db->query("update userrun set status=1 where orderID='".$waitOrderRs["orderID"]."'");	
			    if(!$sql_rs_eer) $sql_j = false;
			    $sql_rs_eer = $db->query("update orderinfo set status=1 where ID='".$waitOrderRs["orderID"]."'");
			    if(!$sql_rs_eer) $sql_k = false;
			    //更改用户属性的中的正在使用的产品编号
			    $sql_rs_eer = updateUserAttribute($userID,array("orderID"=>$waitOrderRs["orderID"],"stop"=>0,"status"=>1));
			    if(!$sql_rs_eer) $sql_l = false; 
	      }else if(strtotime($begindatetime)<=time() && strtotime($enddatetime)<=time()){ //结束当前定单  
		      //停止当前订单  
			    $sql_rs_eer = $db->query("update userrun set status=4 where orderID='".$orderID."'");	
			    if(!$sql_rs_eer) $sql_i = false;
			    $sql_rs_eer = $db->query("update orderinfo set status=4 where ID='".$orderID."'");
			    if(!$sql_rs_eer) $sql_j = false;
			    $sql_rs_eer = updateUserAttribute($userID,array("orderID"=>$orderID,"stop"=>1,"status"=>4));
			    if(!$sql_rs_eer) $sql_k = false;
		  } 
	     }else if($begindatetime=='00-00-00 00:00:00' && $enddatetime =='00-00-00 00:00:00' ){
		        //上线计时用户
				//echo "上线计时用户 有等待运行订单。。。更新订单的开始时间";
		        $sql_rs_eer = updateUserAttribute($userID,array("orderID"=>$orderID,"stop"=>0,"status"=>1));
		        if(!$sql_rs_eer) $sql_i = false;  
			   //$sql_rs_eer = update_new("userrun","orderID='".$orderID."'",array("begindatetime"=>date('Y-m-d H:i:s',time()),'enddatetime'=>date('Y-m-d H:i:s',time())));
			   //if(!$sql_rs_eer) $sql_j = false;
				include('inc/scan_down_line.php');   
		 }   
			/**************** satart begin waitorder end  *****************/  
   	}else{//表示用户没有等待运行的订单,则查出当前的订单信息
    	 // echo "无等待运行订单<hr>";
			$orderRs=$db->select_one("o.productID,p.price,p.auto,p.creditline","orderinfo as o,product as p","o.productID=p.ID and o.ID='".$orderID."'");//查询出订单与产品信息
			//if(!$orderRs) $sql_h =false;
			$userRs =$db->select_one("u.money,a.closing","userinfo as u,userattribute as a","u.ID='".$userID."'  and u.ID=a.userID"); 
			//if(!$userRs)  $sql_i =false;
			$productID   =$orderRs["productID"];
			$productPrice=$orderRs["price"];//product price
			$productAuto =$orderRs["auto"];//product auto renew
			$money       =$userRs["money"];//user surplus money	
			$closing     =$userRs["closing"];// user closing type
			$creditline  =(int)$orderRs["creditline"];//用户信用额度
			//余额供应该产品再续费 时
			//echo "欠费续费<hr>";  
			if(((($money-$productPrice)>=0 && $productPrice>0) || ($productPrice == "0" && $productAuto=="1"))  && $closing!=1){//非0产品续费  以及满足0产品，且允许自动续费的
 			    //echo "余额组以续费<hr>"; 
				//add new order 
				$orderID=addOrder($userID,$productID,1,"SYSTEM_systemScanOrder");
				if($orderID<=0 || !is_numeric($orderID)) $sql_j =false;
				
				$sql_k = addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志
				//replace user param info
				$sql_l = addUserParaminfo($userID,$productID,'','',"userAdd");
				//replace user running time 
				$sql_m = addUserRuntime($userID,$productID,$orderID,1,1);//立即执行,立即计时	 
				$sql_rs_eer = $db->query("update userrun set status=1 where orderID='".$orderID."'");
				if(!$sql_rs_eer) $sql_n =false; 
				$sql_rs_eer = $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 	
				if(!$sql_rs_eer) $sql_o =false; 			
				//更改用户属性的中的正在使用的产品编号
				$sql_p = updateUserAttribute($userID,array("orderID"=>$orderID,"stop"=>0,"status"=>1));	 
			}else{//当余额不够,则直接结束订单
			  // echo "余额不以续费，停机<hr>";
		  	//echo "当余额不够,则直接结束订单<hr>";
			  if($creditline<=0){ 			  	
				$pj=$db->select_one('projectID','userinfo','ID="'.$userID.'"');
				if(!$pj) $sql_k = false;
				$device =$db->select_one("device,days,status","project","ID='".$pj["projectID"]."'");
			 	//如果项目为阿尔卡特且开启项目通告 通告下线
			 	if($device["device"]=="sla-profile" && $device["status"]=="enable" && $device["days"]>0){ 
			 	  $sql_rs_eer = $db->query("update userrun set status=4 where orderID='".$orderID."'");
 				  if(!$sql_rs_eer) $sql_l =false;
				  $sql_rs_eer = $db->query("update orderinfo set status=4 where ID='".$orderID."'");
 			    if(!$sql_rs_eer) $sql_m =false;
				  //更新用户的属性条件,这是方便拨号时验证
				  $sql_n = updateUserAttribute($userID,array("status"=>4,"stop"=>0)); 
				 // echo "用户通告"; 
				   include("inc/send_notice.php"); //用户通告 
				  $sql_n = updateUserAttribute($userID,array("status"=>4,"stop"=>0)); 
				 // echo "用户下线"; 
        	 include('inc/scan_down_line.php'); //用户下线 
			 	}else{//	非阿尔卡特项目和阿尔卡特项目未开始通告 
			  /*****************子母账号子账号停机***********************/  
				//非子账号
				//echo "母账号账号ID：".$userID."<hr>";
			 	$sql_rs_eer = $db->query("update userrun set status=4 where orderID='".$orderID."'");
 				if(!$sql_rs_eer) $sql_l =false;
				$sql_rs_eer = $db->query("update orderinfo set status=4 where ID='".$orderID."'");
 			    if(!$sql_rs_eer) $sql_m =false;
				//更新用户的属性条件,这是方便拨号时验证
				 $sql_n = updateUserAttribute($userID,array("status"=>4,"stop"=>1));  
				//加写订单日志				
 						
				 $sql_o = addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");	 
				//删除ROSIP认证
				//$ros=$db->select_one("*",'ip2ros','projectID="'.$pj['projectID'].'"');
				//delarp_from_ros($ros['rosipaddress'], $ros['rosusername'], $ros['rospassword'],$UserName); 
				//把用户级踢下线的 
 				
				include('inc/scan_down_line.php');   
				//非母账号停机下线结束 
				 //结束订单  判断该帐号是否为母账号  与该账户的流量产品的余额在用户表体现了				 
				//判断账号是否为母账号  YES 子母账号全部停机  NO 非母账号  
				 $Mname  = getUserName($userID);//账号用户名  是否作为母账号了
				 $child=$db->select_one('Mname','userinfo','ID="'.$userID.'"');//该帐号下的所有子账号 
				// if(!$child) $sql_p =false;
				 if($child['Mname']!='' && strpos($child['Mname'],"#")){//母账号
 			      
				    $CID=explode("#",$child['Mname']);//所有子账号
					foreach($CID as $childID){//账号标识不能拨号
 				  
					 //将子账号正在运行订单，和等待运行订单停机    （当母账号续费时，判断子账号停机的账号的是否为正在运行订单或等待运行订单  跟你userrun 订单开始结束时间与当前时间的对比判断）
					  	$childOrderID=$db->select_all("orderID,userID","userrun","userID='".$childID."' and  (status=1  or status=0)");
						//if(!$childOrderID) $sql_q =false;
						$pj=$db->select_one('projectID','userinfo','ID="'.$childID.'"');
						//if(!$pj) $sql_r =false;
						if(is_array($childOrderID)){ 
						 foreach($childOrderID as $child){ 
   						  
								$orderID=$child['orderID'];
								$UserName=getUserName($child['userID']);
								$UserID=$child['userID'];
								//更新用户的属性条件,这是方便拨号时验证  
								//echo "子账号ID：".$UserID."<hr>";
								 $sql_s = updateUserAttribute($UserID,array("stop"=>1));   
								//删除ROSIP认证
								 $ros=$db->select_one("*",'ip2ros','projectID="'.$pj['projectID'].'"');
								// if(!$ros) $sql_t =false;
								 delarp_from_ros($ros['rosipaddress'], $ros['rosusername'], $ros['rospassword'],$UserName); 
								//把用户级踢下线的 
								//echo "<hr>把子账号用户级踢下 线的  $UserName";
								include('inc/scan_down_line.php');    
						 }//foreach out
						}//end is_array
				  }//end foreach $CID
				 } //end if $child['Mname']!=''
			  }// end 非阿尔卡特或阿尔卡特未开通通告				
				}// 信用额度=0
			  else{//有信用额度  ]
			    $sql_rs_eer = $db->query("update userattribute set status=2,stop=0 where userID='".$userID."'");
 				  if(!$sql_rs_eer) $sql_k =false; 
				  $sql_rs_eer = $db->query("update userrun set status=2 where orderID='".$orderID."'");
 				  if(!$sql_rs_eer) $sql_l =false;
				  $sql_rs_eer = $db->query("update orderinfo set status=2 where ID='".$orderID."'");
 			    if(!$sql_rs_eer) $sql_m =false; 
 			   // $db->query("  INSERT INTO runinfo(userID, orderID, stats, price, adddatetime) VALUES (333, 333, 3333, 333, 333333); 
			  } 
			}//end money 
		}// end is user have a wait order 	
	}//end foreach 
}//end if $result1
/*
//第二步是已经使用了信誉度的订单表了
$result2=$db->select_all("*","userrun","status=2 and enddatetime<'".date("Y-m-d H:i:s",time())."'"); 
//if(!$result2 && !is_null($result2))  $sql_u =false;   
if($result2){
	foreach($result2 as $key2=>$rs2){
		$sql_rs_eer = $db->query("update userrun set status=3 where orderID='".$orderID."'");
		if(!$sql_rs_eer) $sql_v =false;
		$sql_rs_eer = $db->query("update orderinfo set status=3 where ID='".$orderID."'");
		if(!$sql_rs_eer) $sql_w =false;	
		//更新用户的属性条件
		$sql_x = updateUserAttribute($userID,array("status"=>3));
		$sql_y = addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志		
	}
} */ 
//第三步 扫描到期用户 查看余额是否足已续费，足以续费则续费
 //1、查询 用户拨号验证表订单到期用户
  $result4 =$db->select_all(" u.`status` as ustatus,a.`status` as astatus ,u.begindatetime,u.enddatetime,a.orderID,a.`stop`,a.userID,a.UserName,ui.money"," userrun as u,userattribute  as a ,userinfo as ui","a.orderID = u.orderID and (a.closing !=1 or ui.closedatetime!='0000-00-00 00:00:00' )  and a.userID=u.userID and a.status = 4 and u.`status`=4 and a.stop=1 and ui.ID=a.userID and ui.ID=u.userID and u.enddatetime <= '".date("Y-m-d H:i:s",time())."'");  
 if(is_array($result4)){ 
  foreach($result4 as $rs4 ){
   $orderID     =$rs4['orderID']; 
	 $userID      =$rs4['userID'];
	 $UserName    =$rs4['UserName'];
	 unset($rs);
     $rs=$db->select_one('*','userrun','userID="'.$userID.'" and status=0 order by orderID asc');//查询该帐号下是否存在等待运行订单  
 //if(!$rs && !is_null($rs))  $sql_aa = false; 
	  //使用产品信息表
	 $productRs   = $db->select_one("o.status,p.price,p.ID,p.auto","orderinfo as o,product as p","o.productID=p.ID and o.ID='".$orderID."'"); 
	 //if(!$productRs)  $sql_bb = false; 
	 $productID   =$productRs["ID"];
	 $productPrice=$productRs["price"];//product price
	 $productAuto =$productRs["auto"];//auto renew
	 $money       = $rs4["money"];//用户余额 
     if(is_array($rs)){//存在
	       //查看订单开始时间与当前时间对比，<=当前时间 并且 结束时间>当前时间  改变状态status=1 如果结束时间<当前时间  结束订单    ，查看余额与产品周期价格的对比进行续费操作
		  unset($orderID);
		  $orderID = $rs["orderID"]; 
		  $begintime = strtotime($rs['begindatetime']);
		  $endtime   = strtotime($rs['enddatetime']);
		  if($begintime > 0 &&  $endtime> 0){ 
		    if($begintime <= time() &&  $endtime  > time()){ //开始时间<=当前时间 && 结束时间>当前时间 更改status=1
		        //更改拨号验证表属性 
				//echo "等待运行订单在时间范围内，运行订单<hr>";
		        $sql_rs_eer =  $db->query("update userattribute set status=1,orderID='".$orderID."',stop=0 where userID='".$userID."'");
			    if(!$sql_rs_eer) $sql_cc =false;
				//更改运行状态表属性
				$sql_rs_eer =  $db->query("update userrun set status=1 where orderID='".$orderID."'");
				if(!$sql_rs_eer) $sql_dd =false;
 				//更改订单表属性
				$sql_rs_eer =  $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 
				if(!$sql_rs_eer) $sql_ee =false;
		  
	   	    }else if( $endtime <= time() &&  $begintime>0 ) {
			//到期的等待运行订单    在等待运行订单只有1个的情况下，现在也是只有一个等待运行订单 
		         //更改拨号验证表属性 
				 //echo "等待运行订单 buzai 时间范围内，撤销订单<hr>";
		        $sql_rs_eer =   $db->query("update userattribute set status=4 where orderID='".$orderID."'");
				if(!$sql_rs_eer) $sql_cc =false;
				//更改运行状态表属性
				$sql_rs_eer =  $db->query("update userrun set status=4 where orderID='".$orderID."'");
				if(!$sql_rs_eer) $sql_dd =false;
 				//更改订单表属性
				$sql_rs_eer =  $db->query("update orderinfo set status=4 where ID='".$orderID."'"); 
				if(!$sql_rs_eer) $sql_ee =false;
			    //1、用户余额与产品周期价格的比较
				if(($money-$productPrice)>=0 && $productPrice>=0){//余额足以续费 
				    //add new order 
					// echo "等待运行订单 buzai 时间范围内，添加订单<hr>";
					$orderID=addOrder($userID,$productID,1,"SYSTEM_systemScanOrder"); 
					if($orderID<=0 || !is_numeric($orderID)) $sql_ff = false;
					$sql_gg = addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志
					//replace user param info
					$sql_hh = addUserParaminfo($userID,$productID,'','',"userAdd");
					//replace user running time 
					$sql_ii = addUserRuntime($userID,$productID,$orderID,1,1);//立即执行,立即计时	 
					$sql_rs_eer =  $db->query("update userrun set status=1 where orderID='".$orderID."'");
					if(!$sql_rs_eer) $sql_jj =false;
					$sql_rs_eer =  $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 	
					if(!$sql_rs_eer) $sql_kk =false; 
					//更改用户属性的中的正在使用的产品编号
					$sql_rs_eer =  updateUserAttribute($userID,array("orderID"=>$orderID,"stop"=>0,"status"=>1));	
					if(!$sql_rs_eer) $sql_ll =false;  
				}//end //余额足以续费 
		    }//end else if  
	     }else if($begintime == 0 &&  $endtime==0){//上线计时用户 
			 //更改用户属性的中的正在使用的产品编号
			 $sql_rs_eer =  updateUserAttribute($userID,array("orderID"=>$orderID,"stop"=>0,"status"=>1));
			 if(!$sql_rs_eer) $sql_jj =false;
			 $sql_rs_eer =  $db->query("update userrun set status=1 where orderID='".$orderID."'");
			 if(!$sql_rs_eer) $sql_kk =false; 
			 $sql_rs_eer =  $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 	
			 if(!$sql_rs_eer) $sql_ll =false; 
			 include('inc/scan_down_line.php');  
		} 
		 
	 }else{//不存在 
	  //产品价格与余额进行对比  余额-产品价格>=0续费 
	        if((($money-$productPrice)>=0 && $productPrice>0)  || ($productPrice == "0" && $productAuto=="1")){//非0产品续费  以及满足0产品，且允许自动续费的/
                	//  echo "余额足以续费 <hr>";
				    //add new order 
					$orderID=addOrder($userID,$productID,1,"SYSTEM_systemScanOrder"); 
					if($orderID<=0 || !is_numeric($orderID)) $sql_cc =false;
					$sql_dd = addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");//加写订单日志
					//replace user param info
					$sql_ee = addUserParaminfo($userID,$productID,'','',"userAdd");
					//replace user running time 
					$sql_ff = addUserRuntime($userID,$productID,$orderID,1,1);//立即执行,立即计时	 
					$sql_rs_eer = $db->query("update userrun set status=1 where orderID='".$orderID."'");
					if(!$sql_rs_eer) $sql_gg =false; 
					$sql_rs_eer = $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 
					if(!$sql_rs_eer) $sql_hh =false; 	 
					//更改用户属性的中的正在使用的产品编号
					$sql_rs_eer = updateUserAttribute($userID,array("orderID"=>$orderID,"stop"=>0,"status"=>1));
					if(!$sql_rs_eer) $sql_ii =false; 	 
				}//end //余额足以续费  
 	 }//end else 不存在 
  }//end foreach
}// end if is_array

 
 
 //第五步 扫描是否有到期的等待运行订单 或 是否有符合正在使用的等待运行订单 
$waitOrder = $db->select_all("begindatetime,enddatetime,userID,orderID","userrun"," status in(0,1) and begindatetime!='00-00-00 00:00:00' and enddatetime!='00-00-00 00:00:00'");   
if($waitOrder){//存在等待运行订单
 foreach($waitOrder as $waitVal){
     $begindatetime = $waitVal["begindatetime"];
		 $enddatetime   = $waitVal["enddatetime"];
		 $userID        = $waitVal["userID"];
		 $orderID       = $waitVal["orderID"];
		 if(strtotime($enddatetime)<= time()){  
			$sql_rs_err = $db->query("update userrun set status=4 where orderID='".$orderID."'");
			if(!$sql_rs_err) $sql_wait_aa =false;
			$sql_rs_err = $db->query("update orderinfo set status=4 where ID='".$orderID."'"); 
			if(!$sql_rs_err) $sql_wait_bb =false;
		 }else if(strtotime($begindatetime) <= time() && strtotime($enddatetime)> time()){ 
			$sql_rs_err = $db->query("update userrun set status=1 where orderID='".$orderID."'");
			if(!$sql_rs_err) $sql_wait_aa =false;
			$sql_rs_err = $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 
			if(!$sql_rs_err) $sql_wait_bb =false;
			$sql_rs_err = $db->query("update userattribute set status=1,stop=0,orderID='".$orderID."' where userID='".$userID."'"); 
			if(!$sql_rs_err) $sql_wait_cc =false; 
		 } 
 } 
} 
//第六步  扫描用户到期后状态值userrun enddatetime <time（） and status=4 but userattribute status =1 and stop = 0 强制下线
$stopOrder = $db->select_all("a.userID,a.orderID,a.UserName","userattribute as a,userrun as u","u.orderID=a.orderID and u.userID=a.userID  and u.enddatetime <'".date("Y-m-d H:i:s",time())."' and a.stop = 0 and u.status =4 and u.enddatetime !='0000-00-00-00:00:00'");  
if(is_array($stopOrder)){
	$pj = $db->select_all("u.ID","userinfo as u,project as p"," u.projectID=p.ID and p.device='sla-profile' and p.`status` ='enable' and p.days >0");
   //print_r($pj); 
    foreach($pj as $p){   
     $pjarr[]=$p["ID"]; 
    }  
 foreach($stopOrder as $uStop){
   $UserName  = $uStop["UserName"];
   $orderID   = $uStop["orderID"];
   $userID    = $uStop["userID"];  
   if(! in_array($userID,$pjarr)){
    //修改用户到期状态
    $sql_rs_eer =   $db->query("update userattribute set status=4,stop=1 where orderID='".$orderID."'");
    if(!$sql_rs_eer) $sql_pp =false;
    //更改运行状态表属性
    $sql_rs_eer =  $db->query("update userrun set status=4 where orderID='".$orderID."'");
    if(!$sql_rs_eer) $sql_qq =false;
    //更改订单表属性
    $sql_rs_eer =  $db->query("update orderinfo set status=4 where ID='".$orderID."'"); 
    if(!$sql_rs_eer) $sql_rr =false; 
    //踢用户下线
    include('inc/scan_down_line.php');  
   } 
 }
}	
	//第七步：信用额度用户到期下线扫描 
$creditDay= $db->select_all("p.creditline,u.enddatetime,att.userID,att.orderID","userattribute as att,orderinfo as o ,product as p, userrun as u","att.status =2  AND o.ID=att.orderID AND p.ID=o.productID AND u.userID=att.userID"); 
 if(is_array($creditDay)){//status =3 为以前的信用额度用完的欠费停用状态，，现在不存在欠费，信用额度是免费使用的 
   foreach($creditDay as $creditRs){
     $orderID       = $creditRs["orderID"];
     $userID        = $creditRs["userID"];
     $UserName      = getUserName($userID); 
     $nowTime       = time(); //当前时间
     $endTime       = $creditRs["enddatetime"]; //产品结束时间
     $creditDays    = $creditRs["creditline"]; //信用额度天
     $creditEndtime = strtotime(date('Y-m-d H:i:s',strtotime("$endTime +$creditDays day"))); //使用信用额度的最后到期时间 
	 if($nowTime >= $creditEndtime){
	    $pj=$db->select_one('projectID','userinfo','ID="'.$userID.'"');
			if(!$pj) $credit_a = false;
			$device =$db->select_one("device,days,status","project","ID='".$pj["projectID"]."'");
			//如果项目为阿尔卡特且开启项目通告 通告下线
		  if($device["device"]=="sla-profile" && $device["status"]=="enable" && $device["days"]>0){ 
			 	  $sql_rs_eer = $db->query("update userrun set status=4 where orderID='".$orderID."'");
 				  if(!$sql_rs_eer) $credit_b =false;
				  $sql_rs_eer = $db->query("update orderinfo set status=4 where ID='".$orderID."'");
 			    if(!$sql_rs_eer) $credit_c =false;
				  //更新用户的属性条件,这是方便拨号时验证
				  $credit_d = updateUserAttribute($userID,array("status"=>4,"stop"=>0));  
				   include("inc/send_notice.php"); //用户通告 
				   include('inc/scan_down_line.php'); //用户下线 
	 	  }else{//非阿尔卡特 或阿尔卡特未开启通告
				$sql_rs_eer = $db->query("update userrun set status=4 where orderID='".$orderID."'");
 				if(!$sql_rs_eer) $credit_b =false;
				$sql_rs_eer = $db->query("update orderinfo set status=4 where ID='".$orderID."'");
        if(!$sql_rs_eer) $credit_c =false; 
				//更新用户的属性条件,这是方便拨号 时验证
				$sql_rs_eer = $db->query("update userattribute set status=4,stop=1 where orderID='".$orderID."'");
 				if(!$sql_rs_eer) $credit_d =false; 
				//加写订单日志 
				 $credit_e =  addOrderLogInfo($userID,$orderID,"4",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");	            //把用户级踢下线的 
 				include('inc/scan_down_line.php');   
				//非母账号停机下线结束 
				//结束订单  判断该帐号是否为母账号  与该账户的流量产品的余额在用户表体现了				 
				//判断账号是否为母账号  YES 子母账号全部停机  NO 非母账号  
				 $Mname  = getUserName($userID);//账号用户名  是否作为母账号了
				 $child=$db->select_one('Mname','userinfo','ID="'.$userID.'"');//该帐号下的所有子账号 
				// if(!$child) $sql_p =false;
				 if($child['Mname']!='' && strpos($child['Mname'],"#")){//母账号
 			        $CID=explode("#",$child['Mname']);//所有子账号
					foreach($CID as $childID){//账号标识不能拨号 
					 //将子账号正在运行订单，和等待运行订单停机    （当母账号续费时，判断子账号停机的账号的是否为正在运行订单或等待运行订单  跟你userrun 订单开始结束时间与当前时间的对比判断）
					  	$childOrderID=$db->select_all("orderID,userID","userrun","userID='".$childID."' and  (status=1  or status=0)");
						//if(!$childOrderID) $sql_q =false;
						$pj=$db->select_one('projectID','userinfo','ID="'.$childID.'"');
						//if(!$pj) $sql_r =false;
						if(is_array($childOrderID)){ 
						 foreach($childOrderID as $child){  
								$orderID=$child['orderID'];
								$UserName=getUserName($child['userID']);
								$UserID=$child['userID'];
								//更新用户的属性条件,这是方便拨号时验证  
								//echo "子账号ID：".$UserID."<hr>";
								 $credit_f = updateUserAttribute($UserID,array("stop"=>1));   
								//删除ROSIP认证
								 $ros=$db->select_one("*",'ip2ros','projectID="'.$pj['projectID'].'"');
								// if(!$ros) $sql_t =false;
								 delarp_from_ros($ros['rosipaddress'], $ros['rosusername'], $ros['rospassword'],$UserName); 
								//把用户级踢下线的 
								//echo "<hr>把子账号用户级踢下 线的  $UserName";
								include('inc/scan_down_line.php');    
						 }//foreach out
						}//end is_array
				  }//end foreach $CID
				 } //end if $child['Mname']!='' 
				}//end 非阿尔卡特项目或阿尔卡特项目未开启通告
	    } 
    }
 }
	//第八步 修改用户的到期时间或升级后用户到有信用额度，用户到期但人在信用额度天数范围内允许上网
  
$creditStatus= $db->select_all("p.creditline,p.type,u.enddatetime,att.userID,att.orderID","userattribute as att,orderinfo as o ,product as p, userrun as u","att.status=4 and o.ID=att.orderID AND p.ID=o.productID AND u.userID=att.userID AND u.orderID = att.orderID");  
if(is_array($creditStatus)){ 
 foreach($creditStatus as $creditRs){
     $orderID       = $creditRs["orderID"];
     $userID        = $creditRs["userID"]; 
     $nowTime       = time(); //当前时间
     $endTime       = $creditRs["enddatetime"]; //产品结束时间
     $creditDays    = $creditRs["creditline"]; //信用额度天
     $creditEndtime = strtotime(date('Y-m-d H:i:s',strtotime("$endTime +$creditDays day")));
     $session_time  =  $creditEndtime  - $nowTime; //使用信用额度的最后到期时间
     $type = $creditRs["type"]; 
 if($type != "hour"){
  if($nowTime < $creditEndtime){//有信用额度可使用
     $sql_rs_eer = $db->query("update userrun set status=2 where orderID='".$orderID."'");
 	  if(!$sql_rs_eer) $credit_g =false;
 $sql_rs_eer = $db->query("update orderinfo set status=2 where ID='".$orderID."'");
 	  if(!$sql_rs_eer) $credit_h =false; 
 //更新用户的属性条件,这是方便拨号 时验证
 $sql_rs_eer = $db->query("update userattribute set status=2,stop=0 where orderID='".$orderID."'");
 	  if(!$sql_rs_eer) $credit_i =false; 
 	  //更新session-time_out的值 
 	  $sql_rs_eer = $db->query("update radreply set Value='".$session_time."'  where Attribute='Session-Timeout' and userID = '".$userID."'"); 
 	  
  }
 }
 }
}
//第九步：判断到期用户是 在通告时间范围内，可拨号状态修改
$noticeRightRs= $db->select_all("productID,projectID,a.userID,a.orderID,run.enddatetime","userinfo as u,userattribute as a,userrun as run,orderinfo as o","a.stop =1 and u.ID=a.userID and o.userID=run.userID and u.ID=o.userID and o.ID=a.orderID and run.orderID=a.orderID");
if($noticeRightRs){
 foreach($noticeRightRs as $val){
   $userID   =$val["userID"];
   $orderID  =$val["orderID"];
   $projectID=$val["projectID"];
   $device   = $db->select_one("device,days,status","project","ID='".$projectID."'");
   $day      =(int)$device["days"];
   //如果项目为阿尔卡特且开启项目通告 通告下线
	 if($device["device"]=="sla-profile" && $device["status"]=="enable" && $device["days"]>0){ 
	    $productRs = $db->select_one("creditline","product","ID='".$productID."'"); //信用额度
	 	  $creditline= (int)$productRs["creditline"];
	 	  $days      = (int)($day + $creditline);
	 	  $endtime   = strtotime(date('Y-m-d H:i:s',strtotime("$enddatetime +$days day")));
	 	  if(time() <= $endtime ){//过了通告时间
	 	   $notice_a = updateUserAttribute($userID,array("status"=>4,"stop"=>0)); 
	 	  }
	 }	
 }
}

//第十步：到期通告 
$noticeRs= $db->select_all("productID,projectID,a.userID,a.orderID,run.enddatetime","userinfo as u,userattribute as a,userrun as run,orderinfo as o","a.status=4 and a.stop =0  and u.ID=a.userID and o.userID=run.userID and u.ID=o.userID and o.ID=a.orderID and run.orderID=a.orderID");
if(is_array($noticeRs)){
	foreach($noticeRs as $rs){		
   $projectID = $rs["projectID"];
   $userID    = $rs["userID"];
   $UserName  = getUserName($userID); 
   $productID = $rs["productID"];
   $enddatetime= $rs["enddatetime"];
   $device    = $db->select_one("device,days,status","project","ID='".$projectID."'");
   $day       =(int)$device["days"];
	 //如果项目为阿尔卡特且开启项目通告 通告下线
	 if($device["device"]=="sla-profile" && $device["status"]=="enable" && $device["days"]>0){ 
	 	  $productRs = $db->select_one("creditline","product","ID='".$productID."'"); //信用额度
	 	  $creditline= (int)$productRs["creditline"];
	 	  $days      = (int)($day + $creditline);
	 	  $endtime   = strtotime(date('Y-m-d H:i:s',strtotime("$enddatetime + {$days} day"))); 
	 	  if(time() > $endtime ){//过了通告时间 
				  $notice_b = updateUserAttribute($userID,array("status"=>4,"stop"=>1));
				  include("inc/send_notice.php"); //用户通告  
				  include('inc/scan_down_line.php'); //用户下线   
	 	  }else{ 
				  $notice_a = updateUserAttribute($userID,array("status"=>4,"stop"=>0));  
	 	  } 
	 } 
  }
}

  if( $sql_a  &&  $sql_b && 	$sql_c && 	$sql_d && 	$sql_e && 	$sql_f && 	$sql_g && 	$sql_h && 	$sql_i && 	$sql_j  &&  $sql_k && 	$sql_l && 	$sql_m && 	$sql_n && 	$sql_o && 	$sql_p  && $sql_q  && $sql_r  && $sql_s &&  $sql_t  &&  $sql_u  &&  $sql_v  && $sql_w &&  $sql_x  &&  $sql_y  &&  $sql_z  &&  $sql_aa  &&  $sql_bb  &&  $sql_cc  && $sql_dd  && $sql_ee  && $sql_ff  && $sql_gg  && $sql_hh  && $sql_ii  && $sql_jj &&  $sql_kk  &&  $sql_ll && $sql_oo && $sql_wait_aa && $sql_wait_bb && $sql_wait_cc && $sql_pp && $sql_qq && $sql_rr && $credit_a && $credit_b && $credit_c && $credit_d && $credit_e && $credit_f && $credit_g && $credit_h && $credit_i && $notice_a && $notice_b){
   MysqlCommit(); 
   echo "success";
 }else{
   MysqRoolback();
    echo "failure";//.$sql_a ."a&&". $sql_b."b&&".	$sql_c."c&&".	$sql_d."d&&".	$sql_e."e&&".	$sql_f."f&&".	$sql_g."g&&".	$sql_h."h&&".	$sql_i."i&&".	$sql_j ."j&&". $sql_k."k&&".	$sql_l."l&&".	$sql_m."m&&".	$sql_n."n&&".	$sql_o."o&&".	$sql_p ."p&&".$sql_q ."q&&".$sql_r ."r&&".$sql_s."s&&". $sql_t ."t&&". $sql_u ."u&&". $sql_v ."v&&".$sql_w."w&&". $sql_x ."x&&". $sql_y ."y&&". $sql_z ."z&&". $sql_aa ."aa&&". $sql_bb ."bb&&". $sql_cc ."cc&&".$sql_dd ."dd&&".$sql_ee ."ee&&".$sql_ff ."ff&&".$sql_gg ."gg&&".$sql_hh ."hh&&".$sql_ii ."ii&&".$sql_jj."jj&&". $sql_kk ."kk&&". $sql_ll."ll&&".$sql_mm."mm&&".$sql_nn."nn";
}  
   MysqlEnd();
   
//用户停机，时间是否过一周期，是销户标记即不允许充值标记

  
/*$result3=$db->select_all("*","userattribute","status=4 and stop=1 and closing !=1");//停机
if($result3){
    foreach($result3 as $key2=>$rs3){
	  $userID=$rs3['userID'];
	  $orderID=$rs3['orderID'];
	  $endTime=$db->select_one('enddatetime','userrun','orderID="'.$orderID.'" and userID="'.$userID.'"');
	  $orderRs=$db->select_one('productID','orderinfo','ID="'.$orderID.'" and userID="'.$userID.'"');
	  $ProductRs=$db->select_one('type,period','product','ID="'.$orderRs['productID'].'"'); 
	  $nowTime =time();
	  $stopTime= $endTime['enddatetime'];//结束时间 
	  $period=$ProductRs['period'];//周期
	 if($ProductRs['type']=='hour'){  
	 
		$onePeriod=strtotime("$stopTime +1 month");//产品结束后一个周期时间点(可续费时间结束时间点)
	 }else if($ProductRs['type']=='day'){
		$onePeriod=strtotime("$stopTime +$period day");//产品结束后一个周期时间点(可续费时间结束时 间点)
		
	 }else if($ProductRs['type']=='month'){
	 
	 
		$onePeriod=strtotime("$stopTime +$period month"); 
	 }else if($ProductRs['type']=='year'){ 
	 
		$onePeriod=strtotime("$stopTime +$period year"); 
	 }   
	 if( $nowTime>$onePeriod ){//过了续费期 标识用户销户 
			updateUserAttribute($userID,array("closing"=>1,"stop"=>1));
			//添加销户日志
			addUserLogInfo($userID,"5","用户销户",getName($userID),'','SYSTEM_SCAN');  
	  }  
	}//end foreach
}//end if*/
  


?>
