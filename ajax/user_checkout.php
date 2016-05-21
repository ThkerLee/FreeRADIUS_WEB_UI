#!/bin/php
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-ridate");
header("Pragma: no-cache");
include_once("../inc/scan_conn.php"); 
@include_once("inc/ajax_js.php");


if ( file_exists("/etc/LANG" ) ) {
  $lang = trim(file_get_contents("/etc/LANG"));
} else {
  $lang = "zh_CN";
}
putenv("LANG={$lang}");
setlocale(LC_ALL,'$lang');
bindtextdomain("greetings", "../locale/");  
textdomain("greetings"); 

$UserName=$_GET["UserName"];
$rs   =$db->select_one("*","userinfo","UserName='".$UserName."'");
$ipRs =$db->select_one("*","radreply","UserName='".$UserName."' and Attribute='Framed-IP-Address'");
$aRs  =$db->select_one("macbind,onlinenum,pause","userattribute","userID='".$rs["ID"]."'");
$sRs  =$db->select_one("r.enddatetime,o.productID","userrun as r ,orderinfo as o","r.orderID=o.ID and r.userID='".$rs["ID"]."' ");//正在使用的订单 and r.status in (0,1,5)
$EndDate=$sRs["enddatetime"];
$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0 ");
if($waitOrderRs){
	$EndDate=$waitOrderRs["enddatetime"];
} 
$pRs  =$db->select_one("*","product","ID='".$sRs["productID"]."'");//订单信息

if($pRs["type"]=="flow" || $pRs["type"]=="hour"){
	$cRs =$db->select_one("sum(stats) as total","runinfo","userID='".$rs["ID"]."'");
}
//统计
if($pRs["type"]=="flow"){
	$title=_("已经使用流量(kb):");
}else if($pRs["type"]=="hour"){
	$title=_("已经使用时间(秒):");
}


$sRs1 =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0");//等待运行的
$rRs1 =$db->select_all("*","radreply","UserName='".$UserName."' and Attribute='mpd-limit'");
if($rRs1){
	foreach($rRs1 as $rKey=>$r_rs){
		$r_str .=$r_rs["Value"]."<br>";
	}
}
  $oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
if($oRs >0){
	$online = "<img src=\"images/online.png\" alt='"._("在线")."'/>";
}else{
	$online = "<img src=\"images/offline.png\" alt='"._("离线")."'/>";
}
 
?>
<style type="text/css">
.f-12{
font-size:12px;
}
</style>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
        <tbody>     
		  <tr>
		    <td colspan="4" align="center" class="bg w"><? echo _("用户详细信息")?></td>
	      </tr>
		  <tr>
			<td width="25%" align="right" class="bg"><? echo _("用户帐号:")?></td>
			<td width="25%" align="left" class="bg"><?=$rs["UserName"]?></td>
		    <td width="25%" align="right" class="bg"><? echo _("帐号密码:")?></td>
		    <td width="25%" align="left" class="bg"><?=$rs["password"] ?></td>
		  </tr> 
		  <tr>
		    <td align="right" class="bg"><? echo _("所属项目:")?></td>
		    <td align="left" class="bg"><?=projectShow($rs["projectID"])?></td>
		    <td align="right" class="bg"><? echo _("用户状态:")?></td>
		    <td align="left" class="bg"><?=$status?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("用户名称")?>:</td>
		    <td align="left" class="bg"><?=$rs["name"]?></td>
		    <td align="right" class="bg"><? echo _("证件号码")?>:</td>
		    <td align="left" class="bg"><?=$rs["cardid"]?></td> 
		  </tr> 
		  <tr>
		    <td align="right" class="bg"><? echo _("收据单号")?>:</td>
		    <td align="left" class="bg"><?=$rs["receipt"]?></td>  
		  </tr>  
		  <tr id="cappingTR">
		    <td width="25%" align="right" class="bg"><? echo _("帐号类别:")?></td>
			<td width="25%" align="left"  class="bg"><? echo _("时长计费")?>  &nbsp;</td>
		    <td align="right" class="bg"><? echo _("帐号金额")?>:</td>
		    <td align="left" class="bg"><?=$rs["money"]?></td> 
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("当前使用的产品：")?></td>
		    <td align="left" class="bg"><?=productShow($sRs["productID"])?><br /><?=$r_str?></td>
		    <td align="right" class="bg"><? echo _("MAC 地址")?>:</td>
		    <td align="left" class="bg"><?=$rs["MAC"]?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg" ><? echo _("用户备注")?>:</td>
		    <td align="left" class="bg" colspan="3"><?=$rs["remark"];?></td> 
		  </tr>
		  <?php 
		  	if($cRs){ 
		  
		  ?>
		  <tr>
		    <td align="right" class="bg"><?=$title?></td>
		    <td align="left" class="bg"><?=$cRs["total"]?></td>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">&nbsp;</td>
	      </tr>
		  <?php }?>
        </tbody>      
    </table>
