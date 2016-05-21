#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
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
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">用户管理</font></td>
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
			<td width="89%" class="f-bulue1">用户管理</td>
			<td width="11%" align="right">&nbsp;</td>
		  </tr>
		  </table>
		  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
			<thead>
				  <tr>
					<th width="4%" align="center" class="bg f-12">编号</th>
					<th width="15%" align="center" class="bg f-12">用户帐号</th>
					<th width="9%" align="center" class="bg f-12">用户姓名</th>
					<th width="8%" align="center" class="bg f-12">所属项目</th>
					<th width="8%" align="center" class="bg f-12">家庭电话</th>
					<th width="13%" align="center" class="bg f-12">工作电话</th>
					<th width="9%" align="center" class="bg f-12">手机号码</th>
					<th width="7%" align="center" class="bg f-12">当前订单</th>
					<th width="16%" align="center" class="bg f-12">下一个订单</th>
					<th width="11%" align="center" class="bg f-12">操作</th>
				  </tr>
			</thead>	     
			<tbody>  
	<?php 
	$result=$db->select_all("*","userinfo","",20);
		if(is_array($result)){
			foreach($result as $key=>$rs){
					
			
			//查询出用户是否存在未进行完的订单
			$userrun=$db->select_one("ur.*,o.*","userrun as ur,orderinfo as o","ur.status=0 and ur.orderID=o.ID and ur.userID='".$rs["ID"]."' order by ur.ID asc");
			$nextOrder=($userrun)?productShow($userrun["productID"]):"无等待订单";
	?>   
			  <tr>
				<td align="center" class="bg"><?=$rs['ID'];?></td>
				<td align="center" class="bg"><?=$rs['account'];?></td>
				<td align="center" class="bg"><?=$rs["name"]?></td>
				<td align="center" class="bg"><?=projectShow($rs["projectid"])?></td>
				<td align="center" class="bg"><?=$rs['homephone'];?></td>
				<td align="center" class="bg"><?=$rs['workphone'];?></td>
				<td align="center" class="bg"><?=$rs["mobile"]?></td>
				<td align="center" class="bg"><?=$rs["money"]?></td>
				<td align="center" class="bg"><?=$nextOrder?></td>
				<td align="center" class="bg">
				  <a  href="user_edit.php?ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
				  <a  href="user_del.php?ID=<?=$rs['ID'];?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
			  </tr>
	<?php  }} ?>
	
			  <tr>
				<td colspan="10" align="center" class="bg">
					<?php $db->page(); ?>			
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
</body>
</html>

