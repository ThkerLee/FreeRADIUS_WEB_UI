#!/bin/php
<?php 
@include_once("inc/conn.php");
include_once("evn.php");  
require_once("ros_static_ip.php");  
date_default_timezone_set('Asia/Shanghai');  
?>
<html>
<head><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("产品管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<!--<script src="js/jquery.js" type="text/javascript"></script>--和下面的jquery冲突-->
<script src="js/jsdate.js" type="text/javascript"></script>
<!--这是点击帮助的脚本-2014.06.07-->
    <link href="js/jiaoben/css/chinaz.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jiaoben/js/jquery-1.4.4.js"></script>   
    <script type="text/javascript" src="js/jiaoben/js/jquery-ui-1.8.1.custom.min.js"></script> 
    <script type="text/javascript" src="js/jiaoben/js/jquery.easing.1.3.js"></script>        
    <script type="text/javascript" src="js/jiaoben/js/jquery-chinaz.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {  		
        $('#Firefoxicon').click(function() {
          $('#Window1').chinaz({
            WindowTitle:          '<b>营帐管理</b>',
            WindowPositionTop:    'center',
            WindowPositionLeft:   'center',
            WindowWidth:          500,
            WindowHeight:         300,
            WindowAnimation:      'easeOutCubic'
          });
        });		
      });
    </script>
   <!--这是点击帮助的脚本-结束-->
