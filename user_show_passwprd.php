#!/bin/php
<?php  
include_once("inc/conn.php");
require_once("evn.php");
//include_once("ajax/loaduser.php");
 
?>
<html>
<head><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理");?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
</head>
<body>
<?php 
$UserName=$_REQUEST["UserName"];
 
$rs=$db->select_one("password","userinfo","account='".$UserName."'");

?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
          <td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理");?></font></td>
        </tr>
      </table></td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
        <tr>
          <td width="89%" class="f-bulue1"> <? echo _("用户密码查询");?></td>
          <td width="11%" align="right">&nbsp;</td>
        </tr>
      </table> 
      <form action="?" method="post" name="myform" onSubmit="return checkOrderForm();">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
          <tbody>
            <tr>
              <td width="13%" align="right" class="bg"><? echo _("用户帐号");?>：</td>
              <td width="87%" align="left" class="line-20 bg"><input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','user_password_account','account','accountTXT');" class="input_out" value="<?=$UserName?>"><? //echo _("点击获取用户密码");?>
                </td>
            </tr>
            <tr>
		   <td align="right" class="bg"><? echo _("用户密码");?>：</td>
		    <td align="left" class="bg">
				<!--<input name="password" type="text" class="input_out" id="password"   onFocus="this.className='input_on'"   onBlur="this.className='input_out';" value="<?=$rs['password']?>">-->	<span id="accountTXT"></span>		</td>
		    </tr> 
            <tr>
              <td align="right" class="bg">&nbsp;</td>
              <td align="left" class="bg"><input type="button" value="<? echo _("密码查询");?>" onClick="ajaxInput('ajax_check.php','user_password_account','account','accountTXT')">              </td>
            </tr> 
          </tbody>
        </table>
      </form></td>
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
 