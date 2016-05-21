#!/bin/php
<?php
date_default_timezone_set('Asia/Shanghai');
@session_start(); 
include("inc/scan_conn.php");
require_once("evn.php");
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("无标题文档")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css"> 
</head>
<script type="text/javascript">
 
</script> 
<?  
 if($_GET['UserName']){
	$UserName = $_GET['UserName']; 
	$userID   = $_GET["ID"]; 
	$manager  = $_SESSION['manager'];
	$repaireID=$_REQUEST["repaireID"]; 
	$managerName = getOperateUserName($manager); 
	$rs2 =$db->select_one("*","ticket","0=0 limit 0,1"); 
	$Service_Call = $rs2["tel"];
	if($_GET['action']=="now"){//添加工单信息时打印票据
	  $rs   =$db->select_one("*","repair","UserName='".$UserName."' and  operator = '".$manager."' order by ID desc  limit 0,1"); 
		$userInfo =$db->select_one("*","userinfo","UserName = '".$UserName."' and ID='".$userID."'"); 
		$name     = $userInfo['name'];
		$tel  = $userInfo["mobile"]. "&nbsp;&nbsp;" .$userInfo["homephone"]."&nbsp;&nbsp;".$userInfo['workphone'];
	  $addr = $userInfo["address"];	
		$pj   = projectShow($userInfo["projectID"]);
		$reason = $rs['reason'];
		$addTime = $rs["startdatetime"];  
	}if($_GET['action']=="show"){//工单浏览打印票据
	  $rs   =$db->select_one("*","repair","UserName='".$UserName."' and  operator = '".$manager."' and ID='".$repaireID."'"); 
		$userInfo =$db->select_one("*","userinfo","UserName = '".$UserName."' and ID='".$userID."'"); 
		$name     = $userInfo['name'];
		$tel  = $userInfo["mobile"]. "&nbsp;&nbsp;" .$userInfo["homephone"]."&nbsp;&nbsp;".$userInfo['workphone'];
	  $addr = $userInfo["address"];	
		$pj   = projectShow($userInfo["projectID"]);
		$reason = $rs['reason'];
		$addTime = $rs["startdatetime"]; 
	    
	} 
	if($rs['type']==1)  $type = '报装';
	elseif($rs['type']==2)  $type = '报修';
	elseif($rs['type']==3)  $type = '其他';
}
?>

<style type="text/css">
<!--
.STYLE4 {
	font-size: <?=$rs2["fontsize"]?>px;
	line-height:<?=$rs2["lineheight"]?>px;
}
.STYLE6 {
font-size: <?=$rs2["tfontsize"]?>px;
/*line-height:50px;*/
}
-->
</style>
<body  style="overflow-x:hidden;overflow-y:auto">
<table width="500<? //=$rs2["tbwidth"]?>mm" height="200<? //=$rs2["tbheight"]?>mm" border="0" align="center" cellpadding="6" cellspacing="0" bordercolor="#8DB2E3" class="bd">
          <tr id='GroupName_tr'>
            <td colspan="4" align="center" valign='top' class='bd_b STYLE6'>工单记录票据</td>
          </tr>
          <tr id='GroupName_tr'>
            <td align="right"  class='bd_b STYLE4'>制表人员：</td>
            <td align="left" class='bd_b bd_l STYLE4'>&nbsp;<?=$managerName?></td>
            <td align="right" class='bd_b bd_l STYLE4'>制表日期：</td>
            <td align="left" class='bd_b bd_l STYLE4'>
              <?=date("Y",time());?>年<?=date("m",time());?>月<?=date("j",time());?>日 &nbsp;</td>
          </tr>
          <tr id='GroupName_tr'>
            <td align="right" class='bd_b STYLE4'>用户账号：</td>
            <td align="left" class='bd_b bd_l STYLE4'>&nbsp;<?=$UserName?></td>
            <td align="right" class='bd_b bd_l STYLE4'>客户名称：</td>
            <td align="left" class='bd_b bd_l STYLE4'>&nbsp;<?=$name?></td>
          </tr> 
		  <tr id='GroupName_tr'>
            <td align="right"class='bd_b STYLE4'>客户电话：</td>
            <td align="left" class='bd_b bd_l STYLE4' colspan="3">&nbsp;<?=$tel?></td> 
          </tr> 
          <tr id='GroupName_tr'>
            <td align="right"class='bd_b STYLE4'>客户地址：</td>
            <td align="left" class='bd_b bd_l STYLE4' colspan="3">&nbsp;<?=$addr?></td> 
          </tr> 
		  <tr id='GroupName_tr'>
            <td align="right" class='bd_b STYLE4'>工单类型：</td>
            <td align="left" class='bd_b bd_l STYLE4' colspan="3">&nbsp;<?=$type?></td> 
          </tr>
		  <tr id='GroupName_tr'>
            <td align="right" class='bd_b STYLE4'>保修原因：</td>
            <td align="left" class='bd_b bd_l STYLE4' colspan="3">&nbsp;<?=$reason?></td> 
          </tr> 
		  <tr id='GroupName_tr'>
            <td align="right" class='bd_b STYLE4'>备&nbsp;&nbsp;注：</td>
            <td align="left" class='bd_b bd_l STYLE4' colspan="3">&nbsp; </td>
          </tr> 
		   <tr id='Framed_IP_Address_tr'>
            <td align="right" class='bd_b STYLE4'>客服电话：</td>
            <td align="left" class='bd_b bd_l STYLE4'>&nbsp;<?=$Service_Call?></td>
            <td align="right" class='bd_b bd_l STYLE4'>用户<a href="javascript:print();" class="STYLE7 STYLE4">确认</a>：</td>
            <td align="left" class='bd_b bd_l STYLE4'>&nbsp;</td>
          </tr> 
</table>
</body>
</html>
