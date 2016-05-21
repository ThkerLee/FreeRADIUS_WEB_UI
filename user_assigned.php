#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
</head>
<body>
<?php
$ID=$_REQUEST["ID"]; 
if($_POST){
	$password      =$_POST["password"];
	$name          =$_POST["name"];
	$projectID     =$_POST["projectID"];
	$cardid        =$_POST["cardid"];
	$workphone     =$_POST["workphone"];
	$homephone     =$_POST["homephone"];
	$mobile 	   =$_POST["mobile"];
	$email         =$_POST["email"];
	$iptype        =$_POST["iptype"];
	$address       =$_POST["address"];	
	$ipaddress     =$_POST["ipaddress"];	
	$money		   =$_POST["money"];
	$sql=array(
		"password"		 =>$password,
		"name"			 =>$name,
		"projectID"	     =>$projectID,
		"cardid"	     =>$cardid,
		"workphone"      =>$workphone,
		"homephone"      =>$homephone,
		"mobile"         =>$mobile,
		"email"          =>$email,
		"address"        =>$address
	);
	$db->update_new("userinfo","ID='".$ID."'",$sql);
	
	//更新用户帐单
	addUserBillInfo($ID,"d",$money);//记录用户帐单
	//记录财务记录
	addCreditInfo($ID,"10",$money);
	
	echo "<script>alert('修改成功');window.location.href='user.php';</script>";
}
$rs=$db->select_one("*","userinfo","ID='".$ID."'");
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">用户管理</font></td>
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
        <td width="89%" class="f-bulue1"> 用户过户</td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform" onSubmit="return checkUserMoveForm();">
<input type="hidden" name="ID" value="<?=$ID?>">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg">用户帐号：</td>
			<td width="87%" align="left" class="bg"><input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["account"]?>" readonly="readonly">	
			</td>
		  </tr>
		  <tr>
		    <td align="right" class="bg">用户密码：</td>
		    <td align="left" class="bg"><input type="text" id="password" name="password" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["password"]?>"></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">所属项目：</td>
		    <td align="left" class="bg"><?=projectSelected($rs["projectID"]);?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">过户费用：</td>
		    <td align="left" class="bg"><input type="text" name="money" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" ></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">用户名称： </td>
		    <td align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["name"]?>"></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">证件号码：</td>
		    <td align="left" class="bg"><input type="text" id="cardid" name="cardid" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';" value="<?=$rs["cardid"]?>"></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">工作电话：</td>
		    <td align="left" class="bg"><input type="text" id="workphone" name="workphone" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';" value="<?=$rs["workphone"]?>"> </td>
	      </tr>
		  <tr id="unitpriceTR">
		    <td align="right" class="bg">家庭电话：</td>
		    <td align="left" class="bg"><input type="homephone" name="homephone" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["homephone"]?>"></td>
	      </tr>
		  <tr id="cappingTR">
		    <td align="right" class="bg">手机号码：</td>
		    <td align="left" class="bg"><input type="text" name="mobile" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["mobile"]?>"></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">电子邮件：</td>
		    <td align="left" class="bg"><input type="text" name="email" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["email"]?>">			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">联系地址：</td>
		    <td align="left" class="bg"><input type="text" name="address" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["address"]?>"></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="提交"></td>
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
</body>
</html>
