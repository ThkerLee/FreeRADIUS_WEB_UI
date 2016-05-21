#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("更换产品")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jquery-1.4.js" type="text/javascript"></script>

</head>
<body>
<?php  

  $UserName=$_REQUEST["UserName"] ; 
  $rs=$db->select_one("u.*,u.enddatetime as uendtime,u.begindatetime as ubegintime ,o.*,p.*,p.name as productName,p.type as Ptype, o.ID as orderID","userrun as u,orderinfo as o,product as p"," u.orderID=o.ID and o.productID=p.ID and u.userID='".$_REQUEST['userID']."' and (u.status in (1,5))");//这里要计算出要退的订单
  $productID=$_REQUEST["productID"]; //当前 
  $checkProductID=$_REQUEST["checkProductOldID"];   
   if(!is_numeric($checkProductID) && $_POST){
     echo "<script>alert('"._("无与之对应的产品可更改")."');window.location.href='user.php';</script>";
     exit;
  }  
  $timeLag  =(int)(time() - strtotime($rs["uendtime"]));  //时差 = 当前时间戳 - 产品结束时间戳 如 > 0 到期
  if($timeLag > 0){    
     echo "<script>alert('"._("该产品已到期,或上线计时未曾计时用户不可更换")."');window.location.href='user.php';</script>";
     exit;
  }
  if( $rs['Ptype']=="hour" || $rs['Ptype']=="flow" ){    
     echo "<script>alert('"._("暂不支持包时、包流量产品的更换")."');window.location.href='user.php';</script>";
     exit;
  }
  if(strpos($checkProductID,";")!=false){
		$ID = explode(";",$checkProductID); 
		$productID=$ID[1];//当前使用产品编号
  }else{
	  $productID=$_REQUEST["productID"];
  }
	//echo $productID; 
 $product=$db->select_one("ID,type,period","product","ID='".$productID."'");//当前产品
