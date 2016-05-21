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
        
        $config = $db->select_one("Name","config","0=0 limit 0,1");
	$Name = $config["Name"];
        
	if($_GET['action']=="10"){//未结束订单 正在运行订单和等待运行订单 
      $orderInfo =$db->select_all("r.begindatetime,r.enddatetime,r.status,p.type,p.name,p.price,o.receipt","userrun as r,product as p,orderinfo as o","r.userID='".$rs['ID']."' and o.userID=r.userID and o.ID = r.orderID and p.ID=o.productID  and (r.status=1 or r.status=0 ) and (o.status=1 or o.status=0 )");  
		 if(empty($orderInfo)){//无未结束订单   即显示完成订单 
			echo "<script>alert('"._("该用户已到期,无使用或等待运行订单")."');</script>";  
		 }  
	}else if($_GET['action']=="1" && !isset($_GET["orderadd"])){//打印当前订单票据 
	  $orderInfo =$db->select_all("r.begindatetime,r.enddatetime,r.status,p.type,p.name,p.price,o.receipt","userrun as r,product as p,orderinfo as o","r.userID='".$rs["ID"]."'  and p.ID=o.productID  and  r.status=1  and o.status=1   and o.userID=r.userID and o.ID = r.orderID"); 
	 
	  if(empty($orderInfo)){
			echo "<script>alert('"._("该用户已到期,无正在使用的订单")."');</script>"; 
	  } 
	}else if($_GET['action']=="0"){//打印等待运行订单票据
     $orderInfo =$db->select_all("r.begindatetime,r.enddatetime,r.status,p.type,p.name,p.price,o.receipt","userrun as r,product as p,orderinfo as o","r.userID='".$rs["ID"]."' and p.ID=o.productID  and  r.status=0 and o.status=0  and o.userID=r.userID and o.ID = r.orderID");
       
		if(empty($orderInfo)){
		 	  echo "<script>alert('"._("该用户无等待运行订单")."');</script>";
		}
	}elseif($_GET['action']=="all"){//打印所有订单  
	  $orderInfo =$db->select_all("r.begindatetime,r.enddatetime,r.status,p.type,p.name,p.price,o.receipt","userrun as r,product as p,orderinfo as o","r.userID='".$rs["ID"]."' and p.ID=o.productID  and o.userID=r.userID and o.ID = r.orderID");
	  if(isset($_GET["installcharge"]) && $_GET["installcharge"]==1){ //用户开户打印票据。。首订单显示初装费用
	     $installcharge = $db->select_one("money","userbill","userID='".$rs["ID"]."' and type = 8");
		   $chargeMoney = empty($installcharge["money"])?'0':$installcharge["money"];
	  }
	  if(isset($_GET["financeID"]) && isset($_GET["financemoney"])){//获取用户的开户及缴费类型和金额
	     $financeID    = $_GET["financeID"];
	     $financemoney = $_GET["financemoney"];
		   $financeName  = getFinanceName($financeID);
	  }
	   
	}elseif($action && isset($_GET["orderadd"])){//打印续费订单
	  $num = $action;
	  $orderInfo =$db->select_all("r.begindatetime,r.enddatetime,r.status,p.type,p.name,p.price,o.receipt","userrun as r,product as p,orderinfo as o","r.userID='".$rs["ID"]."' and p.ID=o.productID  and o.userID=r.userID and o.ID = r.orderID order by r.orderID desc limit 0,".$num .""); 
	}else if($action=="recharge"  ){//用户充值 现金  卡片充值 票据打印
	  $type  =$_GET["type"]; 
	  $money =$_GET["money"];//充值金额
	  $cardNum=$_GET["cardNum"];
	  if($type==0){//现金充值
	     $chargeType = _("现金充值");
	  }else{//卡片充值
	    $chargeType = _("卡片充值")."&nbsp;"._("卡号").$cardNum; 
	  }
	
	}elseif($action =="finance"){//人工收费
	  $type  =$_GET["type"]; 
	  $money  =$_GET["money"];//充值金额
	  $financeID =$_GET["financeID"];//人工收费目录ID
	  $financeName = getFinanceName($financeID);//获取人工收费名
	  if($type==0) $financeType= _("余额扣除"); //余额扣除
	  else if($type==1) $financeType= _("现金支付");
	  else $type= _("未知");  
	
	}elseif($action=='netbar_print'){//打印票据 
	  $attr = $db->select_one("orderID","userattribute","UserName='".$UserName."'");
	  $orderID = $attr["orderID"];
	  $orderInfo =$db->select_one("r.begindatetime,r.enddatetime,r.status,p.type,p.name,p.limittype,o.receipt","userrun as r,product as p,orderinfo as o"," p.ID=o.productID  and o.userID=r.userID and o.ID = r.orderID and r.orderID='".$orderID."'");      $addTime = $rs['adddatetime'];//创建时间
	  $beginTime =$orderInfo["begindatetime"]; //订单开始时间
	  $endTime   =$orderInfo["enddatetime"];
	  $product = $orderInfo["name"];
	  	  if($orderInfo["limittype"]==1) $limittype ="结账下机";
	  else if($orderInfo["limittype"]==2) $limittype ="自动下机";
	}elseif($action=='netbar_checkout'){//结账 
	 //执行下线动作
	 include('inc/scan_down_line.php');
          //--------在t.php记录下线记录2014.03.17----------
               $file = fopen('t.php','a');
               $name="user_show_print.php*票据";
               $time=date("Y-m-d H:i:s",time())."||";
               fwrite($file,$name.$time);
               fclose($file);
        //-----------------------------------------------
	 $orderID = $_GET["orderID"];
	   //修改参数
	   if($rs['checkout']!=1){
       $db->query("update userrun set status=4,enddatetime='".date("Y-m-d H:i:s",time())."' where orderID='".$orderID."'"); 
     }
	 $db->query("update orderinfo set status=4 where ID='".$orderID."'");  
	 $db->query("update userattribute set status=4,stop=1,orderID='".$orderID."' "); 
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
 }
    $title    =($rs2["type"]=="auto")?"".projectShow($rs["projectID"])."".$rs2["name"]."":"".$rs2["name"]."";
	  $mark     =$rs2["mark"];
	  $tel  		=$rs2["tel"]; 
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

