#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("报修记录")?></title>
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
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("报修管理")?></font></td>
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
			<td width="100%" valign="middle" class="f-bulue1 title_bg2" colspan="2"><? echo _("财务数据分析")?></td>
			 
	      </tr>
		  <form action="?" name="myform" method="post">
		  <tr>
		    <td height="22" align="right"><? echo _("年份:")?></td>
		    <td><input type="text" name="search_year" value="<?=$_POST["search_year"]?>">
		     <? echo _("格式");?> ：2010</td>
		    </tr>
		  <tr>
			<td align="right" class="bd_b">&nbsp;</td>
			<td class="bd_b"><input type="submit" value="<? echo _("提交搜索")?>"></td>
			</tr>
		  </form>
		  <tr>
		    <td colspan="2" align="left">
				<?php
					$search_year=$_REQUEST["search_year"];
					include_once 'php-ofc-library/open_flash_chart_object.php';
					open_flash_chart_object( 800, 400, 'chart_report_data.php?search_year='.$search_year);		
				?>				
			</td>
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
</body>
</html>