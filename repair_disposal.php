#!/bin/php
<?php include("inc/conn.php"); 
include_once("evn.php"); 
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("报修登记")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<?php 
$ID = $_REQUEST["ID"];
$receiver =$_POST["operator"];
$reason   =$_POST["reason"];
$days     =$_POST["days"];
//这里生成了处理订单之后，同时要更改报修订单状态，表示正在处理些报修订单了
 
if(empty($receiver) && $_POST &&  $_POST['status_hi']==1){ 
	echo "<script language='javascript'>alert(\""._("请分配人员")."\");window.location.href='repair_disposal.php?ID=".$ID."'</script>";
}
if($_GET["action"]=="disposal" && !empty($receiver)){
	$sql=array(
		"repairID"=>$ID,
		"sender"=>$_SESSION["manager"],
		"receiver"=>$receiver,
		"startdatetime"=>date("Y-m-d H:i:s",time()),
		"status"=>1,
		"days"=>$days
	);
	$db->insert_new("repairdisposal",$sql);
	$db->query("update repair set status=2 where ID='".$ID."'");
	echo "<script language='javascript'>alert(\""._("订单已经分配")."\");window.location.href='repair_disposal.php?ID=".$ID."'</script>";
}

$rs   =$db->select_one("*","repair","ID='$ID'");
if($rs["status"]=="1"){
	$status ="<font color='#DA251D'>"._("报修中")."</font>";
}else if($rs["status"]=="2"){
	$status="<font color='#FfC330'>"._("处理中")."</font>";
}else if($rs["status"]=="3"){
	$status="<font color='#00923F'>"._("处理完成")."</font>";
}
$sql="repairID='".$ID."'";
$result=$db->select_all("*","repairdisposal",$sql,20);
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
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("报修管理")?></font></td>
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
        <td width="93%" class="f-bulue1"><? echo _("报修处理")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <form action="?action=disposal" name="myform" method="post">
	  	  <input type="hidden" name="ID" value="<?=$ID?>">
		  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bd">
			  <tr>
				<td width="18%" align="right" class="bg"><? echo _("用户帐号:")?></td>
				<td width="82%" class="bg"><?=$rs["UserName"]?>				</td>
			  </tr>
			  <tr>
			    <td align="right" valign="top" class="bg"><? echo _("订单状态:")?></td>
			    <td class="bg"><?=$status?><input type="hidden" value="<? echo $rs['status'];?>" name="status_hi"></td>
		    </tr>
			  <tr>
				<td align="right" valign="top" class="bg"><? echo _("事件原因")?></td>
				<td class="bg"><?=$rs["reason"]?></td>
			  </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("分配处理人员")?>:</td>
			    <td class="bg">
				<?php 
					if(empty($result)){
						managerSelect();
					}else{
						echo "<font color='#006600'>"._("订单已经分配,请查看下面处理记录")."</font>";
					}				 
				 ?>			    </td>
		    </tr>
			<?php if(empty($result)){ ?>
			  <tr>
			    <td align="right" class="bg"><? echo _("处理工作日")?></td>
			    <td class="f-6"><input name="days" type="text" size="10">
			    <? echo _("设置工单完成周期")?></td>
		    </tr>
			<?php } ?>
			
			  <tr>
				<td align="right" class="bg">&nbsp;</td>
				<td class="bg">
					<input type="submit" value="<? echo _("提交")?>">
				</td>
			  </tr>
	    </table>
	  </form>
	  <br>
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
		<thead>
			  <tr>
				<th width="4%" align="center" class="bg f-12"><? echo _("编号")?></th>
				<th width="6%" align="center" class="bg f-12"><? echo _("工单编号")?></th>
				<th width="7%" align="center" class="bg f-12"><? echo _("发送人员")?></th>
				<th width="8%" align="center" class="bg f-12"><? echo _("受理人员")?></th>
				<th width="8%" align="center" class="bg f-12"><? echo _("工作日")?></th>
				<th width="15%" align="center" class="bg f-12"><? echo _("开始时间")?></th>
				<th width="15%" align="center" class="bg f-12"><? echo _("结束时间")?></th>
				<th width="21%" align="center" class="bg f-12"><? echo _("备注说明")?></th>
				<th width="8%" align="center" class="bg f-12"><? echo _("当前状态")?></th>
				<th width="8%" align="center" class="bg f-12"><? echo _("操作")?></th>
			  </tr>
		</thead>	     
		<tbody>  
		<?php 
		if(is_array($result)){
		foreach($result as $key=>$rs){
			if($rs["status"]=="1"){
				$status ="<font color='#DA251D'>"._("处理中")."</font>";
				$operate="<a href='repair_disposal.php?ID=".$rs["ID"]."'><font color='#DA251D'>"._("分配")."</font></a>";
			}else if($rs["status"]=="2"){
				$status="<font color='#FfC330'>"._("转发了")."</font>";
				$operate="<a href='#'><font color='#F8C300'>"._("处理")."</font></a>";
			}else if($rs["status"]=="3"){
				$status="<font color='#00923F'>"._("处理完成")."</font>";
				$operate="<a href='#'><font color='#00923F'>"._("完成")."</font></a>";
			}
			
		?>   
		  <tr>
			<td align="center" class="bg"><?=$key+1;?></td>
			<td align="center" class="bg"><?=$rs['repairID'];?></td>
			<td align="center" class="bg"><?=$rs["sender"]?></td>
			<td align="center" class="bg"><?=$rs['receiver'];?></td>
			<td align="center" class="bg"><?=$rs['days'];?></td>
			<td align="center" class="bg"><?=$rs['startdatetime'];?></td>
			<td align="center" class="bg"><?=$rs['enddatetime'];?></td>
			<td align="center" class="bg"><?=$rs['reason'];?></td>
			<td align="center" class="bg"><?=$status?></td>
			<td align="center" class="bg">
				<a href='repair_disposal_edit.php?ID=<?=$rs["ID"]?>'><img src="images/edit.png" width="12" height="12" border="0" title="<? echo _("修改")?>" /></a>
				<a href="repair_disposal_del.php?ID=<?=$rs["ID"]?>"><img src="images/del.png" width="12" height="12" border="0" title="<? echo _("删除")?>" /></a>			</td>
		  </tr>
		<?php  }} ?>
		</tbody>      
		</table>
		<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
			<tr>
				<td align="center" class="bg">
					<?php $db->page($querystring="ID=".$ID.""); ?>			
				</td>
		   </tr>
		   <tr>
			  <td align="left" class="f-6 bg line-20"><? echo _("处理中：表示此工单，已经分配给处理人员正在处理之中")?>
			    <? echo _("转发了：这里是指当前处理的人员由于某种原因不能完成此订单，将此订单转交给别人处理")?>
		      <? echo _("完成了：表示此订单给成功的处理解决了问题，同时回复于客户")?></td>
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

