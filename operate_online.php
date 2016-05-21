#!/bin/php
<?php include("inc/conn.php");include("inc/loaduser.php");include('online.php'); require_once("evn.php");?> 
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
<?php 
$UserName     =$_REQUEST["UserName"];
$starDateTime =$_REQUEST["startDateTime"];
$endDateTime  =$_REQUEST["endDateTime"];
$projectID	  =$_REQUEST["projectID"];
$ipaddr		  =$_REQUEST["ipaddr"];
$address      =$_REQUEST["address"];
$nasip		  =$_REQUEST["nasip"];
$seisiontime  =($_REQUEST["seisiontime"]==1)?"1":"0";
if($_GET["action"]=="clean"){
$db->query("truncate table radacct");
}
$querystring="UserName=".$UserName."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&startDateTime1=".$startDateTime1."&endDateTime1=".$endDateTime1."&projectID=".$projectID."&ipaddr=".$ipaddr."&address=".$address."&nasip=".$nasip."";
?>
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
        <td width="13%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="19%" align="right" class="title_bg2">&nbsp;</td>
        <td width="11%" align="right" class="title_bg2">&nbsp;</td>
        <td width="57%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
		<td align="right"><? echo _("帐号/姓名")?>:</td>
		<td><input name="UserName" type="text" id="UserName" value="<?=$UserName?>"></td>
		<td align="right"><? echo _("拨号时间")?>:</td>
		<td><input type="text" name="startDateTime" onFocus="HS_setDate(this)" value="<?=$startDateTime?>">
		-
		  <input type="text" name="endDateTime" onFocus="HS_setDate(this)" value="<?=$endDateTime?>"></td>
	  </tr>	  
	  <tr>
	    <td align="right"><? echo _("所属项目")?>:</td>
	    <td><?php projectSelected($projectID) ?></td>
	    <td align="right"><? echo _("I P 地址")?>:</td>
	    <td><input type="text" name="ipaddr" value="<?=$ipaddr?>"></td>
	  </tr>
	  <tr>
	    <td align="right"><? echo _("用户地址")?>:</td>
	    <td><input type="text" name="address" value="<?=$address?>"></td>
	    <td align="right"><? echo _("NASIP地址:")?></td>
	    <td><input type="text" name="nasip" value="<?=$nasip?>"></td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>"></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	  </tr>
	  </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="10%" class="f-bulue1"><? echo _("在线管理")?></td>
		<td width="90%" align="center">
		
			<? echo _("用户帐号")?>(<a href="?action=userASC"><? echo _("升序")?></a> / <a href="?action=userDESC"><? echo _("降序")?></a>) ||			
			<? echo _("拨号时间")?>(<a href="?action=bhASC"><? echo _("升序")?></a> / <a href="?action=bhDESC"><? echo _("降序")?></a>) ||
			<? echo _("在线时间")?>(<a href="?action=onlineASC"><? echo _("升序")?></a> / <a href="?action=onlineDESC"><? echo _("降序")?></a>) ||
			<? echo _("上传数据")?>(<a href="?action=upASC"><? echo _("升序")?></a> / <a href="?action=upDESC"><? echo _("降序")?></a>) ||
			<? echo _("下载数据")?>(<a href="?action=downASC"><? echo _("升序")?></a> /	<a href="?action=downDESC"><? echo _("降序")?></a>)
			 &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
			<a href="?action=clean"><font color="#CC0066"><? echo _("清空列表")?></font></a> ||
			<a href="?action=flush"><font color="#CC0066"><? echo _("刷新列表")?></font></a> 
			 
			
		</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>                <th width="4%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("用户帐号")?><? if($_GET['action']=="userASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="userDESC"){echo"<font color='red'><b>↓</b></font>";}?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("用户姓名")?></th>
				<th width="9%" align="center" class="bg f-12"><? echo _("用户地址")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("所属项目")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("拨号时间")?><? if($_GET['action']=="bhASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="bhDESC"){echo"<font color='red'><b>↓</b></font>";}?></th>               
                <th width="8%" align="center" class="bg f-12"><? echo _("在线时间")?><? if($_GET['action']=="onlineASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="onlineDESC"){echo"<font color='red'><b>↓</b></font>";}?></th>
                <th width="7%" align="center" class="bg f-12"><? echo _("IP 地址")?></th>
                <th width="11%" align="center" class="bg f-12"><? echo _("上传数据")?><? if($_GET['action']=="upASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="upDESC"){echo"<font color='red'><b>↓</b></font>";}?></th>
                <th width="12%" align="center" class="bg f-12"><? echo _("下载数据")?><? if($_GET['action']=="downASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="downDESC"){echo"<font color='red'><b>↓</b></font>";}?></th>
              	 <th width="11%" align="center" class="bg f-12"><? echo _("操作")?></th>
			  </tr>
        </thead>
        <tbody>  
<?php 
$sql=" r.UserName=u.UserName and r.AcctStopTime='0000-00-00 00:00:00' and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($UserName){
	$sql .=" and ( u.UserName like '%".mysql_real_escape_string($UserName)."%' or u.name like '%".mysql_real_escape_string($UserName)."%' )";
}
if($startDateTime){
	$sql .=" and r.AcctStartTime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and r.AcctStartTime<'".$endDateTime."'";
}
if($ipaddr){
	$sql .=" and r.FramedIPAddress like '%".mysql_real_escape_string($ipaddr)."%'";
}
if($address){
    $sql .=" and u.address like '%".mysql_real_escape_string($address)."%'";
}
if($nasip){
	$sql .=" and r.NASIPAddress like '%".mysql_real_escape_string($nasip)."%'";
}
if($projectID){
	$sql .=" and u.projectID='".$projectID."'";
}if($_GET['action']=="bhASC"){
	$sql .=" order by r.AcctStartTime ASC ";

}else if($_GET['action']=="bhDESC"){
	$sql .=" order by r.AcctStartTime DESC ";

}else if($_GET['action']=="userASC"){
	$sql .=" order by u.UserName ASC ";

}else if($_GET['action']=="userDESC"){
	$sql .=" order by u.UserName DESC ";

}else if($_GET['action']=="onlineASC"){
  $sql .=" order by r.AcctSessionTime ASC ";
  
}else if($_GET['action']=="onlineDESC"){
  $sql .=" order by r.AcctSessionTime DESC ";
  
}else if($_GET['action']=="upASC"){
  $sql .=" order by r.AcctOutputOctets ASC ";

}
else if($_GET['action']=="upDESC"){
  $sql .=" order by r.AcctOutputOctets DESC ";

}else if($_GET['action']=="downASC"){
  $sql .=" order by r.AcctInputOctets ASC ";

}else if($_GET['action']=="downDESC"){
  $sql .=" order by r.AcctInputOctets DESC ";

}else if($seisiontime){
  $sql .=" order by AcctSessionTime  desc";

}else{
  $sql .=" order by u.UserName  DESC";

}
$result=$db->select_all("r.*,u.*","radacct as r,userinfo as u",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['RadAcctId'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=$rs["address"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs['AcctStartTime'];?></td>
			<td align="center" class="bg"><? echo onlinetime($rs['AcctSessionTime']);?>
			</td>
			<td align="center" class="bg"><?=$rs["FramedIPAddress"]?></td>
			<td align="center" class="bg"><?=flowUnit($rs["AcctInputOctets"],'byte')?></td>
			<td align="center" class="bg"><?=flowUnit($rs["AcctOutputOctets"],'byte')?></td>
			<td align="center" class="bg"><a href="user_down_line.php?UserName=<?=$rs['UserName'];?>"><? echo _("下线")?></a></td>
		  </tr>
<?php  }} ?>
		  <tr>
		    <td colspan="11" align="center" class="bg">
				<?php 
					$querystring="UserName=".$UserName."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&projectID=".$projectID."&seisiontime=".$seisiontime."&ipaddr=".$ipaddr."&nasip=".$nasip."&action=".$_GET["action"]."";
					$db->page($querystring); 
					
				?>
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        运营管理-> <strong>在线管理</strong>
      </p>
      <ul>
          <li>根据项目、账号、用户IP、用户MAC地址、BRAS地址、并发数等查询在线用户信息。</li>
          <li>对在线用户进行解锁操作，管理员可对所有BRAS用户进行下线操作。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

