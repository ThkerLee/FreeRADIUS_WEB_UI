#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>产品管理</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<body>
<?php
$ID=$_REQUEST["ID"]; 
$rs=$db->select_one("*","userinfo","ID='".$ID."'");
$ipRs=$db->select_one("*","radreply","userID='".$ID."' and Attribute='Framed-IP-Address'");
$aRs=$db->select_one("macbind,onlinenum","userattribute","userID='".$ID."'");

$sRs=$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=1");
//帐户状态
$intval = (strtotime($sRs["enddatetime"])-time())/60/60/24;
if($intval > 15){
	$status = "<img src=\"images/green.png\" alt=\"帐户正常\"/>";
}else if($intval >0) {
	$status = "<img src=\"images/yellow.png\" alt=\"即将到期\"/>";
}else{
	$status = "<img src=\"images/red.png\" alt=\"已经到期\"/>";
}


$oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
if($oRs >0){
	$online = "<img src=\"images/online.png\" alt=\"在线\"/>";
}else{
	$online = "<img src=\"images/offline.png\" alt=\"离线\"/>";
}

$rRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)");
//报修改状态
if($rRs["status"]==1){
	$repair = "<img src=\"images/red.png\" alt=\"报修\"/>";
}else if($rRs["status"]==2) {
	$repair = "<img src=\"images/yellow.png\" alt=\"处理\"/>";
}else{
	$repair = "<img src=\"images/green.png\" alt=\"正常\"/>";
}

?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">用户管理</font></td>
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="13%" class="f-bulue1"> 用户详细</td>
		<td width="81%" class="f-bulue1">
		<a href="user_dial_log.php?UserName=<?=$rs["UserName"]?>">拨号记录</a>&gt;&gt; <a href="user_edit.php?ID=<?=$ID?>">用户修改</a>&gt;&gt;  
		<a href="order_add.php?UserName=<?=$rs["UserName"]?>">用户续费</a>&gt;&gt; 
		<a href="user_shutdown.php?UserName=<?=$rs["UserName"]?>">暂停用户</a>&gt;&gt; 
		<a href="user_down_line.php?UserName=<?=$rs["UserName"]?>">用户下线</a>&gt;&gt; 
		<a href="#" onClick="javascript:window.open('user_show_print.php?UserName=<?=$rs["UserName"]?>','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no,status=no,top=100,left=300')">打印凭据</a>&gt;&gt;  
		<a href="repair_add.php?UserName=<?=$rs["UserName"]?>">用户报修</a>&gt;&gt;
		<a href="repair.php?UserName=<?=$rs["UserName"]?>">报修记录</a>&gt;&gt;
		<a href="user_replac_product.php?UserName=<?=$rs["UserName"]?>">更换产品</a>&gt;&gt;		
		<a href="user_change_banwith.php">更改带宽</a></td>
		<td width="6%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform"  onSubmit="return checkUserForm();">
<input type="hidden" name="ID" value="<?=$ID?>">
  	  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg">用户帐号：</td>
			<td width="87%" align="left" class="bg"><?=$rs["UserName"]?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg">所属项目：</td>
		    <td align="left" class="bg"><?=projectShow($rs["projectID"])?>			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">用户状态：</td>
		    <td align="left" class="bg"><?=$status?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">在线状态：</td>
		    <td align="left" class="bg"><?=$online?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">报修状态：</td>
		    <td align="left" class="bg"><?=$repair?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">用户名称： </td>
		    <td align="left" class="bg"><?=$rs["name"]?></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">证件号码：</td>
		    <td align="left" class="bg"><?=$rs["cardid"]?></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">工作电话：</td>
		    <td align="left" class="bg"><?=$rs["workphone"]?></td>
	      </tr>
		  <tr id="unitpriceTR">
		    <td align="right" class="bg">家庭电话：</td>
		    <td align="left" class="bg"><?=$rs["homephone"]?></td>
	      </tr>
		  <tr id="cappingTR">
		    <td align="right" class="bg">手机号码：</td>
		    <td align="left" class="bg"><?=$rs["mobile"]?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">电子邮件：</td>
		    <td align="left" class="bg"><?=$rs["email"]?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">联系地址：</td>
		    <td align="left" class="bg"><?=$rs["address"]?></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">帐号金额：</td>
		    <td align="left" class="bg"><?=$rs["money"]?></td>
		    </tr>
			<tr>
		    <td align="right" class="bg">在线人数：</td>
		    <td align="left" class="bg"><?=$aRs["onlinenum"]?></td>
		    </tr>
		  <tr id="ipaddressTR">
		    <td align="right" class="bg">I P 地址：</td>
		    <td align="left" class="bg"><?=$ipRs["Value"]?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg">MAC 址址： </td>
		    <td align="left" class="bg"><?=$rs["MAC"]?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="提交">			</td>
	      </tr>
        </tbody>      
    </table>	
</form>
	</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>
</body>
</html>

