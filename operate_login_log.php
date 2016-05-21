#!/bin/php
<?php include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("登录记录")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
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
            WindowTitle:          '<b>运营管理</b>',
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
$UserName    =$_REQUEST["UserName"];
$ipaddr      =$_REQUEST["ipaddr"];
$starDateTime=$_REQUEST["startDateTime"];
$endDateTime =$_REQUEST["endDateTime"];
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("运营管理")?></font></td>
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
	<form action="?action=search" name="myform" method="post">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
      <tr>
        <td width="15%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="20%" align="right" class="title_bg2">&nbsp;</td>
        <td width="10%" align="right" class="title_bg2">&nbsp;</td>
        <td width="55%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
		<td align="right"><? echo _("用户帐号:")?></td>
		<td><input name="UserName" type="text" id="UserName" value="<?=$UserName?>"></td>
		<td align="right"><? echo _("开始时间：")?></td>
		<td><input type="text" name="startDateTime" onFocus="HS_setDate(this)" value="<?=$startDateTime?>"></td>
	  </tr>	  
	  <tr>
	    <td align="right"><? echo _("登录地址:")?></td>
	    <td><input type="text" name="ipaddr" value="<?=$ipaddr?>"></td>
	    <td align="right"><? echo _("结束时间:")?></td>
	    <td><input type="text" name="endDateTime" onFocus="HS_setDate(this)" value="<?=$endDateTime?>"></td>
	  </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>"></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	  </tr>
	  </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("登录记录")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="8%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="11%" align="center" class="bg f-12"><? echo _("用户名")?></th>
                <th width="19%" align="center" class="bg f-12"><? echo _("登录时间")?></th>
                <th width="18%" align="center" class="bg f-12"><? echo _("登出时间")?></th>
                <th width="13%" align="center" class="bg f-12"><? echo _("登录地址")?></th>
                <th width="31%" align="center" class="bg f-12"><? echo _("内容")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql=" 0=0 ";
if($UserName){
	$sql .=" and name like '%".mysql_real_escape_string($UserName)."%'";
}
if($ipaddr){
	$sql .=" and loginip like '%".mysql_real_escape_string($ipaddr)."%'";
}
if($startDateTime){
	$sql .=" and logindatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and logindatetime<='".$endDateTime."'";
}
$sql .=" order by ID desc";

$result=$db->select_all("*","loginlog",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=$rs['logindatetime'];?></td>
			<td align="center" class="bg"><?=$rs["logoutdatetime"]?></td>
			<td align="center" class="bg"><?=$rs["loginip"]?></td>
			<td align="center" class="bg"><?=$rs["content"]?></td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="6" align="center" class="bg">
			<?php 
				$querystring="UserName=".$UserName."&ipaddr=".$ipaddr."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."";
				$db->page($querystring); 
			?>	
			</td>
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        运营管理-> <strong>登录日志</strong>
      </p>
      <ul>
          <li>可以查看所有管理员的登录记录并进行查询操作。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

