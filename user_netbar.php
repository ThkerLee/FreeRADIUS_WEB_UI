#!/bin/php
<?php 
include_once("inc/conn.php");
include_once("inc/loaduser.php");
include_once("inc/timeOnLine.php");
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai');  
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("时长计费")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
</head>
<body>  
<?php  
$now = time();
$manager         =$_SESSION["manager"];
$manageNum =$db->select_one("*","manager","manager_account = '".$manager."'");
$addusertotalnum =$manageNum["addusertotalnum"];//允许开户人数
$addusernum      =$manageNum["addusernum"];//已开户人数   
$UserName        =$_REQUEST["UserName"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$projectID		   =$_REQUEST["projectID"];
$name			       =$_REQUEST["name"];
$sex             =$_REQUEST["sex"];
$birthday        =$_REQUEST["birthday"];
$address		     =$_REQUEST["address"];
$mobile			     =$_REQUEST["mobile"];
$productID		   =$_REQUEST["productID"];
$MAC			       =$_REQUEST["MAC"];
$operator        =$_REQUEST["operator"];
$receipt         =$_REQUEST["receipt"];  
$action          =empty($_REQUEST["action"])?"IDDESC":$_REQUEST["action"];     
$querystring="UserName=".$UserName."&name=".$name."&sex=".$sex."&birthday=".$birthday."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&projectID=".$projectID."&productID=".$productID."&address=".$address."&mobile=".$mobile."&MAC=".$MAC."&operator=".$operator."&receipt=".$receipt."&action=".$action.""; 
$usernums = $db->select_count("userinfo",'projectID in  ('. $_SESSION["auth_project"].')'); 
if($_POST['ID'] &&  is_array($_POST['ID'])){
	if (in_array("user_del.php", $_SESSION["auth_permision"])) {
		 include_once("user_del.php"); 
	}else{
	    echo "<script language='javascript'>alert('"._("没有管理权限")."');window.history.go(-1);</script>";
		exit;
	} 
} 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("时长计费")?></font></td>
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
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
			<td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="9%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="56%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"><? echo _("所属项目")?>:</td>
		    <td>
			<?php 
				$sql=" ID in (". $_SESSION["auth_project"].")";
				$projectResult=$db->select_all("*","project",$sql);
				echo "<select name='projectID' onchange=ajaxInput('ajax/project.php','projectID','projectID','productSelectDIV')>";
				echo "<option value=''>"._("选择项目")."</option>";//00是防止。。当projectID=空时产品关联查询显示不了产品  页面user.php
				if(is_array($projectResult)){
					foreach($projectResult as $key=>$projectRs){
						if($projectRs["ID"]==$projectID){
							echo "<option value=".$projectRs["ID"]." selected>".$projectRs["name"]."</option>";
						}else{
							echo "<option value=".$projectRs["ID"].">".$projectRs["name"]."</option>";
						}
					}
				}
				echo "</select>";			
			?>			</td>
		    <td align="right"><? echo _("联系地址")?>:</td>
		    <td><input type="text" name="address" value="<?=$address?>"></td>
	      </tr>
		  <tr>
		    <td align="right"><? echo _("所属产品")?>:</td>
		    <td  id="productSelectDIV"><?=productSelected($productID);?>			</td>
		    <td align="right"><? echo _("用户姓名")?>:</td>
		    <td><input type="text" name="name" value="<?=$name?>"></td>
	      </tr>
		  <tr>
			<td align="right"><? echo _("用户帐号")?>:</td>
			<td><input type="text" name="UserName" value="<?=$UserName?>"></td>
		    <td align="right"><? echo _("开户时间")?>:</td>
		    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)">
		      -
	            <input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>
		  </tr>
		    
		  <tr>  
		  	<td align="right"><? echo _("收据单号")?>:</td>
		    <td><input type="text" name="receipt" value="<?=$receipt?>"></td>
			  <td align="right"><? echo _("MAC 地址")?>:</td>
			  <td> <input type="text" name="MAC" value="<?=$MAC?>">			</td>
		  </tr> 
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input name="submit" type="submit" value="<? echo _("提交搜索")?>"></td>
			<td align="right">&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
	    </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="5%" class="f-bulue1"><? echo _("用户管理")?> </td>
		<td width="95%" align="center">
	  	    <? echo _("用户ID")?>(<a href="?action=IDASC"><? echo _("升序")?></a> / <a href="?action=IDDESC"><? echo _("降序")?></a>) ||	
		    <? echo _("用户帐号")?>(<a href="?action=userASC"><? echo _("升序")?></a> / <a href="?action=userDESC"><? echo _("降序")?></a>) ||			
		 	<? echo _("用户姓名")?>(<a href="?action=nameASC"><? echo _("升序")?></a> / <a href="?action=nameDESC"><? echo _("降序")?></a>) ||
			<? echo _("所属项目")?>(<a href="?action=projectASC"><? echo _("升序")?></a> / <a href="?action=projectDESC"><? echo _("降序")?></a> ) || 
			<? echo _("使用产品")?>(<a href="?action=productASC"><? echo _("升序")?></a> / <a href="?action=productDESC"><? echo _("降序")?></a>) ||
			<? echo _("押金")?>(<a href="?action=moneyASC"><? echo _("升序")?></a> / <a href="?action=moneyDESC"><? echo _("降序")?></a>) || 
			<? 
			   if($addusernum<$addusertotalnum){
			     echo "<a href=\"user_add.php\" class=\"f-b\">"._("添加用户")."</a>"; 
			   }else{ 
			     echo "<font color='red' >"._("添加用户已经达到上限：")."<b>".$addusertotalnum."</b>"._(" 请联系管理员")."</font>";
			   }
			   
			
			?>
			
			
			</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable"  > 
		  <form action="?" method="post" name="iform" >
        <thead>
              <tr>
                <td width="2%" align="center" class="f-b bg f-12 "><input type="checkbox"  name="allID"  id='allID'  value="allID"  onClick="change_allID();"></td>
                  <td width="3%" align="center" class="f-b bg f-12"><? echo _("编号")?><? if($_GET['action']=="IDASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="IDDESC"){echo"<font color='red'><b>↓</b></font>";}?></td> 
			    <td width="8%" align="center" class="f-b bg f-12"><? echo _("用户帐号")?><? if($_GET['action']=="userASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="userDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("证件编号")?> </td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("用户姓名")?><? if($_GET['action']=="nameASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="nameDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("所属项目")?><? if($_GET['action']=="projectASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="projectDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("使用产品")?><? if($_GET['action']=="productASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="productDESC"){echo"<font color='red'><b>↓</b></font>";}?></td> 
                <!--
                <td width="6%" align="center" class="f-b bg f-12"><? echo _("开户时间")?></td>
                -->
                <td width="6%" align="center" class="f-b bg f-12"><? echo _("开始时间")?><? if($_GET['action']=="stimeASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="stimeDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="6%" align="center" class="f-b bg f-12"><? echo _("结束时间")?></td> 
				        <td width="7%" align="center" class="f-b bg f-12"><? echo _("计费类型")?></td>  
				        <td width="7%" align="center" class="f-b bg f-12"><? echo _("上网时长")?></td> 
				        <td width="4%" align="center" class="f-b bg f-12"><? echo _("余额")?><? if($_GET['action']=="moneyASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="moneyDESC"){echo"<font color='red'><b>↓</b></font>";}?></td> 
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("状态")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("在线")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("报修")?></td>
                <td width="12%" align="center" class="f-b bg f-12"><? echo _("操作")?></td>
              </tr>
        </thead>	     
        <tbody>  
<?php  
$sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and p.type='netbar_hour'";
 
if($UserName){
	$sql .=" and u.UserName like '%".$UserName."%'";
}

if($name){
	$sql .=" and u.name like '%".$name."%'";
}
if($sex && $sex!=""){
	$sql .=" and u.sex like '%".$sex."%'";
}
if($birthday){
	$sql .=" and u.birthday like '%".$birthday."%'";
}
if($address){
	$sql .=" and u.address like '%".$address."%'";
}
if($mobile){
	$sql .=" and u.mobile like '%".$mobile."%'";
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
if($productID){
	$sql .=" and o.productID='".$productID."'";
}
if($MAC){
	$sql .=" and u.MAC='".$MAC."'";
}
if($operator){
	$sql .=" and u.zjry='".$operator."'";
}
if($receipt){
	$sql .=" and u.receipt like '%".$receipt."%'";
}  
if($_GET['action']=="userASC"){
    $sql .=" order by u.UserName ASC ";

}else if($_GET['action']=="userDESC"){
    $sql .=" order by u.UserName DESC ";

}else if($_GET['action']=="nameASC"){
    $sql .=" order by u.name ASC "; 
	
}else if($_GET['action']=="nameDESC"){
    $sql .=" order by u.name DESC "; 
	
}else if($_GET['action']=="projectASC"){
    $sql .=" order by u.projectID ASC "; 
	
}else if($_GET['action']=="projectDESC"){
    $sql .=" order by u.projectID DESC "; 
	
}else if($_GET['action']=="productASC"){
    $sql .=" order by p.name ASC "; 
	
}else if($_GET['action']=="productDESC"){
    $sql .=" order by p.name DESC "; 
	
}else if($_GET['action']=="moneyASC"){
    $sql .=" order by u.money ASC "; 
	
}else if($_GET['action']=="moneyDESC"){
    $sql .=" order by u.money DESC "; 
	
}else if($_GET['action']=="IDASC"){
    $sql .=" order by u.ID ASC ";  
	
}else if($_GET['action']=="IDDESC"){
    $sql .=" order by u.ID DESC ";  
	
}else{
    $sql .=" order by u.ID DESC";
	
} 
$t = time();
$sql_from = "userinfo as u,userattribute as a,orderinfo as o,product as p ";
$fen .= " select count(*) as num from ".$sql_from ." where ".$sql ;
//echo $fen;
$result=$db->select_all("u.checkout,u.ID,u.account,u.UserName,u.cardid,u.adddatetime,u.name,u.Mname,u.projectID,u.MAC,u.mobile,u.money,a.orderID,a.closing as closing,p.name as product_name,p.limittype",$sql_from,$sql,20);  
//echo time() - $t;
//echo "<hr>";
	if(is_array($result)){ 
		foreach($result as $key=>$rs){
			$sRs=$db->select_one("begindatetime,enddatetime","userrun","userID='".$rs["ID"]."' and orderID='".$rs["orderID"]."'");
			$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0   order by enddatetime desc limit 0,1"); 
			$oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
			$rRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)");
			$EndDate=$sRs["enddatetime"];
			if($waitOrderRs){
				$EndDate=$waitOrderRs["enddatetime"];
			}
			$adddatetime = date("Y-m-d",strtotime($rs['adddatetime']));
			
			if($rs['limittype']==1 )$limittype = "结账下机";
			else if($rs['limittype']==2) $limittype = "自动下机";
			else $limittype="未知";
			/* //用户余额 
		   $money = $rs['money'];
			 if($money<0)       $type =_('补交费用:').abs($money)."."; 
		   else if($money>0)  $type =_('退还费用:').abs($money).".";
		   else if($money==0) $type =_('不设找补.'); 
		   */
		  //上网时长
		  $totalStatus = $db->select_one("sum(stats) as totals" ,"runinfo as r,userattribute as att","att.userID=r.userID and att.orderID=r.orderID and att.userID='".$rs["ID"]."'");
			//帐户状态
			unset($intval);
			$intval = (strtotime($EndDate)-strtotime(date("Y-m-d H:i:s",time())))/60/60/24;	
			if($sRs["enddatetime"]=="0000-00-00 00:00:00" || $EndDate=="0000-00-00 00:00:00" ){
				$intval=16;
			}
			if($intval > 15){
				$Status = "<img src=\"images/green.png\" alt='". _("帐户正常")."'/>";//
			}else if($intval >=0) {
				$Status = "<img src=\"images/yellow.png\" alt='"._("即将到期")."'/>";
			}else{
				$Status = "<img src=\"images/red.png\" alt='"._("已经到期")."'/>";
			}
			unset($repair);
			//报修改状态
			if($rRs["status"]==1){
				$repair = "<img src=\"images/red.png\" alt='"._("报修")."'/>";
			}else if($rRs["status"]==2) {
				$repair = "<img src=\"images/yellow.png\" alt='"._("处理")."'/>";
			}else{
				$repair = "<img src=\"images/green.png\" alt='"._("正常")."'/>";
			}
			if($oRs >0){
				$online = "<img src=\"images/online.png\" alt='"._("在线")."'/>";
			}else{
				$online = "<img src=\"images/offline.png\" alt='"._("离线")."'/>";
			}
			$begin_date =($sRs["begindatetime"]!="0000-00-00 00:00:00")?date("Y-m-d",strtotime($sRs["begindatetime"])):"0000-00-00";
			$end_date   =($EndDate!="0000-00-00 00:00:00")?date("Y-m-d",strtotime($EndDate)):"0000-00-00";
?>   
		  <tr>
		    <td align="center" class="bg"><input type="checkbox" name="ID[]" id="ID" value="<?=$rs["ID"]?>"></td>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg" <? if($rs['Mname']!='' && strpos($rs['Mname'],"#")){ echo " style=\"background-color:#FFBB77\""; }else if($rs['Mname']!='' && strpos($rs['Mname'],"#")===false){echo " style=\"background-color:#99FF99\"";} ?>><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/user_checkout_loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["cardid"]?></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs["product_name"]?></td>  
			<!--<td align="center" class="bg"><?=$adddatetime?></td>-->
			<td align="center" class="bg"><?=$begin_date?></td>
			<td align="center" class="bg"><?=$end_date?></td>
			<td align="center" class="bg"><?=$limittype?></td> 
			<td align="center" class="bg"><?=online($totalStatus["totals"])?></td>  
			<td align="center" class="bg"><?=$rs["money"]?></td> 
			<td align="center" class="bg"><?=$Status?></td>
			<td align="center" class="bg"><?=$online?></td>
			<td align="center" class="bg"><?=$repair?></td>
			<td align="center" class="bg">  
			  <a  href="user_edit.php?ID=<?=$rs['ID'];?>" title="<? echo _("修改")?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
			  <a  href="user_del.php?ID=<?=$rs['ID'];?>" onClick="javascript:return(confirm('<? echo _("确认删除")?>'));" title="<? echo _("删除")?>"><img src="images/del.png" width="12" height="12" border="0" /></a>  
				</td>
		
		  </tr>
<?php  }} ?>
		  <tr>
		  <td align="left" class="bg" colspan="17" ><input  type="submit"  name="dell_all" value="<? echo _("批量删除")?>"  onClick="javascript:return window.confirm('<? echo _("确认删除")?>？');" ></td> 
		  </tr>
        </tbody> 
		
		 </form>     
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
</body>
<script>
function change_allID(){
	ide=document.iform.allID.checked;
	div=document.getElementById("myTable").getElementsByTagName("input");
	for(i=0;i<div.length;i++){ 
		div[i].checked=ide;
	}  
} 
function excel(){ 
   	alert('No Manager Permision'); 
}
</script>
</html>

