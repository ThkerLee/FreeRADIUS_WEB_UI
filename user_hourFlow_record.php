#!/bin/php
<?php 
session_start();
//include("inc/conn.php"); 
include("inc/timeOnLine.php");
include("inc/loaduser.php");
include("inc/db_config.php");
include("inc/class_mysql.php");
include("inc/class_database.php");
include("inc/class_public.php");
require_once("evn.php");
$db   = new Db_class($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//程序
$d    = new db($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//数据库备份 
$conn = mysql_connect($mysqlhost,$mysqluser,$mysqlpwd); 
mysql_select_db($mysqldb,$conn);
mysql_query("set names utf8"); 
date_default_timezone_set('Asia/Shanghai'); 
/**/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jsdate.js" type="text/javascript"></script>

<style type="text/css">

body,td,th {
	font-size: 12px;
}

</style>
</head>
<body>



<?php 

$UserName        =$_REQUEST["UserName"];
$name            =$_REQUEST["name"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$operator		 =$_POST["operator"];
$productID		 =$_REQUEST["productID"];
$querystring="UserName=".$UserName."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&startDateTime1=".$startDateTime1."&endDateTime1=".$endDateTime1."&operator=".$operator."&productID=".$productID."";
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">产品消费查询</font></td>
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
        <td width="12%" class="f-bulue1 title_bg2">条件搜索</td>
		<td width="88%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
	  	<td align="right">用户帐号：</td>
		<td><input type="text" name="UserName" value="<?=$UserName?>"></td>
	  </tr>
	   <tr>
	  	<td align="right">用 户 名：</td>
		<td><input type="text" name="name" value="<?=$name?>"></td>
	  </tr>
	  <tr>
	    <td align="right">使用产品：</td>
	    <td><?=productSelected($productID);?></td>
	    </tr>
	  <tr>
	    <td align="right">开始时间：</td>
	    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)">
	    -
	      <input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>
	    </tr>
	  
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="提交"></td>
	    </tr>
	  </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1">用户消费查询列表</td>
		<td width="10%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
	 <?
	 
//$str="SELECT u.ID as userID ,sum(a.AcctSessionTime) as sumtime,sum(a.AcctInputOctets) as suminput,sum(a.AcctOutputOctets) as sumoutput from radacct as a ,userinfo as u  where a.UserName=u.UserName and   a.UserName='".$UserName."'";

	/*
               <!-- <th width="10%" align="center" class="bg f-12">总流量</th>-->
               <!-- <th width="10%" align="center" class="bg f-12">超额流量</th>-->
*/

	 ?>
        


<?php 
//$sql="o.userID=u.ID and o.ID=r.orderID and p.ID=o.productID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
 
$sql="u.ID=a.userID AND o.productID=p.ID AND o.ID=a.orderID AND u.projectID=pj.ID AND r.UserName=u.UserName AND   u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($UserName){
	$sql .=" and u.UserName like '%".$UserName."%'";
}
if($name){
	$sql .=" and u.name like '%".$name."%'";
}
if($startDateTime){
	$sql .=" and u.adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and u.adddatetime<'".$endDateTime."'";
}

if($productID){
	$sql .=" and p.ID='".$productID."'";
}
$sql.=" GROUP BY UID ORDER BY UID ASC";

$pdpj=$db->select_all("DISTINCT(u.ID) AS UID,u.UserName AS UserName,SUM(r.AcctSessionTime)AS sumtime,SUM(r.AcctInputOctets) AS suminput,SUM(r.AcctOutputOctets) AS sumoutput,a.orderID AS orderID,p.name AS product_name,p.type AS type,p.period AS period,o.adddatetime AS starttime,u.adddatetime AS starttime ,u.name AS name,pj.ID AS pjID","userinfo AS u,userattribute AS a,orderinfo AS o,product AS p,project AS pj,radacct AS r",$sql,20);



	   if($pdpj[0]['type']=="hour"){//时间限制用户
	   
	   ?><thead>
              <tr>
                <th width="10%" align="center" class="bg f-12">编号</th>
                <th width="10%" align="center" class="bg f-12">用户帐号</th>
				
                <th width="10%" align="center" class="bg f-12">用户名</th>
                <th width="10%" align="center" class="bg f-12">所选项目</th>
                <th width="10%" align="center" class="bg f-12">所选择产品</th>
				 <th width="10%" align="center" class="bg f-12">已用流量</th>	
                <th width="10%" align="center" class="bg f-12">总时长</th>			
                <th width="10%" align="center" class="bg f-12">已用时长</th>
                <th width="10%" align="center" class="bg f-12">剩余时长</th>
				
                <th width="10%" align="center" class="bg f-12">超出时长</th>
              </tr>
        </thead>	     
        <tbody>  <?
	if(is_array($pdpj))  { 
	      foreach($pdpj as $row) {
		  
		$flow=$row['sumoutput']+$row['suminput'];
		
	   $syTime=$row['period']*3600-$row['sumtime'];//剩余时长
	   if($syTime<0){
	   		$fullTime=abs($syTime);
	  	    $syTime=0;
	   } 
	   
	?>
		  <tr>
		    <td align="center" class="bg"> <?=$row['UID']?></td>
			<td align="center" class="bg"> <a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$row["UserName"]?>','loadusershow')"><?=$row["UserName"]?></a>&nbsp;</td>    
				<td align="center" class="bg"><?=$row["name"]?>&nbsp;</td>  
			  <td align="center" class="bg"><?=projectShow($row["pjID"])?>&nbsp;</td>
			<td align="center" class="bg"><?=$row["product_name"]?>&nbsp;</td>
			
			<td align="center" class="bg"><?=flowUnit($flow,"kb")?> &nbsp;</td>
			<td align="center" class="bg"><?=$row['period']."小时"?>&nbsp;</td>
			<td align="center" class="bg"><?=online($row['sumtime'])?>&nbsp;</td>
			
			<td align="center" class="bg"><?=online($syTime)?>&nbsp;</td>
			<td align="center" class="bg"><?=online($fullTime)?>&nbsp;</td>
			
		  </tr>
		  
	   
 <?php  }//foreach
      }//if
     }//if hour
	 elseif($pdpj[0]['type']=="flow"){//流量限制用户
 //单位转换 1M=1024KB *1024=kb
 
		       

 ?> <thead>
              <tr>
                <th width="10%" align="center" class="bg f-12">编号</th>
                <th width="10%" align="center" class="bg f-12">用户帐号</th>
                <th width="10%" align="center" class="bg f-12">所选项目</th>
                <th width="10%" align="center" class="bg f-12">所选择产品</th>				
                <th width="10%" align="center" class="bg f-12">在线时长</th>
                <th width="10%" align="center" class="bg f-12">总流量</th>			
                <th width="10%" align="center" class="bg f-12">已用流量</th>
                <th width="10%" align="center" class="bg f-12">剩余流量</th>
				
                <th width="10%" align="center" class="bg f-12">超出流量</th>
              </tr>
        </thead>	     
        <tbody>  
		<?	if(is_array($pdpj))  { 
		    foreach($pdpj as $row) {
			 $flow=$row['sumoutput']+$row['suminput'];
 				$syFlow=$pdpj[0]['period']*1024*1024-$flow;
				
				if($syFlow<0){
				
				  $fullFlow=abs($syFlow);
				  $syFlow=0;
 					}
		?>
		<tr>
		    <td align="center" class="bg"> <?=$row['UID']?></td>
			<td align="center" class="bg"> <a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$row["UserName"]?>','loadusershow')"><?=$row["UserName"]?></a>&nbsp;</td>     
				<td align="center" class="bg"><?=$row["name"]?>&nbsp;</td>  
			 <td align="center" class="bg"><?=projectShow($row["pjID"])?>&nbsp;</td>
			<td align="center" class="bg"><?=$row["product_name"]?>&nbsp;</td>
			
			<td align="center" class="bg"><?=$row['period']."M"?>&nbsp;</td>
			<td align="center" class="bg"><?=flowUnit($flow,"kb")?>&nbsp;</td>
			
			<td align="center" class="bg"><? if($syFlow==0){echo "0KB";}else{ flowUnit($syFlow,"kb");}?>&nbsp;</td>
			
			<td align="center" class="bg"><?=flowUnit($fullFlow,"kb")?>&nbsp; </td>
		  </tr>
		 <?
		}//foreach
 }//if
 
 }//if flow
 else{//既不是流量用户也不是时间限制用户
 ?> 
 <thead>
              <tr>
                <th width="10%" align="center" class="bg f-12">编号</th>
                <th width="12%" align="center" class="bg f-12">用户帐号</th>
				
                <th width="12%" align="center" class="bg f-12">用户名</th>
                <th width="13%" align="center" class="bg f-12">所选项目</th>
                <th width="12%" align="center" class="bg f-12">所选择产品</th>		
                <th width="13%" align="center" class="bg f-12">在线时长</th>
                <th width="12%" align="center" class="bg f-12">已用流量</th>
              </tr>
        </thead>	     
        <tbody>  
		<? 
		if(is_array($pdpj))  { 
		foreach($pdpj as $row) {
		$flow=$row['sumoutput']+$row['suminput']; 
		?>
		<tr>
				<td align="center" class="bg"> <?=$row['UID']?></td>
				<td align="center" class="bg"> <a href="#" OnClick=		"download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$row["UserName"]?>','loadusershow')"><?=$row["UserName"]?></a>&nbsp;</td>   
			<td align="center" class="bg"><?=$row["name"]?>&nbsp;</td>  
			 <td align="center" class="bg"><?=projectShow($row["pjID"])?>&nbsp;</td>
			<td align="center" class="bg"><?=$row["product_name"]?>&nbsp;</td>			
			<td align="center" class="bg"><?=online($row['sumtime'])?>&nbsp;</td>
			
			<td align="center" class="bg"><?=flowUnit($flow,"kb")?>&nbsp;</td>
			
		  </tr>
 <?
  }//foreach
  
  }//if
  
 }//else
 ?>

		  <tr>
		    <td colspan="10" align="center" class="bg">
				<?php $db->page($querystring); ?></td>
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
</body>
</html>

