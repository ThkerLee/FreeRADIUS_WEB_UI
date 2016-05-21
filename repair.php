#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php");  
include("inc/loaduser.php");
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("报修记录")?></title>
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

</head>
<?php 
$UserName     =$_REQUEST["UserName"];
$starDateTime =$_REQUEST["startDateTime"];
$endDateTime  =$_REQUEST["endDateTime"];
$status 	    =$_REQUEST["status"];
$type         =$_REQUEST["type"];
$operator     =$_REQUEST["operator"];
?>
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
		<td width="19%" align="right" class="title_bg2">&nbsp;</td>
        <td width="11%" align="right" class="title_bg2">&nbsp;</td>
        <td width="57%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
		<td align="right"><? echo _("帐号/姓名:")?></td>
		<td><input name="UserName" type="text" id="UserName" value="<?=$UserName?>"></td>
		<td align="right"><? echo _("开始时间：")?></td>
		<td><input type="text" name="startDateTime" onFocus="HS_setDate(this)" value="<?=$startDateTime?>"></td>
	  </tr>	  
	  <tr>
	    <td align="right"><? echo _("订单状态:")?></td>
	    <td>
				<select name="status">
					<option value=""><? echo _("选择状态")?></option>
					<option value="1" <?php if($status=="1") echo "selected"; ?>><? echo _("报修中")?></option>
					<option value="2" <?php if($status=="2") echo "selected"; ?>><? echo _("处理中")?></option>
					<option value="3" <?php if($status=="3") echo "selected"; ?>><? echo _("完成")?></option>
				</select>		
		</td>
	    <td align="right"><? echo _("结束时间:")?></td>
	    <td><input type="text" name="endDateTime" onFocus="HS_setDate(this)" value="<?=$endDateTime?>"></td>
	  </tr>
	  <tr>
	    <td align="right"><? echo _("工单类型:")?></td>
	    <td><select name="type">
					<option value="" <?php if($type=="") echo "selected"; ?>><? echo _("工单类型")?></option>
					<option value="1" <?php if($type=="1") echo "selected"; ?>><? echo _("报装")?></option>
					<option value="2" <?php if($type=="2") echo "selected"; ?>><? echo _("报修")?></option>
					<option value="3" <?php if($type=="3") echo "selected"; ?>><? echo _("其他")?></option>
				</select></td>
	    <td align="right"><? echo _("操作人员:")?></td>
	    <td><?php managerSelect($operator); ?></td>  
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>"></td>
	    <td>&nbsp;</td>
	    <td>  
	    	 <? 
	    	 $nums = $db->select_count("repair",'userID !=0');  
	    	 if($nums>0){ ?>  <a href="PHPExcel/excel_repair.php?<?=$querystring ?>" style="color:#FF3300;" ><? echo _("EXCEL导出"); }?></a>    
	    	</td>
	  </tr>
	  </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="93%" class="f-bulue1"><? echo _("报修记录")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
		<thead>
			  <tr>
				<th width="4%" align="center" class="bg f-12"><? echo _("编号")?></th>
				<th width="7%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
				<th width="10%" align="center" class="bg f-12"><? echo _("工单类别")?></th>
				<th width="34%" align="left" class="bg f-12"><? echo _("说明")?></th>
				<th width="13%" align="center" class="bg f-12"><? echo _("开始时间")?></th>
				<th width="12%" align="center" class="bg f-12"><? echo _("结束时间")?></th>
				<th width="6%" align="center" class="bg f-12"><? echo _("登记人员")?></th>
				<th width="8%" align="center" class="bg f-12"><? echo _("当前状态")?></th>
				<th width="6%" align="center" class="bg f-12"><? echo _("操作")?></th>
			  </tr>
		</thead>	     
		<tbody>  
		<?php 
		$sql="r.UserName=u.UserName and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") ";
		if($UserName)$sql .=" and r.UserName like '%".mysql_real_escape_string($UserName)."%'";
		if($startDateTime)$sql .=" and r.startdatetime>='".$startDateTime."'";
		if($endDateTime)$sql .=" and r.startdatetime<'".$endDateTime."'";
		if($status)$sql .=" and r.status='".$status."'";
		if($type) $sql .=" and r.type ='".$type."'";
		if($operator)$sql .=" and r.operator='".$operator."'";
		$sql .=" order by r.ID desc";
	
		$result=$db->select_all("r.*","repair as r,userinfo as u",$sql,20);
		
	
		if(is_array($result)){
		foreach($result as $key=>$rs){
			if($rs["status"]=="1")$status ="<a href='repair_disposal.php?ID=".$rs["ID"]."'><font color='#DA251D'>". _("订单申请中")."</font></a>";
			else if($rs["status"]=="2")$status="<a href='repair_disposal.php?ID=".$rs["ID"]."'><font color='#FfC330'>"._("订单处理中")."</font></a>";
	    else if($rs["status"]=="3")$status="<a href='repair_disposal.php?ID=".$rs["ID"]."'><font color='#00923F'>"._("订单处理完成")."</font></a>";
			if($rs["type"]=="1")$type=_("报装");
			else if($rs["type"]=="2") $type=_("报修");
			else if($rs["type"]=="3")$type=_("其他");
			
		?>   
		  <tr>
			<td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$type?></td>
			<td align="left" class="bg"><?=$rs["reason"]?></td>
			<td align="center" class="bg"><?=$rs['startdatetime'];?></td>
			<td align="center" class="bg"><?=$rs['enddatetime'];?></td>
			<td align="center" class="bg"><?=$rs['operator'];?></td>
			<td align="center" class="bg"><?=$status?></td>
			<td align="center" class="bg"><?=$operate?>
			  <a href='repair_disposal.php?ID=<?=$rs["ID"]?>'><img src="images/op.png" width="14" height="14" border="0" title="<? echo _("处理报修单")?>" /></a>
				<a href='repair_edit.php?ID=<?=$rs["ID"]?>'><img src="images/edit.png" width="12" height="12" border="0" title="<? echo _("修改")?>" /></a>
				<a href="repair_del.php?ID=<?=$rs["ID"]?>"><img src="images/del.png" width="12" height="12" border="0" title="<? echo _("删除")?>" /></a>	
				<a href="#" onClick="javascript:window.open('repair_show_print.php?UserName=<?=$rs['UserName']?>&ID=<?=$rs['userID']?>&repaireID=<?=$rs['ID']?>&action=show','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no,status=no,top=100,left=300')"><? echo _("打印")?></a>			</td>
			 </tr>
		<?php  }} ?>
		</tbody>      
		</table>
		<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
			<tr>
				<td align="center" class="bg">
					<?php $db->page($querystring="UserName=".$UserName."");  ?>			
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
        工单管理-> <strong>工单记录</strong>
      </p>
      <ul>
          <li>对已录入的工单实现查询，编辑，处理，删除等功能。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>
