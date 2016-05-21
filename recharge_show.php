#!/bin/php
<?php 
include("inc/conn.php"); 
include("inc/loaduser.php");
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css"> 
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
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("充值统计")?> </font></td>
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<input type="radio" value="once" name="credit" class="status"  id="mo" <? if($_GET['action']=='onceShow' || empty($_GET['action'])){ echo "checked";}?> onClick="document.location='?action=onceShow'"> <? echo _("充值记录查询")?> 
	<input type="radio" value="sum" name="credit" class="status" id='project' <? if($_GET['action']=='sumShow'){  echo "checked"; }?>  onClick="document.location='?action=sumShow'"><? echo _("充值统计查询")?>
	
<?
   if($_REQUEST['action']=="sumShow"){
      $action       = trim($_REQUEST["action"]);
	  $name          = trim($_REQUEST["name"]);
	  $moneyMin      = trim($_REQUEST['moneyMin']);
	  $moneyMax      = trim($_REQUEST["moneyMax"]); 
	  $querystring ="action=".$action."&name=".$name."&moneyMin=".$moneyMin."&moneyMax=".$moneyMax;  //所有的总共收费金额 体现 非单笔
   
   ?>
 <form action="?action=sumShow" name="myform" method="post">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
      <tr>
        <td width="12%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="88%" align="right" class="title_bg2"  colspan="3">&nbsp;</td>
      </tr> 
	  <tr>
	  	<td align="right"><? echo _("用户帐号:")?></td>
		<td><input type="text" name="account" value="<?=$account?>"></td> 
	  </tr> 
	  <tr> 
		<td align="right"><? echo _("用户姓名:")?></td>
		<td><input type="text" name="name" value="<?=$name?>"></td> 
	  </tr>
	  <tr> 
		<td align="right"><? echo _("最低金额:")?></td>
		<td><input type="text" name="moneyMin" value="<?=$moneyMin?>"></td>
	  </tr>
	   <tr> 
		<td align="right"><? echo _("最高金额:")?></td>
	    <td><input type="text" name="moneyMax" value="<?=$moneyMax?>" ></td> 
	  </tr>   
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>"></td>
	    </tr>
	  </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("充值统计查询")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="12%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="16%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="19%" align="center" class="bg f-12"><? echo _("用户姓名")?></th> 
                <th width="19%" align="center" class="bg f-12"><? echo _("充值金额")?></th> 
              </tr>
        </thead>	     
        <tbody>  
<?php 
 $sql=" u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")"; 
 
