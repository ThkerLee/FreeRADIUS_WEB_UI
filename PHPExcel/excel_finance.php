#!/bin/php
<?php 
//header("content-type:text/html; charset=utf-8");
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="finance.csv"'); 
require_once("../inc/conn.php");mysql_query("set names utf8"); 
echo"编号,用户帐号,用户姓名,类型,金额,收费人员,收费时间,备注\n";
$account         =$_REQUEST["account"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$operator		     =$_REQUEST["operator"];
$type 			     =$_REQUEST["type"];
$projectID		   =$_REQUEST["projectID"];
$check           =$_REQUEST["check"]; 
$sql="b.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";  
if($account)$sql .=" and u.account like '%".$account."%'";
if($startDateTime)$sql .=" and b.adddatetime>='".$startDateTime."'";
if($endDateTime)$sql .=" and b.adddatetime<'".$endDateTime."'"; 
if($operator) $sql .=" and b.operator='".$operator."'"; 
if($type!="change" && isset($type) && $type!='' ) $sql .=" and b.type='".$type."'"; 
if($projectID) $sql .=" and u.projectID='".$projectID."'";  
if($check){
  if($check  == "check_true") $sql .=" and b.check =1";
  if($check  == "check_false") $sql .=" and b.check =0";
  if($check  == "all") $sql .=" and b.check in (0,1)";  
} 
$resultSum=$db->select_one("sum(b.money) as sumMoney","userbill as b,userinfo as u",$sql." and b.type !=4 and b.type !=8  and b.type !=3 and b.type !=6 and b.type !=5 and b.remark!='System:add user financial subjects'");  
if(! $resultSum["sumMoney"]){     
    $resultSum["sumMoney"]=0;   
}
//var_dump($resultSum);
if($type != "change" && $type != ""){
    $userbillsum=$db->select_one("sum(money)","userbill","type = '$type'");
   // echo '$type:'.$type;
    if($type == 5 || $type == 6){
        $sum=$resultSum["sumMoney"]-$userbillsum['sum(money)'];
    }else{
        $sum=$resultSum["sumMoney"];
    }
}else{
$MoneySql="(type = 5 or type = 6) ";
if($startDateTime)$MoneySql .=" and adddatetime>='".$startDateTime."'";
if($endDateTime)$MoneySql .=" and adddatetime<'".$endDateTime."'"; 
if($operator) $MoneySql .=" and operator='".$operator."'"; 
if($type!="change" && isset($type) && $type!='' ) $MoneySql .=" and type='".$type."'"; 
     $userbillsum=$db->select_one("sum(money)","userbill",$MoneySql);
     //echo $userbillsum['sum(money)'];
    $sum=$resultSum["sumMoney"]-$userbillsum['sum(money)'];   
      
   // }
}
$sql .=" and b.remark!='System:add user financial subjects'  and b.type !=4  and b.type !=3  and b.type !=8  order by billID desc";  
$result=$db->select_all("b.*,b.money as billMoney,b.remark as bRemark,b.ID as billID,b.check,u.*,b.adddatetime as badddatetime","userbill as b,userinfo as u",$sql);
if(is_array($result)){
		foreach($result as $key=>$rs){
                   switch($rs['type']){
		case "0":
			$type="开户预存";
			break;
		case "1":
			$type="前台续费";
			break;
		case "2":
			$type="卡片充值";
			break;
		case "3":
			$type="订单退还";
			break;
		case "4":
			$type="订单扣费";
			break;
		case "5":
			$type="销户退费";
			break;
		case "6":
			$type="用户冲帐";
			break;
		case "7":
			$type="用户移机";
			break;
		case "8":
			$type="安装费用";
			break;
                case "9":
			$type="支付宝充值";
			break;
		case "a":
			$type="违约金";
			break;
		case "b":
			$type="暂停费用";
			break;
	    case "c":
			$type="暂停恢复退费";
			break; 
	}
                 echo $rs['billID'].",".$rs["name"].",".$rs['UserName'].",".$type.",".$rs['billMoney'].",".$rs["operator"].","."'".$rs["badddatetime"].",".$rs["bRemark"]."\n";   
                }
}
?>


