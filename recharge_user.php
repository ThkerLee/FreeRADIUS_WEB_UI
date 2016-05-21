#!/bin/php
<?php 
include("inc/conn.php"); 
include_once("evn.php"); 
date_default_timezone_set('Asia/Shanghai'); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("收费管理")?> </title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<!--<script src="js/jquery.js" type="text/javascript"></script>--和下面的jquery冲突-->
<!--这是点击帮助的脚本-2014.06.07-->
    <link href="js/jiaoben/css/chinaz.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jiaoben/js/jquery-1.4.4.js"></script>   
    <script type="text/javascript" src="js/jiaoben/js/jquery-ui-1.8.1.custom.min.js"></script> 
    <script type="text/javascript" src="js/jiaoben/js/jquery.easing.1.3.js"></script>        
    <script type="text/javascript" src="js/jiaoben/js/jquery-chinaz.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {  		
        $('#Firefoxicon').click(function() {
          $('#Window1').chinaz({
            WindowTitle:          '<b>营帐管理</b>',
            WindowPositionTop:    'center',
            WindowPositionLeft:   'center',
            WindowWidth:          500,
            WindowHeight:         300,
            WindowAnimation:      'easeOutCubic'
          });
        });		
      });
    </script>
   <!--这是点击帮助的脚本-结束-->
</head>
<body>
<?php  
if($_POST){
	$account       =$_POST["account"];
	$remark        =$_POST["remark"];
	$userID        =$_POST["userID"];
	$UserName      =$account;
	$money  	     =$_POST["money"];
	$card_num      =$_POST["card_num"];
	$card_pwd      =$_POST["card_pwd"];
	$rechage       =empty($_POST["rechange"])?"0":$_POST["rechange"];
	$nowTime       =date('Y-m-d H:i:s',time());
	// 根据用户ID查询该用户产品是否为时长计时，如用户为已到期用户则续费正常使用 
	$Prs= $db->select_one("p.type,att.status,att.stop,att.orderID","orderinfo as o,userattribute as att,product as p ","att.userID=o.userID and o.productID=p.ID and att.userID ='".$userID."' "); 
	if($rechage==0 || $rechage=='') $recharge_money=$_POST["recharge_money"];
	else if($rechage == 1)$recharge_money=$_POST["rechangeMoney"];
	  
	//获取收费金额状态是否为超过应收取的费用 false 超额
	$managerTotalMoney = managerTotalmoneyShow();
	if( $managerTotalMoney == false) {
		//收费金额超额不允许收费
		 echo "<script> alert('"._("收费金额已达上限，请联系管理员")."'); window.location.href='recharge_user.php' ;</script>";
		 exit;
	}else{
	 //更新用户剩余的金额
	 addRechargeInfo($userID,$recharge_money); 
	   if($rechage==0){//现金充值 
		  //添加用户帐单记录，
	      addUserBillInfo($userID,"1",$recharge_money,$remark);  
		  //添加财务记录
		  addCreditInfo($userID,"1",$recharge_money);
		  if($Prs["type"]=="netbar_hour" && ($Prs["status"]==4 ||$Prs["stop"]==1)){
		  	$db->query("update userattribute set status = 1, stop =0 where userid = '".$userID."'");	$db->query("update orderinfo set status = 1  where userid = '".$userID."' and ID = '".$Prs["orderID"]."'");
		  	$db->query("update userrun set status = 1,balance =1  where userid = '".$userID."'  ");
		
		  }
	   }else{//卡片充值
	   $cardRs=$db->select_one("*","card","cardNumber='".$card_num ."' and actviation='".$card_pwd."' and sold in (0,1) and recharge='0'");
	   //sold 1销售 rechang = 0为充值
			if($cardRs){
				$recharge_money=$cardRs["money"];
				if($cardRs['sold']==1){//已销售  近充值  销售的时候就有购买用户
				$sql="update card set recharge='1',ivalidTime='".$nowTime."' where cardNumber='".$card_num."' and actviation='".$card_pwd."'";
				}else{//未销售  ，进行销售并充值
				  $sql="update card set recharge='1',sold='1',solder='".$_SESSION["manager"]."',UserName='".$account."',soldTime = '".$nowTime."',ivalidTime='".$nowTime."' where cardNumber='".$card_num."' and actviation='".$card_pwd."'";
				} 
				$db->query($sql);
				$sql_log="insert into cardlog(cardNumber,type,UserName,addTime,operator,content) values('".$card_num."','4','".$UserName."','".$nowTime."','".$_SESSION["manager"]."','".$_SESSION["manager"]." sold');";
				$db->query($sql_log);
			}else{
				echo "<script>alert('"._("卡号信息不正确")."');window.location.href='recharge_user.php?userName=".$account."';</script>";
				$_SESSION["cardRecharge"][]="1";
				exit;
			}  
	       //添加用户帐单记录，->s
	       addUserBillInfo($userID,"2",$recharge_money,_("用户卡充值")); 
	       //添加财务记录
	       addCreditInfo($userID,"2",$recharge_money);
	   } 
		
		$toatal=$recharge_money+$money;
	    echo "<script>if(confirm('". _("充值成功，当前用户金额为:￥").$toatal. _("是否用户续费")."?')){window.location.href='order_add.php?UserName=".$account."';}</script>";
            $re=$db->select_one("*","message","type = 2");//短信发送用
                          $status=$re['status'];
                          if($status == "enable"){
                    echo "<script>if(window.confirm('"._("是否发送短信")."？')){window.open('recharge_message.php?account=".$_POST['account']."','newname','height=60,width=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=0,left=0');}</script>";
                          }
	    echo"<script>if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?action=recharge&UserName=".$account."&type=".$rechage."&money=".$recharge_money."&cardNum=".$card_num."','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=100,left=300');}</script>";
	}//end if else 
	
}

