#!/bin/php
<?php include("inc/conn.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2800.1528" name=GENERATOR>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<link href="style/bule/tooltip.css" rel="stylesheet" type="text/css">	
<style type="text/css">
<!--
.STYLE3 {color: #FFFFFF; font-weight: bold; }
.STYLE4 {color: #FFFFFF; font-weight: bold; font-size: 30px; }
-->
</style>
</HEAD>
<?php

$rs=$db->select_one("*","config","0=0 order by ID desc limit 0,1");
if($_POST){
	$account=$_POST["username"];
	$passwd =$_POST["pwd"];
	$record         =$db->select_one("*","userinfo","account='".$account."' and password='".$passwd."'");
	if($record){
		$_SESSION["clientID"]         =$record["ID"];
		echo "<script language='javascript'>window.location.href='client/index.php';</script>";
	}else{
		echo "<script language='javascript'>alert('输入有误');window.history.go(-1);</script>";
	}
} 
?>	
<form action="?action=check" method="post" name="myform" enctype="multipart/form-data"> 
<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="5">
  <tr><td>
<table width="511" height="352" border="0" align="center" cellpadding="0" cellspacing="0"  background=<?="./images/".$rs['picLogin']?> ><!--background="images/login_bg.jpg"-->
  <tr>
    <td valign="bottom">
        <table width="100%" height="340" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td height="130" align="left">&nbsp;</td>
            <td  align="left">&nbsp;</td>
            <td align="left"><table width="100%" height="75" border="0" cellpadding="5" cellspacing="0" >
              <tr>
                <td width="40%" align="center" class="STYLE4">®</td>
                <td width="60%"><span class="STYLE3"><?=$rs['WEB']?><!--http://www.natshell.com--></span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td valign="top"><span class="STYLE3"><?=$rs['Name']?><!--蓝海卓越计费管理系统--></span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="30" align="left">&nbsp;</td>
            <td height="25" align="right" class="f-bulue2">帐号：</td>
            <td width="64%" align="left"><input type="text" name="username" class="input_out"> </td>
          </tr>
          <tr>
            <td width="14%" height="30" align="left">&nbsp;</td>
            <td width="22%" height="34" align="right" class="f-bulue2">密码：</td>
            <td align="left"><input type="password" name="pwd"  title="输密码" class="input_out">            </td>
          </tr>
          <tr>
            <td height="51" align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left"><input type="image" value="提交" src="images/login.jpg"></td>
          </tr>
          <tr>
            <td height="30" align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td height="10" align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right" valign="baseline" class="f-gray">版权所有：星锐蓝海网络科技有限公司&nbsp;&nbsp;</td>
          </tr>
        </table>
</td>
  </tr>
</table>
</td></tr>
</table>
</form>
</html>