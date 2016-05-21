#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
</head>
<body>
<?php
$ID=$_REQUEST["ID"]; 
if($_POST){
	$userID		   =$_POST["ID"];
	$UserName	   =$_POST["UserName"];
	$projectID     =$_POST["projectID"];
	$upbandwidth   =$_POST["upbandwidth"];
	$downbandwidth =$_POST["downbandwidth"];

	$projectRs=$db->select_one("p.device,p.mtu,p.accounts,p.status","project as p,userinfo as u","p.ID=u.projectID and u.ID='".$userID."'");//
	                                         
	//用户设备信息
	$device = $projectRs["device"];//设备信息 
	$acount = $projectRs['accounts'];
	$mtu    = $projectRs['mtu'];
	//产品带宽信息
	$upload_bandwidth   = $upbandwidth;
	$download_bandwidth = $downbandwidth; 
	$upload             = $upbandwidth * 1024;
	$download           = $downbandwidth * 1024;
	$Up_Burst           = $upbandwidth * 1024/8 * 1.5;
	$Down_Burst         = $downbandwidth  * 1024/8 * 1.5;	

	//根据不同的设备添加用户的技术参数
	if($device=='natshell'){
		if($speedStatus=="1"){//当启用内部限速规则
			$speed=$db->select_all("*","speedrule","projectID='".$projectID."'");
			$speedCount=count($speed);
			if(is_array($speed)){
				foreach($speed as $speedKey=>$speedRs){
					$dstip[$speedKey]    =$speedRs["dstip"];
				}
			}
			$del_sql="delete from radreply where userID='".$userID."' and (Attribute='mpd-limit' or Attribute='mpd-filter' )";
			$db->query($del_sql);
			if($speedCount){
				$speedCount=$speedCount*2;$j=0;
				for($i=0;$i<$speedCount;$i+=2){
					$str =  "1#".($i+1)."=nomatch dst net ".$dstip[$j];
					$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-filter','+=','".$str."');"; 
					$db->query($strShaperSQL);
					$str =  "2#".($i+1)."=nomatch src net ".$dstip[$j];
					$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-filter','+=','".$str."');"; 
					$db->query($strShaperSQL);
				}//end for
			}//end if
			$j=$j*2;
			$str =  "in#1=flt1  rate-limit ".$upload;
			$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','".$str."');"; 
			$db->query($strShaperSQL);
			$str =  "out#2=flt2  rate-limit ".$download;
			$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','".$str."');"; 
			$db->query($strShaperSQL);	
		}else{
			$db->delete_new("radreply","userID='".$userID."' and  Attribute='mpd-limit' ");	
									
																																										
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','in#1=all shape $upload  $Up_Burst pass');";
			$db->query($strSQL);
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','out#2=all shape $download $Down_Burst pass');";
			$db->query($strSQL);
		}//end speedStatus
					
	}else if($device=="mikrotik"){//另外一种ROS 
		$db->delete_new("radreply","userID='".$userID."' and  Attribute='Mikrotik-Rate-Limit'");				
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Mikrotik-Rate-Limit','+=','$upload_bandwidth"."k/".$download_bandwidth."k');";
		$db->query($strSQL);			
			
	}else if($device=="ma5200f"){
	      $product_upload_bandwidth    = $upload_bandwidth * 1024;
		$product_download_bandwidth  = $download_bandwidth * 1024;
 
		//删除已有的带宽参数 
		$strSQL = "delete  from radreply where UserName='".$UserName."' and Attribute !='Framed-IP-Address' ";
		$db->query($strSQL); 
		
	    $peak_product_upload_bandwidth = $product_upload_bandwidth +1 ;
	    $peak_product_download_bandwidth = $product_download_bandwidth +1 ;
 
		while ( strlen_utf8($product_upload_bandwidth) < 8 ) {
		  $product_upload_bandwidth = "0".$product_upload_bandwidth;	
		}
		while ( strlen_utf8($peak_product_download_bandwidth) < 8) {
		  $peak_product_download_bandwidth = "0".$peak_product_download_bandwidth;	
		}
		while ( strlen_utf8($peak_product_upload_bandwidth) < 8) {
		  $peak_product_upload_bandwidth = "0".$peak_product_upload_bandwidth;	
		}
		while ( strlen_utf8($product_download_bandwidth) < 8) {
		  $product_download_bandwidth = "0".$product_download_bandwidth;	
		} 
		$str = "{$peak_product_upload_bandwidth}{$product_upload_bandwidth}{$peak_product_download_bandwidth}{$product_download_bandwidth}";
		$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Class','+=','".$str."');"; 
		$db->query($strShaperSQL); 
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Acct-Interim-Interval','+=','$acount');";
		$db->query($strSQL);
 	    $mtu_sql="insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Framed-MTU','+=','$mtu')"; 
	    $db->query($mtu_sql); 
	}

	
	echo "<script>alert('修改成功');window.location.href='user.php';</script>";
}
$rs=$db->select_one("*","userinfo","ID='".$ID."'");
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">用户管理</font></td>
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
        <td width="89%" class="f-bulue1"> 更改带宽 </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform" onSubmit="return checkUserMoveForm();">
<input type="hidden" name="ID" value="<?=$ID?>">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg">用户帐号：</td>
			<td width="87%" align="left" class="bg"><input type="text" id="account" name="UserName" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["UserName"]?>" readonly="readonly">			</td>
		  </tr>
		  <tr>
		    <td align="right" class="bg">用户密码：</td>
		    <td align="left" class="bg"><input type="text" id="password" name="password" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["password"]?>"></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">所属项目：</td>
		    <td align="left" class="bg"><?=projectSelected($rs["projectID"]);?></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">上行带宽： </td>
		    <td align="left" class="bg"><input type="text"  name="upbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out">
		      kbit</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">下行带宽：</td>
		    <td align="left" class="bg"><input type="text" name="downbandwidth" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';">
		      kbit</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="提交"></td>
	      </tr>
        </tbody>      
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
</body>
</html>

