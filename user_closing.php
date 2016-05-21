#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("更换产品");?></title>
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
if($_POST){
	$account       =$_POST["account"];
	$userID        =$_POST["userID"];
	$orderID	     =$_POST["orderID"];//要修改订单编号
	$orderID	     =explode("##",$orderID);
	$penaltyStatus =$_POST["penaltyStatus"];//违约金启用状态
	$penalty       =$_POST["penalty"];
	$porductID	   =$_POST["productID"];//更改产品编号
	$factmoney     =$_POST["factmoney"];//实际退费
	$remark        =$_POST["remark"]; 
	if(is_array($orderID)){
		$count=count($orderID)-1;//这是里要少一个
		for($i=0;$i<$count;$i++){
			 closingOrder($userID,$orderID[$i]); 
		}
	}  
	//生成一销户订单
	userClosing($userID,$orderID,$factmoney);
	
	//更新用户帐单记录
	if($penaltyStatus=="enable" && $penalty ){
	  addUserBillInfo($userID,'a',$penalty,$remark);//扣除违约金
	}
	 addUserBillInfo($userID,5,$factmoney,$remark);

	//更新用户属性表1=表示销户了
	updateUserAttribute($userID,array("closing"=>1,"stop"=>1));

	//记录用户操作记录
	addUserLogInfo($userID,"5",_("用户销户"),getName($userID));//增加销户动作	$_SERVER['REQUEST_URI']
	
	echo "<script language='javascript'>alert('". _("操作成功")."');</script>"; 
	
}

?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理");?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("用户销户");?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform" onSubmit="return checkUserClosingForm();">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" valign="middle" class="bg"><? echo _("用户帐号");?>:</td>
			<td width="87%" align="left" class="bg">
			<input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','closing_check_account','account','accountTXT');" class="input_out"> <span id="accountTXT"><? echo _("如果从其它页面跳转至此，页面请点击用户帐号输入框，以获得用户信息");?></span></td>
		  </tr>
		  <tr>
		    <td align="right" valign="middle" class="bg"><? echo _("启用违约金额");?>:</td>
		    <td align="left" class="bg">
		    <input type="radio" name="penaltyStatus"  value="enable"  onClick="penaltyStatusShow();showFactmoney();" >  <? echo _("启用");?> 
			<input type="radio" name="penaltyStatus"  value="disabled" onClick="penaltyStatusShow();showFactmoney();"checked="checked" >  <? echo _("禁用");?>
			
			</td>
		 </tr>
		  <tr id="penalty_tr">
		    <td align="right" valign="middle" class="bg"><? echo _("违约金额");?>:</td>
		    <td align="left" class="bg"><input type="text" name="penalty" id="penalty" value="0" onFocus="this.className='input_on';" onBlur="this.className='input_out';showFactmoney();" class="input_out">&nbsp;<? echo _("元");?>&nbsp;&nbsp;<font color="red"><? echo _("金额亦可自定义")?> </font>
			
			</td>
		 </tr>
		  <tr>
		    <td align="right" valign="middle" class="bg"><? echo _("实退金额");?>:</td>
		    <td align="left" class="bg"><input type="text" name="factmoney" onFocus="this.className='input_on';"  onBlur="this.className='input_out';" class="input_out">
			
			</td>
		    </tr>
		  <tr>
		    <td align="right" valign="top" class="bg"><? echo _("备注说明");?>: </td>
		    <td align="left" class="bg">
				<textarea name="remark" cols="50" rows="5"  onFocus="this.className='textarea_on'" onBlur="this.className='textarea_out';" class="textarea_out"></textarea>			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="<? echo _("提交");?>">			</td>
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
        用户管理-> <strong>用户销户</strong>
      </p>
      <ul>
          <li>此功能主要用于用户由于中途停止使用网络进行退费的情况。</li>
          <li>用户销户后，此用户状态即为停用状态，不可再使用，如需再次使用，需要进行续费操作。</li>
          <li>销户后，用户帐号仍然存在。</li>
      </ul>

    </div>
<!---------------------------------------------->
<script language="javascript">
<!--
 window.onLoad=penaltyStatusShow();
function product_type_change(){
	v=document.myform.type.value;
	if(v=="year"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("年");?></font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>天</font>";
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
 
function penaltyStatusShow(){
 var status = document.myform.penaltyStatus[0].checked;
 if(status==true){
     document.getElementById("penalty").value=document.getElementById("penaltyAjax").value; 
	 document.getElementById("penalty_tr").style.display=""; 
 }else{
	 document.getElementById("penalty_tr").style.display="none"; 
	 document.getElementById("penalty").value=0;  
 } 
}

function showFactmoney(){
  document.getElementById("factmoney").value= document.getElementById("surplusToalMoeny").value - document.getElementById("penalty").value;


}
-->
</script>
</body>
</html>