</head>
<body>
<?php  
MysqlBegin();//开始事务定义 
$sql_a = true;  $sql_b = true;	$sql_c = true;	$sql_d = true;	$sql_e = true;	$sql_f = true;	$sql_g = true;	$sql_h = true;	$sql_i = true;	$sql_j = true;  $sql_k = true;	$sql_l = true;	$sql_m = true;	$sql_n = true;	$sql_o = true;	$sql_p = true; $sql_q = true; $sql_r = true; $sql_s = true; $sql_t = true;  $sql_u = true;   $sql_v = true;  $sql_w = true;  $sql_x = true;  $sql_y = true;  $sql_z = true; $sql_aa = true; $sql_bb = true; $sql_cc = true; $sql_dd = true; $sql_ee = true;   $sql_ff =true; $sql_gg = true;
$sql_rs_a  = true ; $sql_rs_b  = true ; $sql_rs_c  = true ;
$sql_rs_err = true ;
$sql_rs_aa = true ;  $sql_rs_bb = true ;  $sql_rs_cc = true ;  $sql_rs_dd = true ;  $sql_rs_ee = true ;  $sql_rs_ff = true ;  $sql_rs_gg = true ;  $sql_rs_gg = true;   $sql_rs_hh = true;
//信用额度
$credit_a= true ;$credit_b =true;
$UserName=$_REQUEST["UserName"];
$token = date('YmdHis',time());#生成新的令牌
if($_POST){
//	if(trim($_POST["begindatetime"])=="")$_POST["begindatetime"] =date("Y-m-d",time());  
//  $_POST["begindatetime"] =date("Y-m-d",strtotime($_POST["begindatetime"]));
//  $_POST["begindatetime"] = $_POST["begindatetime"]." 23:59:59";
	if($_POST['token'] == $_SESSION['token']){#用户提交表单后，比较用户提交的令牌和会话中保存的令牌是否相同
		$_SESSION['token'] = $token;#如果是相同的令牌，修改会话中的令牌，然后处理业务逻辑
	
		$UserName      =$_POST["account"];
		$remark        =$_POST["remark"];
		$isrechange    =$_POST["isrechange"];
               $old_productID =$_POST["old_productID"];
	        $productID     =$_POST["productID"];
		$timetype      =$_POST["timetype"];
		$userID        =getUserID($UserName);
		$money  	     =$_POST["money"];
		$addordernum   =$_POST["period"];
		$recharge_money=$_POST["recharge_money"]; //充值金额，并非用户余额	 
		$begindatetime =$_POST["begindatetime"];
		$nowTime       =date("Y-m-d H:i:s",time());  
		$projectDevice =$_POST["projectDevice"];
		$rechangeMoney =0;//卡片充值金额
                //查看当前产品是否是为隐藏或产品到期的 若为隐藏或为到期的则不能续费2014.04.10修改-----------------------
                $re=$db->select_one("*","product","ID=".$old_productID);
                $hide= $re["hide"];
                 $endTime=$re["enddatetime"];//产品到期时间
               
                $Now_Time  = date("Y-m-d",time());
                
                if(($hide == 1 && $productID =="") || ($endTime < $Now_Time && $endTime != "0000-00-00") ){ 
                 echo "<script>alert('"._("当前产品已到期不能续费,请更换产品")."');window.history.go(-1);</script>";
		exit;   
                }
              //-------------------------------------------------------------------------
		//未更换产品时该产品是否已被移出该项目不允许更换产品 
		if($isrechange=="no"){ 
			$projectAll =$db->select_one("projectID,areaID","userinfo","ID=".$userID); 
			//判断未更换的产品是否还属于用户所属项目
			$normalProduct=$db->select_count("productandproject","areaID=".$projectAll["areaID"]." and projectID=".$projectAll['projectID']. " and productID=".$old_productID);
			if($normalProduct<=0){
				echo "<script>alert('"._("当前用户使用的产品已被移除该用户所属项目,请更换产品")."');window.history.go(-1);</script>";
				exit;
				echo "<script>alert('"._("用户当前使用的产品已不存在，请更换产品")."');window.history.go(-1);</script>";
				exit;  
			}		
		} 
		if(isset($_POST['rechangeMoney']) && $_POST['rechange']==1 ){//卡片充值
			$rechangeMoney = $_POST["rechangeMoney"]; 
		}
		$managerTotalMoney = managerTotalmoneyShow();
		
		if($managerTotalMoney ==false && $recharge_money >0){//用户充值续费，而非余额续费
			$sql_aa = false;
			echo "<script>alert('"._("收费金额已经达到上限,请联系管理员")."');window.history.go(-1);</script>";
			exit;
		}   
		//判断该用户当前使用产品是否为时长计费，是否到期，为到期的用户不允许续费，只允许充值
		$netbarType = $db->select_one("p.type,u.enddatetime","orderinfo as o ,product as p,userattribute as a ,userrun as u","p.ID = o.productID and a.orderID = o.ID and a.userID = o.userID and a.orderID=u.orderID and o.ID=u.orderID and a.userID='".$userID."'");
		if($netbarType["type"]=="netbar_hour"){
			if($netbarType["enddatetime"]=="0000-00-00 00:00:00" || strtotime($netbarType["enddatetime"]) > time()){//无截止时间 或未到结束时间
				echo "<script>alert('"._("当前用户").$UserName._("时长计费用户未到期，只允许充值！")."');window.history.go(-1);</script>";
				exit; 
			}
		}
		//用户的当前产品ID不能为空
		if(!$old_productID  || !is_numeric($old_productID ) || $old_productID <0 ) $sql_a = false; else $sql_a = true;
		//判断更换产品时 如果更换后的产品的编号不存不不为数字 或 < 0 即不合法清空下
		if(($isrechange  && $isrechange =='yes')&& (!$productID || !is_numeric($productID) || $productID< 0) ) $sql_b =  false; else $sql_b = true;  
		 
		
		//判断续费用户是否存在多订单，且订单为上线计时用户
		if($timetype==1){
			$run=$db->select_count("userrun","userID = '".$userID."' and  begindatetime ='0000-00-00 00:00:00' and enddatetime='00-00-00 00:00:00'");    
			if(!is_numeric($run)) $sql_h = false; else $sql_h = true;
			if($run>0){
			   $_POST["begindatetime"]="00-00-00 00:00:00"; 
				 echo "<script>alert('"._("当前用户").$UserName._("存在上线计时订单，只允许续上线计时！")."');window.history.go(-1);</script>";
				 exit;
				  
			}
		}
		
		//判断运行表里该用户是否有 等待运行的产品 即 userrun 表中字段 status==0  如果存在则不允许续费 反之则可
		$run=$db->select_count("userrun as r,userinfo as u,orderinfo as o,product as p","u.ID=r.userID and u.userName='".$UserName."' and r.status=0 and p.ID=o.productID 
	and  r.orderID = o.ID  and (p.type ='hour' or p.type='flow')"); 
		if(!is_numeric($run)) $sql_c = false; else $sql_c = true;
		 
		if($run>0){
			 echo "<script>alert('"._("当前用户").$UserName._("有等待运行的订单，请勿重复续费！")."\\n \\n"._("如需续费，请将该订单撤销后方可进行本次操作")."');window.history.go(-1);</script>";
			 exit;
		}
		
		  
	  
		//这里是判断产品是是否继续使用之前的，还是更换产品的ID编号
		$productID 	   =($isrechange=="yes")?$productID:$old_productID;
		$productResult =$db->select_all("*","product","ID='".$productID."'");
		if(!$productResult) $sql_k  = false ; else $sql_k = true ; 
		$productResult =$productResult[0];
		$change        =($money+$recharge_money+$rechangeMoney) - $productResult['price']*$addordernum;//用户余额+预补费用+卡片充值金额（现金续费则为0）  是否小于产品费用*续费周期
		if($change<0){//判断用户余额是否够充值
		   echo"<script>alert('"._("您所预存的金额不足支付所选产品的价格")."');window.history.go(-1);</script>";
		   
		   exit;
		} 
		$totalRechangeMoney = $recharge_money + $rechangeMoney;//用户所充值金额的总和
		//更新用户剩余的金额
		$sql_rs_err = addRechargeInfo($userID,$totalRechangeMoney);
		if(!$sql_rs_err) $sql_l = false; else $sql_k = true ; 
		if($recharge_money >0){//用户续费过
		  //添加用户帐单记录，
		  $sql_rs_err = addUserBillInfo($userID,"1",$recharge_money,$remark);
		  if(!$sql_rs_err) $sql_m = false; else $sql_m = true ;
		  //添加财务记录
		  $sql_rs_err =  addCreditInfo($userID,"1",$recharge_money,projectShow($rs["projectID"]));
		  if(!$sql_rs_err) $sql_n = false ; else $sql_n = true ;
		}if($rechangeMoney>0){//卡片续费   
		   $card_num = $_POST["card_num"];
		   $card_pwd = $_POST["card_pwd"]; 
		   $cardRs=$db->select_one("*","card","cardNumber='".$card_num ."' and actviation='".$card_pwd."' and sold in (0,1) and recharge='0'");
		   //sold 1销售 rechang = 0为充值
				if($cardRs){
					$recharge_money=$cardRs["money"];
					if($cardRs['sold']==1){//已销售  近充值  销售的时候就有购买用户
					$cardUpSql = array("recharge"=>1,"ivalidTime"=>$nowTime); 
					$sql_rs_err = $db->update_new("card","cardNumber='".$card_num."' and actviation='".$card_pwd."'",$cardUpSql);    
					if(!$sql_rs_err) $sql_rs_gg = false ; else $sql_rs_gg = true ; 
					}else{//未销售  ，进行销售并充值				  
					 $cardUpSql = array("recharge"=>1,"ivalidTime"=>$nowTime,"sold"=>1,"solder"=>$_SESSION["manager"],"UserName"=>$UserName,"soldTime"=>$nowTime); 
					 $sql_rs_err = $db->update_new("card","cardNumber='".$card_num."' and actviation='".$card_pwd."'",$cardUpSql); 
					if(!$sql_rs_err) $sql_rs_gg = false ; else $sql_rs_gg = true ;
					} 
					$sql_log=array("cardNumber"=>$card_num,"type"=>4,"UserName"=>$UserName,"addTime"=>$nowTime,"operator"=>$_SESSION["manager"],"content"=>$_SESSION["manager"]."sold");
					$sql_rs_err = $db->insert_new("cardlog",$sql_log);  
					if(!$sql_rs_err) $sql_rs_hh = false ; else $sql_rs_hh = true ;
				}else{
					echo "<script>alert('"._("卡号信息不正确")."');window.location.href='order_add.php?userName=".$account."';</script>";
					$_SESSION["cardRecharge"][]="1";
					exit;
				}  
			   //添加用户帐单记录，->s
			   $sql_rs_err=addUserBillInfo($userID,"2",$recharge_money,_("用户卡充值")); 
			   if(!$sql_rs_err) $sql_m = false; else $sql_m = true ;
			   //添加财务记录
			   $sql_rs_err =addCreditInfo($userID,"2",$recharge_money);
			   if(!$sql_rs_err) $sql_n = false; else $sql_n = true ;
			   //添加财务记录 
		} 
		$toatal=$recharge_money+$money + $rechangeMoney;   
	   //查询当前用户最后一个订单的状态即结束时间
		$lastOrderStatus =$db->select_one("status,enddatetime,orderID","userrun","userID='".$userID."' order by enddatetime desc"); 
		if($lastOrderStatus){
		  $lastStatus = $lastOrderStatus["status"];
			$lastEnddatetime = $lastOrderStatus["enddatetime"];
			$begindatetime = $lastEnddatetime;
			$lastOrderID = $lastOrderStatus["orderID"];
			 if($lastStatus==0 || $lastStatus==1){//有等待运行订单
				for($i=1;$i<=$addordernum;$i++){
				  //上线计时用户
				   if($timetype==0){
						$orderID=addOrder($userID,$productID,$status,$operator,$receipt); 
						if(is_numeric($orderID) && $orderID>0) $sql_e = true;  else $sql_e = false; 
						//用户运行时间
						$status = addUserRuntime($userID,$productID,$orderID,0,$timetype,"00-00-00 00:00:00");  
						if(is_numeric($status) && $status >=0) $sql_f = true ;else $sql_f = false; 
				   }else{//立即计时或指定时间计时用户
						if($_POST["begindatetime"]!='' &&   strtotime($begindatetime) > strtotime($_POST["begindatetime"])){
							 echo"<script>alert('"._("订单开始时间必须大于等于上一订单的结束时间00000:").$begindatetime."');window.history.go(-1);</script>";
					exit;
						}
						$orderID=addOrder($userID,$productID,$status,$operator,$receipt); 
						if(is_numeric($orderID) && $orderID>0) $sql_e = true;  else $sql_e = false; 
						$begindatetime  = userAddBegindatetime($userID);
						if($i==1){
						   if($_POST["begindatetime"] !='') $begindatetime = $_POST["begindatetime"];
						   if(strtotime($lastEnddatetime) < time()){
							  //一下三部针对  当前订单根据时间应该到期，但还未扫描，状态均为正在使用，这是用户续费，（能续费，同时2个订单正在使用，且拨号验证表为上一到期订单与相应的到期状态，用户无法上网）
							   $sql_cc =$db->update_new("userattribute","userID='".$userID."'",array("status"=>1,"stop"=>0,"orderID"=>$orderID));
							   $sal_dd =$db->update_new("orderinfo","ID='".$lastOrderID."'",array("status"=>4));
							   $sql_ee =$db->update_new("userrun","orderID='".$lastOrderID."'",array("status"=>4)); 
							}
						 } 
						//用户运行时间
						$status = addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime);
						if(is_numeric($status) && $status >=0) $sql_f = true ;else $sql_f = false; 
					} 
				 } //end for
			  } elseif($lastStatus==4 || $lastStatus==2){//到期用户 既无等待运行订单也无正在使用订单   涉及子母账号  2到期使用信用额度的账户
					if($_POST["begindatetime"]!=''){
						//到期 用户 续费选择开始时间为上一订单的结束时间 自动添加时分秒
						$postBegindate =date("Y-m-d",strtotime($_POST["begindatetime"]));
						$besdate       =date("Y-m-d",strtotime($begindatetime));
					  if($postBegindate ==$besdate ) $_POST["begindatetime"]=$postBegindate." 23:59:59";
						if(strtotime($begindatetime) > strtotime($_POST["begindatetime"])){
						   echo"<script>alert('"._("订单开始时间必须大于等于上一订单的结束时间22222:").$begindatetime."');window.history.go(-1);</script>";
					 exit;
						} 
					}else $begindatetime = date("Y-m-d H:i:s",time());  
					 //更改用户带宽属性 
					 addUserParaminfo($userID,$productID,'','',"userAdd");
					//续费订单
					for($i=1;$i<=$addordernum;$i++){
					//上线计时用户
					  if($timetype==0){
						  $orderID=addOrder($userID,$productID,$status,$operator,$receipt); 
						  if(is_numeric($orderID) && $orderID>0) $sql_e = true;  else $sql_e = false; 
						  //用户运行时间
						  $status = addUserRuntime($userID,$productID,$orderID,0,$timetype,"00-00-00 00:00:00");
						  if(is_numeric($status) && $status >=0) $sql_f = true ;else $sql_f = false; 
					   }else{//立即计时或指定时间计时用户
						   if($_POST["begindatetime"]!='' && strtotime($begindatetime) > strtotime($_POST["begindatetime"])){
							   echo"<script>alert('"._("订单开始时间必须大于等于上一订单的结束时间3333:").$begindatetime."');window.history.go(-1);</script>";
					   exit;
							}
							$orderID=addOrder($userID,$productID,$status,$operator,$receipt); 
							if(is_numeric($orderID) && $orderID>0) $sql_e = true;  else $sql_e = false; 
							  $begindatetime  = userAddBegindatetime($userID);
							if($i==1){
							   if($_POST["begindatetime"] !='') $begindatetime = $_POST["begindatetime"];
							} 
							//用户运行时间
							$status = addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime);
							if(is_numeric($status) && $status >=0) $sql_f = true ;else $sql_f = false; 
						} 
					  }
					  //更新到期正在使用信用额度或使用完信用额度的用户续费，更新当前订单的状态为4，而非为当期值，status=2or 3
						if($lastStatus==2 ){
						   $sql_rs_err = $db->query("update userrun set status=4 where orderID='".$lastOrderID."'");
						   if(!$sql_rs_err) $credit_a = false ; 
						   $sql_rs_err = $db->query("update orderinfo set status=4 where ID='".$lastOrderID."'"); 
						   if(!$sql_rs_err) $credit_b = false ;   
						}
					  //修改拨号验证表状态
					  
					  //查询当前用户最后一个订单的状态即结束时间
					  $nowTime = date("Y-m-d H:i:s",time());
			  $useRunOrderID =$db->select_one("status,orderID","userrun","userID='".$userID."' and status in (0,1) order by enddatetime asc limit 0,1"); // begindatetime<='".$nowTime."' and enddatetime > '".$nowTime."' 
					  if($useRunOrderID){
						$userNowStatus = $useRunOrderID['status'];
						$userNowOrderid = $useRunOrderID['orderID'];
						if($userNowStatus ==1 || $userNowStatus ==0 ){
							 $sql_rs_eer = updateUserAttribute($userID,array("orderID"=>$userNowOrderid,"stop"=>0,"status"=>1));
							 if(!$sql_rs_eer) $sql_g = false;
						}else{
							 $sql_rs_eer = updateUserAttribute($userID,array("orderID"=>$userNowOrderid,"stop"=>1,"status"=>0));
							 if(!$sql_rs_eer) $sql_g = false;
						}
					  
					  }   //end for
					  //恢复子母账号恢复
					  //母账号添加ROSip认证
						$projectRs=$db->select_one('projectID',"userinfo","ID='".$userID."'");
						$projectID=$projectRs['projectID'];
						$pj=$db->select_one('*','ip2ros',"projectID='".$projectID ."'");  
						$ipRs=$db->select_one("*","radreply","userID='".$userID."' and Attribute='Framed-IP-Address'");
						//|| !$pj || !$ipRs 待处理
						if((!$projectRs ) || (!is_numeric($projectID) || $projectID <=0   )) $sql_v = false ;else $sql_v = true ;    
						$account=$UserName; 
						if($pj){
						  $sql_w =  addarp2ros($pj['rosipaddress'], $pj['rosusername'], $pj['rospassword'], $ipaddress, $account, $pj['inf']);
						 } 
						//判断子母账号 
						 $child=$db->select_one('Mname','userinfo','ID="'.$userID.'"');//该帐号下的所有子账号 
						 if(!$child) $sql_x = false ; else $sql_x = true ; 
						  if($child['Mname']!='' && strpos($child['Mname'],"#")){//母账号
							 $CID=explode("#",$child['Mname']);//所有子账号 
							  array_pop($CID); 
							  //查看子账号是否标识停机 即当前应该为正常状态因母账号停机而表示stop=1
							  $time=date("Y-m-d H:i:s",time());
							  $j = count($CID); 
							  foreach($CID as $userID){
								  $projectRs=$db->select_one('projectID',"userinfo","ID='".$userID."'");
								  $projectID=$projectRs['projectID'];
								  $pj=$db->select_one('*','ip2ros',"projectID='".$projectID ."'"); 
								  if((!$projectRs || !$pj) || ( !is_numeric($projectID) || $projectID<=0) ) $sql_rs_a = false; else $sql_rs_a = true ;
								  //查找子账号的订单 未结束的订单 并且订单开始时间>当前时间
								  $orderInfo=$db->select_all('begindatetime,enddatetime,orderID','userrun','userID="'.$userID.'" and  enddatetime> "'.$time.'"');
								  if(!$orderInfo) $sql_rs_b = false ; else $sql_rs_b = true ; 
								  if($orderInfo){//存在符合条件订单  子账号洗存在的订单
									if(is_array($orderInfo)){//此层  返回给外围 $sql_rs_c  内层 标记为 $sql_rs_aa  即 $sql_rs_xx
									   $m = count($orderInfo) - 1; 
									   foreach($orderInfo as $orderVal){
										 $orderID=$orderVal['orderID'];
										 if(!$orderID || !is_numeric($projectID) || $projectID <=0) $sql_rs_aa = false ; else $sql_rs_aa = true;
										 $orderBeginTime=strtotime($orderVal['begindatetime']);	
										 $orderEndTime=strtotime($orderVal['enddatetime']);	
										 if($orderBeginTime<time() && $orderEndTime>time()){//正在运行订单
											//恢复子账号的当前订单  
											$sql_rs_err = $db->query("update userrun set status=1 where orderID='".$orderID."'");
											if(!$sql_rs_err) $sql_rs_bb = false ; else $sql_rs_bb = true ; 
											$sql_rs_err = $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 
											if(!$sql_rs_err) $sql_rs_cc = false ; else $sql_rs_cc = true ; 
											//更新用户的属性条件,这是方便拨号时验证 
											$sql_rs_err = updateUserAttribute($userID,array("status"=>1,"stop"=>0)); 
											if(!$sql_rs_err) $sql_rs_dd = false ; else $sql_rs_dd = true ; 
											//加写订单日志	 1续费 			
											$sql_rs_err =  addOrderLogInfo($userID,$orderID,"1",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder"); 
											if(!$sql_rs_err) $sql_rs_ee = false ; else $sql_rs_ee = true ; 
											
											//ROSIP
											$projectRs=$db->select_one('projectID',"userinfo","ID='".$userID."'");
											$projectID=$projectRs['projectID'];
											$pj=$db->select_one('*','ip2ros',"projectID='".$projectID ."'");  
											$ipRs=$db->select_one("*","radreply","userID='".$userID."' and Attribute='Framed-IP-Address'");
											$account=$UserName;
											$ipaddress=$ipRs['Value']; 
											if((!$projectRs || !$pj || !$ipRs) || (!is_numeric($projectID) || $projectID <=0 || !$projectID)) 
											$sql_rs_ff = false ;else $sql_rs_ff = true ;   
											if($pj){
											   $sql_rs_gg = addarp2ros($pj['rosipaddress'], $pj['rosusername'], $pj['rospassword'], $ipaddress, $account, $pj['inf']);
											} 
										 }else if($orderBeginTime>time() && $orderEndTime>time()){//等待运行订单 
										   //恢复子账号的等待运行订单  
											$sql_rs_err = $db->query("update userrun set status=0 where orderID='".$orderID."'");
											if(!sql_rs_err) $sql_rs_bb = false ; else $sql_rs_bb = true ;
											$sql_rs_err = $db->query("update orderinfo set status=0 where ID='".$orderID."'"); 
											if(!sql_rs_err) $sql_rs_cc = false ; else $sql_rs_cc = true ; 
											//更新用户的属性条件,这是方便拨号时验证
											$sql_rs_err = updateUserAttribute($userID,array("status"=>0,"stop"=>0));  
											if(!sql_rs_err) $sql_rs_dd = false ; else $sql_rs_dd = true ; 
											//加写订单日志	 1续费 			
											$sql_rs_err = addOrderLogInfo($userID,$orderID,"1",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder");
											if(!sql_rs_err) $sql_rs_ee = false ; else $sql_rs_ee = true ;  
											 
										 }else if($orderEndTime<time()){// 结束订单 
										   //恢复子账号的等待运行订单  
											$sql_rs_eer = $db->query("update userrun set status=4 where orderID='".$orderID."'");
											if(!$sql_rs_err) $sql_rs_bb = false ; else $sql_rs_bb = true ;
											$sql_rs_err = $db->query("update orderinfo set status=4 where ID='".$orderID."'"); 
											if(!$sql_rs_err) $sql_rs_cc = false ; else $sql_rs_cc = true ; 
											//更新用户的属性条件,这是方便拨号时验证
											$sql_rs_err = updateUserAttribute($userID,array("status"=>4,"stop"=>1));
											if(!$sql_rs_err) $sql_rs_dd = false ; else $sql_rs_dd = true ;   
											$sql_rs_err = $db->query("update orderinfo set status=4 where ID='".$orderID."'");  
											if(!$sql_rs_err) $sql_rs_ee = false ; else $sql_rs_ee = true ;   
											//加写订单日志	 1续费 			
											$sql_rs_err = addOrderLogInfo($userID,$orderID,"1",$_SERVER['REQUEST_URI'],"SYSTEM_systemScanOrder"); 
											if(!$sql_rs_err) $sql_rs_ff = false ; else $sql_rs_ff = true ;   
										  
										 }	 
										 if($sql_rs_aa && $sql_rs_bb && $sql_rs_cc && $sql_rs_dd && $sql_rs_ee && $sql_rs_ff && $sql_rs_gg ){
										   $m++;
										   continue; 
										 }else{
											 break ;
										 } 	   
									   }//end foreach
									   
									}//end is_array
								  }//end $orderInfo  
								  
								   if($m==count($orderInfo)) $sql_c = true ;else $sq_c = false ; 
								   if($sql_rs_a && $sql_rs_b && $sql_rs_c ){//循环遍历且成功执行
									  $j++;
									  continue; 
								  }else{
									  break;
								  }
							  }//end foreach $CID
							  //循环遍历且成功执行
							  if($j==count($CID))  $sql_y = true ;else $sql_y = false; 
						  }    
			  }
		 
		}else  $sql_e = false;
		/* ------------------------------------------------------------------------------------2014.02.28
		//修改rasreplay 表中的Session-Timeout 值为 截至时间 
		$rasreplay=$db->select_one("enddatetime","userrun"," userID='".$userID."' and status in(0,1) order by enddatetime "); //订单结束时间
		if(!$rasreplay) $sql_p = false ; else $sql_p = true ;
		
		$stoptime=strtotime($rasreplay['enddatetime'])-time();//结束时间减去当前时间
		 //对已有用户的记录的修改
		// mysql_query("update radreply set  Value='".$stoptime."' where userID='".$userID."' and Attribute='Session-Timeout'"); 
		 $sql_rs_err = $db->query("update radreply set  Value='".$stoptime."' where userID='".$userID."' and Attribute='Session-Timeout'");
		 if(!$sql_rs_err) $sql_q = false ; else $sql_q = true ; ---------------------------------------------------------*/
		
		if($old_productID==$productID){//仅仅续费
		//记录用户操作记录
		 $sql_rs_err = addUserLogInfo($userID,"1",_("用户续费"),getName($userID),$recharge_money);//$_SERVER['REQUEST_URI']
		 if(!$sql_rs_err) $sql_z = false ;else $sql_rs_z = true;
		}else{//续费+更换产品
		//记录用户操作记录
		$sql_rs_err =  addUserLogInfo($userID,"10",_("用户续费:并将【下一订单产品】更改为:").productShow($productID),getName($userID),$totalRechangeMoney);//$_SERVER['REQUEST_URI']	 
		if(!$sql_rs_err) $sql_z = false ;else $sql_z = true ;
		} 
		
		//用户续费成功后，查看用户使用的项目
		$uInfo = $db->select_one("projectID","userinfo","ID='".$userID."' and UserName='".$UserName."'"); 
		if(!uInfo) $sql_aa = false ; else $sql_aa = true ; 
		//启用即将到和到期的删除ROSip和华为ip****************************************************
                 $RID=$uInfo["projectID"];    //2014-07-02 修改
                $re= $db->select_one("*","project","device='".$projectDevice."' and ID ='".$RID."' ");//ROS  2014-07-02 修改
                $remind=$re['remind'];
                $duestatus=$re['duestatus'];
                //2014.02.28把$remind== "enable" && $duestatus== "enable"改为($remind== "enable" || $duestatus== "enable")
		if($projectDevice=="mikrotik" && ($remind== "enable" || $duestatus== "enable")){
			 $sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and  Attribute ='Framed-IP-Address' ");	 
                          $sql_rs_err = $db->delete_new("ippool_tmp","userID=".$userID."");//2014.02.28 添加这个行代码
		}
                $re1= $db->select_one("*","project","device='".$projectDevice."'");//华为
                $duestatus1=$re1['duestatus'];
		if($projectDevice=="ma5200f"  && $duestatus1== "enable"){
			 $sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and  Attribute ='Framed-IP-Address' ");	 
		}
		//删除用户IP在分配IP   还是当前产品。。。主要针对即将到期删除通告IP
		//$sql_rs_err = getBindUserIP($uInfo['projectID'],$userID,$UserName);
		if(!$sql_rs_err) $sql_bb = false ;else $sql_bb = true ;
		 
		$sql_ff =$db->update_new("userinfo","ID='".$userID."'",array("closedatetime"=>'0000-00-00 00:00:00'));
		
		$sql_gg =$db->update_new("userattribute","userID='".$userID."'",array("closing"=>0));
	   if($sql_a && $sql_b && $sql_c && $sql_d && $sql_e && $sql_f && $sql_g && $sql_h && $sql_i && $sql_j && $sql_k && $sql_l && $sql_m && $sql_n && $sql_o && $sql_p && $sql_q && $sql_r && $sql_s && $sql_t && $sql_u && $sql_v && $sql_w && $sql_x && $sql_y && $sql_z && $sql_aa && $sql_bb && $sql_cc && $sql_dd &&  $sql_ee && $sql_ff && $sql_gg && $sql_rs_gg && $sql_rs_hh && $credit_a && $credit_b ){
	   
		   MysqlCommit(); 
		   $c=_("操作成功" );	
	   } else{
		   MysqRoolback();
		   $c=_("操作失败"); 
		//.$sql_a ."sql_a&&". $sql_b ."sql_b&&". $sql_c ."sql_c&&". $sql_d ."sql_d&&". $sql_e ."sql_e&&". $sql_f ."sql_f&&". $sql_g ."sql_g&&". $sql_h ."sql_h&&". $sql_i ."sql_i&&". $sql_j ."sql_j&&". $sql_k ."sql_k&&". $sql_l ."sql_l&&". $sql_m ."sql_m&&". $sql_n ."sql_n&&". $sql_o ."sql_o&&". $sql_p ."sql_p&&". $sql_q ."sql_q&&". $sql_r ."sql_r&&". $sql_s ."sql_s&&". $sql_t ."sql_t&&". $sql_u ."sql_u&&". $sql_v ."sql_v&&". $sql_w ."sql_w&&". $sql_x ."sql_x&&". $sql_y ."sql_y&&". $sql_z."sql_z&&". $sql_aa ."sql_aa&&". $sql_bb ."sql_bb&&". $sql_cc ."sql_cc&&". $sql_dd ."sql_dd&&".  $sql_ee ."sql_ee&&". $sql_ff ."sql_ff&&". $sql_gg ."&&gg". $sql_rs_gg ."sql_rs_gg&&". $sql_rs_hh."sql_rs_hh".$credit_a."credit_a".$credit_b ."credit_b"
	  }    
		 echo "<script language='javascript'>alert('".$c."');</script>"; 
		 if($c == _("操作成功")){
                     $re=$db->select_one("*","message","type = 2");//短信发送用
                          $status=$re['status'];
                          if($status == "enable"){
                    echo "<script>if(window.confirm('"._("是否发送短信")."？')){window.open('recharge_message.php?account=".$_POST['account']."','newname','height=60,width=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=0,left=0');}</script>";
                          }
		 echo "<script>if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?UserName=".$_POST['account']."&action=$addordernum&orderadd=orderadd','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,top=100,left=300');}window.location.href='?UserName=$UserName';</script>";	
		 }else{
		  echo "<script>alert(".$c.");window.location.href='user.php';</script>"; 
		 }	 
		 MysqlEnd();//关闭连接
	}else{
		echo "<script>alert(\""._("请不要重复提交!")."\");window.location.href='user.php';</script>";
		exit;
	}
}else{#第一次访问该页面时,将生成的新的令牌保存到会话中
	$_SESSION['token'] = $token;
}
?> 
 <form action="?" method="post" name="myform" onSubmit="return checkOrderForm();"> 	
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
          <td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("营帐管理")?></font></td>
            <td width="3%" height="35">
               <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
           </td> <!------帮助--2014.06.07----->         
        </tr>
      </table></td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
        <tr>
          <td width="89%" class="f-bulue1"> <? echo _("用户续费")?></td>
          <td width="11%" align="right">&nbsp;</td>
        </tr>
      </table>
	 
<?php if($UserName){  ?>
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
        <thead>
              <tr>
                <td width="2%" align="center" class="f-b bg f-12 "><input type="checkbox" name="allID" value=""></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("编号")?></td>
                <td width="11%" align="center" class="f-b bg f-12"><? echo _("用户帐号")?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("用户姓名")?></td>
                <td width="14%" align="center" class="f-b bg f-12"><? echo _("所属项目")?></td>
                <td width="17%" align="center" class="f-b bg f-12"><? echo _("使用产品")?></td>
                <td width="9%" align="center" class="f-b bg f-12"><? echo _("手机号码")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("余额")?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("开始时间")?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("结束时间")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("状态")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("在线")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("报修")?></td>
                <td width="6%" align="center" class="f-b bg f-12"><? echo _("操作")?></td>
              </tr>
        </thead>	     
        <tbody>  
<?php
$sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].")";
if($UserName){
	$sql .=" and u.UserName ='".$UserName."'";//like '%%'
}
$sql .=" order by ID desc";
$result=$db->select_all("u.*,a.orderID,a.closing,p.name as product_name,p.ID as PID,p.period,p.type,p.timetype,p.periodtime","userinfo as u,userattribute as a,orderinfo as o,product as p",$sql,20);
 
	if(is_array($result)){
		foreach($result as $key=>$rs){ 
			$sRs=$db->select_one("begindatetime,enddatetime","userrun","userID='".$rs["ID"]."' and orderID='".$rs["orderID"]."'");
			$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0  and enddatetime != '00-00-00 00:00:00' order by enddatetime desc");
			$oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
			$rRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)"); 
			$EndDate=$sRs["enddatetime"];
			$Ptype  = $rs["type"];
			if($waitOrderRs){
				$EndDate=$waitOrderRs["enddatetime"];
			} 
			 //订单结束时间
			 $period=$rs['period'];//周期
				 if($rs['type']=='hour'){  
				 
				    $endPeriod=strtotime("$EndDate +1 month");//产品结束后一个周期时间点(可续费时间结束时间点)
				 }else if($rs['type']=='day'){
				    $endPeriod=strtotime("$EndDate +$period day");//产品结束后一个周期时间点(可续费时间结束时 间点)
					
				 }else if($rs['type']=='month'){
				 
				    $endPeriod=strtotime("$EndDate +$period month"); 
				 }else if($rs['type']=='year'){ 
				 
				    $endPeriod=strtotime("$EndDate +$period year"); 
				 }elseif($rs['type']=="flow"){ 
					if($rs['timetype']=="days"){
						$endPeriod = strtotime($EndDate." +".$rs['periodtime']." day"); //流量包天用户
					}else if($rs['timetype']=="months"){
						$endPeriod = strtotime($EndDate." +".$rs['periodtime']." month");//流量包月用户
					}  
		    	}  
			//帐户状态
			unset($intval);
			$intval = mysqlDatediff($EndDate,date("Y-m-d",time()));	
			if($sRs["enddatetime"]=="0000-00-00 00:00:00"){
				$intval=16;
			}
			if($intval > 15){
				$Status = "<img src=\"images/green.png\" alt='". _("帐户正常")."'/>";
			}else if($intval >=0) {
				$Status = "<img src=\"images/yellow.png\" alt='". _("即将到")."'/>";
			}else{
				$Status = "<img src=\"images/red.png\" alt='". _("已经到期")."'/>";
			}
			unset($repair);
			//报修改状态
			if($rRs["status"]==1){
				$repair = "<img src=\"images/red.png\" alt='". _("报修")."'/>";
			}else if($rRs["status"]==2) {
				$repair = "<img src=\"images/yellow.png\" alt='". _("处理")."'/>";
			}else{
				$repair = "<img src=\"images/green.png\" alt='". _("正常")."'/>";
			}
			if($oRs >0){
				$online = "<img src=\"images/online.png\" alt='". _("在线")."'/>";
			}else{
				$online = "<img src=\"images/offline.png\" alt='". _("离线")."'/>";
			}
			$begin_date =($sRs["begindatetime"]!="0000-00-00 00:00:00")?mysqlShowDate($sRs["begindatetime"]):"0000-00-00";
			$end_date   =($EndDate!="0000-00-00 00:00:00")?mysqlShowDate($EndDate):"0000-00-00";
?>   
		  <tr>
		    <td align="center" class="bg"><input type="checkbox" name="ID" value="<?=$rs["ID"]?>"></td>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>       
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs["product_name"]?></td>
			<td align="center" class="bg"><?=$rs["mobile"]?></td>
			<td align="center" class="bg"><?=$rs["money"]?></td>
			<td align="center" class="bg"><?=$begin_date?></td>
			<td align="center" class="bg"><?=$end_date?></td>
			<td align="center" class="bg"><?=$Status?></td>
			<td align="center" class="bg"><?=$online?></td>
			<td align="center" class="bg"><?=$repair?></td>
			<td align="center" class="bg">
			  <a  href="user_edit.php?ID=<?=$rs['ID'];?>" title="<? echo _("修改")?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
			  <a  href="user_del.php?ID=<?=$rs['ID'];?>" title="<? echo _("删除")?>"><img src="images/del.png" width="12" height="12" border="0" /></a>	
			   		</td>
		  </tr>
<?php  }}?>
        </tbody>      
    </table>
