#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai'); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<?php 
$UserName=$_REQUEST["UserName"];
$num     =$db->select_count("radacct","UserName='".$UserName."'");
$result  =$db->select_all("*","radacct","UserName='".$UserName."'");
?>
<body>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">
			  <? echo _("用户管理")?></font></td>
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
        <td width="93%" class="f-bulue1"><? echo _("用户拨号记录")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
              <tr>
                <td width="11%" align="center" class="bg f-b"><? echo _("主叫ID")?></td>
                <td width="14%" align="center" class="bg f-b"><? echo _("上线时间")?></td>
                <td width="14%" align="center" class="bg f-b"><? echo _("下线时间")?></td>
                <td width="14%" align="center" class="bg f-b"><? echo _("在线时间")?></td>
                <td width="12%" align="center" class="bg f-b"><? echo _("上传流量")?></td>
                <td width="10%" align="center" class="bg f-b"><? echo _("下载流量")?></td>
                <td width="13%" align="center" class="bg f-b"><? echo _("接入网关")?></td>
                <td width="12%" align="center" class="bg f-b"><? echo _("IP地址")?></td>
              </tr>

<?php
if(is_array($result)){
	foreach($result as $rs){
?>
              <tr>
                <td align="center" class="bg"><?=$rs["AcctSessionId"]?></td>
                <td align="center" class="bg"><?=$rs["AcctStartTime"]?></td>
                <td align="center" class="bg"><?=$rs["AcctStopTime"]?></td>
                <td align="center" class="bg"><?=$rs["AcctSessionTime"]?></td>
                <td align="center" class="bg"><?=$rs["AcctInputOctets"]?></td>
                <td align="center" class="bg"><?=$rs["AcctOutputOctets"]?></td>
                <td align="center" class="bg"><?=$rs["NASIPAddress"]?></td>
                <td align="center" class="bg"><?=$rs["Framed_IP_Address"]?></td>
              </tr>
<?php
	}
}
?>
      </table>
	<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
		<tr>
		    <td align="center" class="bg">
				<?php $db->page(); ?>			
			</td>
	   </tr>
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
</body>
</html>

