#!/bin/php
<?php include("inc/conn.php");  
include_once("evn.php"); ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("管理员密码修改")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
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
<?php 
$ID=$_SESSION["managerID"]; 
$manager=$_SESSION["manager"];
$rs =$db->select_one("*","manager","ID='".$ID."'");
if($_POST){ 
	$manager_passwd   =$_POST["newpwd"];  
	/*if($manager_passwd!=$pwd ){
	 echo "<script>alert('两次密码输入不一致'); window.location.href='manager_pwd_edit.php?managerID=".$ID."';</script>";
	 exit;
	} */ 
	$db->update_new("manager","manager_account='".$_POST["manager_account"]."'",array("manager_passwd"=>$manager_passwd));

	echo "<script>alert('密码修改成功');window.location.href='manager.php';</script>";
	
} 

//查询项目集合
 
  if($manager !="admin"){  
 ?>
 <form action="?ID=<?=$ID?>" method="post" name="myform"  onSubmit="return checkManagerFrom();">
  
 <table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统权限")?></font></td>
                        <td width="3%" height="35">
                                <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                        </td> <!------帮助--2014.06.07----->
		  </tr>
   		</table>	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("系统用户修改")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr> 
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
    	
      <tr>
        <td align="right" class="bg"><? echo _("系统帐号")?>:</td>
        <td align="left" class="bg"><input type="text" name="manager_account" value="<?=$rs["manager_account"]?>" readonly="readonly"></td>
      </tr> 
      
      <tr >
        <td align="right" class="bg"><? echo _("旧 密 码")?>:</td>
        <td colspan="2" align="left" class="bg">
		    <input type="hidden" name="oldpwd" value="<?=$rs["manager_passwd"]?>" >
			   <input type="password" name="manager_passwd" >		</td>
      </tr>
	   <tr>
        <td align="right" class="bg"><? echo _("新 密 码")?>:</td>
        <td colspan="2" align="left" class="bg">
			<input type="password" name="newpwd" >		</td>
      </tr>
	  <tr>
        <td align="right" class="bg"><? echo _("确认密码")?>:</td>
        <td colspan="2" align="left" class="bg">
			<input type="password" name="newpwd1" >		</td>
      </tr> 
       <tr>
        <td align="right" class="bg">&nbsp;</td>
        <td align="left" class="bg"><input name="submit" type="submit" value="<? echo _("提交")?>">        </td>
      </tr>
    </tbody>
  </table> 
</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>   
</form>	
<?php
} elseif($manager =="admin"){
	
?>
<form action="?" method="post" name="myform"  onSubmit="return checkManagerFrom();">
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统权限")?></font></td>
                        <td width="3%" height="35">
                                <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                        </td> <!------帮助--2014.06.07----->
		  </tr>
   		</table>	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("系统用户修改")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
 
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
      <tr>
        <td align="right" class="bg"><? echo _("系统帐号")?>:</td>
        <td align="left" class="bg"><input type="text"  id="manager_account" name="manager_account" value="<?=$rs["manager_account"]?>" onBlur="this.className='input_out';ajaxInput('ajax_check.php','manager_account','manager_account','manager_accountTXT');"> 
        </td>
      </tr>  
      <tr >  
        <td align="right" class="bg">
        <? echo _("旧 密 码")?>:<br><br>
        <? echo _("新 密 码")?>:<br><br><br>
        <? echo _("确认密码")?>:<br><br>
        </td>
        <td colspan="2" align="left" class="bg">
         <span id="manager_accountTXT">
		      <input type="hidden" name="oldpwd" value="<?=$rs["manager_passwd"]?>" >
			    <input type="text" name="manager_passwd" readonly="readonly"  value="<?=$rs["manager_passwd"]?>" ><br><br>
			    <input type="password" name="newpwd" value=""><br><br>
			    <input type="password" name="newpwd1" value="">	<br><br>
    	  </span>
				</td> 
      </tr> 
    <tr>
        <td align="right" class="bg">&nbsp;</td>
        <td align="left" class="bg"><input name="submit" type="submit" value="<? echo _("提交")?>">        </td>
      </tr>
    </tbody>
  </table> 
</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>  
</form>	

<?php
	}
?>
  <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>密码修改</strong>
      </p>
      <ul>
          <li>支持管理员、操作员修改自己的登录密码。</li>
          <li>admin管理账号可以修改所有操作员的密码。</li>
      </ul>

    </div>
<!---------------------------------------------->   
</body>
</html>

