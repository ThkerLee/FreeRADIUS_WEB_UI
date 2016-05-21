#!/bin/php
<?php 
require_once("inc/conn.php"); 
include_once("./ros_static_ip.php");
require_once("evn.php");
include_once("inc/ajax_js.php");
 ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("添加区域")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<body> 
<?php    
$_REQUEST;
$ID = $_REQUEST["ID"];
if($_POST){  
	  $sql=array(
		 "name"=>$_POST["name"], 
		 "description"=>$_POST["description"],  
	);
	
	$r=$db->update_new("area","ID='".$ID."'",$sql);
	 echo "<script>alert('". _("修改成功") ." ');window.location.href='area.php';</script>"; 
} 
$rs=$db->select_one("*","area","ID='".$ID."'"); 

?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("区域添加")?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("区域添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
   <form action="?" method="post" name="myform" >
   	<input type="hidden" value="<?=$_REQUEST["ID"]?>"name="ID" >
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("名称：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'"  value="<?=$rs['name']?>"  onBlur="this.className='input_out';ajaxInput('ajax_check.php','projectName','name','nameTXT');" class="input_out"><span id="nameTXT"></span>			</td>
		  </tr>  
		  <tr > 
		    <td align="right" class="bg" height="30px"><? echo _("备注")?>:</td> 
		    <td align="left" class="bg" height="30px"><textarea name="description" id="description"cols="50"  rows="2"><?=$rs["description"]?></textarea></td> 
		  </tr> 
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit"  value="<? echo _("提交")?>"  ></td>
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