<?php  }


	//判断运行表里该用户是否有 等待运行的产品 即 userrun 表中字段 status==0  如果存在则不允许续费 反之则可
    $run=$db->select_count("userrun as r,userinfo as u","u.ID=r.userID and u.userName='".$UserName."' and r.status in (0,1)"); 
	if($run)  $orderCount = $run;  else $orderCount =0; 
	
	$projectRs=$db->select_one('u.projectID,u.areaID ,p.device',"userinfo as u,project as p","UserName='".$UserName."' and p.ID=u.projectID");
  $projectID=$projectRs['projectID']; 
  $areaID   =$projectRs['areaID'];
  $projectDevice=$projectRs['device'];  
  ?>
<input type="hidden" value="<?=$result[0]['PID']?>" name="old_productID" >
	  <input type="hidden" value="<?=$orderCount?>" name="orderCount" id ="orderCount">  
	  <input type="hidden" value="<?=$projectID.",".$areaID?>" name="userProjectID" id ="userProjectID">
	  <input type="hidden" value="<?=$projectDevice?>" name="projectDevice" id ="projectDevice">  
	  <input type="hidden" border=<?=$Ptype?> name="Ptype" id="Ptype" >
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
          <tbody>
            <tr>
              <td width="13%" align="right" class="bg"><? echo _("用户帐号")?>:</td>
              <td width="87%" align="left" class="line-20 bg"><input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='inpu   t_out';ajaxInput('ajax_check.php','order_check_account','account','accountTXT');showOrderAdd(this);" class="input_out" value="<?=$UserName?>">
                <span id="accountTXT"></span></td>
            </tr>  
            <tr>
              <td align="right" class="bg"><? echo _("是否更换产品")?>:</td>
              <td align="left" class="bg">
			  <input type="radio" name="isrechange" value="no" checked="checked" onClick="product_isrechange();ajaxInput('ajax_check.php','productIDPeriod','old_productID','productPeriodTXT');" onChange="totalMoneys()"><? echo _("暂不")?> 
			  <input type="radio" name="isrechange" value="yes" onClick="product_isrechange();ajaxInput('ajax/project.php','userProjectID','userProjectID','productSelectDIV');" onChange="totalMoneys()" onBlur="ajaxInput('ajax_check.php','productIDPeriod','old_productID','productPeriodTXT');"><? echo _("更换")?> 
			  </td>
            </tr>
            <tr id="select_product_ID">
              <td align="right" class="bg"><? echo _("选择产品")?>:</td>
              <td align="left" class="bg" id="productSelectDIV"><select><option><? echo _("请选择产品");?> </option></select><? //=productSelected();?> </td>
            </tr>
		    <tr> 
		    <td align="right" class="bg">* <? echo _("续费周期")?>:</td> 
		    <td align="left" class="bg"> <span id="productPeriodTXT">
			 <? 
			    if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")){//IE
			 ?>
			  <span id="productPeriodTXT">
			    <select id='period' name='period' onClick="ajaxInput('ajax_check.php','productIDPeriod','old_productID','productPeriodTXT')" >
			    <option  value='' ><? echo _("请您选择周期") ?></option>
			    <option   value='1' selected="selected">1 </option>
			  </select>
			  </span>
			<?
			  }else{//Firefox 或其他浏览器<input type="hidden" name="usercheck" id="accountTXT">
			 // echo "firefox";
			?>   
			
			   <select id="productPeriodTXT" name='period' onFocus="ajaxInput('ajax_check.php','productIDPeriod','old_productID','productPeriodTXT')" >
			   <option  value='' ><? echo _("请您选择周期") ?></option>
			   <option   value='1' selected="selected">1</option>
			   </select> 
			   <input name="installchargeold" id="installchargeold" type="hidden" value="">
			   <input type="hidden" value="disable" name="status" id="status" > 
			<? 
			} 
			?>  </span><? echo _("如：购买包一月订单，续费3周期，即生成3张订单。开始时间为当前指定时间，下一订单的开始时间为上一订单的结束时间。")?> </td> 
		    </tr> 
			<tr>
		    <td align="right" class="bg"><? echo _("缴费方式")?>:</td>
		    <td align="left" class="bg"><?=rechangType();?> 
		   	</td>
	       </tr> 
		 <tr id="TR_card_num" style="display:none">
		    <td align="right" class="bg"><? echo _("充值卡号:")?> </td>
		    <td align="left" class="bg" >
			<input type="text" id="card_num" name="card_num"   onFocus="this.className='input_on'"   onBlur="this.className='input_out';ajaxInput('ajax_check.php','card_num','card_num','cardTXT');" class="input_out"><span id="cardTXT"></span></td>
		 </tr>
		 <tr id="TR_card_pwd" style="display:none">
		    <td align="right" class="bg"><? echo _("充值密码:")?> </td>
		    <td align="left" class="bg" >
			<input type="text" id="card_pwd" name="card_pwd"   onFocus="this.className='input_on'"   onBlur="this.className='input_out';" class="input_out">
			<span id="cardTXT"></span></td>
		 </tr>  
		   <tr>
		    <td align="right" class="bg"><? echo _("充值金额")?>:</td>
		    <td align="left" class="bg">
		   <input name="recharge_money" type="text" class="input_out" id="recharge_money"   onFocus="this.className='input_on';totalMoneys();"  onBlur="this.className='input_out';" value="0"><font color="#FF0000">	<? echo _("扣除余额仍需支付的金额");?></font>
		   	</td>
	       </tr>	   
            <tr id="timetype_ID" >
		    <td align="right" class="bg"><? echo _("计时类型")?>:</td>
		    <td align="left" class="bg">
				<input type="radio" name="timetype" value="1"<? if($Ptype !="hour"  && $Ptype !="flow") echo "checked=\"checked\"";?> onClick="timetype_change();"><? echo _("立即计时")?>
				<input type="radio" name="timetype" value="0"<? if($Ptype =="hour" || $Ptype =="flow") echo "checked=\"checked\"";?> onClick="timetype_change();"><? echo _("上线计时")?>	<?  if($Ptype =="hour" || $Ptype =="flow") { ?>	<span style="color:red;"><? echo _("小时流量用户建议上线计时");?></span><?  }?>
					</td>
		    </tr>
		   <tr id="begindatetimeTR">
		    <td align="right" class="bg">开始时间:</td>
		    <td align="left" class="bg"><input type="text" name="begindatetime"  onFocus="HS_setDate(this)"> 
		    <? echo _("计时类型如果为“立即计时”，开始时间为空表示从当前时间开始计算");  //echo _("开始时间必须大于等于上一订单的开始时间。已完成订单：开始时间为当前时间或设定时间。等待运行订单：开始间大于等于最后等待运行时间的结束时间。");?></td>
		    </tr>
			<tr>
		    <td align="right" valign="top" class="bg"><? echo _("订单备注")?>: </td>
		    <td align="left" class="bg">
				<textarea name="remark" cols="50" rows="5"  onFocus="this.className='textarea_on'" onBlur="this.className='textarea_out';" class="textarea_out"></textarea></td>
	      </tr>
            <tr>
              <td align="right" class="bg">&nbsp;</td>
              <td align="left" class="bg">
				<input type="hidden" name="token" value="<?php echo $token?>">
				<input type="submit" id="submitBtn" value="<?php echo _("提交")?>">
			  </td>
            </tr>
          </tbody>
        </table>
      </td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>
