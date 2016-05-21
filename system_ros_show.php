#!/bin/php
<?php include("/usr/local/user-gui/inc/scan_conn.php");  
include_once("evn.php"); 
?> 
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OTHER到期公告</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 

function get_client_ip(){ //获得pppoe拨号上来的ipaddress
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
           $ip = getenv("HTTP_CLIENT_IP");
       else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
           $ip = getenv("HTTP_X_FORWARDED_FOR");
       else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
           $ip = getenv("REMOTE_ADDR");
       else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
           $ip = $_SERVER['REMOTE_ADDR'];
       else
           $ip = "unknown";
   return($ip);
}
$FramedIPAddress = get_client_ip();
echo $FramedIPAddress;

//得到系统设置的到期信息
$rs=$db->select_one("*","maturity_notice","0=0 order by ID desc limit 0,1");

//取得用户的信息
$aRs=$db->select_one("*","radacct","AcctStopTime='0000-00-00 00:00:00' and FramedIPAddress='".$FramedIPAddress."'");	
$UserName=$aRs["UserName"];


//查询用户到到期天数
$uRs=$db->select_one("u.*,r.enddatetime","userinfo as u,userattribute as a,userrun as r","u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and u.UserName='".$aRs["UserName"]."'");

echo print_r($uRs);

$intval = (strtotime($uRs["enddatetime"])-strtotime(date("Y-m-d",time())))/60/60/24;	
if($intval<=$rs["day_apart"]){
echo "用户你好，你的帐号离到期时间还有：".$intval."天";
}

?>

<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
  	    <tbody>
		  <tr>
		    <td align="left" class="bg"><p >提前通告的天数</p></td>
		    <td align="left" class="bg"><?=$rs["day_apart"]?>
				
			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><p >web服务器的端口</p></td>
		    <td align="left" class="bg"><?=$rs["port"]?>
			
			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg">内容</td>
		    <td align="left" class="bg"><?=$rs["content"]?>	
			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
		    <td align="left" class="bg">&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"></td>
		  </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg">&nbsp;</td>
		  </tr>
        </tbody>      
    </table>	


</body>
</html>

