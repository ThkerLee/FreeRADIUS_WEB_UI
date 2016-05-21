#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai'); 
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
<?php 
$todaytime  =date("Y-m-d H:i:s",time());
$timeleng   =$_POST["timeleng"];
$logtimeleng=$_POST['logtimeleng'];
$logintimeleng=$_POST['logintimeleng']; 
if($timeleng!='')
  $rethertime =date("Y-m-d H:i:s",(time()-$timeleng*3600*24)); 

if($logtimeleng!='')
  $logtime =date("Y-m-d H:i:s",(time()-$logtimeleng*3600*24)); 
  
if($logintimeleng!='')
  $logintime =date("Y-m-d H:i:s",(time()-$logintimeleng*3600*24));  
 
if($_POST){
   if($logtimeleng=='' && $timeleng=='' && $logintimeleng==''){
     echo "<script>alert('"._("请选择清空项")."');window.history.go(-1);</script>"; 
	 exit;
   }else{
   //清除拨号记录
        if($timeleng =="all") {
		    $db->query("truncate table bak_acct"); 
		 }else{
		    $db->query("delete from bak_acct where AcctStopTime<='".$rethertime."'"); 
		} 
	//清除操作日志		
		if($logtimeleng =="all") {
		    $db->query("truncate table userlog");  
		 }else{
		   $db->query("delete from userlog where adddatetime<='".$logtime."'"); 
		}
	//清除登录日志		
		if($logintimeleng =="all") { 
		    $db->query("truncate table loginlog");  
		 }else{
		   $db->query("delete from loginlog where logindatetime<='".$logtime."'"); 
		}		
      echo "<script>alert('"._("清除成功!")."');window.history.go(-1);</script>";
	  exit;
   }
 
}
?>
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
		<td width="89%" class="f-bulue1"><? //echo _("数据库拨号记录")?><? echo _("数据库记录清除")?></td>
		<td width="11%" align="right">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
		<form action="?action=cleanlog" method="post" name="myform">
		<tr>
		<td width="19%" align="right" class="bg"><? echo _("拨号记录:")?></td><!--清除时间-->
		<td width="81%" class="bg">
		<input type="radio" name="timeleng" value="" <?php if($timeleng=='') echo "checked";?>><? echo _("暂不清除")?>
		<input type="radio" name="timeleng" value="15" <?php if($timeleng==15) echo "checked";?>><? echo _("15天之前")?>
		<input type="radio" name="timeleng" value="30" <?php if($timeleng==30) echo "checked";?>><? echo _("一个月之前")?>
		<input type="radio" name="timeleng" value="90" <?php if($timeleng==90) echo "checked";?>><? echo _("三个月之前")?>
		<input type="radio" name="timeleng" value="all" <?php if($timeleng=='all') echo "checked";?>><? echo _("所有记录")?>
		</td>
		</tr> 
		<tr>
		<td width="19%" align="right" class="bg"><? echo _("操作日志:")?></td>
		<td width="81%" class="bg">
		<input type="radio" name="logtimeleng" value="" <?php if( $logtimeleng=='') echo "checked";?>><? echo _("暂不清除")?>
		<input type="radio" name="logtimeleng" value="15" <?php if($logtimeleng==15) echo "checked";?>><? echo _("15天之前")?>
		<input type="radio" name="logtimeleng" value="30" <?php if($logtimeleng==30) echo "checked";?>><? echo _("一个月之前")?>
		<input type="radio" name="logtimeleng" value="90" <?php if($logtimeleng==90) echo "checked";?>><? echo _("三个月之前")?>
		<input type="radio" name="logtimeleng" value="all" <?php if($logtimeleng=='all') echo "checked";?>><? echo _("所有记录")?>
		</td>
		</tr>
		<tr>
		<td width="19%" align="right" class="bg"><? echo _("登录记录:")?></td>
		<td width="81%" class="bg">
		<input type="radio" name="logintimeleng" value="" <?php if($logintimeleng=='') echo "checked";?>><? echo _("暂不清除")?>
		<input type="radio" name="logintimeleng" value="15" <?php if($opetimeleng==15) echo "checked";?>><? echo _("15天之前")?>
		<input type="radio" name="logintimeleng" value="30" <?php if($logintimeleng==30) echo "checked";?>><? echo _("一个月之前")?>
		<input type="radio" name="logintimeleng" value="90" <?php if($logintimeleng==90) echo "checked";?>><? echo _("三个月之前")?>
		<input type="radio" name="logintimeleng" value="all" <?php if($logintimeleng=='all') echo "checked";?>><? echo _("所有记录")?>
		</td>
		</tr>
		<tr>
		<td align="right" class="bg">&nbsp;</td>
		<td class="bg"><input type="submit" value="<? echo _("清除记录")?>"></td>
		</tr>
		</form>
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>清空记录</strong>
      </p>
      <ul>
          <li>此功有可允许清空数据库中的用户拨号记录、操作日志、登录记录。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

