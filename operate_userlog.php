#!/bin/php
<?php  
include("inc/conn.php"); 
require_once("evn.php"); 
$UserName        =$_REQUEST["UserName"];
$operator        =$_REQUEST["operator"];
$type            =(int)$_REQUEST["type"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$projectID		 =$_REQUEST["projectID"]; 
$querystring="UserName=".$UserName."&name=".$name."&operator=".$operator."&type=".$type."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&projectID=".$projectID."";
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
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
        <td width="12%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="88%"  colspan="3"align="right" class="title_bg2">&nbsp;</td>
      </tr>
		<tr>
		<td align="right"><? echo _("用户帐号")?>:</td>
		<td><input type="text" name="UserName" value="<?=$UserName?>"></td> 
		<td align="right"><? echo _("开始时间")?>:</td>
		<td><input type="text" name="startDateTime"  value="<?=$startDateTime?>" onFocus="HS_setDate(this)"></td>
		</tr> 
		<tr>
		<td align="right"><? echo _("操作人员")?>:</td>
		<td><?php managerSelect($operator)?></td>
		<td align="right"><? echo _("结束时间")?>:</td>
		<td><input type="text" name="endDateTime"  value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>
		</tr>
		<tr> 
		<td align="right"><? echo _("类型")?>:</td>
		<td><?=showUserLogType($type)?></td> 
		<td align="right"><? echo _("所属项目")?>:</td>
		<td><?php projectSelected($projectID) ?></td>
		</tr>   
		<tr>
		<td align="right">&nbsp;</td>
		<td><input type="submit" value="<? echo _("提交")?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./PHPExcel/excel_userlog.php"><font color="red"><? echo _("EXCEL导出")?></font></a></td>
		<td align="right">&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
	  </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("日志管理")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="8%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="6%" align="center" class="bg f-12"><? echo _("类型")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("用户姓名")?></th>
                <th width="14%" align="center" class="bg f-12"><? echo _("所属项目")?></th>
                <th width="19%" align="center" class="bg f-12"><? echo _("操作时间")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("操作人员")?></th>
                <th width="26%" align="center" class="bg f-12"><? echo _("内容")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql="projectID in (". $_SESSION["auth_project"].") ";//and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($UserName){
	$sql .=" and account like '%".mysql_real_escape_string($UserName)."%'";
}
if($operator){
	$sql .=" and operator like '%".$operator."%'";
}
if($type && $type!='0'){
    $type = $type - 1;
	$sql .=" and type = $type ";
}
if($startDateTime){
	$sql .=" and adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and adddatetime<'".$endDateTime."'";
}
if($projectID){
	$sql .=" and projectID='".$projectID."'";
}
	$sql .=" order by ID desc ";
 //$result=$db->select_all("l.*,l.ID as logID,u.*,l.adddatetime as operatortime","userlog as l,userinfo as u",$sql,20);
 $userlog=$db->select_all("*","userlog",$sql,20);

	if(is_array($userlog)){
	//$i=0;
		foreach($userlog as $key=>$rs){
		 // $result=  $result[$i];
		  //$i++;
?>        
		  <tr>
		   <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><?=userLogStatus($rs["type"])?></td>
			<td align="center" class="bg"><?=$rs['account'];?></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs['adddatetime'];?></td>
			<td align="center" class="bg"><?=getOperateUserName($rs['operator']);?></td>
			<td align="center" class="bg"><?=$rs["content"]?></td>
		  <!-- 
		    <td align="center" class="bg"><?=$rs['logID'];?></td>
			<td align="center" class="bg"><?=userLogStatus($rs["type"])?></td>
			<td align="center" class="bg"><?=$rs['account'];?></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs['operatortime'];?></td>
			<td align="center" class="bg"><?=$rs['operator'];?></td>
			<td align="center" class="bg"><?=$rs["content"]?></td>
			-->
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="8" align="center" class="bg"><?php $db->page($querystring); ?>	</td>
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
        运营管理-> <strong>日志操作</strong>
      </p>
      <ul>
          <li>支持对操作员的日常操作记录并支持查询。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

