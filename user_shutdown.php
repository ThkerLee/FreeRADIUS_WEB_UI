#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("收费管理");?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
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
            WindowTitle:          '<b>用户管理</b>',
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
$UserName = $_REQUEST["UserName"];
$action   = $_REQUEST["action"]; 
$operator = $_SESSION["manager"];
if($_POST){   
$account         =trim($_POST["account"]);
$userID          =$_POST["userID"];
$money           =$_POST["money"];
$remark 		     =$_POST["remark"];
if($account !=""){   
 if($_GET['action']=='pause'){//用户暂停  
	$methods         =$_POST['methods']; //支付方式
	$type            ="b";//userbill状态为违约金
	$stopdatetime    =date("Y-m-d",time());	
        if(empty($_POST["restoredatetime"])){
          echo "<script>alert('"._("请设置恢复时间")."');window.history.go(-1);</script>";
	 exit;   
        }  else {
         $restoredatetime =$_POST["restoredatetime"];   
        }

  $managerTotalMoney = managerTotalmoneyShow();
	if( $managerTotalMoney == false && $money >0 && $methods!='0') 
	{ 
		//收费金额超额不允许收费
		 echo "<script> alert('"._("收费金额已达上限，请联系管理员")."'); window.location.href='user.php' ;</script>";
		 exit;
	}else{
		   if(!isset($_POST["userID"])){
			   $rs=$db->select_one("ID","userinfo","UserName='".$account."'");
			   $userID = $rs['ID']; 
			}
			if($methods=='0'){//余额支付
				  $rs=$db->select_one("money","userinfo","ID='".$userID."'");
				   
				  $Emoney=(int)$rs['money']; 
				  addUserBillInfo($userID,$type,$money,$remark,$operator);//添加用户账单记 
				  $Emoney=$Emoney-$money;//扣除金额后的剩余金额
				  $db->update_new("userinfo","ID='".$userID."'",array("money"=>$Emoney));
			}else{//现金支付
			    addCreditInfo($userID,'1',$money,$projectID="",$operator);//用户收取现金  财务添加==用户续费 
		      addUserBillInfo($userID,$type,$money,$remark,$operator);   
			}
		  	//停机操作
			 // userApplyInfo($userID,$stopdatetime,$restoredatetime); 
		  	//记录操作记录,设置为6
			 // addUserLogInfo($userID,6,_("恢复时间：".$restoredatetime.""),getName($userID)); 
		  	echo "<script>window.location.href='pause.php?action=pause&ID=".$userID ."&stoptime=".$stopdatetime."&restoretime=".$restoredatetime."'</script>";
	 }// end if 用户收费上线  
 }else{//用户暂停恢复
       $refund = $_POST["refund"];
       $rs=$db->select_one("ID,money","userinfo","UserName='".$account."'");
	     $userID = $rs['ID'];
	     $refund = $_POST["refund"]; 
	     $Emoney=(int)$rs['money']; 
	     $restoredatetime = $_POST["restoredatetime"]; 
	     $type = "c";
	     if($refund=='0'){//划分余额
	        $Emoney = $Emoney + $money;//余额为当前余额+退款 
		      if(is_numeric($money)>0) {
		         addUserBillInfo($userID,$type,$money,_("暂停恢复退费"),$operator);//添加用户账单记  c暂停恢复退费 
		         $db->update_new("userinfo","ID='".$userID."'",array("money"=>$Emoney)); 
		         // addCreditInfo($userID,'1',$money,$projectID="",$operator="");//用户收取现金  财务添加==用户续费  要是添加财务，所有用的统计的金额就有出入了 暂停 100 恢复退费 50  
             //addUserBillInfo($userID,$type,$money,_("暂停恢复退费"),$operator='');   
		      } 
	     }else{//现金退费
	           addOrderRefund($userID,3,$money,$money,$orderID,$operator,"暂停恢复退费");//tyoe=3恢复暂停  现金退费 
	     }  
       echo "<script>window.location.href='pause.php?action=restore&ID=".$userID ."'</script>";
 } 
}else{ 
	 echo "<script> alert('"._("用户名不能为空")."');</script>";
		
 }
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
          <td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理");?></font></td>
            <td width="3%" height="35">
               <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
           </td> <!------帮助--2014.06.07----->         
        </tr>
      </table></td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
        <tr>
          <td width="89%" class="f-bulue1"> <? echo _("用户申请报停/申请恢复");?></td>
          <td width="11%" align="right">&nbsp;</td>
        </tr>
      </table>
	  <?php
	    if($action == "pause"){//暂停
	  ?>
      <form action="?action=pause" method="post" name="myform" onSubmit="return checkShutdownForm();">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
          <tbody>
            <tr>
              <td width="13%" align="right" class="bg"><? echo _("用户帐号");?>:</td>
              <td width="87%" align="left" class="bg"><input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';check_pause_user();" class="input_out" value="<?=$UserName?>">
                <span id="accountTXT"><? echo _("如果从其它页面跳转至此，页面请点击用户帐号输入框，以获得用户信息");?></span></td>
            </tr>
			<tr>
			<td width="13%" align="right" class="bg"><? echo _("缴费方式:")?> </td>
			<td width="87%" align="left" class="bg"> <? paymentShow();?></td>
		   </tr>
		   <tr>
			<td width="13%" align="right" class="bg"><? echo _("暂停费用:")?> </td>
			<td width="87%" align="left"  class="bg"><input type="text" id="money" name="money" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"></td>
		  </tr>
           <!-------------------------------------------2014.03.07修改 <tr>
              <td align="right" class="bg"><? echo _("暂停时间");?>:</td>
              <td align="left" class="bg"><input type="text" name="stopdatetime" class="input_out"  onFocus="HS_setDate(this)">
               <? echo _("设置用户暂停,开始时间默认为0000-00-00 00:00:00表示用户没有暂停");?></td>
            </tr>
            <tr>--------------------------------------------->
              <td align="right" class="bg"><? echo _("恢复时间");?>:</td>
              <td align="left" class="bg"><input type="text" name="restoredatetime" class="input_out"  onFocus="HS_setDate(this)"></td>
            </tr>
            <tr>
              <td align="right" class="bg">&nbsp;</td>
              <td align="left" class="bg"><input type="submit" value="<? echo _("提交");?>">              </td>
            </tr>
          </tbody>
        </table>
      </form>
        <!-----------这里是点击帮助时显示的脚本--2014.06.07------暂停----->
 <div id="Window1" style="display:none;">
      <p>
        用户管理-> <strong>停机保号</strong>
      </p>
      <ul>
          <li>对用户可以进行停机保号操作，停机保号用户可以随时恢复。</li>
          <li>如果用户帐号到期，则自动进行停机保号状态。</li>
          <li>在下一个续费周期结束后，还未进行续费操作， 则自动转入停机用户状态。</li>
      </ul>

    </div>
<!---------------------------------------------->
	  <?php
	  }else{//暂停恢复 
	  ?>
	  <form action="?action=restore" method="post" name="myform" onSubmit="return checkRefundForm();">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
          <tbody>
            <tr>
              <td width="13%" align="right" class="bg"><? echo _("用户帐号");?>:</td>
              <td width="87%" align="left" class="bg"><input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';check_pause_user();" class="input_out" value="<?=$UserName?>">
                <span id="accountTXT"><? echo _("如果从其它页面跳转至此，页面请点击用户帐号输入框，以获得用户信息");?></span></td>
            </tr>
			<tr>
			<td width="13%" align="right" class="bg"><? echo _("退款方式")?>: </td>
			<td width="87%" align="left" class="bg"> <? refundWayShow();?></td>
		   </tr>
			<tr>
			<td width="13%" align="right" class="bg"><? echo _("退还费用")?>: </td>
			<td width="87%" align="left" class="bg"><input type="text" id="money" name="money" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"  >
            </td>
		   </tr>  
            <!--<tr>
              <td align="right" class="bg"><? echo _("恢复时间");?>:</td>
              <td align="left" class="bg"><input type="text" name="restoredatetime" class="input_out"  onFocus="HS_setDate(this)">
<? echo _("设置用户恢复开始时间，默认为0000-00-00	00:00:00 如果没有设置停机时间时，在此设置没有任何效果 ");?>			</td>
            </tr>-->
            <tr>
              <td align="right" class="bg">&nbsp;</td>
              <td align="left" class="bg"><input type="submit" value="<?php echo _("提交");?>">              </td>
            </tr>
          </tbody>
        </table>
      </form>
	  <!-----------这里是点击帮助时显示的脚本--2014.06.07------恢复----->
 <div id="Window1" style="display:none;">
      <p>
        用户管理-> <strong>停机恢复</strong>
      </p>
      <ul>
          <li>此功能用于对用户帐号进行暂停使用，通常用于交了一个较长时间网费的用户，中途需要进行暂停的情况下使用。</li>
          <li>停机时只改状态为停机，复机时对停机天数进行顺延。</li>
          <li>可以在组织参数管理下设置停机费、停机维护费、复机费等，对用户收取适当费用。</li>
      </ul>

    </div>
<!---------------------------------------------->
	  <?php 
	  }
	  
	  ?>
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
<!--
//window.onLoad=product_type_change();

function check_pause_user(){
//**************************************
//function check()   异步调用函数
//pageName           加载的页面
//dataName           传送参数名
//getValueID         传送参数值的ID
//setValueID         返回值的显示的ID
function ajaxStopInput(pageName,dataName,getValueId,setValueId){  
	xmlhttp=GetXmlHttpObject();
	url= ""+pageName+"?"+dataName+"="+escape(document.getElementById(getValueId).value);
	xmlhttp.open("GET",url,true); 
	xmlhttp.send(null); 
	xmlhttp.onreadystatechange=function(){ 
		if (xmlhttp.readyState == 4){ 
			if (200==xmlhttp.status){ //浏览器返回状态
				document.getElementById(setValueId).innerHTML=xmlhttp.responseText; 
				document.myform.stopdatetime.value=document.myform.stopdate.value;
				document.myform.restoredatetime.value=document.myform.restore.value;
			}else{ 
				document.getElementById(setValueId).innerHTML="<? echo _("数据加载中...");?>"; 
			} 
		} 
	} 
} 
ajaxStopInput('ajax_check.php','shutdown_account','account','accountTXT');
}


function product_type_change(){
	v=document.myform.type.value;
	if(v=="year"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("年");?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天");?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="month"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("月");?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天");?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
	}else if(v=="hour"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("时");?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元");?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/时");?></font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	}else if(v=="flow"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("M");?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元");?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/M");?></font>";
		document.getElementById("unitpriceTR").style.display="block";
		document.getElementById("cappingTR").style.display="block";
	}
	

}
-->
</script>
</body>
</html>
