#!/bin/php
<?
//第五步 扫描是否有到期的等待运行订单 或 是否有符合正在使用的等待运行 
  $waitOrder = $db->select_all("begindatetime,enddatetime,userID,orderID","userrun"," status = 0");  
  if($waitOrder){//存在等待运行订单
     foreach($waitOrder as $waitVal){
	         $enddatetime = $waitVal["enddatetime"];
			 $userID      = $waitVal["userID"];
			 $orderID     = $waitVal["orderID"];
	         if(strtotime($enddatetime)<= time()){ 
				sql_rs_err = $db->query("update userrun set status=4 where orderID='".$orderID."'");
			    if(!$sql_rs_err) $sql_wait_aa =false;
			    $sql_rs_err = $db->query("update orderinfo set status=4 where ID='".$orderID."'"); 
			    if(!$sql_rs_err) $sql_wait_bb =false;
			 }else if(strtotime($begindatetime) >= time() && strtotime($enddatetime)> time()){
			    $sql_rs_err = $db->query("update userrun set status=1 where orderID='".$orderID."'");
			    if(!$sql_rs_err) $sql_wait_aa =false;
			    $sql_rs_err = $db->query("update orderinfo set status=1 where ID='".$orderID."'"); 
			    if(!$sql_rs_err) $sql_wait_bb =false;
				$sql_rs_err = $db->query("update userattribute set status=1,stop=0,orderID='".$orderID."' where userID='".$userID."'"); 
			    if(!$sql_rs_err) $sql_wait_cc =false; 
			 } 
	 } 
  }
 



?>