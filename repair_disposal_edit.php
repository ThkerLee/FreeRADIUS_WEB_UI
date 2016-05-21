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
$ID          =$_REQUEST["ID"];
$repairID    =$_POST["repairID"];
$receiver    =$_POST["operator"];
$reason      =$_POST["reason"];
$reply		 =$_POST["reply"];
$status      =$_POST["status"];
$nowdatetime =date("Y-m-d H:i:s",time());
$rs  =$db->select_one("*","repairdisposal","ID='$ID'");

$rs1 =$db->select_one("*","repair","ID='".$rs["repairID"]."'");
if($rs1["status"]=="1"){
	$status1 ="<font color='#DA251D'>"._("报修中")."</font>";
}else if($rs1["status"]=="2"){
	$status1 ="<font color='#FfC330'>"._("处理中")."</font>";
}else if($rs1["status"]=="3"){
	$status1 ="<font color='#00923F'>"._("处理完成")."</font>";
}

//这里修改了处理订单，同时要更改订单状态
if($_GET["action"]=="disposal"){
	if($status==2){//表示订单转发给别人了
		$sql=array(
			"repairID"=>$repairID,
			"sender"=>$_SESSION["manager"],
			"receiver"=>$receiver,
			"startdatetime"=>$nowdatetime,
			"status"=>1,
			"days"=>$_POST["days"]
		);
		$db->insert_new("repairdisposal",$sql);
		$update_sql=array(
			"enddatetime"=>$nowdatetime,
			"status"=>2,
			"reason"=>$reason
		);
		$update_sql="update repairdisposal set enddatetime='".$nowdatetime."',status=2,reason='".$reason."' where ID='".$ID."' ";
		$db->query($update_sql);
	}else if($status==3){//表示订单处理完了
		$update_sql="update repairdisposal set enddatetime='".$nowdatetime."',status=3,reason='".$reason."' where ID='".$ID."' ";
		$db->query($update_sql);
		
		//回复报修订单表
		$db->query("update repair set status=3,reply='".$reply."',enddatetime='".$nowdatetime."' where ID='".$repairID."'");		
	}else if($status==1){//表示现在还没有处理完的
		$update_sql="update repairdisposal set reason='".$reason."' where ID='".$ID."' ";	
		$db->query($update_sql);
	}
	echo "<script language='javascript'>alert('"._("修改成功")."');window.location.href='repair_disposal.php?ID=".$repairID."'</script>";
}

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
		  <input type="hidden" name="repairID" value="<?=$rs["repairID"]?>">
		  <input type="hidden" name="days" value="<?=$rs["days"]?>">
		  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
			  <tr>
				<td width="18%" align="right" class="bg"><? echo _("用户帐号:")?></td>
				<td width="82%" class="bg"><?=$rs1["UserName"]?>				</td>
			  </tr>
			  <tr>
			    <td align="right" valign="top" class="bg"><? echo _("订单状态:")?></td>
			    <td class="bg"><?=$status1?></td>
		    </tr>
			  <tr>
				<td align="right" valign="top" class="bg"><? echo _("备注说明")?>:</td>
				<td class="bg"><?=$rs1["reason"]?></td>
			  </tr>
			  <tr>
			    <td height="50" colspan="2" align="center" class="f-bulue bg"><? echo _("处理记录单")?></td>
		    </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("开始时间")?>:</td>
			    <td class="bg">
					<?=$rs["startdatetime"]?>				</td>
		    </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("分配人员")?>:</td>
			    <td class="bg"><?=$rs["sender"]?></td>
		    </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("处理人员")?>:</td>
			    <td class="bg"><?=$rs["receiver"]?></td>
		    </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("当前状态")?>:</td>
			    <td class="bg">
					<input type="radio" name="status" value="1" <?php if($rs["status"]==1) echo "checked ";if($rs["status"]>1) echo "disabled=\"disabled\"";?>  onClick="status_change()" >  
					<? echo _("处理")?>
					<input type="radio" name="status" value="2" <?php if($rs["status"]==2) echo "checked ";if($rs["status"]>1) echo "disabled=\"disabled\"";?>  onClick="status_change()" >  
					<? echo _("转发")?>
					<input type="radio" name="status" value="3" <?php if($rs["status"]==3) echo "checked ";if($rs["status"]>1) echo "disabled=\"disabled\"";?>  onClick="status_change()" >
					<? echo _("完成")?></td>
		    </tr>
			  <tr id="receiver_div">
			    <td align="right" class="bg"><? echo _("接受人员")?>：</td>
			    <td class="bg"><?=managerSelect($rs["receiver"]);?></td>
		    </tr>
	      <tr id="reason_div">
			    <td align="right" valign="top" class="bg"><? echo _("备注说明")?>:</td>
	        <td class="bg"><textarea name="reason" cols="60" rows=5><?=$rs["reason"]?></textarea>
	          <? echo _("备注说明");?></td>
		    </tr>
			  <tr id="reply_div">
			    <td align="right" valign="top" class="bg"><? echo _("回复用户")?>：</td>
			    <td class="bg"><textarea name="reply" cols="60" rows=5><?=$rs1["reply"]?></textarea>
			      <? echo _("这是回复给客户的信息，客户会通过平台看到你给出的回复")?></td>
		    </tr>
			  <tr>
			    <td align="right" class="bg">&nbsp;</td>
			    <td class="bg f-6 line-20">
					<? echo _("处理：表示此工单，已经分配给处理人员正在处理之中。")?><br>
					<? echo _("转发：这里是指当前处理的人员由于某种原因不能完成此订单，将此订单转交给别人处理")?><br>
					<? echo _("完成：表示此订单给成功的处理解决了问题，同时回复于客户")?>
				
				</td>
		    </tr>
			  <tr>
				<td align="right" class="bg">&nbsp;</td>
				<td class="bg">
					<?php if($rs["status"]==1){ ?>
					<input type="submit" value="<? echo _("提交")?>">
					<?php } ?>
				</td>
			  </tr>
	    </table>
	  </form>
	</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
  
</table>
<script language="javascript">
<!--
window.onLoad=status_change();
function status_change(){
	v=document.myform.status;
	if(v[0].checked==true){
		document.getElementById("receiver_div").style.display='none';
		document.getElementById("reason_div").style.display='block';
		document.getElementById("reply_div").style.display="none";
	}else if(v[1].checked==true){
		document.getElementById("receiver_div").style.display='block';
		document.getElementById("reason_div").style.display='block';	
		document.getElementById("reply_div").style.display="none";
	}else if(v[2].checked==true){
		document.getElementById("receiver_div").style.display='none';
		document.getElementById("reason_div").style.display='block';
		document.getElementById("reply_div").style.display="block";		
	}
}

-->
</script>
</body>
</html>

