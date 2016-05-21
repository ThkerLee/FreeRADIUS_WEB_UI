#!/bin/php
<?php  
include("inc/conn.php");
require_once("evn.php"); 
require_once("inc/timeOnLine.php");  
date_default_timezone_set('Asia/Shanghai');  
 
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("无标题文档")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css"> 
</head>
<script type="text/javascript">
/*
$(document).ready(function() {
	$("#userinfolist").tableSorter({
		stripingRowClass: ['tr_bg','even'],	// Class names for striping supplyed as a array.
		stripRowsOnStartUp: true		// Strip rows on tableSorter init.
	});
});

 SELECT r.begindatetime,r.enddatetime,r.orderenddatetime,p.type,p.name,p.price,r.status,r.userID,o.ID as ordeid
  FROM userrun as r,product as p,userattribute as att,orderinfo as o 
  WHERE att.userID=r.userID and r.userID='4433' and o.userID=att.userID and p.ID=o.productID  and r.status=1 
  
*/
</script> 
<?php  
 if($_GET){
	$UserName= $_GET['UserName'];
	$action =$_GET["action"]; 
	$rs  =$db->select_one("*","userinfo","UserName='".$UserName."'");
	$rs2 =$db->select_one("*","ticket","0=0 limit 0,1");  
	 if($action=='netbar_checkout'){//结账 
	 //执行下线动作
	 include('inc/scan_down_line.php');
       //--------在t.php记录下线记录2014.03.17----------
               $file = fopen('t.php','a');
               $name="user_checkout.php*执行下线动作";
               $time=date("Y-m-d H:i:s",time())."||";
               fwrite($file,$name.$time);
               fclose($file);
        //-----------------------------------------------
	 $orderID = $_GET["orderID"];
	   //修改参数
	 if($rs['checkout']!=1) 
   $db->query("update userrun set status=4,enddatetime='".date("Y-m-d H:i:s",time())."',orderenddatetime='".date("Y-m-d H:i:s",time())."' where orderID='".$orderID."'"); 
      
	 $db->query("update orderinfo set status=4 where ID='".$orderID."'");  
	 $db->query("update userattribute set status=4,stop=1 where orderID='".$orderID."' "); 
	   //结账标识
	 $db->query("update userinfo set checkout=1,money = 0 where  UserName='".$UserName."' "); 
	 $attr = $db->select_one("sum(r.stats) as totalNum","userattribute as a,runinfo as r","a.UserName='".$UserName."' and a.orderID = r.orderID "); 
	 $run  = $db->select_one("begindatetime ,enddatetime,userID","userrun","orderID='".$orderID."'");
	 $userID = $run["userID"]; 
	 $addTime = $rs['adddatetime'];//创建时间
	 $beginTime =$run["begindatetime"]; //订单开始时间
	 $endTime    =$run["enddatetime"];  //订单结束时间 
	 if($rs && $rs['totalNum']>0)  $onlineTime = day($rs['totalNum']);
	 else $onlineTime =0; 
	 $money =(int)$rs['money'];//余额，正数为应返还的金额  负数为补交的金额
     if($money<0){
	    $type =_('补交费用'); 
		 //充值
		 //添加用户帐单记录，
	     addUserBillInfo($userID,"1",$money,_("时长计费补交费用"));  
		 //添加财务记录
		   addCreditInfo($userID,"1",$money);
	 }else if($money>0){
	    $type =_('退还费用');
		//冲账
		//添加用户帐单记录，
	    addUserBillInfo($userID,"6",$money,_("时长计退还费用"));
	    //添加财务记录
	    addOrderRefund($userID,2,$money,$money,$orderID,$_SESSION["manager"],_("时长计退还费用")); //记录用户操作记录
	    addUserLogInfo($userID,"9",_("用户冲账:").$money,getName($userID),$money);//$_SERVER['REQUEST_URI']
   
	 }else if($money==0){
	    $type =_('不设找补');
	 } 
	  $title    =($rs2["type"]=="auto")?"".projectShow($rs["projectID"])."".$rs2["name"]."":"".$rs2["name"]."";
	  $mark     =$rs2["mark"];
	  $tel  		=$rs2["tel"]; 
 }
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
line-height:50px;
}
table{
margin-bottom:<?=$rs2["tbmarginbottom"]?>px;
}
-->
</style> 
<body  style="overflow-x:hidden;overflow-y:auto">

<? 
 if($action=='netbar_checkout'){//下线票据
?>   
 <table width="<?=$rs2["tbwidth"]?>mm" height="<?=$rs2["tbheight"]?>mm" border="0" align="center" cellpadding="6" cellspacing="0" bordercolor="#8DB2E3" class="bd">
          <tr id='GroupName_tr'>
            <td colspan="4" align="center" valign='top' class='bd_b STYLE6'><?=$title?>&nbsp;</td> 
          </tr>
          <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("项目")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
              <?=projectShow($rs["projectID"])?>			  </td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("制表日期")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
              <?=date("Y",time());?><? echo _("年")?><?=date("m",time());?><? echo _("月")?><?=date("j",time());?><? echo _("日")?> &nbsp;</td>
          </tr>    
		  <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("用 户 名")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["UserName"]?>&nbsp;</td>
            <td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("客服人员")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'><?=$_SESSION['manager'];?>&nbsp;</td>
          </tr> 
		 <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("开始时间:")?></td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$beginTime?>&nbsp;</td>
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("结束时间")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$endTime ?></td>
	   </tr>
	   <tr id='GroupName_tr'> 
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("在线时间")?>:</td>
            <td align="left"  class='bd_b bd_l STYLE4'><?=$onlineTime;?></td>
			<td align="right" valign='top' class='bd_b bd_l STYLE4' style="color:red"><?=$type?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'style="color:red">
			<?  $c=new ChineseNumber();
			    echo _("￥").$rs["money"]._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($rs["money"]).")";
			?>&nbsp;
			 </td>
	    </tr>	 
        <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("备注")?>:</td>
            <td colspan="3" align="left" class='bd_b bd_l STYLE4'><?=$mark?>&nbsp;</td>
          </tr> 
          
          <tr id='Framed_IP_Address_tr'>
            <td align="right" valign='top' class="bg STYLE4"><? echo _("客服电话")?>:</td>
            <td align="left" valign='top' class="bg bd_l STYLE4"><?=$tel?>&nbsp;</td>
            <td align="right" valign='top' class="bg bd_l STYLE4"><? echo _("用户")?><a href="javascript:print();" class="STYLE7 STYLE4"><? echo _("确认")?></a>:</td>
            <td align="right" valign='top' class="bg bd_l STYLE4">&nbsp;</td>
          </tr>

 <? } ?>
          
</table>
</body>
</html>
