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
<title><? echo _("收费管理")?></title>
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
	$money  	   =$_POST["money"];
	//更新用户剩余的金额
	addRechargeInfo($userID,$money,1);// number 1 this subtract user's money
	//添加用户帐单记录，
	addUserBillInfo($userID,"6",$money,$remark);
	//添加财务记录
	addOrderRefund($userID,2,$money,$money,$orderID,$_SESSION["manager"],$remark);//number 2 ,this is reverse 
	//记录用户操作记录
	 addUserLogInfo($userID,"9",_("用户冲账:").$money,getName($userID),$money);//$_SERVER['REQUEST_URI']
    echo "<script>alert('"._("冲账成功!")."');</script>";
    echo "<script>if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?UserName=".$_POST['account']."&action=reverse&money=".$_POST["money"]."&remark=".$_POST["remark"]."','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=100,left=300');}window.location.href='user.php';</script>";
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("收费管理")?></font></td>
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
        <td width="89%" class="f-bulue1"> <? echo _("用户冲帐")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform" onSubmit="return checkReverseForm();">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("用户帐号:")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','reverse_check_account','account','accountTXT');" class="input_out"> <span id="accountTXT"></span></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("冲帐金额:")?></td>
		    <td align="left" class="bg">
				<input type="text" id="money" name="money"   onFocus="this.className='input_on'"   onBlur="this.className='input_out';" class="input_out">			</td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="bg"><? echo _("备注")?>: </td>
		    <td align="left" class="bg">
				<textarea name="remark" cols="50" rows="5"  onFocus="this.className='textarea_on'" onBlur="this.className='textarea_out';" class="textarea_out"></textarea>
				(<? echo _("管理员给用户多充值了费用，需要用户冲帐给扣除去")?>)
			</td>
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
<!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        营帐管理-> <strong>用户冲账</strong>
      </p>
      <ul>
          <li>对已经收取费用进行冲帐，冲帐金额不能大于用户余额。</li>
          <li>用户帐号：输入用户帐号，可以显示当前用户的帐户余额。</li>
          <li>冲帐金额：需要为用户进行退费的金额。</li>
      </ul>

    </div>
<!---------------------------------------------->
<script language="javascript">
<!--
//window.onLoad=product_type_change();
function product_type_change(){
	v=document.myform.type.value;
	if(v=="year"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo ("年") ?> </font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo ("天") ?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="month"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo ("月") ?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo ("天") ?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="hour"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo ("时") ?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo ("元") ?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo ("元/时") ?></font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	}else if(v=="flow"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>M</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo ("元") ?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo ("元/M") ?></font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	}
	

}
-->
</script>
</body>
</html>

