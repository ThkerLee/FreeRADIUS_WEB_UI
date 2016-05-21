#!/bin/php
<?php 
include_once("inc/conn.php");
include_once("inc/loaduser.php");
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai');  
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
<script src="js/WdatePicker.js" type="text/javascript"></script> 
<!--这是点击帮助的脚本-2014.06.07-->
    <link href="js/jiaoben/css/chinaz.css" rel="stylesheet" type="text/css"/>
   <!-- <script type="text/javascript" src="js/jiaoben/js/jquery-1.4.4.js"></script>   --和上面的JS有冲突-->
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
$now = time();
$manager         =$_SESSION["manager"];
$manageNum =$db->select_one("*","manager","manager_account = '".$manager."'");
$addusertotalnum =$manageNum["addusertotalnum"];//允许开户人数
$addusernum      =$manageNum["addusernum"];//已开户人数   
$UserName        =$_REQUEST["UserName"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
 $areaID     =$_REQUEST["areaID"];
// $pjID            =explode(",",$_POST["areaprojectID"]);
//$projectID  =$pjID[1];//2014.04.25修改
 $projectID      =$_REQUEST["projectID"]; //2014.04.25修改
$name	         =$_REQUEST["name"];
$sex             =$_REQUEST["sex"];
$NAS_IP        =$_REQUEST["NAS_IP"];
$address		     =$_REQUEST["address"];
$mobile			     =$_REQUEST["mobile"];
$productID		   =$_REQUEST["productID"];
$MAC			       =$_REQUEST["MAC"];
$operator        =$_REQUEST["operator"];
$receipt         =$_REQUEST["receipt"];  
$IP              =$_REQUEST["IP"]; 
$action          =empty($_REQUEST["action"])?"IDDESC":$_REQUEST["action"]; 
//2014.09.25添加受理人员
$accept_name = $_REQUEST["accept_name"];
$querystring="accept_name=".$accept_name."&areaID=".$areaID."&UserName=".$UserName."&name=".$name."&sex=".$sex."&NAS_IP=".$NAS_IP."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&projectID=".$projectID."&productID=".$productID."&address=".$address."&mobile=".$mobile."&MAC=".$MAC."&operator=".$operator."&receipt=".$receipt."&action=".$action.""; 
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
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td>
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
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
			<td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="9%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="56%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"><? echo _("所属区域")?>:</td>
		    <td><?php selectArea($areaID);?></td>
		    <td align="right"><? echo _("联系地址")?>:</td>
		    <td><input type="text" name="address" value="<?=$address?>"></td>
	      </tr>
		  <tr>
		    <td align="right"><? echo _("所属项目")?>:</td>
		    <td align="left" class="bg" id="projectSelectDIV"> 
		    <select><option><? echo _("选择项目");?></option></select>
			  </td>
		    <td align="right"><? echo _("用户姓名")?>:</td>
		    <td><input type="text" name="name" value="<?=$name?>"></td>
	      </tr>
		  <tr>
			  <td align="right"><? echo _("用户产品")?>:</td>
			  <td align="left" class="bg"  id="productSelectDIV">
			 	<select ><option><? echo _("请选择产品");?></option></select> 
		    <span id='productTXT'></span>
		    </td>
		    <td align="right"><? echo _("开户时间")?>:</td>
		    <td>
		    	<input type="text" name="startDateTime" value="<?=$startDateTime?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
		      -
	       <input type="text" name="endDateTime" value="<?=$endDateTime?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"></td>
		  </tr>
		   <tr>
			<td align="right">受理人员:</td>
			<td><?php acceptSelect($accept_name); ?></td>
			<td align="right"><? echo _("IP地址")?>:</td>
			<td>
				<input type="text" name="IP" value="<?=$IP?>">			
				<!--
				<input  type="radio"name="sex" value="male"  <? if($sex=="male") echo "checked=\"checked\"";?> > <? echo _("男");?>
				<input  type="radio"name="sex" value="female"  <? if($sex=="female") echo "checked=\"checked\"";?> > <? echo _("女");?> 
				<input  type="radio"name="sex" value=""  <? if($sex=="") echo "checked=\"checked\"";?> > <? echo _("ALL");?>   
				-->
		  </tr>
                  <tr>
			<td align="right"><? echo _("用户帐号")?>:</td>
			<td><input type="text" name="UserName" value="<?=$UserName?>"></td>
			<td align="right"><? echo _("MAC 地址")?>:</td>
			<td><input type="text" name="MAC" value="<?=$MAC?>"></td>
		  </tr>
		  <tr>
		    <td align="right"><? echo _("手机号码")?>:</td> 
		    <td><input type="text" name="mobile" value="<?=$mobile?>" ></td>
		    <td align="right"><? echo _("收据单号")?>:</td>
		    <td><input type="text" name="receipt" value="<?=$receipt?>"></td>
	      </tr>

		  <tr>
			<td align="right"><!--<? echo _("装机人员")?>:--></td>
			<td><?php // managerSelect($operator); ?> </td>
			<td align="right">&nbsp;</td>
		 	<td> <input name="submit" type="submit" value="<?php echo _("提交搜索")?>">&nbsp;&nbsp;&nbsp;&nbsp;  <? if($usernums>0) { ?><a href="PHPExcel/excel_userinfo.php?<?=$querystring?>" style="color:#FF3300;" ><? echo _("EXCEL导出"); }?></a>  </td>
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
			<? echo _("余额")?>(<a href="?action=moneyASC"><? echo _("升序")?></a> / <a href="?action=moneyDESC"><? echo _("降序")?></a>) || 
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
                <td width="5%" align="center" class="f-b bg f-12"><? echo _("编号")?><? if($_GET['action']=="IDASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="IDDESC"){echo"<font color='red'><b>↓</b></font>";}?></td> 
			          <td width="8%" align="center" class="f-b bg f-12"><? echo _("用户帐号")?><? if($_GET['action']=="userASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="userDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("用户姓名")?><? if($_GET['action']=="nameASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="nameDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("所属项目")?><? if($_GET['action']=="projectASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="projectDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("使用产品")?><? if($_GET['action']=="productASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="productDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("MAC地址")?> </td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("手机号码")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("余额")?><? if($_GET['action']=="moneyASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="moneyDESC"){echo"<font color='red'><b>↓</b></font>";}?></td> 
                <td width="4%" align="center" class="f-b bg f-12">押金</td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("开始时间")?><? if($_GET['action']=="stimeASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="stimeDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("结束时间")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("状态")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("在线")?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("报修")?></td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("操作")?></td>
              </tr>
        </thead>	     
        <tbody>  
<?php 
//----------------------2014.06.03修改页面显示条数-------------------------------------------
	if(isset($_GET["showNum"])){
         $re=$db->select_one("*","shownum","ID = 1");
         if(!$re){
             $db->insert_new("shownum",array("num"=>$_GET["showNum"]));
         }  else {
           $db->update_new("shownum","id=1",array("num"=>$_GET["showNum"]));  
         }
			
	}
 //-------------------2014.06.03结束--------------------------------- 
 //------------------------------------------------------------------
$sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($accept_name)$sql .=" and u.accept_name like '%".$accept_name."%'"; 
if($UserName)$sql .=" and u.UserName like '%".mysql_real_escape_string($UserName)."%'";
if($name)$sql .=" and u.name like '%".mysql_real_escape_string($name)."%'";
if($areaID)$sql .=" and u.areaID='".$areaID."'";
if($sex && $sex!="")$sql .=" and u.sex like '%".$sex."%'";
if($NAS_IP)$sql .=" and u.NAS_IP like '%".mysql_real_escape_string($NAS_IP)."%'";
if($address)$sql .=" and u.address like '%".$address."%'";
if($mobile)$sql .=" and u.mobile like '%".mysql_real_escape_string($mobile)."%'";
if($startDateTime)$sql .=" and u.adddatetime>='".$startDateTime."'";
if($endDateTime)$sql .=" and u.adddatetime<'".$endDateTime."'";
if($projectID)$sql .=" and u.projectID='".$projectID."'";
if($productID)$sql .=" and o.productID='".$productID."'";
if($MAC)$sql .=" and u.MAC='".mysql_real_escape_string($MAC)."'";
if($operator)$sql .=" and u.zjry='".$operator."'";
if($receipt)$sql .=" and u.receipt like '%".mysql_real_escape_string($receipt)."%'"; 
if(trim($IP)){
	 $tables =",radreply as rp"; 
	 $sql   .=" and rp.userID = u.ID and rp.userID =o.userID and  rp.userID = a.userID  and rp.Value='".mysql_real_escape_string($IP)."' and rp.Attribute='Framed-IP-Address'";
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
$re=$db->select_one("*","shownum","ID = 1");//查询显示条数
if(!empty($re["num"])){
    $showNum = $re["num"];
}  else {
   $showNum=20; 
}
$result=$db->select_all("o.productID,u.checkout,u.ID,u.account,u.UserName,u.name,u.Mname,u.projectID,u.MAC,u.mobile,u.money,a.orderID,a.closing as closing,p.name as product_name,p.limittype,p.type,u.pledgemoney","userinfo as u,userattribute as a,orderinfo as o,product as p ".$tables,$sql,$showNum);  
//echo time() - $t;
//echo "<hr>"; 
	if(is_array($result)){ 
		foreach($result as $key=>$rs){
			$sRs=$db->select_one("begindatetime,enddatetime,balance","userrun","userID='".$rs["ID"]."' and orderID='".$rs["orderID"]."'");
			$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' order by orderID desc limit 0,1"); 
			$oRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
			$rRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)");
			$EndDate=$sRs["enddatetime"];
			if($waitOrderRs){
				$EndDate=$waitOrderRs["enddatetime"];
			} 
			 //用户余额
			 $money = $rs['money'] - $sRs["balance"];
			 if($money<0)       $type =_('补交费用:').abs($money)."."; 
		   else if($money>0)  $type =_('退还费用:').abs($money).".";
		   else if($money==0) $type =_('不设找补.'); 
			//帐户状态
			unset($intval);
			$intval =mysqlDatediff($EndDate,date("Y-m-d H:i:s",time()));	
			if($sRs["enddatetime"]=="0000-00-00 00:00:00" || $EndDate=="0000-00-00 00:00:00" ){
				$intval=16;
			}
			if($intval > 15){
				$Status = "<img src=\"images/green.png\" title='". _("帐户正常")."'/>";//
			}else if($intval >0) {
				$Status = "<img src=\"images/yellow.png\" title='"._("即将到期")."'/>";
			}else{
				$Status = "<img src=\"images/red.png\" title='"._("已经到期")."'/>";
			}
			unset($repair);
			//报修改状态
			if($rRs["status"]==1){
				$repair = "<img src=\"images/red.png\" title='"._("报修")."'/>";
			}else if($rRs["status"]==2) {
				$repair = "<img src=\"images/yellow.png\" title='"._("处理")."'/>";
			}else{
				$repair = "<img src=\"images/green.png\" title='"._("正常")."'/>";
			}
			if($oRs >0){
				$online = "<img src=\"images/online.png\" title='"._("在线")."'/>";
			}else{
				$online = "<img src=\"images/offline.png\" title='"._("离线")."'/>";
			}
			$begin_date =($sRs["begindatetime"]!="0000-00-00 00:00:00")?mysqlShowDate($sRs["begindatetime"]):"0000-00-00";
			$end_date   =($EndDate!="0000-00-00 00:00:00")?mysqlShowDate($EndDate):"0000-00-00";
?>   
		  <tr>
		  <td align="center" class="bg"><input type="checkbox" name="ID[]" id="ID" value="<?=$rs["ID"]?>"></td>
		  <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg" <? if($rs['Mname']!='' && strpos($rs['Mname'],"#")){ echo " style=\"background-color:#FFBB77\""; }else if($rs['Mname']!='' && strpos($rs['Mname'],"#")===false){echo " style=\"background-color:#99FF99\"";} ?>>
			<a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a>  
			</td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs["product_name"]?></td>
			<td align="center" class="bg"><?=$rs["MAC"]?></td>
			<td align="center" class="bg"><?=$rs["mobile"]?></td>
			<td align="center" class="bg"><?=$rs["money"]?></td>
                        <td align="center" class="bg"><?=$rs["pledgemoney"]?></td>
			<td align="center" class="bg"><?=$begin_date?></td>
			<td align="center" class="bg"><?=$end_date?></td>
			<td align="center" class="bg"><?=$Status?></td>
			<td align="center" class="bg"><?=$online?></td>
			<td align="center" class="bg"><?=$repair?></td>
			<td align="center" class="bg">  
			  <a  href="user_edit.php?ID=<?=$rs['ID'];?>&UserName=<?=$UserName?>&startDateTime=<?=$startDateTime?>&endDateTime=<?=$endDateTime?>&projectID=<?=$projectID?>&name=<?=$name?>&sex=<?=$sex?>&productID=<?=$productID?>&NAS_IP=<?=$NAS_IP?>&address=<?=$address?>&MAC=<?=$MAC?>&operator=<?=$operator?>&receipt=<?=$receipt?>" title="<? echo _("修改")?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
			  <a  href="user_del.php?ID=<?=$rs['ID'];?>" onClick="javascript:return(confirm('<?php echo _("确认删除")?>'));" title="<? echo _("删除")?>"><img src="images/del.png" width="12" height="12" border="0" /></a>  
				</td> 
		  </tr>
<?php  }} ?>
		  <tr>
		  <td align="left" class="bg" colspan="16" ><input  type="submit"  name="dell_all" value="<?php echo _("批量删除")?>"  onClick="javascript:return window.confirm('<?php echo _("确认删除")?>？');" >
                  显示条数:<a href="?showNum=20">20</a> <a href="?showNum=50">50</a> <a href="?showNum=100">100</a> <a href="?showNum=500">500</a> <!-----2014.06.03增加显示条数------->
                  </td> 
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        用户管理-> <strong>用户管理</strong>
      </p>
      <ul>
          <li>对用户进行查询，修改，删除，导出等动作。</li>
          <li>用户搜索：可以根据所属项目、所属产品、用户帐号、手机号码、联系地址、用户姓名、开户时间 、MAC 地址等进行用户的查询，查询条件为模糊查询，即输入部分字段，即可将包含此字段的所有用户搜索出来。</li>
          <li>点击用户名，可以对用户进行相关操作.</li>
          <li>可以已EXECL方式导出用户信息。</li>
      </ul>

    </div>
<!---------------------------------------------->

</body>
<script>
	window.onLoad=ajaxInput('ajax/project.php','areaID','areaID','projectSelectDIV');
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
function Refresh(){
	window.location.href="user.php";
}
</script>
</html>

