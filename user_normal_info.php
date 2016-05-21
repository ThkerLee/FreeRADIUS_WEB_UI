#!/bin/php
<?php include("inc/conn.php");
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai'); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理");?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
<!--这是点击帮助的脚本-2014.06.07-->
    <link href="js/jiaoben/css/chinaz.css" rel="stylesheet" type="text/css"/>
   
    <script type="text/javascript" src="js/jiaoben/js/jquery-ui-1.8.1.custom.min.js"></script> 
    <script type="text/javascript" src="js/jiaoben/js/jquery.easing.1.3.js"></script>        
    <script type="text/javascript" src="js/jiaoben/js/jquery-chinaz.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {  		
        $('#Firefoxicon').click(function() {
          $('#Window1').chinaz({
            WindowTitle:          '<b>用户管理</b>',
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
//有网用户  
 // 1、用户总数 - 停机用户数（含销户用户）
$UserName        =$_REQUEST["UserName"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$areaID          =$_REQUEST["areaID"];
//$projectID       =explode(",",$_POST["areaprojectID"]);
//$projectID       =$projectID[1]; //2014.08.13修改
$projectID      =$_REQUEST["projectID"];//2014.08.13修改
$productID       =$_REQUEST["productID"];  
$operator        =$_REQUEST["operator"]; 
$type 			     =$_REQUEST["type"]; 
$NAS_IP=$_REQUEST["NAS_IP"];
//$querystring="account=".$account."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&operator=".$operator."&type=".$type."&projectID=".$projectID."&c_type=".$c_type."&areaID=".$areaID.""; 
$querystring="areaID=".$areaID."&UserName=".$UserName."&name=".$name."&sex=".$sex."&birthday=".$birthday."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&projectID=".$projectID."&productID=".$productID."&address=".$address."&NAS_IP=".$NAS_IP."&MAC=".$MAC."&operator=".$operator."&receipt=".$receipt."&action=".$action.""; 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理");?></font></td>
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
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索");?></td>
			<td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="9%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="56%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"><? echo _("所属区域");?>:</td>
		    <td><? selectArea($areaID);?></td>
		    <td align="right"><? echo _("联系地址");?>:</td>
		    <td><input type="text" name="address" value="<?=$address?>"></td>
	      </tr>
		  <tr>
		    <td align="right"><? echo _("所属项目")?>:</td>
		    <td align="left" class="bg" id="projectSelectDIV"> 
		    <select><option><? echo _("选择项目");?></option></select>
			  </td>
		    <td align="right"><? echo _("用户姓名");?>:</td>
		    <td><input type="text" name="name" value="<?=$name?>"></td>
	      </tr>
		  <tr>
		  	<td align="right"><? echo _("用户产品")?>:</td>
			  <td align="left" class="bg"  id="productSelectDIV">
			 	<select ><option><? echo _("请选择产品");?></option></select> 
		    <span id='productTXT'></span>
		    </td> 
		    <td align="right"><? echo _("开始时间");?>:</td>
		    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)">
		      -
	            <input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>
		  </tr>
		  <tr>
		  <td align="right"><? echo _("用户帐号");?>:</td>
			<td><input type="text" name="UserName" value="<?=$UserName?>"></td> 
			<td align="right"><? echo _("MAC 地址");?>:</td>
			<td>
				<input type="text" name="MAC" value="<?=$MAC?>">			</td>
		  </tr>
		  <tr>
		  	<td align="right"><? echo _("NAS 地址");?>:</td>
			  <td><input type="text" name="NAS_IP" value="<?=$NAS_IP?>" ></td>
		    <td align="right"><? echo _("收据单号");?>:</td>
		    <td><input type="text" name="receipt" value="<?=$receipt?>"></td>
	      </tr>
		  <tr>
		  	<td align="right"><? echo _("装机人员");?>:</td>
		    <td><?php managerSelect($operator); ?></td> 
			  <td align="right">&nbsp;</td>
			  <td><input name="submit" type="submit" value="<? echo _("提交搜索");?>">
      
       <a href="PHPExcel/excel_normalinfo.php?<?=$querystring?>" style="color:#FF3300;"><? echo _("EXCEL导出");?></a></td>
		  
		  </tr>
	    </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="93%" class="f-bulue1"><? echo _("有网用户管理");?></td>
		<td width="7%" align="center"><a href="user_add.php" class="f-b"><? echo _("添加用户");?></a></td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
        <thead>
              <tr>
                <td width="2%" align="center" class="f-b bg f-12 "><input type="checkbox" name="allID" value=""></td>
                <td width="3%" align="center" class="f-b bg f-12"><? echo _("编号");?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("用户帐号");?></td>
                <td width="6%" align="center" class="f-b bg f-12"><? echo _("用户姓名");?></td>
                <td width="12%" align="center" class="f-b bg f-12"><? echo _("所属项目");?></td>
                <td width="17%" align="center" class="f-b bg f-12"><? echo _("使用产品");?></td>
                <td width="9%" align="center" class="f-b bg f-12"><? echo _("MAC地址");?> </td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("手机号码");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("余额");?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("开始时间");?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("结束时间");?></td>
                <td width="3%" align="center" class="f-b bg f-12"><? echo _("状态");?></td>
                <td width="3%" align="center" class="f-b bg f-12"><? echo _("在线");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("报修");?></td>
                <td width="5%" align="center" class="f-b bg f-12"><? echo _("操作");?></td>
              </tr>
        </thead>	     
        <tbody>  
<?php 

$nowTime  = date("Y-m-d H:i:s",time());

$sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and (r.enddatetime>'$nowTime' or r.enddatetime = '0000-00-00 00:00:00'  )and r.userID=u.ID and r.orderID=o.ID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") ";
if($UserName){
	$sql .=" and u.UserName like '%".mysql_real_escape_string($UserName)."%'";
}
if($name){
	$sql .=" and u.name like '%".mysql_real_escape_string($name)."%'";
}
if($areaID){
   $sql .=" and u.areaID=".$areaID;
}
if($address){
	$sql .=" and u.address like '%".mysql_real_escape_string($address)."%'";
}
if($NAS_IP){
	$sql .=" and u.NAS_IP like '%".mysql_real_escape_string($NAS_IP)."%'";
}
if($startDateTime){
	$sql .=" and r.begindatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and r.begindatetime<='".$endDateTime."'";
}
if($projectID){
	$sql .=" and u.projectID='".$projectID."'";
}
if($productID){
	$sql .=" and o.productID='".$productID."'";
}
if($MAC){
	$sql .=" and u.MAC='".mysql_real_escape_string($MAC)."'";
}
if($operator){
	$sql .=" and u.zjry='".$operator."'";
}
if($receipt){
	$sql .=" and u.receipt like '%".mysql_real_escape_string($receipt)."%'";
} 
$sql .=" and r.begindatetime !='0000-00-00 00:00:00'  order by u.UserName DESC";

$result=$db->select_all("DISTINCT u.ID,u.UserName,u.name,u.Mname,u.projectID,u.MAC,u.mobile,u.money,a.orderID,p.name as product_name","userinfo as u,userattribute as a,orderinfo as o,product as p,userrun as r",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
			$sRs=$db->select_one("begindatetime,enddatetime","userrun","userID='".$rs["ID"]."' and orderID='".$rs["orderID"]."'");
			$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' order by orderID desc limit 0,1");
			$oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
			$rRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)");
			$EndDate=$sRs["enddatetime"];

			if($waitOrderRs){
				$EndDate=$waitOrderRs["enddatetime"];
			}
			
			
			//帐户状态
			unset($intval);
			$intval = mysqlDatediff($EndDate,date("Y-m-d H:i:s",time())); 
			if($sRs["enddatetime"]=="0000-00-00 00:00:00"){
				$intval=16;
			}
			if($intval > 15){
				$Status = "<img src=\"images/green.png\" alt=\"帐户正常\"/>";
			}else if($intval >=0) {
				$Status = "<img src=\"images/yellow.png\" alt=\"即将到期\"/>";
			}else{
				$Status = "<img src=\"images/red.png\" alt=\"已经到期\"/>";
			}
			unset($repair);
			//报修改状态
			if($rRs["status"]==1){
				$repair = "<img src=\"images/red.png\" alt=\"报修\"/>";
			}else if($rRs["status"]==2) {
				$repair = "<img src=\"images/yellow.png\" alt=\"处理\"/>";
			}else{
				$repair = "<img src=\"images/green.png\" alt=\"正常\"/>";
			}
			if($oRs >0){
				$online = "<img src=\"images/online.png\" alt=\"在线\"/>";
			}else{
				$online = "<img src=\"images/offline.png\" alt=\"离线\"/>";
			}
			$begin_date =($sRs["begindatetime"]!="0000-00-00 00:00:00")?mysqlShowDate($sRs["begindatetime"]):"0000-00-00";
			$end_date   =($EndDate!="0000-00-00 00:00:00")?mysqlShowDate($EndDate):"0000-00-00";
?>   
		  <tr>
		    <td align="center" class="bg"><input type="checkbox" name="ID" value="<?=$rs["ID"]?>"></td>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs["product_name"]?></td>
			<td align="center" class="bg"><?=$rs["MAC"]?></td>
			<td align="center" class="bg"><?=$rs["mobile"]?></td>
			<td align="center" class="bg"><?=$rs["money"]?></td>
			<td align="center" class="bg"><?=$begin_date?></td>
			<td align="center" class="bg"><?=$end_date?></td>
			<td align="center" class="bg"><?=$Status?></td>
			<td align="center" class="bg"><?=$online?></td>
			<td align="center" class="bg"><?=$repair?></td>
			<td align="center" class="bg">
			  <a  href="user_edit.php?ID=<?=$rs['ID'];?>" title="<? echo _("修改");?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
			  <a  href="user_del.php?ID=<?=$rs['ID'];?>" onClick="javascript:return(confirm('<? echo _("确认删除");?>'))" title="<? echo _("删除");?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
		  </tr>
<?php  }} ?>
        </tbody>      
    </table>
	<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
		<tr>
		    <td align="center" class="bg">
				<?php $db->page($querystring); ?>			
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
 <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        用户管理-> <strong>在网用户</strong>
      </p>
      <ul>
          <li>对正常在网可以使用的用户的查询、编辑、删除、导出等动作。</li>
          <li>可以查看在网的所有用户，并可根据条件进行搜索。</li>
      </ul>

    </div>
<!---------------------------------------------->   
</body>
<script>
window.onLoad=ajaxInput('ajax/project.php','areaID','areaID','projectSelectDIV'); 
</script>
<?
@include_once("inc/loaduser.php");
?>
</html>

