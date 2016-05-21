#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
?>

<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("产品管理")?> </title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
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

  $userID=getUserID($_POST['account']);
  $financeID=$_POST['financeID'];//收费科目
  $type=financeType($financeID);
  $money=(int)$_POST['financemoney'];//从余额里扣除
  $remark=$_POST['remark'];
  $methods=$_POST['methods']; //1现金支付  0余额扣除
  
	$managerTotalMoney = managerTotalmoneyShow();
	if( $managerTotalMoney == false) {
		//收费金额超额不允许收费
		 echo "<script> alert('"._("收费金额已达上限，请联系管理员")."'); window.location.href='finance_MTC_add.php' ;</script>";
		 exit;
	}else{
		if($methods==0){
		  $rs=$db->select_one("money","userinfo","ID='".$userID."'");
		  $Emoney=(int)$rs['money']; 
		  addUserBillInfo($userID,$type,$money,$remark,$operator='');
		  $Emoney=$Emoney-$money;
		  $db->update_new("userinfo","ID='".$userID."'",array("money"=>$Emoney)); 
		  echo "<script>alert('". _("添加成功") ."');if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?UserName=".$_POST['account']."&action=finance&type=".$methods."&money=".$money."&financeID=".$financeID."','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,top=100,left=300');}window.location.href='finance_MTC_add.php';</script>";	
   
    }else{//现金
        addCreditInfo($userID,'q',$money,$projectID="",$operator="");
        addUserBillInfo($userID,$type,$money,$remark,$operator=''); 
		    echo "<script>alert('". _("添加成功") ."');if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?UserName=".$_POST['account']."&action=finance&type=".$methods."&money=".$money."&financeID=".$financeID."','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,top=100,left=300');}window.location.href='finance_MTC_add.php';</script>";
  
    }//end if else
  }//end if 收费金额上线
  
  
 
  
}

//查询项目集合
$projectResult=$db->select_all("*","project","");
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("营帐管理")?> </font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("人工缴费添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform"  onSubmit="return checkMTCForm();"> 
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("用 户 名:")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','MTC_check_account','account','accountTXT');" class="input_out"> <span id="accountTXT"></span></td>
		  </tr>
		   <tr>
			<td width="13%" align="right" class="bg"><? echo _("缴费方式:")?> </td>
			<td width="87%" align="left" class="bg"> <? paymentShow();?></td>
		  </tr>
		   <tr>
			<td width="13%" align="right" class="bg"><? echo _("缴费科目:")?> </td> 
			<td width="87%" align="left" class="bg">
			<?php 
		   $mResult=$db->select_all("*","finance","");
			echo "<select name='financeID' id='financeID' onchange=ajaxInput('ajax_check.php','financeID','financeID','financeTEXT'); onBlur=ajaxInput('ajax_check.php','remark','financeID','remarkTEXT');>";
			echo "<option value=''>". _("选择科目")."</option>";
			if($mResult){
				foreach($mResult as $mKey=>$mRs){
					echo "<option value='".$mRs["ID"]."'";
					if($manager==$mRs["ID"]) echo "selected";
					echo ">".$mRs["name"]."</option>";
				}
			}
			echo "</select>";
			?>  </td><? //=financeSelect();?>
		  </tr>  
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("金 额:")?> </td>
			<td width="87%" align="left" class="bg" id="financeTEXT"><input type="text" id="financemoney" name="financemoney" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"> <font color="red"><? echo _("金额亦可自定义")?> </font>  </td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("缴费描述")?> </td>
		    <td align="left" class="bg"  id="remarkTEXT">
			<textarea name="remark" cols="50" rows="5"  onFocus="this.className='textarea_on'" onBlur="this.className='textarea_out';" class="textarea_out"></textarea>	<font color="red"><? echo _("点击获取科目备注，亦可修改")?> </font>		</td>
	    </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value=<? echo _("\"提交\"")?> onClick="javascript:return window.confirm('"._("确认提交?")."');">			</td>
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
        营帐管理-> <strong>人工收费</strong>
      </p>
      <ul>
          <li>对用户进行相应服务的科目手动执行收费功能。</li>
          <li>可以进行对其他收费项目的管理，通常本功能是配合财务科目一起实现。</li>
      </ul>

    </div>
<!---------------------------------------------->
<script>
function showFinance(){
alert('9090');
 f = document.getElementById('fMoney').value;
 alert(f);
 
}


</script> 
</body>
</html>

