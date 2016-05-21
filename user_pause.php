#!/bin/php
<?php 
include("inc/conn.php");
require_once("evn.php");
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
$UserName     =$_REQUEST["UserName"];
$startDateTime=$_REQUEST["startDateTime"];
$endDateTime =$_REQUEST["endDateTime"];
$projectID	 =$_REQUEST["projectID"];
$name			   =$_REQUEST["name"];
$address		 =$_REQUEST["address"];
$querystring="UserName=".$UserName."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&projectID=".$projectID."&address=".$address."";
$usernums=$db->select_count("userinfo as u,userattribute as att",'projectID in  ('. $_SESSION["auth_project"].') and att.status=5 and att.userID=u.ID');  
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
		    <td width="14%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="51%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"><? echo _("所属项目");?>:</td>
		    <td><?php projectSelected($projectID) ?></td>
		    <td><? echo _("联系地址");?>:</td>
		    <td><input type="text" name="address" value="<?=$address?>"></td>
	      </tr>
		  <tr>
			<td align="right"><? echo _("用户帐号");?>:</td>
			<td><input type="text" name="UserName" value="<?=$UserName?>"></td>
		    <td><? echo _("用户姓名");?>:</td>
		    <td><input type="text" name="name" value="<?=$name?>"></td>
		  </tr>
		  <tr>
			<td align="right"><? echo _("开始时间");?>:</td>
			<td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)"></td>
			<td><? echo _("结束时间");?>:</td>
			<td><input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>
		  </tr>
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input type="submit" value="<? echo _("提交搜索");?>"></td>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;  <? if($usernums>0) { ?>  <a href="PHPExcel/excel_pause.php?<?=$querystring?>" style="color:#FF3300;" ><? echo _("EXCEL导出"); }?></a>  </td>
		  </tr>
	    </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="93%" class="f-bulue1"><? echo _("停机保号用户管理");?></td><!--暂停用户管理-->
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
        <thead>
              <tr>
                <td width="2%" align="center" class="f-b bg f-12 "><input type="checkbox" name="allID" value=""></td>
                <td width="3%" align="center" class="f-b bg f-12"><? echo _("编号");?></td>
                <td width="11%" align="center" class="f-b bg f-12"><? echo _("用户帐号");?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("用户姓名");?></td>
                <td width="18%" align="center" class="f-b bg f-12"><? echo _("所属项目");?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("手机号码");?></td>
                <td width="6%" align="center" class="f-b bg f-12"><? echo _("帐户余额");?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("暂停时间");?></td>
                <td width="11%" align="center" class="f-b bg f-12"><? echo _("恢复时间");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("状态");?></td>
                <td width="5%" align="center" class="f-b bg f-12"><? echo _("在线");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("报修");?></td>
                <td width="6%" align="center" class="f-b bg f-12"><? echo _("操作");?></td>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql="u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and a.status=5 and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($UserName){
	$sql .=" and u.UserName like '%".mysql_real_escape_string($UserName)."%'";
}
if($name){
	$sql .=" and u.name like '%".mysql_real_escape_string($name)."%'";
}
if($address){
	$sql .=" and u.address like '".mysql_real_escape_string($address)."'";
}
if($startDateTime){
	$sql .=" and u.adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and u.adddatetime<'".$endDateTime."'";
}
if($projectID){
	$sql .=" and u.projectID='".$projectID."'";
}

$result=$db->select_all("u.ID,u.UserName,u.account,u.name,u.Mname,u.projectID,u.MAC,u.mobile,u.money,r.enddatetime,r.stopdatetime,r.restoredatetime","userinfo as u,userattribute as a,userrun as r",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
			$oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
			$rRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)");

			//帐户状态
			$intval = (strtotime($rs["enddatetime"])-time())/60/60/24;
			if($rs["enddatetime"]=="0000-00-00 00:00:00"){
				$intval=16;
			}
			if($intval > 15){
				$Status = "<img src=\"images/green.png\" alt=\"帐户正常\"/>";
			}else if($intval >0) {
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
?>   
		  <tr>
		    <td align="center" class="bg"><input type="checkbox" name="ID" value="<?=$rs["ID"]?>"></td>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs["mobile"]?></td>
			<td align="center" class="bg"><?=$rs["money"]?></td>
			
			<td align="center" class="bg"><?=$rs["stopdatetime"]?></td>
			<td align="center" class="bg"><?=$rs["restoredatetime"]?></td>
			<td align="center" class="bg"><?=$Status?></td>
			<td align="center" class="bg"><?=$online?></td>
			<td align="center" class="bg"><?=$repair?></td>
			<td align="center" class="bg">
			  <a  href="user_edit.php?ID=<?=$rs['ID'];?>" title="<? echo _("修改");?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
			  <a  href="user_del.php?ID=<?=$rs['ID'];?>" title="<? echo _("删除");?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
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
        用户管理-> <strong>暂停用户</strong>
      </p>
      <ul>
          <li>对在停机恢复中已经做暂停的用户可以进行查询、编辑、删除等动作。</li>
      </ul>

    </div>
<!---------------------------------------------->
<?php 
include("inc/loaduser.php");
?>
</body>
</html>

