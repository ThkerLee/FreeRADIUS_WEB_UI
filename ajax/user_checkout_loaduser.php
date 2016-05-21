#!/bin/php
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-ridate");
header("Pragma: no-cache");
@include_once("../inc/scan_conn.php"); 
@include_once("../inc/timeOnLine.php"); 
@include_once("inc/ajax_js.php"); 
date_default_timezone_set('Asia/Shanghai'); 

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
$aRs  =$db->select_one("macbind,onlinenum,pause,status","userattribute","userID='".$rs["ID"]."'");
$sRs  =$db->select_one("r.enddatetime,o.productID,o.ID,r.balance","userrun as r ,orderinfo as o","r.orderID=o.ID and r.userID='".$rs["ID"]."' ");//正在使用的订单 and r.status in (0,1,5)

$EndDate=$sRs["enddatetime"];
$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0 ");
if($waitOrderRs){
	$EndDate=$waitOrderRs["enddatetime"];
}


$pRs  =$db->select_one("*","product","ID='".$sRs["productID"]."'");//订单信息

if($pRs["type"]=="flow" || $pRs["type"]=="hour"|| $pRs["type"]=="netbar_hour"){
	$cRs =$db->select_one("sum(stats) as total","runinfo as r ,userattribute as att","r.userID='".$rs["ID"]."' and r.orderID =att.orderID");
}
//统计
if($pRs["type"]=="flow"){
	$title=_("已经使用流量(kb):");
}else if($pRs["type"]=="hour"){
	$title=_("已经使用时间(秒):");
}
//用户余额
$money = $rs['money'] - $sRs["balance"];
if($money<0)       $type =_('补交费用:').abs($money)."."; 
else if($money>0)  $type =_('退还费用:').abs($money).".";
else if($money==0) $type =_('不设找补.'); 

$sRs1 =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0");//等待运行的
$rRs1 =$db->select_all("*","radreply","UserName='".$UserName."' and Attribute='mpd-limit'");
if($rRs1){
	foreach($rRs1 as $rKey=>$r_rs){
		$r_str .=$r_rs["Value"]."<br>";
	}
}
//帐户状态
$intval = (strtotime($EndDate)-time())/60/60/24;
if($sRs["enddatetime"]=='0000-00-00 00:00:00' || $EndDate =='0000-00-00 00:00:00'  ){
$intval=16;
}
/*
if($intval > 15){
	$status = "<img src=\"images/green.png\" alt='"._("帐户正常")."'/>";
}else if($intval >0) {
	$status = "<img src=\"images/yellow.png\" alt='"._("即将到期")."'/>";
}else{
	$status = "<img src=\"images/red.png\" alt='"._("已经到期")."'/>";
}*/
if($aRs["status"]==4)
  $status = "<img src=\"images/red.png\" alt='"._("已经到期")."'/>";
else
	$status = "<img src=\"images/green.png\" alt='"._("帐户正常")."'/>";

$oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
if($oRs >0){
	$online = "<img src=\"images/online.png\" alt='"._("在线")."'/>";
}else{
	$online = "<img src=\"images/offline.png\" alt='"._("离线")."'/>";
}

$rRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)");
//报修改状态
if($rRs["status"]==1){
	$repair = "<img src=\"images/red.png\" alt='"._("报修")."'/>";
}else if($rRs["status"]==2) {
	$repair = "<img src=\"images/yellow.png\" alt='"._("处理")."'/>";
}else{
	$repair = "<img src=\"images/green.png\" alt='"._("正常")."'/>";
}

//$pause=($aRs["pause"]==1)?"<a href='user_shutdown.php?action=restore&UserName=".$rs["UserName"]."'><font color='#ff0000'>". _("暂停中-点击恢复")."</font>":"<a href='user_shutdown.php?action=pause&UserName=".$rs["UserName"]."'>"._("正常-点击立即暂停")."</a>"; 我的暂停到暂停页面  收取费用

$pause=($aRs["pause"]==1)?"<a href='pause.php?action=restore&ID=".$rs["ID"]."'><font color='#ff0000'>". _("暂停中-点击恢复")."</font>":"<a href='pause.php?action=pause&ID=".$rs["ID"]."'>"._("正常-点击立即暂停")."</a>";//这是立即暂停  不用收取用户的暂停费用  且时间不会像后推移