?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"> <? echo _("收费管理")?> </font></td>
                        <td width="3%" height="35">
                           <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                       </td> <!------帮助--2014.06.07----->
                       
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
        <td width="89%" class="f-bulue1"> <? echo _("用户充值") ?> </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform" onSubmit="return checkRechargeForm();"> 
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("用户帐号")?> </td>
			<td width="87%" align="left" class="bg">
			<input type="text" id="account" name="account" value="<?=$_REQUEST['userName']?>" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','order_check_account','account','accountTXT');" class="input_out"> <span id="accountTXT"><font color="red"><? echo _("页面跳转到此页请点击获取用户基本信息")?> </font></span></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("充值方式:")?> </td>
		    <td align="left" class="bg"><?=rechangType()?></td>
		</tr> 
		  <tr id="TR_money" style="display:none">
		    <td align="right" class="bg"><? echo _("充值金额:")?> </td>
		    <td align="left" class="bg" >
				<input type="text" id="recharge_money" name="recharge_money"   onFocus="this.className='input_on'"   onBlur="this.className='input_out';" class="input_out"></td>
		 </tr>
		 <tr id="TR_card_num" style="display:none">
		    <td align="right" class="bg"><? echo _("充值卡号:")?> </td>
		    <td align="left" class="bg" >
			<input type="text" id="card_num" name="card_num"   onFocus="this.className='input_on'"   onBlur="this.className='input_out';ajaxInput('ajax_check.php','card_num','card_num','cardTXT');" class="input_out"><span id="cardTXT"></span></td>
		 </tr>
		 <tr id="TR_card_pwd" style="display:none">
		    <td align="right" class="bg"><? echo _("充值密码:")?> </td>
		    <td align="left" class="bg" >
			<input type="text" id="card_pwd" name="card_pwd"   onFocus="this.className='input_on'"   onBlur="this.className='input_out';" class="input_out">
			<span id="cardTXT"></span></td>
		 </tr>  
		  <tr>
		    <td align="right" valign="top" class="bg"><? echo _("订单备注:") ?> </td>
		    <td align="left" class="bg">
				<textarea name="remark" cols="50" rows="5"  onFocus="this.className='textarea_on'" onBlur="this.className='textarea_out';" class="textarea_out"></textarea>
			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value= <? echo _("提交")?> >			</td>
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
<!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        营帐管理-> <strong>用户充值</strong>
      </p>
      <ul>
          <li>对用户进行充值，包括上网费用和其他费用。</li>
          <li>用户帐号：输入用户帐号，可以显示当前用户的帐户余额及使用产品情况。</li>
          <li>充值方式： 可使用现金充值， 由管理员手动输入， 也可以用卡片充值， 根据充值卡上金额进行充值。</li>
          <li>充值金额：需要为用户进行充值的金额。</li>
      </ul>

    </div>
<!---------------------------------------------->
<script language="javascript">
<!--
//window.onLoad=product_type_change();
window.onLoad = showRechang();
 
function product_type_change(){
	v=document.myform.type.value;
	if(v=="year"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("年")?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="month"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("月")?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="hour"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("时")?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元")?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/时")?></font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	}else if(v=="flow"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>M</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元")?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/M")?></font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	} 
}
function showRechang(){ 
  t = document.getElementById("rechange").value;  
  if(t ==0){//现金充值
     document.getElementById('TR_money').style.display="";
	 document.getElementById("TR_card_num").style.display="none"; 
	 document.getElementById("TR_card_pwd").style.display="none"; 
  }else if(t==1){//卡片充值
     document.getElementById('TR_money').style.display="none";
	 document.getElementById("TR_card_num").style.display=""; 
	 document.getElementById("TR_card_pwd").style.display="";  
  }else{
     document.getElementById('TR_money').style.display="none";
	 document.getElementById("TR_card_num").style.display="none"; 
	 document.getElementById("TR_card_pwd").style.display="none";  
  } 
}
-->
</script>
</body>
</html>

