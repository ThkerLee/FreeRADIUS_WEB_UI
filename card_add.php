#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("卡片管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<!--<script src="js/jquery.js" type="text/javascript"></script>--和下面的jquery冲突-->
<script src="js/jsdate.js" type="text/javascript"></script>
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
            WindowTitle:          '<b>卡片管理</b>',
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
if($_POST){
$prefix     =$_POST["prefix"];
$startNum   =$_POST["startNum"];
$endNum     =$_POST["endNum"];
$money      =$_POST["money"];
$ivalidTime =$_POST["ivalidTime"];
$remark     =$_POST["remark"];
addCard($prefix,$startNum,$endNum,$money,$ivalidTime,$remark);
echo "<script>alert('"._("卡片生成成功!")."');window.location.href='card.php';</script>";
exit;
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
          <td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("卡片管理")?></font></td>
            <td width="3%" height="35">
                <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
            </td> <!------帮助--2014.06.07----->          
        </tr>
      </table></td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">

	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
        <tr>
          <td width="89%" class="f-bulue1"> <? echo _("生成卡片")?></td>
          <td width="11%" align="right">&nbsp;</td>
        </tr>
      </table>
      <form action="?" method="post" name="myform" onSubmit="return checkCardForm();">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
          <tbody>
            <tr>
              <td width="13%" align="right" class="bg"><? echo _("卡号前缀:")?></td>
              <td width="87%" align="left" class="bg"><input type="text" id="prefix" name="prefix" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"> 
              <? echo _("生成卡号的前缀，比如：natshell 那这次生成卡号编号将会是以(卡号前缀+卡号)")?> </td>
            </tr>
            <tr>
              <td align="right" class="bg"><? echo _("起始卡号:")?></td>
              <td align="left" class="bg"><input type="text" id="startNum" name="startNum" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out">
              <? echo _("设置卡号的起始编号，只能是(0-9)数字")?></td>
            </tr>
            <tr>
              <td align="right" class="bg"><? echo _("结束卡号:")?></td>
              <td align="left" class="bg"><input type="text" id="endNum" name="endNum" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out">
              <? echo _("设置卡号的结束编号，只能是(0-9)数字")?></td>
            </tr>
            <tr>
              <td align="right" class="bg"><? echo _("卡片金额:")?></td>
              <td align="left" class="bg"><input type="text" id="money" name="money" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"> 
                <? echo _("设置卡号的充值金额，只能是(0-9)数字，并且不能有小数位")?></td>
            </tr>
            <tr>
              <td align="right" class="bg"><? echo _("失效时间:")?></td>
              <td align="left" class="bg"><input type="text" id="ivalidTime" name="ivalidTime" onFocus="this.className='input_on';HS_setDate(this)" onBlur="this.className='input_out';" class="input_out"> 
              <? echo _("设置卡号的失效时间，如果此卡号没有在规定的时间内使用将会自动失效")?></td>
            </tr>
            <tr>
              <td align="right" class="bg"><? echo _("备注:")?> </td>
              <td align="left" class="bg"><input type="text" id="remark" name="remark" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"></td>
            </tr>
            <tr>
              <td align="right" class="bg">&nbsp;</td>
              <td align="left" class="bg"><input type="submit" value="<? echo _("提交")?>">              </td>
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        卡片管理-> <strong>生成卡片</strong>
      </p>
      <ul>
          <li>根据管理员设定，生成指定金额和有效日期的充值卡，密码随机设定以防止破解。</li>
          <li>卡号前缀：生成卡号的前缀，比如：NS，那么该次生成卡号编号将会是以 NS 卡号的形式生成。</li>
          <li>起始卡号：设置卡号的起始编号，只能是（0-9）数字。</li>
          <li>结束卡号：设置卡号的结束编号，只能是（0-9）数字。</li>
          <li>卡片金额：设置卡号的充值金额，只能是（0-9）数字，并且不能有小数位。</li>
          <li>失效时间：设置卡号的失效时间，如果此卡号没有在规定的时间内使用将会自动失效。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>
