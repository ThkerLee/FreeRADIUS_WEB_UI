#!/bin/php
<?php include_once("inc/conn.php");
include_once("inc/loaduser.php");
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理");?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>
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
$UserName        =$_REQUEST["mUserName"];   
//$cUserName       =$_REQUEST["cUserName"];  
$action          =$_REQUEST["action"];
$querystring="UserName=".$UserName."&action=".$action;//&cUserName=".$cUserName."";
 
if($_POST['ID'] &&  is_array($_POST['ID'])){
  include_once("user_del.php"); 
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("子母账号对照");?></font></td>
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
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("母账号条件搜索");?></td>
			<td width="21%" align="right" class="title_bg2">&nbsp;   </td>
		    <td width="9%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="56%" align="right" class="title_bg2">&nbsp;</td>
		  </tr> 
		  <tr>
			<td align="right"><? echo _("母帐号");?>:</td>
			<td><input type="text" name="mUserName" value="<?=$UserName?>"></td>
		    <td align="right">&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		   
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input name="submit" type="submit" value="<? echo _("提交搜索");?>"></td>
			<td align="right">&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp; </td>
		  </tr>
	    </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="10%" class="f-bulue1"><? echo _("用户管理");?> </td>
		<td width="90%" align="right"> 
			<a href="user_add.php" class="f-b"><? echo _("添加用户");?></a>
			</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable"  > 
		  <form action="?" method="post" name="iform" >
        <thead>
              <tr>
                <td width="2%" align="center" class="f-b bg f-12 "><input type="checkbox"  name="allID"  id='allID'  value="allID"  onClick="change_allID();"></td>
                  <td width="5%" align="center" class="f-b bg f-12"><? echo _("编号");?><? if($_GET['action']=="IDASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="IDDESC"){echo"<font color='red'><b>↓</b></font>";}?></td> 
			    <td width="8%" align="center" class="f-b bg f-12"><? echo _("用户帐号");?><? if($_GET['action']=="userASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="userDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("用户姓名号");?><? if($_GET['action']=="nameASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="nameDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="10%" align="center" class="f-b bg f-12"><? echo _("所属项目");?><? if($_GET['action']=="projectASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="projectDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="17%" align="center" class="f-b bg f-12"><? echo _("使用产品");?><? if($_GET['action']=="productASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="productDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("MAC地址");?> </td>
                <td width="8%" align="center" class="f-b bg f-12"><? echo _("手机号码");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("余额");?><? if($_GET['action']=="moneyASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="moneyDESC"){echo"<font color='red'><b>↓</b></font>";}?></td> 
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("开始时间");?><? if($_GET['action']=="stimeASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="stimeDESC"){echo"<font color='red'><b>↓</b></font>";}?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("结束时间");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("状态");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("在线");?></td>
                <td width="4%" align="center" class="f-b bg f-12"><? echo _("报修");?></td>
                <td width="7%" align="center" class="f-b bg f-12"><? echo _("操作");?></td>
              </tr>
        </thead>	     
        <tbody>  
<?php  
$sql="u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and u.Mname!='' ";
 
if($UserName){
	$sql .=" and u.account like '%".mysql_real_escape_string($UserName)."%'";
} 
$result=$db->select_all("u.ID,u.UserName,u.account,u.name,u.Mname,u.projectID,u.MAC,u.mobile,u.money,a.orderID,p.name as product_name","userinfo as u,userattribute as a,orderinfo as o,product as p ",$sql,20);
//获取所有母账号
if(!empty($result) && is_array($result)){
  foreach($result as $mom){ 
     if(strpos($mom['Mname'],"#")){ 
	   $MomAccount[]=$mom['account']; //所有母账号 用户名
	 }  
  }  //$MOM=array_unique($MomAccount); 
  if(is_array( $MomAccount)){
	foreach($MomAccount as $UserName) {//母账号
	    $ID=getUserID($UserName); 
		$ChildID=showMMname($ID) ;
	    $CID=explode("#",$ChildID);
		array_pop($CID);  
		$rs=$db->select_one('*',"userinfo","ID='".$ID."' and account='".$UserName."'");
		$attRs=$db->select_one("orderID","userattribute","userID='".$ID."' and UserName='".$UserName."'");
		$ProductIDRs=$db->select_one("productID",'orderinfo',"userID='".$ID."'  and  ID='".$attRs["orderID"]."'");
		$sRs=$db->select_one("begindatetime,enddatetime","userrun","userID='".$ID."' and orderID='".$attRs["orderID"]."'");
		$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$ID."'  order by orderID desc limit 0,1");
		$oRs=$db->select_count("radacct","UserName='".$rs['account']."' and AcctStopTime='0000-00-00 00:00:00'");
		$rRs=$db->select_one("status","repair","userID='".$ID."' and  status in (1,2)");
		$EndDate=$sRs["enddatetime"];
		if($waitOrderRs){
			$EndDate=$waitOrderRs["enddatetime"];
		}
		//帐户状态
		unset($intval);
		$intval = mysqlDatediff($EndDate,date("Y-m-d H:i:s",time()));	
		if($sRs["enddatetime"]=="0000-00-00 00:00:00"){
			$intval=16;
		}
		if($intval > 15){
			$Status = "<img src=\"images/green.png\" alt=\"帐户正常\"/>";
		}else if($intval >0) {
			$Status = "<img src=\"images/yellow.png\" alt=\"即将到期\"/>";
		}else{
			$Status = "<img src=\"images/red.png\" alt=\"已经到期\"/>";
		}
		unset($repair);
		//报修改状态
		if($rRs["status"]==1){
			$repair = "<img src=\"images/red.png\" alt=\"报修\"/>";
		}else if($rRs["status"]==2) {
			$repair = "<img src=\"images/yellow.png\" alt=\"处理\"/>";
		}else{
			$repair = "<img src=\"images/green.png\" alt=\"正常\"/>";
		}
		if($oRs >0){
			$online = "<img src=\"images/online.png\" alt=\"在线\"/>";
		}else{
			$online = "<img src=\"images/offline.png\" alt=\"离线\"/>";
		}
		$begin_date =($sRs["begindatetime"]!="0000-00-00 00:00:00")?mysqlShowDate($sRs["begindatetime"]):"0000-00-00";
		$end_date   =($EndDate!="0000-00-00 00:00:00")?mysqlShowDate($EndDate):"0000-00-00";
       ?>
	   <tr>
		    <td align="center" class="bg"><input type="checkbox" name="ID[]" id="ID" value="<?=$rs["ID"]?>"></td>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg" style="background-color:#FFBB77"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=productShow($ProductIDRs["productID"])?></td>
			<td align="center" class="bg"><?=$rs["MAC"]?></td>
			<td align="center" class="bg"><?=$rs["mobile"]?></td>
			<td align="center" class="bg"><?=$rs["money"]?></td>
			<td align="center" class="bg"><?=$begin_date?></td>
			<td align="center" class="bg"><?=$end_date?></td>
			<td align="center" class="bg"><?=$Status?></td>
			<td align="center" class="bg"><?=$online?></td>
			<td align="center" class="bg"><?=$repair?></td>
			<td align="center" class="bg">
			  <a  href="user_edit.php?ID=<?=$rs['ID'];?>" title="<? echo _("修改");?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
			  <a  href="user_del.php?ID=<?=$rs['ID'];?>" onClick="javascript:return(confirm('<? echo _("确认删除");?>'));" title="<? echo _("删除");?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
		</tr>
	   <?php
	 //  <!--  子账号 -->  
	   foreach($CID as $ID){  
        $Csql="u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and u.Mname='".$UserName."'  and u.ID='".$ID."'";
        $Cresult=$db->select_all("u.ID,u.UserName,u.account,u.name,u.Mname,u.projectID,u.MAC,u.mobile,u.money,a.orderID,p.name as product_name","userinfo as u,userattribute as a,orderinfo as o,product as p ",$Csql,20); 
	   // $cRs=$db->select_all("*","userinfo","Mname='".$UserName."'");
		if(is_array($Cresult)){
		  unset($rs);
		  foreach($Cresult as $key=>$rs ){
		    $CsRs=$db->select_one("begindatetime,enddatetime","userrun","userID='".$rs["ID"]."' and orderID='".$rs["orderID"]."'");
			$CwaitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0");
			$CoRs=$db->select_count("radacct","UserName='".$rs["UserName"]."' and AcctStopTime='0000-00-00 00:00:00'");
			$CrRs=$db->select_one("status","repair","userID='".$rs["ID"]."' and  status in (1,2)");
			$CEndDate=$CsRs["enddatetime"];

			if($CwaitOrderRs){
				$CEndDate=$CwaitOrderRs["enddatetime"];
			}
			
			
			//帐户状态
			unset($intval);
			$intval = mysqlDatediff($CEndDate,date("Y-m-d",time()));	
			if($sRs["enddatetime"]=="0000-00-00 00:00:00"){
				$intval=16;
			}
			if($intval > 15){
				$Status = "<img src=\"images/green.png\" alt=\"帐户正常\"/>";
			}else if($intval >=0) {
				$Status = "<img src=\"images/yellow.png\" alt=\"即将到期\"/>";
			}else{
				$Status = "<img src=\"images/red.png\" alt=\"已经到期\"/>";
			}
			unset($repair);
			//报修改状态
			if($CrRs["status"]==1){
				$repair = "<img src=\"images/red.png\" alt=\"报修\"/>";
			}else if($CrRs["status"]==2) {
				$repair = "<img src=\"images/yellow.png\" alt=\"处理\"/>";
			}else{
				$repair = "<img src=\"images/green.png\" alt=\"正常\"/>";
			}
			if($CoRs >0){
				$online = "<img src=\"images/online.png\" alt=\"在线\"/>";
			}else{
				$online = "<img src=\"images/offline.png\" alt=\"离线\"/>";
			}
			$Cbegin_date =($CsRs["begindatetime"]!="0000-00-00 00:00:00")?mysqlShowDate($CsRs["begindatetime"]):"0000-00-00";
			$Cend_date   =($CEndDate!="0000-00-00 00:00:00")?mysqlShowDate($CEndDate):"0000-00-00";
			 
		?> 
		  <tr>
		    <td align="center" class="bg"><input type="checkbox" name="ID[]" id="ID" value="<?=$rs["ID"]?>"></td>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg" style="background-color:#99FF99"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>
			<td align="center" class="bg"><?=$rs["product_name"]?></td>
			<td align="center" class="bg"><?=$rs["MAC"]?></td>
			<td align="center" class="bg"><?=$rs["mobile"]?></td>
			<td align="center" class="bg"><?=$rs["money"]?></td>
			<td align="center" class="bg"><?=$Cbegin_date?></td>
			<td align="center" class="bg"><?=$Cend_date?></td>
			<td align="center" class="bg"><?=$Status?></td>
			<td align="center" class="bg"><?=$online?></td>
			<td align="center" class="bg"><?=$repair?></td>
			<td align="center" class="bg">
			  <a  href="user_edit.php?ID=<?=$rs['ID'];?>" title="<? echo _("修改");?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
			  <a  href="user_del.php?ID=<?=$rs['ID'];?>" onClick="javascript:return(confirm('<? echo _("确认删除");?>'));" title="<? echo _("删除");?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
		
		  </tr>
		<?php
		  }//end freach
		} //end is_array($crs) 
	  }// end foreach
	} 
  } //end if is_array($MomAccount)
}//end if
   ?>
		  <tr>
		  <td align="left" class="bg" colspan="15" ><input  type="submit"  name="dell_all" value="<? echo _("批量删除");?>"  onClick="javascript:return window.confirm('<? echo _("确认删除");?>?');" ></td> 
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
        用户管理-> <strong>子母账号</strong>
      </p>
      <ul>
          <li>在添加用户的页面需是子帐号的添加在该页面就会显示出相应的子母帐号，方便用户管理。</li>
      </ul>

    </div>
<!---------------------------------------------->
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