</form>
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        营帐管理-> <strong>用户续费</strong>
      </p>
      <ul>
          <li>对终端用户进行续费操作。</li>
          <li>如果用户当前产品套餐还在使用，可以进行预续费。</li>
          <li>如果用户已经到期，可以对用户进行正常续费。</li>
          <li>预续费的产品套餐还可以进行续费取消。</li>
          <li>在产品管理里面被隐藏了的产品不能续费。</li>
      </ul>

    </div>
<!---------------------------------------------->
<script language="javascript"> 
window.onLoad=ajaxInput('ajax_check.php','order_check_account','account','accountTXT');  
window.onLoad=timetype_change();
window.onLoad=product_isrechange();
window.onload=showRechang();

function timetype_change(){
	v=document.myform.timetype;
	if(v[0].checked==true){
		document.getElementById("begindatetimeTR").style.display="";
	}else if(v[1].checked=true){
		document.getElementById("begindatetimeTR").style.display="none";
	}	
}
function product_isrechange(){ 
	v=document.myform.isrechange;
	if(v[0].checked==true){
		document.getElementById("select_product_ID").style.display="none";
		//document.getElementById("timetype_ID").style.display="none";
		//document.getElementById("begindatetimeTR").style.display="none";	
	}else{
		document.getElementById("select_product_ID").style.display="";
		//document.getElementById("timetype_ID").style.display="block";
		//document.getElementById("begindatetimeTR").style.display="block";	
	}
}
function totalMoneys(){ 
   var rechangeMoney = 0;//卡片充值金额初始值为0
   var money  = document.getElementById("money").value; //账户余额
   var period =  document.getElementById("period").value; // 续费周期
   var productPrice = document.getElementById("productPrice").value; //产品价格
   var change=document.myform.isrechange;  //是否更换产品
   var rechangeType = document.getElementById("rechange").value;//缴费方式
   
   if(change[1].checked==true){
     var productPrice = document.getElementById("changeProductPrice").value; //产品价格
   } 
   if(rechangeType == 1){//卡片充值 
         rechangeMoney = document.getElementById("rechangeMoney").value;//卡片金额
	 var total=productPrice * period - rechangeMoney - money;//除去余额和卡片充值费用 应该补交的费用
   } else  
     var total=productPrice * period - money;//除去余额应该补交的费用
   
   if(total < 0) total = 0;
   document.getElementById("recharge_money").value=total;  
} 
function showOrderAdd(v){
   val = v.value;
   window.location.href="order_add.php?UserName="+val; 
}
function showRechang(){ 
  t = document.getElementById("rechange").value;  
  if(t ==0){//现金充值 
	 document.getElementById("TR_card_num").style.display="none"; 
	 document.getElementById("TR_card_pwd").style.display="none"; 
  }else if(t==1){//卡片充值 
	 document.getElementById("TR_card_num").style.display=""; 
	 document.getElementById("TR_card_pwd").style.display="";  
  }else{ 
	 document.getElementById("TR_card_num").style.display="none"; 
	 document.getElementById("TR_card_pwd").style.display="none";  
  } 
}
</script>
</body>
</html>
<?

include_once("inc/loaduser.php");
?>
