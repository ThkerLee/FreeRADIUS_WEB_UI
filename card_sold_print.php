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
<title><? echo _("卡片打印");?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
$ID=$_REQUEST["ID"];
$ID=explode("-",$ID);
$rs2 =$db->select_one("*","ticket","0=0 limit 0,1");
if(is_array($ID)){
	foreach($ID as $vID){
		$rs=$db->select_one("*","card","ID='".$vID."'");
?>
<table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
  <tr>
    <td width="81" align="right" class="bg"><? echo _("卡&nbsp;&nbsp;&nbsp;&nbsp;号");?>：</td>
    <td width="296" class="bg"><?=$rs["cardNumber"]?></td>
  </tr>
  <tr>
    <td align="right" class="bg"><? echo _("密&nbsp;&nbsp;&nbsp;&nbsp;码");?>：</td>
    <td class="bg"><?=$rs["actviation"]?></td>
  </tr>
  <tr>
    <td align="right" class="bg"><? echo _("金&nbsp;&nbsp;&nbsp;&nbsp;额");?>：</td>
    <td class="bg"><?=$rs["money"]?> ￥</td>
  </tr>
  <tr>
    <td align="right" class="bg"><? echo _("客户电话");?>：</td>
    <td class="bg"><?=$rs2["tel"]?></td>
  </tr>
</table>
<br>
<br>

<?php 
	}
}
?>
<table width="400" border="0" align="center" cellpadding="5" cellspacing="1">
	<tr><td><a href="javascript:print();" class="STYLE2"><? echo _("确认");?></a></td></tr>

</table>

</body>
</html>

