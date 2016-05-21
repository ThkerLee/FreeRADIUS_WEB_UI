#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="normalinfo.csv"'); 
echo "帐号,姓名,类型,金额,收费人员,收费时间,账单备注\n";  
require_once("../inc/conn.php");mysql_query("set names utf8"); //
//********************************************设置保存的条件
@$UserName        =$_REQUEST["account"];
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$projectID		    =$_REQUEST["projectID"]; 
@$operator        =$_REQUEST["operator"];
@$type            =$_REQUEST["type"]; 
$nowTime  = date("Y-m-d H:i:s",time());  
$sql="b.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";  
if($UserName) $sql .=" and u.account like '%".$UserName."%'"; 
if($startDateTime) $sql .=" and b.adddatetime>='".$startDateTime."'";
if($endDateTime)$sql .=" and b.adddatetime<'".$endDateTime."'";
if($operator)$sql .=" and b.operator='".$operator."'";
if($type!="change" && isset($type) && $type!='' )$sql .=" and b.type='".$type."'";
if($projectID)	$sql .=" and u.projectID='".$projectID."'";
$sql .=" order by billID desc"; 
$result=$db->select_all("b.*,b.money as billMoney,b.remark as bRemark,b.ID as billID,u.*,b.adddatetime as badddatetime","userbill as b,userinfo as u",$sql);
 if(is_array($result)){
		foreach($result as $key=>$rs){   
			 
 /**
 * 根据传入的订单运行编号，1，2，3，4，返回当前在线状态
 *
 * @param resource product $value
 * @return true or false
 */  
  $numarray=array("a","b","c",0,1,2,3,4,5,6,7,8);//userbil表1-9用户账单 收费情况
  if($rs['type']=="0") $type="开户预存";
  else if($rs['type']=="1") $type="前台续费";
  else if($rs['type']=="2") $type="卡片充值";
  else if($rs['type']=="3") $type="订单退还";
  else if($rs['type']=="4") $type="订单扣费";
  else if($rs['type']=="5") $type="销户退费";
  else if($rs['type']=="6") $type="用户冲帐";
  else if($rs['type']=="7") $type="用户移机";
  else if($rs['type']=="8") $type="安装费用";
  else if($rs['type']=="a") $type="违约金";
  else if($rs['type']=="b") $type="暂停费用";
  else if($rs['type']=="c") $type="暂停恢复退费";
  else{
   $MTC=$db->select_one("name","finance","type='".$rs['type']."'"); 
	  if($MTC){
		  foreach($MTC as $name){ 
		     $type=$name;
		 }//end foreach 
	  }else  $type = "未知"; 
   }  

		 echo "'".$rs["UserName"].",'".$rs["name"].",'".$type.",'".$rs["billMoney"].",".$rs["operator"].",'".$rs["badddatetime"].",'".$rs["bRemark"]."\n";   
		}
} 
?>

