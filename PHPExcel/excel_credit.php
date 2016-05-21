#!/bin/php
<?php     
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="rechangelog.csv"'); 
echo "帐号,姓名,类型,金额,时间,操作人员\n";  
require_once("../inc/conn.php");mysql_query("set names utf8"); //utf8
//********************************************设置保存的条件 
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$operator		  =$_REQUEST["operator"];
@$projectID		  =$_REQUEST["projectID"];
@$action          =$_REQUEST["action"];
@$sql=" c.userID=u.ID and  u.projectID in (".$_SESSION["auth_project"].")";
if($startDateTime){
	$sql .=" and c.adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and c.adddatetime<'".$endDateTime."'";
}
if($operator){
	$sql .=" and c.operator='".$operator."'";
}
if($projectID){
	$sql .=" and u.projectID='".$projectID."'";
}
    $sql .=" order by u.UserName desc";
@$result  =$db->select_all("c.*,u.UserName,u.name","credit as c,userinfo as u",$sql);

if(is_array($result)){
	$row=2;
	foreach($result as $rs){
		//查询出用户的IP地址
		if($rs["type"]== 1){
			$type="用户续费";
		}elseif($rs["type"]== 0 ){
			$type="开户预存";
		}else if($rs["type"]==2){
                        $type=_("卡片充值");
                }else if($rs["type"]==3){
                        $type=_("用户移机");
                }  else if($rs["type"]==9){
                        $type=_("支付宝充值");
                }elseif ($rs["type"]==10) {
                        $type=_("用户过户");
                }elseif ($rs["type"]==11) {
                            $type=_("收取押金");
                                }
	  echo "'".$rs["UserName"].","."'".$rs["name"].",".$type.","."'".$rs["money"].","."'".$rs["adddatetime"].","."'".$rs["operator"]."\n";  

	}
} 
?>