if($_POST){ 
	//$account        = $_POST["account"];
	$UserName         = $_POST["account"];
	$userID           = $_POST["userID"];
	$surplusMoney     = $_POST["surplusMoney"];//产品可退余额
	$money            = $_POST['money'];//账户预存金额
	$orderID	        = $_POST["orderID"];//要修改订单编号
	$checkPID	        = $_POST["checkPID"];//更改产品编号
	$remark		        = $_POST["remark"];
	$bjmoney          = $_POST['bjmoney'];//补交费用   post 提交
	//$bjMoney          = $_POST['bjMoney'];//为负数为 产品应补费用(即扣除费用)  正数为产品退还费用
	$begindatetime    = $_POST['begindatetime'];//订单运行开始时间
	$enddatetime      = $_POST['enddatetime'];//订单运行结束时间 余额
	$newEnddatetime   = $_POST["newEnddatetime"];//几天用户更改到期时间  到期时间值
	$remark           = $_POST["remark"]; 
	$status 	      = 1;//立即执行 
  //查询当前产品与更换之后的产品的周期的对比，延长或缩短更正当前订单周期时间
 $productNewRs= $db->select_one("type,period","product","ID='".$checkPID."'");//更换后的产品
 if($product["period"] != $productNewRs["period"]){//周期不同 
    $pubegintime = $rs["ubegintime"];//当前订单开始时间
    $puendtime   = $rs["uendtime"];//当前订单结束时间 
 	  $pNewEndtime = mysqlGteDate($pubegintime,$productNewRs["period"],$productNewRs["type"]); 
 	  $timeDiff    = mysqlDatediff($pNewEndtime ,$puendtime);// 负数为应该减去的天数 正数为应该添加的天数 
 	  //更换后订单的结束时间 	 
 	 if($pNewEndtime <= date("Y-m-d H:i:s",time())){//更换后的订单的结束时间小于当前时间不予更换
 	 	  echo "<script>alert('更换后的订单结束时间".$pNewEndtime ."小于等于当前时间不允许更换');window.location.href='user.php';</script>";
 	 	  exit;
   }else{ 
   	//更换当前订单时间
   	 $db->update_new("userrun","userID=".$userID." and orderID =".$orderID." ",array("orderenddatetime"=>$pNewEndtime,"enddatetime"=>$pNewEndtime)); 
    //更换等待运行订单时间
     $orderWriteRs = $db->select_all("begindatetime,enddatetime,orderID","userrun","status =0 and begindatetime !='0000-00-00 00:00:00' and enddatetime !='0000-00-00 00:00:00' and enddatetime >'".date("Y-m-d H:i:s",time())."'"); 
     if($orderWriteRs){
      foreach($orderWriteRs as $orderRs){
      	$updateBeginDay =mysqlGteDate($orderRs["begindatetime"],$timeDiff,"day");
      	$updateEndDay   =mysqlGteDate($orderRs["enddatetime"],$timeDiff,"day");
      	$diffSql =array("begindatetime"=>$updateBeginDay,"enddatetime"=>$updateEndDay,"orderenddatetime"=>$updateEndDay );
      	$db->update_new("userrun","orderID=".$orderRs["orderID"]." and userID =".$userID,$diffSql);  
      }
     } 
   }
 } $product=$db->select_one('*',"product","ID='".$checkPID."' ");
	 
	//修改 订单表产品ID
	$upOrderinfo=array("productID"=>$checkPID);
	$db->update_new('orderinfo',"userID='".$userID."' and ID='".$orderID."'",$upOrderinfo);
	  
	//更改产品属性表    删除原来属性  添加新属性 
	$db->delete_new("radreply","userID='".$userID."'");
	//更改技术参数
	addUserParaminfo($userID,$checkPID);
	
	//记录对用户操作记录
	addUserLogInfo($userID,7,_("产品:").productShow($productID)._("[更改为]").productShow($checkPID),getName($userID),$bjmoney);	
	
	if($bjmoney>0){//补交费用 
	  //  用户余额= 余额-补交费用
 	  $totalSurplusMoney =$money - $bjmoney;// >= 0 则视为余额足以扣除  <0 视为扣除余额后，负的值为实际收费费用
 	  if($totalSurplusMoney>=0)  $upArray=array("money"=>$totalSurplusMoney);
	  elseif($totalSurplusMoney <0 ){//余额不足补交 
	  	$sybjmoney = -$totalSurplusMoney;
	   addUserBillInfo($userID,"3",$sybjmoney,'产品更补交费用'.$remark);//返还费用   产品更改退还费用 
	   addUserBillInfo($userID,"1",$sybjmoney,_("产品更补交续费费用:").$remark);//续费 
 	   addUserBillInfo($userID,"4",$sybjmoney,_("产品更改补交扣除费用:").$remark);   
	   $upArray=array("money"=>0);	
	  }
 	  //更改用户余额
    $db->update_new('userinfo',"ID='".$userID."'",$upArray);   
	}else{ //退还费用为负数 
	    //更改用户余额
	    $totalSurplusMoney = $money-$bjmoney;
	    $upArray=array("money"=>$totalSurplusMoney); 
	    $db->update_new('userinfo',"ID='".$userID."'",$upArray); 
	} 
	if($bjmoney>0){//补交费用 
	  //  用户余额= 余额-补交费用 
	  if($totalSurplusMoney<0){ 
	  	  $totalSurplusMoney = abs($totalSurplusMoney);//实际收费 
	  	  //添加财务记录   
	      addCreditInfo($userID,"1",$totalSurplusMoney);//续费
	      echo "<script language='javascript'>alert('"._("扣除余额".$money."后实际收费".$totalSurplusMoney."元")."');</script>" ;
	  } 
 	}
	echo "<script language='javascript'>alert('"._("变更产品成功")."');</script>"; 
        if($bjmoney >= 0){ 
	echo "<script>if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?UserName=".$_POST['account']."&action=replac&bjmoney=".$_POST['bjmoney']."&replacremark=".$_POST["remark"]."&checkPID=".$_POST["checkPID"]."','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=100,left=300');}window.location.href='user.php';</script>";
			}
} 
$projectRs=$db->select_one('projectID,areaID',"userinfo","UserName='".$UserName."'");
$projectID=$projectRs['projectID'];
$areaID   =$projectRs['areaID']; 
?>
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
        <td width="89%" class="f-bulue1"><? echo _("更换产品")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform" onSubmit="return checkUserReplacProductForm();">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" valign="middle" class="bg"><? echo _("用户帐号:")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" id="account"  name="account" readonly="readonly" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','replac_prodcut_check_account','account','accountTXT');" class="input_out" value="<?=$UserName?>"> 
			<span id="accountTXT"><? echo _("如果从其它页面跳转至此，页面请点击用户帐号输入框，以获得用户信息")?></span></td>
		  </tr>  
		  <tr >
		    <td align="right" class="bg" width="150px"><? echo _("选择更换产品:")?></td> 
		    <td align="left" class="bg"><?=productSelect($product['type'],$product['period'],$product['ID'],$UserName,$projectID,$areaID)?>
		     </td> 
		  </tr>  
			<tr>
		    <td align="right" valign="bottom" class="bg"><? echo _("更换产品费用:")?> </td>
		    <td align="left" class="bg">
				<li class='check_li'><? echo _("注:")?><font  color="red"><? echo _("返还费用")?></font>
				  <? echo _("负数为返还的费用,例如：-800")?></li><li class='check_li'><? echo _("注:")?><font  color="red"><? echo _("补交费用")?></font>
				  <? echo _("更换产品总共需要补交的费用，例如：800")?></li>
				  <li class='check_li'><font  color="red"><? echo _("以上返回补交费用【均以余额体现】,值仅供参考,运行商可根据内部规则自定义换算")?></font></li><input type="text" name="bjmoney"  id='bjmoney'  ></td>
	    </tr> 
		  <tr>
		    <td align="right" valign="top" class="bg"><? echo _("备注说明:")?> </td>
		    <td align="left" class="bg">
				<textarea name="remark" cols="50" rows="5"  onFocus="this.className='textarea_on'" onBlur="this.className='textarea_out';" class="textarea_out"></textarea>			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="<? echo _("提交")?>">			</td>
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
<script language="javascript">
// window.onload=prodetype_change(); 
//window.onLoad=product_type_change();
function product_type_change(){
	v=document.myform.type.value;
	if(v=="year"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>+<? echo _("年")?>+</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>+<? echo _("天")?>+</font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="month"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>+<? echo _("月")?>+</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>+<? echo _("天")?>+</font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="hour"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>+<? echo _("时")?>+</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>+<? echo _("元")?>+</font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>+<? echo _("元/时")?>+</font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	}else if(v=="flow"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>M</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>+<? echo _("元")?>+</font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>+<? echo _("元/M")?>+</font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	} 
} 
/*
function prodetype_change(){ 
	v=document.myform.periodtype;
	if(v[0].checked){ 
		//alert(v[0].checked+"same");  
		document.getElementById("periodTypeSame").style.display=""; 
		document.getElementById("periodTypeDiff").style.display="none";   
	}else if(v[1].checked){ 
		//alert(v[1].checked+"diff");
		document.getElementById("periodTypeDiff").style.display="";
		document.getElementById("periodTypeSame").style.display="none";  
	}	 
}  
*/
</script>
</body>
</html>

