#!/bin/php
<?php include("inc/conn.php"); 
include("inc/loaduser.php");
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai'); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
<script src="js/jsdate.js" type="text/javascript"></script>



</head>
<body>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">运营管理</font></td>
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
        <td width="12%" class="f-bulue1 title_bg2">条件搜索</td>
		<td width="88%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
	  	<td align="right">用户帐号：</td>
		<td><input type="text" name="account"></td>
	  </tr>
	  <tr>
	    <td align="right">开始时间：</td>
	    <td><input type="text" name="startDateTime" onFocus="HS_setDate(this)"></td>
	    </tr>
	  <tr>
	    <td align="right">结束时间：</td>
	    <td><input type="text" name="endDateTime" onFocus="HS_setDate(this)"></td>
	    </tr>
		  <tr>
			<td align="right">所属项目：</td>
			<td><?php projectSelected() ?></td>
			</tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="提交"></td>
	    </tr>
	  </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1">用户计时间帐单列表</td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
        <thead>
              <tr>
                <th width="4%" align="center" class="bg f-12">编号</th>
                <th width="15%" align="center" class="bg f-12">用户帐号</th>
                <th width="9%" align="center" class="bg f-12">用户姓名</th>
                <th width="13%" align="center" class="bg f-12">使用费用</th>
                <th width="16%" align="center" class="bg f-12">拨号时间</th>
                <th width="16%" align="center" class="bg f-12">在线时长</th>
                <th width="16%" align="center" class="bg f-12">备注</th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql="r.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
$account         =$_REQUEST["account"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$operator		 =$_REQUEST["operator"];
$type 			 =$_REQUEST["type"];
$projectID		 =$_REQUEST["projectID"];
$querystring="account=".$account."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&operator=".$operator."&type=".$type."&projectID=".$projectID."";

if($account){
	$sql .=" and u.account like '%".$account."%'";
}
if($startDateTime){
	$sql .=" and r.adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and r.adddatetime<'".$endDateTime."'";
}
if($projectID){
	$sql .=" and u.projectID='".$projectID."'";
}
$sql .=" order by rID desc";

$result=$db->select_all("r.price,r.ID as rID,r.stats,r.adddatetime,u.UserName","runinfo as r,userinfo as u",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['rID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=$rs['price'];?></td>
			<td align="center" class="bg"><?=$rs["adddatetime"]?></td>
		    <td align="center" class="bg"><?=$rs["stats"]?></td>
		    <td align="center" class="bg"><?=$rs["remark"]?></td>
		  </tr>
<?php  }} ?>
        </tbody>      
    </table>
	<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
		<tr>
		    <td align="center" class="bg">
				<?php $db->page(); ?>			
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

