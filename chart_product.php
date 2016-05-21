#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("营帐管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("产品管理")?></font></td>
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" align="center" valign="top">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="bd">
		  <tr>
			<td width="14%" valign="middle" class="f-bulue1 title_bg2"><? echo _("产品数据分析")?></td>
			<td align="right" class="title_bg2">&nbsp;</td>
	      </tr>
		  <form action="?" name="myform" method="post">
		  <tr>
		    <td height="22" align="right"><? echo _("时间：")?></td>
		    <td><? echo _("从")?>
		      <input type="text" name="startDateTime" value="<?=$_REQUEST["startDateTime"]?>" onFocus="HS_setDate(this)">
		      <? echo _("至")?>
		      <input type="text" name="endDateTime" value="<?=$_REQUEST["endDateTime"]?>" onFocus="HS_setDate(this)">		      </td>
		    </tr>
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input type="submit" value="<? echo _("提交搜索")?>"></td>
			</tr>
		  <tr>
		    <td align="right">&nbsp;</td>
		    <td>&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="right">&nbsp;</td>
		    <td>&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="right">&nbsp;</td>
		    <td>&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="right" class="bd_b">&nbsp;</td>
		    <td class="bd_b">&nbsp;</td>
		    </tr>
		  </form>
		  <tr>
		    <td colspan="2" align="left">
				<?php
					$startDateTime=$_REQUEST["startDateTime"];
					$endDateTime  =$_REQUEST["endDateTime"];
					include_once 'php-ofc-library/open_flash_chart_object.php';
					open_flash_chart_object( 800, 800, 'chart_product_data.php?startDateTime='.$startDateTime.'&endDateTime='.$endDateTime.'');	
					$where_sql="0=0";

				
				?>			</td>
	      </tr>
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
<script src="js/jsdate.js" type="text/javascript"></script>
</body>
</html>