?>
<style type="text/css">
.f-12{
font-size:12px;
}
</style>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
        <tbody>     
		  <tr>
		<td colspan="4" align="center" class="bg w">
	<a href="operate_netplay_log.php?UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("上网记录")?></a>&gt;&gt; 
		<a href="user_rewrite.php?UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("用户重建")?></a>&gt;&gt; 
		<a href="user_edit.php?ID=<?=$rs["ID"]?>" class="f-12"><? echo _("用户修改")?></a>&gt;&gt;  
		<a href="order_add.php?UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("用户续费")?></a>&gt;&gt; 
		<a href="user_shutdown.php?action=pause&UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("停机保号")?></a>&gt;&gt; <!--暂停用户-->
		<a href="user_show_passwprd.php?UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("查看密码")?></a>&gt;&gt; 
		<a href="user_down_line.php?UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("用户下线")?></a>&gt;&gt; 
		
		  
		<? echo _("打印");?>(<a href="#" onClick="javascript:window.open('user_show_print.php?UserName=<?=$rs["UserName"]?>&action=10','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no,status=no,top=100,left=300')"><? echo _("未结束订单");?></a> ||   
 
		<a href="#" onClick="javascript:window.open('user_show_print.php?UserName=<?=$rs["UserName"]?>&action=1','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no,status=no,top=100,left=300')"><? echo _("当前订单")?></a>|| 
		
		<a href="#" onClick="javascript:window.open('user_show_print.php?UserName=<?=$rs["UserName"]?>&action=0','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no,status=no,top=100,left=300')"><? echo _("等待运行订单")?></a>|| 
		
		<a href="#" onClick="javascript:window.open('user_show_print.php?UserName=<?=$rs["UserName"]?>&action=all','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no,status=no,top=100,left=300')"><? echo _("所有订单")."</a>)". _("凭据")?>
		
		<a href="repair_add.php?UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("用户报修")?></a>&gt;&gt;
		<a href="user_move.php?UserName=<?=$rs["UserName"]?>&ID=<?=$rs["ID"]?>" class="f-12"><? echo _("用户移机")?></a>&gt;&gt;
		 <a href="user_replac_product.php?UserName=<?=$rs["UserName"]?>&productID=<?=$sRs["productID"]?>&userID=<?=$rs["ID"]?>" class="f-12"><? echo _("更换产品")?></a> &gt;&gt; 
		
		<a href="user_flow_monitor.php?UserName=<?=$rs["UserName"]?>" class="f-12"><? echo _("流量时间")?></a> &gt;&gt;
		
		<!--<a href="user_hour_record.php?UserName=<?=$rs["UserName"]?>" class="f-12">时间</a> &gt;&gt;-->
		
		<a href="user_change_banwith.php?UserName=<?=$rs["UserName"]?>&ID=<?=$rs["ID"]?>"><? echo _("更改带宽")?></a>
		&gt;&gt;			
		<a href="order.php?UserName=<?=$rs["UserName"]?>&ID=<?=$rs["ID"]?>"><? echo _("订单记录")?></a>
		<!--
		<? if ($rs['checkout']!=1) {?>
		&gt;&gt;			
		<a href="#"onClick="javascript: if(confirm('<?=$type?>确定要结账吗?')){javascript:window.location.reload(); javascript:window.open('user_checkout.php?UserName=<?=$rs["UserName"]?>&action=netbar_checkout&orderID=<?=$sRs['ID']?>','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no,status=no,top=100,left=300');}"><? echo _("结账")?></a>
		<? } ?>
		-->
		</td>
	      </tr>
		  <tr>
			  <td width="25%" align="right" class="bg"><? echo _("用户帐号:")?></td>
			  <td width="25%" align="left" class="bg"><?=$rs["UserName"]?></td> 
		    <td align="right" class="bg"><? echo _("所属项目:")?></td>
		    <td align="left" class="bg"><?=projectShow($rs["projectID"])?></td> 
		  </tr> 
		  <tr>
		  	<td align="right" class="bg"><? echo _("用户名称")?>:</td>
		    <td align="left" class="bg"><?=$rs["name"]?></td>
		    <td align="right" class="bg"><? echo _("用户状态:")?></td>
		    <td align="left" class="bg"><?=$status?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("证件号码")?>:</td>
		    <td align="left" class="bg"><?=$rs["cardid"]?></td> 
		    <td align="right" class="bg"><? echo _("在线状态:")?></td>
		    <td align="left" class="bg"><?=$online?></td>
		  </tr>
		  <tr>
		  	<td align="right" class="bg"><? echo _("收据单号")?>:</td>
		    <td align="left" class="bg"><?=$rs["receipt"]?></td>  
		    <td align="right" class="bg"><? echo _("报修状态:")?></td>
		    <td align="left" class="bg"><?=$repair?></td>
		  </tr>
		  <tr>
		      <td align="right" class="bg"><? echo _("在线人数")?>:</td>
	        <td align="left" class="bg"><?=$aRs["onlinenum"]?></td>
		      <td align="right" class="bg"><? echo _("上网时长")?>:</td>
	        <td align="left" class="bg"><?=online($cRs["total"])?></td> 
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