<?php
if($orderInfo){ 
  $i=0; 
  foreach($orderInfo as $val){  
   $i++; 
   @$begindatetime=$val["begindatetime"]; 
	 @$enddatetime  =$val["enddatetime"];
	 @$type         =$val["type"];  // $price        = $db->select_one("price","product",'name="'.productTypeShow($type).'"');
	 @$price   	    =$val["price"];
	 @$productname  =$val["name"];   
	 @$status       =$val["status"]; 
	 @$receipt      =$val["receipt"];
?>

 <?php if($action=="all" || $action=='10' || $action=='0' || $action=='1' || isset($_GET['orderadd'])){ //充值票据
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
              <?=date("Y",time());?><?php echo _("年")?><?=date("m",time());?><?php echo _("月")?><?=date("j",time());?><?php echo _("日")?> &nbsp;</td>
          </tr> 
          <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("用 户 名")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["UserName"]?>&nbsp;</td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("密码")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["password"]?>&nbsp;</td>
          </tr>
          <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("客户名称")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["name"]?>&nbsp;</td>
			      <td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("客服人员")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'><?=$_SESSION['manager'];?>&nbsp;</td></td>  
          </tr>
          <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("证件号码")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["cardid"];?></td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("产品说明")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><? echo _("名称")?>:<?=$productname?>&nbsp;&nbsp;<? echo _("类型")?>:<?=productTypeShow($type)?>&nbsp;</td>
          </tr>
          <tr id='GroupName_tr'>
            <td width="16%" align="right" class='bd_b STYLE4'><? echo _("移动电话")?>:</td>
            <td width="38%" align="left" class='bd_b bd_l STYLE4'><?=$rs["mobile"];?>&nbsp; 
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("装机地址")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["address"];?></td>
		     </tr>
          <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("开始时间")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$begindatetime;?></td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("结束时间")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$enddatetime;?><!--最后订单结束时间-->
              <?php if($expired) echo " <font color=\"#FF0000\"><strong>该用户已经到期! </strong></font>"; ?> &nbsp;         </td>
          </tr> 
		 <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("订单状态")?>:</td> 
			<?php if(isset($chargeMoney) && $i==1) { ?>
			  <td align="left"  class='bd_b bd_l STYLE4'><?=orderStatus($status);?>&nbsp;</td>
			  <td align="right" class='bd_b bd_l STYLE4'><? echo _("初装费用")?>:</td>
              <td align="left" class='bd_b bd_l STYLE4'>
			  <?php 
				$c=new ChineseNumber();
				echo _("￥").$chargeMoney._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($chargeMoney).")";
			?> <!--开户首订单显示初装费用-->
			<?php }else{ ?>
			  <td align="left"  colspan="3"class='bd_b bd_l STYLE4'><?=orderStatus($status);?>&nbsp;</td> 
			<?php } ?>  
         <?php
		  if(isset($_GET["financeID"]) && is_numeric($_GET['financeID']) && $i ==1){//缴费类型
		 ?>
		  <tr id='Name_f'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("缴费名称")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$financeName;?></td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("缴费金额")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
			  <?php 
				$c=new ChineseNumber();
				echo _("￥").$financemoney._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($financemoney).")";
			?> <!--开户首订单显示初装费用-->
			&nbsp;         </td>
          </tr> 
		 
		 <?php
		  } 
		 ?>
		  
		   </tr>  
          <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("收据单号")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$receipt?>&nbsp;</td>
            <td align="right" class='bd_b bd_l STYLE4'>押金金额:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
               <?php 
				$c=new ChineseNumber();
				echo _("￥").$rs["pledgemoney"]._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($rs["pledgemoney"]).")";
		?>	&nbsp;	 
                
            </td>
          </tr>         
          </tr>  
          <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("产品金额")?>:</td>
            <td colspan="3" align="left" class='bd_b bd_l STYLE4'>
			<?php 
				$c=new ChineseNumber();
				echo _("￥").$price._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($price).")";
			?>	&nbsp;		
			
			</td>
          </tr> 
          <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("帐户余额")?>:</td>
            <td colspan="3" align="left" class='bd_b bd_l STYLE4'>
			<?php 
				echo _("￥").$rs["money"]._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($rs["money"]).")";
			?>	&nbsp;
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
<?php 
  } 
}
}
if($action=="recharge" ){//用户充值票 
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
             <td align="right" valign='top' class='bd_b STYLE4'><? echo _("客户名称")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["name"]?>&nbsp;</td>
            <td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("充值方式")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$chargeType?>&nbsp;</td> 
		   
	     </tr>
		  <tr id='GroupName_tr'> 
		   <td align="right" valign='top' class='bd_b STYLE4'><? echo _("充值金额")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
			<?php 
				$c=new ChineseNumber();
				echo _("￥").$money._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($money).")";
			?>
			</td>
            <td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("帐户余额")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'>
			<?php 
				echo _("￥").$rs["money"]._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($rs["money"]).")";
			?>	&nbsp;
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
<?php		 
 }else if($action =="finance"){//人工收费
?> <table width="<?=$rs2["tbwidth"]?>mm" height="<?=$rs2["tbheight"]?>mm" border="0" align="center" cellpadding="6" cellspacing="0" bordercolor="#8DB2E3" class="bd">
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
             <td align="right" valign='top' class='bd_b STYLE4'><? echo _("缴费方式:")?></td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$financeType?>&nbsp;</td>
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("缴费科目")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$financeName;?></td>
	   </tr>	  
		  <tr id='GroupName_tr'> 
		   <td align="right" valign='top' class='bd_b STYLE4'><? echo _("缴费金额")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
			<?php 
				$c=new ChineseNumber();
				echo _("￥").$money._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($money).")";
			?>
			</td>
            <td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("帐户余额")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'>
			<?php 
				echo _("￥").$rs["money"]._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($rs["money"]).")";
			?>	&nbsp;
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

<?php } elseif($action=='netbar_checkout'){//下线票据
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
			<?php  $c=new ChineseNumber();
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

 <?php }elseif($action=='netbar_print'){//开户票据
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
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("证件号码")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["cardid"];?>&nbsp;</td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("密码")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["password"]?>&nbsp;</td>
          </tr>
          </tr>
		 <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("开始时间:")?></td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$beginTime?>&nbsp;</td>
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("结束时间")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$endTime;?>&nbsp;</td>
	   </tr>
	   <tr id='GroupName_tr'> 
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("开户时间");?>:</td>
		    <td align="left" class='bd_b bd_l STYLE4' > <?=$addTime?>&nbsp; </td>
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("使用产品")?>:</td>
            <td align="left"  class='bd_b bd_l STYLE4'><?=$product;?>&nbsp;</td>
	    </tr>
		
		<tr id='GroupName_tr'> 
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("业务类型")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><? echo _("时长计费")?>&nbsp;</td> 
		    <td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("计费类型")?>:</td>
            <td align="left"  class='bd_b bd_l STYLE4'><?=$limittype;?>&nbsp;</td> 
	    </tr> 
		 <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("余额")?>:</td>
            <td colspan="3" align="left" class='bd_b bd_l STYLE4'> 
			<?  $c=new ChineseNumber();
			    echo _("￥").$rs["money"]._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($rs["money"]).")";
			?>
			&nbsp;</td>  
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
  <?php }elseif($action == 'reverse'){//冲账票据 2014.08.04
 	$reversemoney=$_GET['money'];
	$reversremark=$_GET["remark"];
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
             <td align="right" valign='top' class='bd_b STYLE4'><? echo _("客户名称")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["name"]?>&nbsp;</td>
            <td align="right" valign='top' class='bd_b bd_l STYLE4'>移动电话:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["mobile"];?>&nbsp;</td> 
		   
	     </tr>
		  <tr id='GroupName_tr'> 
		   <td align="right" valign='top' class='bd_b STYLE4'><? echo _("冲账金额")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
			<?php 
				$c=new ChineseNumber();
				echo _("￥-").$reversemoney._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($reversemoney).")";
			?>
			</td>
            <td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("帐户余额")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'>
			<?php 
				echo _("￥").$rs["money"]._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($rs["money"]).")";
			?>	&nbsp;
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
<?php }elseif($action == 'replac'){//产品更换票据
  			$bjmoney=$_GET['bjmoney'];
  			$replacremark=$_GET["replacremark"];
  			$checkPID=$_GET["checkPID"];
  			$re=$db->select_one("name","product","ID=$checkPID");
  	 ?>
  <table width="<?=$rs2["tbwidth"]?>mm" height="<?=$rs2["tbheight"]?>mm" border="0" align="center" cellpadding="6" cellspacing="0" bordercolor="#8DB2E3" class="bd">
          <tr id='GroupName_tr'>
            <td colspan="4" align="center" valign='top' class='bd_b STYLE6'><?=$title?>&nbsp;</td> 
          </tr>
          <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("名称")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
              <?php echo _("产品更换");?>			  </td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("制表日期")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
              <?=date("Y",time());?><? echo _("年")?><?=date("m",time());?><? echo _("月")?><?=date("j",time());?><? echo _("日")?> &nbsp;</td>
          </tr> 
		  <tr id='GroupName_tr'>
		   <td align="right" valign='top' class='bd_b STYLE4'><? echo _("用 户 名")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["UserName"]?>&nbsp;</td> 
			<td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("产品")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'><?=$re["name"] ?>&nbsp;</td>
          </tr>
		 <tr id='GroupName_tr'>
             <td align="right" valign='top' class='bd_b STYLE4'><? echo _("客户名称")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["name"]?>&nbsp;</td>
            <td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("开票人")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$_SESSION['manager'];?>&nbsp;</td> 
		   
	     </tr>
		  <tr id='GroupName_tr'> 
		   <td align="right" valign='top' class='bd_b STYLE4'><? echo _("金额")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
			<?php 
				$c=new ChineseNumber();
				echo _("￥").$bjmoney._("元")."&nbsp;&nbsp;("._("大写金额").":";
				echo $c->ParseNumber($bjmoney).")";
			?>
			</td>
            <td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("收款单位")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'>
			<?=$Name;?>
			</td> 
          </tr> 
		  <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("备注")?>:</td>
            <td colspan="3" align="left" class='bd_b bd_l STYLE4'><?=$replacremark ?>&nbsp;</td>

          <tr id='Framed_IP_Address_tr'>
            <td align="right" valign='top' class="bg STYLE4"><? echo _("客服电话")?>:</td>
            <td align="left" valign='top' class="bg bd_l STYLE4"><?=$tel?>&nbsp;</td>
            <td align="right" valign='top' class="bg bd_l STYLE4"><? echo _("用户")?><a href="javascript:print();" class="STYLE7 STYLE4"><? echo _("确认")?></a>:</td>
            <td align="right" valign='top' class="bg bd_l STYLE4">&nbsp;</td>
          </tr>
  </table>
  <?php } ?>
          
</table>
</body>
</html>
