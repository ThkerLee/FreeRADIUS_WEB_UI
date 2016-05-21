#!/bin/php
<?php include("inc/conn.php"); ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("我要留言")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<?php 
$ID =$_REQUEST["ID"];
if($_POST){
	$sql=array(
		"status"    =>$_POST["status"],
		"title"     =>$_POST["title"],
		"content"   =>$_POST["content"],
		"reply"     =>$_POST["reply"],
		"updatetime"=>date("Y-m-d H:i:s",time())
	);
	$db->update_new("guestbook","ID='".$ID."'",$sql);
	echo "<script language='javascript'>alert('". _("留言成功") ." ');window.location.href='guestbook.php';</script>";
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
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("留言管理")?></font></td>
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
        <td width="93%" class="f-bulue1"><? echo _("留言管理")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
	  <?php 
	  	$rs=$db->select_one("*","guestbook","ID='".$ID."'");
	  ?>
  	  <form action="?action=addSave" name="myform" method="post">
	  	<input type="hidden" name="ID" value="<?=$ID?>">
		  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
			  <tr>
				<td width="18%" align="right" class="bg"><? echo _("标题：")?></td>
				<td width="82%" class="bg"><input type="text" name="title" value="<?=$rs["title"]?>"></td>
			  </tr>
			  <tr>
				<td align="right" class="bg"><? echo _("内容：")?></td>
				<td class="bg"><textarea name="content" rows=5 cols=60><?=$rs["content"]?></textarea></td>
			  </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("回复：")?></td>
			    <td class="bg"><textarea name="reply" rows=5 cols=60><?=$rs["reply"]?></textarea></td>
		    </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("状态：")?></td>
			    <td class="bg">
				<input type="radio" name="status" value="1" <?php if($rs["status"]==1) echo "checked"; ?>><? echo _("启用")?>
				<input type="radio" name="status" value="0" <?php if($rs["status"]=="0") echo "checked"; ?>><? echo _("禁用")?>
				</td>
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

