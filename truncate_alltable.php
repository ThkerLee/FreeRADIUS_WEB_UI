#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统设置")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php   
if($_GET['action']=='default'){
	if($_POST['default']=='default'){
		$d->query("show table status from $mysqldb");
		while($d->nextrecord()){
			$table=$d->f('Name');   
			if($table!="client_notice" && $table!='config' && $table!='duenotice'&& $table!='grade'&& $table!='managerpermision'&& $table!='maturity_notice'  && $table!='publicnotice' && $table!='sync_mysql' && $table!="ticket"){
			mysql_query("truncate table $table"); 
			}//end if 
		}//end wile 
		//添加超级管理员用户 admin admin  
		$auth_manpen = $db->select_all("permision_param","managerpermision",""); 
		foreach($auth_manpen as $auth_val){  //获取所有用户权限
		   $auth .=$auth_val['permision_param']."#";
		}			
		   $auth = substr($auth,0,-1); 
		//插入超级管理员的信息
	  	$sqlManager=array(
				"manager_account"=>"admin",
				"manager_passwd"=>"admin", 
				"manager_permision"=>$auth,
				"manager_groupID"=>1,
				"manager_gradeID"=>'1,2',
				"addusertotalnum"=>0,
				"addusernum"=>0 
				); 
		$db->insert_new("manager",$sqlManager); 		
		//插入组的信息
		    
	  	$sqlGroup=array(
				"group_name"=>"Administrator",
				"group_permision"=>$auth, 
				"group_areaID"=>"",
				"group_gradeID"=>"1,2" 
				); 
		$db->insert_new("managergroup",$sqlGroup);
		//修改config表的界面配置//2014.07.08修改
                $sqlConfig=array(
                    "site"=>"计费管理系统",
                    "copyright"=>"版权所有:星锐蓝海网络科技有限公司",
                    "speedStatus"=>"",
                    "macStatus"=>"",
                    "onlinenum"=>1,
                    "picTopLeft"=>"logo/1354125865.jpg",
                    "picTopRight"=>"logo/1354125879.jpg",
                    "picBottomLeft"=>"logo/1338456892.jpg",
                    "picBottomRight"=>"logo/1338456900.jpg",
                    "picLogin"=>"images/1338456908.jpg",
                    "WEB"=>"http://www.natshell.com",
                    "Name"=>"蓝海卓越计费管理系统",
                    "copyrightLog"=>"版权所有:星锐蓝海网络科技有限公司",
                    "CRStatement"=>"1、本系统为商业授权，未经授权，不得以任何方式进行对本系统进行破解、复制、传播等行为;
                                    2、用户可自由选择是否使用本系统，任何未经授权的使用，在使用中出现的问题和由此造成的一切损失本公司不承担任何责任;
                                    3、您可以对本系统进行修改和美化，但必须保留完整的版权信息; 
                                    4、本系统受中华人民共和国《著作权法》《计算机软件保护条例》《商标法》《专利法》等相关法律、法规保护，星锐蓝海网络科技有限公司保留一切权利。",
                    "Contact"=>"028-86679789",
                    "vlanStatus"=>0,
                    "nasStatus"=>0,
                    "scannum"=>12
                );
                $db->update_new("config","",$sqlConfig);
		//给用户权限赋值session值，避免用户登出后在登录 会权限不足无法操作而登出 
		$_SESSION["auth_permision"]	   =explode("#",$auth);
		
		echo "<script>alert('"._("数据库出厂设置恢复成功")."！');window.location.href='truncate_alltable.php';</script>";
	}else{
	   echo "<script>alert('"._("请选择出厂设置")."！');window.location.href='truncate_alltable.php';</script>";
	
	}//end post 
}//end get
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
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
		<tr>
		<td width="89%" class="f-bulue1"><? echo _("数据库出厂设置")?></td>
		<td width="11%" align="right">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
		<form action="?action=default" method="post" name="myform">
		<tr>
		<td width="19%" align="right" class="bg"><? echo _("数据库恢复出厂设置")?>：</td>
		<td width="81%" class="bg">
		<input type="checkbox" value="default" name="default"  /><? echo _("出厂设置")?>&nbsp;&nbsp;<br><br> <font color="red" ><? echo _("注：提交后除基本信息表外，所有数据将清空 请谨慎操作")?>！</font>
		 
		</tr>
		<tr>
		<td align="right" class="bg">&nbsp;</td>
		<td class="bg"><input type="submit" value="<? echo _("确认出厂设置")?>" onClick="javascript: return window.confirm('<? echo _("确认恢复出厂设置，所有用户信息将清空")?>?');"></td>
		</tr>
		</form>
		</table>	
		<br></td>
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