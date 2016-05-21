#!/bin/php
<?php 
include("inc/conn.php");  
require_once("evn.php"); 
$rs=$db->select_one("*","config","0=0 order by ID desc limit 0,1");
if($_GET["action"]=="remote"){
	$permissionType=$_REQUEST["permissionType"];
	$speedStatus   =$_REQUEST["speedStatus"];
	$macStatus     =$_REQUEST["macStatus"];
	$vlanStatus    =$_REQUEST["vlanStatus"];
	$nasStatus     =$_REQUEST["nasStatus"];
	$onlinenum     =$_REQUEST["onlinenum"];
	////数据库外部链接操作
//	if($permissionType==1){
//		mysql_query("grant all privileges on radius.* to radius@'%' identified by 'ds549gGF32fdkk2Ter675wiyi23de5' with grant option",$conn) ;
//	}else if($permissionType==0){
//		mysql_query("delete from user where User='radius' and Host='%'",$conn);
//	} 
	//内部限速操作
	if($speedStatus!=''){ 
		$sql=array(
			"speedStatus"=>$_POST["speedStatus"]
		);
		$db->update_new("config","",$sql);
		$db->query("update userattribute set speedrule='".$speedStatus."'"); 
	}
	//绑定MAC操作
	if($macStatus!=''){ 
		$sql=array(
			"macStatus"=>$_POST["macStatus"]
		);
		$db->update_new("config","",$sql);
		$db->query("update userattribute set macbind='".$macStatus."'"); 
	}
	//绑定VLAN操作
	if($vlanStatus!=''){ 
		$sql=array(
			"vlanStatus"=>$_POST["vlanStatus"]
		);
		$db->update_new("config","",$sql);
		$db->query("update userattribute set vlanbind='".$vlanStatus."'"); 
	}//绑定NAS IP操作
	if($nasStatus!=''){ 
		$sql=array(
			"nasStatus"=>$_POST["nasStatus"]
		);
		$db->update_new("config","",$sql);
		$db->query("update userattribute set nasbind='".$nasStatus."'"); 
	}
	//绑定在线人数操作
	if($onlinenum!=''){ 
		$sql=array(
			"onlinenum"=>$_POST["onlinenum"]
		);
		$db->update_new("config","",$sql);
		$db->query("update userattribute set onlinenum='".$onlinenum."'"); 
	} 
	echo "<script>alert('"._("操作成功")."');window.location.href='system_configuration.php';</script>";
	exit;
} 
elseif($_REQUEST["action"]=='macempty'){ 
   $mac=array(
			"MAC"=>''
	);
   $db->update_new("userinfo","",$mac);
   echo "<script>alert('"._("操作成功")."');window.location.href='system_configuration.php';</script>";
   exit;
}elseif($_REQUEST["action"]=='nasempty'){ 
   $nas=array(
			"NAS_IP"=>''
	);
   $db->update_new("userinfo","",$nas);
   echo "<script>alert('"._("操作成功")."');window.location.href='system_configuration.php';</script>";
   exit;
}elseif($_REQUEST["action"]=='vlanempty'){
   $vlan=array(
			"VLAN"=>''
	);
   $db->update_new("userinfo","",$vlan);
   echo "<script>alert('"._("操作成功")."');window.location.href='system_configuration.php';</script>";
   exit;

}
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
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
            WindowTitle:          '<b>系统设置</b>',
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
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统设置")?></font></td>
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
		<td width="89%" class="f-bulue1"><? echo _("批量配置")?></td>
		<td width="11%" align="right">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
		<form action="?action=remote" method="post" name="myform">
		<!--<tr>
			<td width="18%" align="right" class="bg"><? echo _("链接类类型:")?></td>
			<td width="82%" class="bg">
			    <input type="radio" name="permissionType" value="1" <?php if($dbRs["Host"]=="%") echo "checked"; ?>>
			<? echo _("启用远程数据库管理")?>
			    <input type="radio" name="permissionType" value="0" <?php if($dbRs["Host"]!="%") echo "checked"; ?>>	<? echo _("禁用远程数据库管理")?></td>
		</tr>-->
		<tr>
		    <td width="18%" height="30" align="right" class="bg"><? echo _("是否启用户内部限速")?></td>
	        <td width="82%" align="left" class="bg">
			    <input type="radio" name="speedStatus" value="" <?php if($rs["speedStatus"]=="") echo "checked"; ?>><? echo _("暂不更新");?>
			    <input type="radio" name="speedStatus" value="1" <?php if($rs["speedStatus"]=="1") echo "checked"; ?>><? echo _("启用");?>
				<input type="radio" name="speedStatus" value="0" <?php if($rs["speedStatus"]=="0") echo "checked"; ?>><? echo _("禁用");?> 
				<span class="f-bulue1"><? echo _("当启用内部限速之后会用户的限速规则会以内部限速为准")?></span></td>
		</tr> 
		<tr>
		    <td width="18%" height="30" align="right" class="bg"><? echo _("是否启用MAC地址绑定")?></td>
	        <td width="82%" align="left" class="bg"> 
			    <input type="radio" name="macStatus" value="" <?php if($rs["macStatus"]=='') echo "checked"; ?>><? echo _("暂不更新")?>
			    <input type="radio" name="macStatus" value="1" <?php if($rs["macStatus"]==1) echo "checked"; ?>><? echo _("启用")?>
				<input type="radio" name="macStatus" value="0" <?php if($rs["macStatus"]=="0") echo "checked"; ?>><? echo _("禁用")?> 
				<input type="button" name="macempty"  onClick="myform.action='?action=macempty';myform.submit();"value="<? echo _("全部清空")?>" > </td>
	   </tr>
	  <tr>
		    <td width="18%" height="30" align="right" class="bg"><? echo _("是否启用VLAN地址绑定")?></td>
	        <td width="82%" align="left" class="bg">
			    <input type="radio" name="vlanStatus" value="" <?php  if($rs["vlanStatus"]=='') echo "checked"; ?>><? echo _("暂不更新")?>
			    <input type="radio" name="vlanStatus" value="1" <?php if($rs["vlanStatus"]==1) echo "checked"; ?>><? echo _("启用")?>
				<input type="radio" name="vlanStatus" value="0" <?php if($rs["vlanStatus"]=="0") echo "checked"; ?>><? echo _("禁用")?>
				<input type="button" name="vlanempty" onClick="myform.action='?action=vlanempty';myform.submit();" value="<? echo _("全部清空")?>"  ></td></td>
	   </tr> 
	   <tr>
		    <td width="18%" height="30" align="right" class="bg"><? echo _("是否启用NAS IP地址绑定")?></td>
	        <td width="82%" align="left" class="bg">
			    <input type="radio" name="nasStatus" value="" <?php  if($rs["nasStatus"]=='') echo "checked"; ?>><? echo _("暂不更新")?>
			    <input type="radio" name="nasStatus" value="1" <?php if($rs["nasStatus"]==1) echo "checked"; ?>><? echo _("启用")?>
				<input type="radio" name="nasStatus" value="0" <?php if($rs["nasStatus"]=="0") echo "checked"; ?>><? echo _("禁用")?>
				<input type="button" name="nasempty" onClick="myform.action='?action=nasempty';myform.submit();"value="<? echo _("全部清空")?>"  > </td>
	   </tr>
	    <tr>
		    <td width="18%" height="30" align="right" class="bg"><? echo _("统一设置用户同时在线人数")?></td>
	        <td width="82%" align="left" class="bg">
				<input type="text" name="onlinenum" value="<?=$rs["onlinenum"]?>">
			</td>
	   </tr>
		<tr>
		<td align="right" class="bg">&nbsp;</td>
		<td class="bg"><input type="submit" value="<?php echo _("保存设置")?>"></td>
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>批量配置</strong>
      </p>
      <ul>
          <li>管理员可以对用户内部限速、是否启用MAC地址绑定、是否启用VLAN地址绑定、是否启用NAS IP地址绑定、同时在线人数统一操作。</li>
          <li>是否启用用户内部限速： 此功有可统一设置是否启用内部用户单独限速功能，选择启用或禁用，点击保存，立刻生效，此页面无状态记录，即不会出现启用或禁用状态。</li>
          <li>是否启用 MAC 地址绑定：此功有可统一设置是否启用 MAC 地址绑定，选择启用或禁用，点击保存，立刻生效，此页面无状态记录，即不会出现启用或禁用状态。</li>
          <li>是否启用 VLAN 地址绑定：统一设置所有的用户是否启用VLAN地址绑定，选择启用或禁用，点击保存，立刻生效，此页面无状态记录，即不会出现启用或禁用状态。</li>
          <li>是否启用 NAS IP 地址绑定：统一设置所有的用户是否启用NAS IP绑定，选择启用或禁用，点击保存，立刻生效，此页面无状态记录，即不会出现启用或禁用状态</li>
          <li>统一设置用户同时在线人数：此功能可统一设置同一帐号允许同时在线人数功能，填写数字，点击保存，立刻生效，此页面无状态记录，即不会出现当前为状态为允许几个用户同时在线。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

