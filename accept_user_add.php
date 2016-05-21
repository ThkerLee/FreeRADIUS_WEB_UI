#!/bin/php
<?php include("inc/conn.php"); 
include_once("evn.php");  ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统管理组")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<body>
<?php 
if($_POST){
    $accept_name    =$_POST["accept_name"];
    $accept_phone   =$_POST["accept_phone"];
    $manager_mobile =$_POST["manager_mobile"];
    $accept_address=$_POST["accept_address"];
	$sql=array(
		"accept_name"=>$accept_name,
		"accept_phone"=>$accept_phone,
		"manager_mobile"=>$manager_mobile,
		"accept_address"=>$accept_address
	);
        if(empty($accept_name) || empty($accept_phone)){
          echo "<script>alert('"._("姓名或手机号不能为空")."');window.location.href='accept_user.php';</script>";  
        }  else {
            $db->insert_new("accept",$sql); 
        }
	
	echo "<script>alert('"._("操作成功")."');window.location.href='accept_user.php';</script>";
}

//查询项目集合
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统设置")?></font></td>
		  </tr>
   		</table>	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("受理人员添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform" onSubmit="return checkManagerAdd()" >
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
		<tr>
		<td align="right" class="bg">受理人员姓名</td>
		<td align="left" class="bg"><input type="text" name="accept_name" value=""></td>
		</tr> 
		<tr>
		<td width="13%" align="right" class="bg">手机号：</td>
		<td width="83%" align="left" class="bg"><input type="text" name="accept_phone" value=""></td>
		</tr>
		<tr>
		<td align="right" class="bg">家庭电话：</td>
		<td colspan="2" align="left" class="bg">
		<input type="text" name="manager_mobile" value="">		</td>
		</tr> 
		<tr>
        <td align="right" class="bg">地址：</td>
        <td colspan="2" align="left" class="bg"> 
			<input type="text" name="accept_address" value=""></td>     
       </tr>



		<tr>
		<td align="right" class="bg">&nbsp;</td>
		<td align="left" class="bg"><input name="submit" type="submit" onClick="javascript:return window.confirm( '<?php echo _("确认提交")?>？ ');"value="<?php echo _("提交")?>">        </td>
		</tr>
    </tbody>
  </table>
</form>	</td>
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
function permision_change(id){
	v   =document.getElementById(id).checked;
	subv=document.getElementById("sub"+id).getElementsByTagName("input");
	for(i=0;i<subv.length;i++){
		subv[i].checked=v;
	}

}
--> 
</script>
</body>
</html>