$result=$db->select_all("u.* ","userinfo as u",$sql,20); 
	if(is_array($result)){ 
		foreach($result as $key=>$rs){
		  $creditSql  = "type !=''";
		if($operator) 
		  $creditSql .=" and operator = '".$operator."'";
		if($startDateTime) 
		  $creditSql .=" and adddatetime >= '".$startDateTime."'";
		if($endDateTime) 
		  $creditSql .=" and adddatetime <= '".$endDateTime."'";    
		 
	      $creditSumMoney = $db->select_one("sum(money) as totalMoney","credit","userID='".$rs['ID']."' '". $creditSql."'"); 
		  
			if($moneyMax && $moneyMin){
			  if($creditSumMoney['totalMoney']>=$moneyMin && $creditSumMoney['totalMoney']<=$moneyMax){
			  ?>
				<tr>
					<td align="center" class="bg"><?=$rs['ID']?>&nbsp;</td><!-- 用户ID-->
					<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td> 
					<td align="center" class="bg"><?=$rs['name']?>&nbsp;</td>
					<td align="center" class="bg"><?=$creditSumMoney['totalMoney']?>&nbsp;</td> 
				 </tr> 
			  <? 
			  } 
			}else{
				if($moneyMin) {//只有最小值
				 if($creditSumMoney['totalMoney']>=$moneyMin){//  显示>=min
				   ?>
				      <tr>
						<td align="center" class="bg"><?=$rs['ID']?>&nbsp;</td><!-- 用户ID-->
						<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td> 
						<td align="center" class="bg"><?=$rs['name']?>&nbsp;</td>
						<td align="center" class="bg"><?=$creditSumMoney['totalMoney']?>&nbsp;</td> 
					 </tr> 
				   <? 
				 } 
				}//end $moneyMin
				elseif($moneyMax){//只有最大值
				 if($creditSumMoney['totalMoney']<=$moneyMax){//  显示<=mix
				   ?>
				      <tr>
						<td align="center" class="bg"><?=$rs['ID']?>&nbsp;</td><!-- 用户ID-->
						<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td> 
						<td align="center" class="bg"><?=$rs['name']?>&nbsp;</td>
						<td align="center" class="bg"><?=$creditSumMoney['totalMoney']?>&nbsp;</td> 
					 </tr> 
				   <? 
				 } //end  $moneyMax
				}else{//无最大最小值的限制
				 ?>
				    <tr>
						<td align="center" class="bg"><?=$rs['ID']?>&nbsp;</td><!-- 用户ID-->
						<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td> 
						<td align="center" class="bg"><?=$rs['name']?>&nbsp;</td>
						<td align="center" class="bg"><?=$creditSumMoney['totalMoney']?>&nbsp;</td> 
					 </tr>  
				 <? 
				}
			}//end else
   } //end foreach 
  }//end if is_array ?>

		  <tr>
		    <td colspan="6" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
          </tr>
        </tbody>      
    </table>
   <?   
   }//end action = sumShow  充值统计查询
   elseif($_REQUEST["action"]=="onceShow" || empty($_REQUEST['action'])){ 
	  $action        = trim($_REQUEST["action"]);
	  $name          = trim($_REQUEST["name"]); 
	  $moneyMin      = trim($_REQUEST['moneyMin']);
	  $moneyMax      = trim($_REQUEST["moneyMax"]); 
	  $startDateTime = trim($_REQUEST["startDateTime"]);
	  $endDateTime   = trim($_REQUEST["endDateTime"]);
	  $operator      = $_REQUEST['operator']; 
	  $querystring ="action=".$action."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&moneyMin=".$moneyMin."&moneyMax=".$moneyMax."&operator=".$operator; 
   ?>
  <form action="?action=onceShow" name="myform" method="post">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
      <tr>
        <td width="12%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="88%" align="right" class="title_bg2"  colspan="3">&nbsp;</td>
      </tr> 
	  <tr>
	  	<td align="right"><? echo _("用户帐号:")?></td>
		<td><input type="text" name="account" value="<?=$account?>"></td> 
		<td align="right"><? echo _("用户姓名:")?></td>
		<td><input type="text" name="name" value="<?=$name?>"></td> 
	  </tr>  
	  <tr> 
		<td align="right"><? echo _("最低金额:")?></td>
		<td><input type="text" name="moneyMin" value="<?=$moneyMin?>"></td> 
		<td align="right"><? echo _("最高金额:")?></td>
	    <td><input type="text" name="moneyMax" value="<?=$moneyMax?>" ></td> 
	  </tr> 
	   <tr>
	    <td align="right"><? echo _("开始时间:")?></td>
		<td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)"></td> 
		<td align="right"><? echo _("结束时间:")?></td>
	    <td><input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>  
	  </tr>   
	  <tr>
	    <td align="right"><? echo _("收费人员:")?></td>
	    <td><?php managerSelect($_POST["operator"]) ?></td> 
	  </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>"></td>
	    </tr>
	  </table>
	</form>
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("充值记录")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="12%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("用户姓名")?></th>
                <th width="15%" align="center" class="bg f-12"><? echo _("缴费类型")?></th>
                <th width="19%" align="center" class="bg f-12"><? echo _("充值金额")?></th>
                <th width="12%" align="center" class="bg f-12"><? echo _("收费人员")?></th>
                <th width="22%" align="center" class="bg f-12"><? echo _("收费时间")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql="c.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($account){
	$sql .=" and u.account='".$account."'";
}
if($name)
    $sql .=" and u.name='".$name."'";
if($moneyMin)
    $sql .=" and c.money >='".$moneyMin."'";
if($moneyMax)
    $sql .=" and c.money <='".$moneyMax."'";
if($startDateTime){
	$sql .=" and c.adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and c.adddatetime<'".$endDateTime."'";
}
if($operator){
	$sql .=" and c.operator='".$operator."'";
}
$result=$db->select_all("c.*,u.*,c.ID as creditID,c.money as creditMoney,c.adddatetime as rechargetime","credit as c,userinfo as u",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
		if($rs["type"]==0){
			$type=_("开户预存");
		}else if($rs["type"]==1){
			$type=_("前台续费");
		}else if($rs["type"]==2){
			$type=_("卡片充值");
		}
		
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['creditID'];?>&nbsp;</td>
			<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a>&nbsp;</td>
			  <td align="center" class="bg"><?=$rs['name'];?>&nbsp;</td>
			<td align="center" class="bg"><?=$type?>&nbsp;</td>
			<td align="center" class="bg"><?=$rs["creditMoney"]?>&nbsp;</td>
			<td align="center" class="bg"><?=$rs["operator"]?>&nbsp;</td>
			<td align="center" class="bg"><?=$rs["rechargetime"]?>&nbsp;</td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="7" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
          </tr>
        </tbody>      
    </table>	
   <? 
   }//end action =onceShow  充值记录查询
 
?>
		
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

