#!/bin/php
<?php include("inc/conn.php");
include_once("evn.php"); 
date_default_timezone_set('Asia/Shanghai');
 ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("报修登记")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<?php 
$ID     =$_REQUEST["ID"];
$status =$_POST["status"];
$reply  =$_POST["reply"];
if($_POST){
	if($status==3){
		$db->query("update repair set reply='".$reply."' where ID='".$ID."'");
		echo "<script language='javascript'>alert('"._("回复成功")."');window.location.href='repair.php';</script>";	
	}
}
$rs=$db->select_one("*","repair","ID='".$ID."'");
if($rs["status"]=="1"){
	$status_str ="<font color='#DA251D'>"._("报修中")."</font>";
}else if($rs["status"]=="2"){
	$status_str="<font color='#FfC330'>"._("处理中")."</font>";
}else if($rs["status"]=="3"){
	$status_str="<font color='#00923F'>"._("处理完成")."</font>";
} 
?>
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
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="93%" class="f-bulue1"><? echo _("报修登记")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <form action="?action=addSave" name="myform" method="post">
	 	 <input type="hidden" name="ID" value="<?=$ID?>">
	  	  <input type="hidden" name="status" value="<?=$rs["status"]?>">
		  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
			  <tr>
				<td width="18%" align="right" class="bg"><? echo _("报修用户")?>:</td>
				<td width="82%" class="bg"><?=$rs["UserName"]?></td>
			  </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("当前状态")?>:</td>
			    <td class="bg"><?=$status_str?> <a href="repair_disposal.php?ID=<?=$rs["ID"]?>"><? echo _("查看处理记录")?></a></td>
		    </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("报修时间")?>:</td>
			    <td class="bg"><?=$rs["startdatetime"]?></td>
		    </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("事件原因")?>:</td>
			    <td class="bg"><?=$rs["reason"]?></td>
		    </tr>
			  <tr>
				<td align="right" valign="top" class="bg"><? echo _("回复用户")?>:</td>
				<td class="bg">
					<textarea name="reply" rows=8 cols=50><?=$rs["reply"]?></textarea>				</td>
			  </tr>
			  <tr>
				<td align="right" class="bg">&nbsp;</td>
				<td class="bg">
					<input type="submit" value="<? echo _("提交")?>">				</td>
			  </tr>
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

