#!/bin/php
﻿<?php 
include("inc/conn.php");
include_once("evn.php"); 
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("处理记录")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
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
            WindowTitle:          '<b>工单管理</b>',
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
<?php 
$ID = $_REQUEST["ID"];
$starDateTime =$_REQUEST["startDateTime"];
$endDateTime  =$_REQUEST["endDateTime"];
$sql=" r.ID=d.repairID";
if($startDateTime){
	$sql .=" and d.startdatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and d.startdatetime<'".$endDateTime."'";
}
if($_SESSION["manager"]!="admin"){
	$sql .=" and d.receiver='".$_SESSION["manager"]."'";
}
$sql .=" order by d.ID DESC";
$result=$db->select_all("r.*,d.*,d.status as d_status","repairdisposal as d,repair as r",$sql,20);
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
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("报修管理")?></font></td>
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
		<td width="49%" align="right" class="title_bg2">&nbsp;</td>
        <td width="7%" align="right" class="title_bg2">&nbsp;</td>
        <td width="31%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
		<td align="right"><? echo _("处理开始时间:")?></td>
		<td><input type="text" name="startDateTime" onFocus="HS_setDate(this)" value="<?=$startDateTime?>">
		  -
		    <input type="text" name="endDateTime" onFocus="HS_setDate(this)" value="<?=$endDateTime?>"></td>
		<td align="right">&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>	  
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
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
        <td width="93%" class="f-bulue1"><? echo _("处理记录")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
		<thead>
			  <tr>
				<th width="4%" align="center" class="bg f-12"><? echo _("编号")?></th>
				<th width="6%" align="center" class="bg f-12"><? echo _("工单编号")?></th>
				<th width="7%" align="center" class="bg f-12"><? echo _("用户名")?></th>
				<th width="7%" align="center" class="bg f-12"><? echo _("分配人员")?></th>
				<th width="6%" align="center" class="bg f-12"><? echo _("受理人员")?></th>
				<th width="5%" align="center" class="bg f-12"><? echo _("工作日")?></th>
				<th width="12%" align="center" class="bg f-12"><? echo _("开始时间")?></th>
				<th width="12%" align="center" class="bg f-12"><? echo _("结束时间")?></th>
				<th width="25%" align="center" class="bg f-12"><? echo _("备注说明")?></th>
				<th width="10%" align="center" class="bg f-12"><? echo _("当前状态")?></th>
				<th width="6%" align="center" class="bg f-12"><? echo _("操作")?></th>
			  </tr>
		</thead>	     
		<tbody>  
		<?php 
		if(is_array($result)){
		foreach($result as $key=>$rs){
			if($rs["d_status"]=="1"){
				$status ="<font color='#DA251D'>"._("处理中")."</font>";
				$operate="<a href='repair_disposal.php?ID=".$rs["ID"]."'><font color='#DA251D'>"._("分配")."</font></a>";
			}else if($rs["d_status"]=="2"){
				$status="<font color='#FfC330'>"._("转发了")."</font>";
				$operate="<a href='#'><font color='#F8C300'>"._("处理")."</font></a>";
			}else if($rs["d_status"]=="3"){
				$status="<font color='#00923F'>"._("处理完成")."</font>";
				$operate="<a href='#'><font color='#00923F'>"._("完成")."</font></a>";
			}
			
		?>   
		  <tr>
			<td align="center" class="bg"><?=$key+1;?></td>
			<td align="center" class="bg"><a href='repair_disposal_edit.php?ID=<?=$rs["ID"]?>'><?=$rs['repairID'];?></a></td>
			<td align="center" class="bg"><?=$rs["UserName"]?></td>
			<td align="center" class="bg"><?=$rs["sender"]?></td>
			<td align="center" class="bg"><?=$rs['receiver'];?></td>
			<td align="center" class="bg"><?=$rs['days'];?></td>
			<td align="center" class="bg"><?=$rs['startdatetime'];?></td>
			<td align="center" class="bg"><?=$rs['enddatetime'];?></td>
			<td align="center" class="bg"><?=$rs['reason'];?></td>
			<td align="center" class="bg"><?=$status?></td>
			<td align="center" class="bg">
				<a href='repair_disposal_edit.php?ID=<?=$rs["ID"]?>'><img src="images/edit.png" width="12" height="12" border="0" /></a>
				<a href="repair_disposal_del.php?ID=<?=$rs["ID"]?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
		  </tr>
		<?php  }} ?>
		</tbody>      
		</table>
		<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
			<tr>
				<td align="center" class="bg">
					<?php $db->page($querystring="ID=".$ID.""); ?>				</td>
		   </tr>
			<tr>
			  <td align="left" class="f-6 bg line-20"><? echo _("处理中:表示此工单,已经分配给处理人员正在处理之中")?><BR>
			    <? echo _("转发了:这里是指当前处理的人员由于某种原因不能完成此订单,将此订单转交给别人处理")?> <BR>
		      <? echo _("完成了:表示此订单给成功的处理解决了问题,同时回复于客户")?> </td>
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
        工单管理-> <strong>处理记录</strong>
      </p>
      <ul>
          <li>对已经完成的工单进行查询，统计功能。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

