#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="rechangelog.csv"'); 
echo "帐号,姓名,类型,金额,时间,操作人员\n";  
require_once("../inc/conn.php");mysql_query("set names utf8"); //utf8 
//********************************************设置保存的条件
   @$account       = trim($_REQUEST["account"]); 
	  @$name          = trim($_REQUEST["name"]); 
	  @$moneyMin      = trim($_REQUEST['moneyMin']);
	  @$moneyMax      = trim($_REQUEST["moneyMax"]); 
	  @$startDateTime = trim($_REQUEST["startDateTime"]);
	  @$endDateTime   = trim($_REQUEST["endDateTime"]);
	  @$operator      = $_REQUEST['operator'];  
	  @$sql="c.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
	if($account){
		$sql .=" and u.account='".$account."'";
	}
	if($name)
		$sql .=" and u.name='".$name."'";
	if($moneyMin)
		$sql .=" and c.money >='".$moneyMin."'";
	if($moneyMax)
		$sql .=" and c.money <='".$moneyMax."'";
	if($startDateTime){
		$sql .=" and c.adddatetime>='".$startDateTime."'";
	}
	if($endDateTime){
		$sql .=" and c.adddatetime<'".$endDateTime."'";
	}
	if($operator){
		$sql .=" and c.operator='".$operator."'";
	}
@$result=$db->select_all("c.*,u.*,c.ID as creditID,c.money as creditMoney,c.adddatetime as rechargetime","credit as c,userinfo as u",$sql);
	if(is_array($result)){
		$row=2;
		foreach($result as $key=>$rs){
		if($rs["type"]==0){
			$type=_("开户预存");
		}else if($rs["type"]==1){
			$type=_("前台续费");
		}else if($rs["type"]==2){
			$type=_("卡片充值");
		}  
		echo "'".$rs["UserName"].","."'".$rs["name"].",".$type.","."'".$rs["creditMoney"].","."'".$rs["rechargetime"].","."'".$rs["operator"]."\n";  
	}
}



?>

