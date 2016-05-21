#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统升级")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<?php 
if($_POST){
	$sql=array(
		"speedStatus"=>$_POST["speedStatus"]
	);
	$db->update_new("config","",$sql);
	$db->query("update userattribute set speedrule='".$_POST["speedStatus"]."'");
	echo "<script language='javascript'>alert('"._("保存成功")."');</script>";
}
?>

<body>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统设置")?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("系统配置")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
		<?php 
		$rs=$db->select_one("*","config","0=0 order by ID desc limit 0,1");
		?>
	  <form action="?" method="post" name="myfrom">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
  	    <tbody>
		  <tr>
		    <td width="18%" height="30" align="left" class="bg"><? echo _("是否启用户内部限速")?></td>
	        <td width="82%" align="left" class="bg"><input type="radio" name="speedStatus" value="1" <?php if($rs["speedStatus"]=="1") echo "checked"; ?>><? echo _("启用");?>
				<input type="radio" name="speedStatus" value="0" <?php if($rs["speedStatus"]=="0") echo "checked"; ?>><? echo _("禁用");?> 
				<span class="f-bulue1"><? echo _("当启用内部限速之后会用户的限速规则会以内部限速为准")?></span>			</td>
		  </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="<? echo _("保存")?>"></td>
		  </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg">&nbsp;</td>
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

