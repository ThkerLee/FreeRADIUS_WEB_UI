#!/bin/php
<?php   
session_start();
include("./inc/db_config.php"); 
include("inc/class_mysql.php");
include("inc/class_database.php");
include("inc/class_public.php"); 
require_once("evn.php"); 
 if(in_array("system_database_XMLA.php",$_SESSION['auth_permision'])==false)
{
   echo "<script language='javascript'>alert('"._("没有管理权限")."');window.history.go(-1);</script>";
   exit;
}
$db   = new Db_class($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//程序
$d    = new db($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//数据库备份 
$conn = mysql_connect($mysqlhost,$mysqluser,$mysqlpwd); 
mysql_select_db($mysqldb,$conn);
mysql_query("set names utf8"); 
$configRs=$db->select_one("*","config","0=0 order by ID DESC"); 
$config_version=$configRs["version"];
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
<script src="js/datechooser.js" type="text/javascript"></script>
</head>
<body>
<?
$num=$db->select_count("sync_mysql","ID=1");

if($_POST){
  
	if($_GET['action']=="update"){
		
		$status           = $_POST["status"];
		$period           = $_POST["period"];
	    $master_dbname    = $_POST["master_dbname"];
		$master_ipaddress = $_POST["master_ipaddress"];
		$master_username  = $_POST["master_username"];
		if($_POST["old_master_pwd"]==$_POST["master_pwd"]) 
		$master_pwd       = base64_decode($_POST["master_pwd"]);
		else
		$master_pwd       = $_POST["master_pwd"]; 
		
		$slave_dbname     = $_POST["slave_dbname"];
		$slave_ipaddress  = $_POST["slave_ipaddress"];
		$slave_username   = $_POST["slave_username"];
		
		if($_POST["old_slave_pwd"]==$_POST["slave_pwd"]) 
		$slave_pwd        = base64_decode($_POST["slave_pwd"]);
		else
		$slave_pwd        = $_POST["slave_pwd"];
		}
	
	if($num>0){
		 
		  
		 $update=" update sync_mysql set status='".$status."' ,period='".$period."',master_dbname='".$master_dbname."', master_ipaddress='".$master_ipaddress."',master_username='".$master_username."',master_pwd='".$master_pwd."' , slave_dbname='".$slave_dbname."',slave_ipaddress='".$slave_ipaddress."',slave_username='".$slave_username."' ,slave_pwd='".$slave_pwd."' where ID=1";
		 mysql_query($update);		 
		 
		 //if($status=="yes"){		 
		     pclose(popen("/usr/bin/sync_mysql&", "r"));
		// }
		     echo "<script>alert('"._("设置成功")."');</script>";
		
	}else{
	    $insert=" insert into sync_mysql values(1,'".$status."' ,'".$period."','".$master_dbname."','".$master_ipaddress."', '".$master_username."', '".$master_pwd."' ,  '".$slave_dbname."','".$slave_ipaddress."','".$slave_username."' ,'".$slave_pwd."')";
	    mysql_query($insert);
	 if($status=="yes"){
	   pclose(popen("/usr/bin/sync_mysql", "r"));
	 }
	  echo "<script>alert('"._("设置成功")."');</script>";
	}
}
if($num>0){
   $rs=$db->select_one("*","sync_mysql","ID=1");

}



?>




<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("数据库同步")?></font></td>
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

        <td width="89%" class="f-bulue1"> <? echo _("数据库同步")?></td>

		<td width="11%" align="right">&nbsp;</td>

      </tr>

	  </table>
	<form  action="?action=update" method="post" name="myform"  onSubmit="return checkDBForm();" >
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">

        <tbody>     

		  <tr>

			<td width="13%" align="right" class="bg"><? echo _("状态")?></td>
			<td width="87%" align="left" class="bg">
			<input type="radio" name="status" id="status" value="yes" <? if($rs['status']=="yes" || $rs['status']=="")echo "checked";?> ><? echo _("开启")?>
			<input type="radio" value="no" name="status" id="status" <? if($rs['status']=="no") echo "checked";?>/><? echo _("禁止")?>
			 </td>
		  </tr>
		    <tr>
			<td width="13%" align="right" class="bg">&nbsp;</td>
			<td width="87%" align="left" class="bg">&nbsp;</td>
		  </tr>
		   <tr>
			<td width="13%" align="right" class="bg"><? echo _("周期")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" name="period" id="period"  value="<?=$rs['period']?>"  > <? echo _("分(周期不能小于2分钟)")?></td>
		  </tr> 
		   <tr>
			<td width="13%" align="right" class="bg">&nbsp;</td>
			<td width="87%" align="left" class="bg">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("主机数据库名")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" name="master_dbname" id="master_dbname"  value="<?=$rs['master_dbname']?>"  >
			 </td>
		  </tr>
		 
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("主机地址")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" name="master_ipaddress" id="master_ipaddress"  value="<?=$rs['master_ipaddress']?>"  >        </td>
		  </tr> 
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("主机登录名")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" name="master_username" id="master_username"  value="<?=$rs['master_username']?>"  >          </td>
		  </tr>
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("主机登录密码")?></td>
			<td width="87%" align="left" class="bg">
			<input type="hidden" value="<?=base64_encode($rs['master_pwd']); ?>" name="old_master_pwd" >
			<input type="password" name="master_pwd" id="master_pwd"  value="<?=base64_encode($rs['master_pwd'])?>"></td>
		  </tr>
		   <tr>
			<td width="13%" align="right" class="bg">&nbsp;</td>
			<td width="87%" align="left" class="bg">&nbsp;</td>
		  </tr>
		    <tr>
			<td width="13%" align="right" class="bg"><? echo _("备份机数据库名")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" name="slave_dbname" id="slave_dbname"  value="<?=$rs['slave_dbname']?>">
			</td>
			
		</tr>
		 <tr>
			<td width="13%" align="right" class="bg"><? echo _("备份机地址")?></td>
			<td width="87%" align="left" class="bg">
			<input type="text" name="slave_ipaddress" id="slave_ipaddress"  value="<?=$rs['slave_ipaddress']?>">       </td> 
		</tr>
		<tr>
			<td width="13%" align="right" class="bg"><? echo _("备份机登陆名")?> </td>
			<td width="87%" align="left" class="bg">
			<input type="text" name="slave_username" id="slave_username"  value="<?=$rs['slave_username']?>" >					            </td> 
		</tr>
		<tr>
			<td width="13%" align="right" class="bg"><? echo _("备份机登陆密码")?> </td>
			<td width="87%" align="left" class="bg">
			<input type="hidden" value="<?=base64_encode($rs['slave_pwd']); ?>" name="old_slave_pwd" >
			<input type="password" name="slave_pwd" id="slave_pwd"  value="<?=base64_encode($rs['slave_pwd'])?>"  >					             </td>      
		</tr>
		
		  <tr>

		    <td align="right" class="bg">&nbsp;</td>

		    <td align="left" class="bg">
			      
				<input  type="submit"  value="<? echo _("提交")?>" onClick="javascript:return window.confirm( '确认提交？ ');">			</td>

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