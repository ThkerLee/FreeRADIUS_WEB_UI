#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php");  
include("inc/loaduser.php");

$sql="o.userID=u.ID and o.ID=r.orderID and r.status in(1,5) and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
$account         =$_REQUEST["account"];
$startDateTime   =$_REQUEST["startDateTime"];
$startDateTime1  =$_REQUEST["startDateTime1"];
$endDateTime     =$_REQUEST["endDateTime"];
$endDateTime1    =$_REQUEST["endDateTime1"];
$operator		     =$_REQUEST["operator"];
$querystring     ="account=".$account."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&startDateTime1=".$startDateTime1."&endDateTime1=".$endDateTime1."&operator=".$operator."";
if($account) $sql .=" and u.account like '%".mysql_real_escape_string($account)."%'"; 
if($startDateTime) $sql .=" and r.begindatetime>='".$startDateTime."'"; 
if($startDateTime1) $sql .=" and r.begindatetime<'".$startDateTime1."'"; 
if($endDateTime)$sql .=" and r.enddatetime>='".$endDateTime."'";
if($endDateTime1)$sql .=" and r.enddatetime<'".$endDateTime1."'";
if($operator)	$sql .=" and o.operator='".$operator."'"; 
$Rsnums=$db->select_count("orderinfo as o,userinfo as u,userrun as r",$sql);   
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
            WindowTitle:          '<b>营帐管理</b>',
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
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("营帐管理")?> </font></td>
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
		<td width="88%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
	  	<td align="right"><? echo _("用户帐号:")?></td>
		<td><input type="text" name="account"></td>
	  </tr>
	  
	  <tr>
	    <td align="right"><? echo _("开始时间：")?> </td>
	    <td>
		<input type="text" name="startDateTime" onFocus="HS_setDate(this)">
	    <? echo _("至");?>
		<input type="text" name="startDateTime1" onFocus="HS_setDate(this)">
		 </td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("结束时间:")?> </td>
	    <td>
		<input type="text" name="endDateTime" onFocus="HS_setDate(this)">
		<? echo _("至");?>
		<input type="text" name="endDateTime1" onFocus="HS_setDate(this)">
		</td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("操作人员:")?> </td>
	    <td><?php managerSelect($_POST["operator"]) ?></td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if($Rsnums>0) { ?>  <a href="PHPExcel/excel_orderrun.php?<?=$querystring?>" style="color:#FF3300;" ><? echo _("EXCEL导出"); }?></a>  </td>
	    </tr>
	  </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("当前系统正在使用订单列表")?> </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="9%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="22%" align="center" class="bg f-12"><? echo _("所选择产品")?></th>
                <th width="22%" align="center" class="bg f-12"><? echo _("开始时间")?></th>
                <th width="17%" align="center" class="bg f-12"><? echo _("结束时间")?></th>
                <th width="12%" align="center" class="bg f-12"><? echo _("当前状态")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$result=$db->select_all("o.*,u.*,o.ID as orderID,r.*","orderinfo as o,userinfo as u,userrun as r",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){ 
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['orderID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=productShow($rs["productID"])?></td>
			<td align="center" class="bg"><?=$rs["begindatetime"]?></td>
			<td align="center" class="bg"><?=$rs["enddatetime"]?></td>
			<td align="center" class="bg"><?=orderStatus($rs["status"])?></td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="6" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
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
        营帐管理-> <strong>运行订单</strong>
      </p>
      <ul>
          <li>查看用户当前订单运行情况。</li>
          <li>可实现查看用户在某一时间段的订单运行情况。</li>
          <li>运行的订单表示此订单正在运行，所有的计费及产品功能都在执行中。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

