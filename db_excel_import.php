#!/bin/php
<?php
require_once("evn.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("数据备份")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<link href="inc/dialog.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="780" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	<table border="0" cellpadding="0" cellspacing="2" class="contmenu">
          <tr>             
           <td width="72" height="21" align="center" background="images/contentmenubg2.jpg"><a href="backup.php"><? echo _("系统备份")?></a></td>
			<td width="72" height="21" align="center" background="images/contentmenubg2.jpg"><a href="restore.php"><? echo _("系统还原")?></a></td>
			<td width="72" height="21" align="center" background="images/contentmenubg.jpg"><a href="excel.php"><? echo _("数据导入")?></a></td>
          </tr>
      </table>
	</td>
  </tr>
  <tr>
    <td class="bg1">
	    <br>
    <table width="98%" align="center" class="title_bg2 border_t border_l border_r">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("excel数据导入")?></td>
        <td width="11%" align="right"></td>
      </tr>
	</table>
<?php 
if($_GET["action"]!="import"){
?>
<table width="98%" border="0" align="center" cellpadding="5" cellspacing="0" >
	<tr>
	<td width="89%">
	<?php
	if(!$_POST){	
	//上传excel文件
	
		$uptypes=array('application/vnd.ms-excel');
		$max_file_size=2000000;
		$file=$_FILES["excelfile"];
		
	   if (!is_uploaded_file($file[tmp_name])){//是否存在文件
		   echo "<script language=javascript>alert('"._("请先选择你要上传的文件!")."');history.go(-1);</script>";
		   exit();
	   }
		if($max_file_size < $file["size"]){//检查文件大小
		   echo "<script language=javascript>alert('"._("文件大小不能超过2M!")."');history.go(-1);</script>";
		   exit();
	   }
	   if(!in_array($file["type"], $uptypes)){//检查文件类型
			echo "<script language=javascript>alert('"._("文件类型不符!")."".$file["type"]."');history.go(-1);</script>";
		   exit();
	   }
	   if(!move_uploaded_file ($file["tmp_name"],"user.xls")){ 
		   echo "<script language=javascript>alert('"._("移动文件出错!")."');history.go(-1);</script>";
		   exit();
	   }   
	}	

	require_once 'Excel/reader.php';
	require_once 'inc/conn.php';
	// ExcelFile($filename, $encoding);
	$data = new Spreadsheet_Excel_Reader();
	
	// Set output Encoding.
	$data->setOutputEncoding('UTF-8');
	
	/***
	* if you want you can change 'iconv' to mb_convert_encoding:
	* $data->setUTFEncoder('mb');
	*
	**/
	
	/***
	* By default rows & cols indeces start with 1
	* For change initial index use:
	* $data->setRowColOffset(0);
	*
	**/
	
	
	
	/***
	*  Some function for formatting output.
	* $data->setDefaultFormat('%.2f');
	* setDefaultFormat - set format for columns with unknown formatting
	*
	* $data->setColumnFormat(4, '%.3f');
	* setColumnFormat - set format for column (apply only to number fields)
	*
	**/
	
	$data->read('user.xlsx');
	$data->setRowColOffset(1);
	/*
	
	
	$data->sheets[0]['numRows'] - count rows
	$data->sheets[0]['numCols'] - count columns
	$data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column
	
	$data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
	
	$data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
	if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
	$data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
	$data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
	$data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
	*/
	
	error_reporting(E_ALL ^ E_NOTICE);
	
	$row=$_REQUEST["row"];
	$errNum=$_REQUEST["errNum"];
	$successNum=$_REQUEST["successNum"];
	if(empty($row)) $row=2;
	if(empty($errNum)) $errNum=0;
	if(empty($successNum)) $successNum=0;	
	//for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	
	$Now_Time = date("Y-m-d",time()); 
	unset($input_errors);
	//读取POST变量
	$UserName      =	$data->sheets[0]['cells'][$row][1];
	$User_Password =	$data->sheets[0]['cells'][$row][2];   
	$Name          =	$data->sheets[0]['cells'][$row][3];            
	$Mail          =	$data->sheets[0]['cells'][$row][4];        
	$HomePhone     =	$data->sheets[0]['cells'][$row][5];       
	$Mobile        =	$data->sheets[0]['cells'][$row][6];            
	$Manager       =	$data->sheets[0]['cells'][$row][7];     
	$Installer     =	$data->sheets[0]['cells'][$row][8];  
	$Address       =	$data->sheets[0]['cells'][$row][9];         
	$IDNumber      =	$data->sheets[0]['cells'][$row][10];   
	$Product_ID    =	$data->sheets[0]['cells'][$row][11]; 
	$GroupName     =	$data->sheets[0]['cells'][$row][12];
	$bind_mac      =    $data->sheets[0]['cells'][$row][13]; 
	$EndDate       =    $data->sheets[0]['cells'][$row][14]; 
	$EndDate 	   =    date("Y-m-d",mktime($EndDate));
	//if(empty($bind_mac))?$bind_mac = 0:$bind_mac = 1;
	
	//****************************************************************
	//处理IP的函数 
	function ipD2H($strIP){
		return dechex($strIP[0]*16777216+$strIP[1]*65536+$strIP[2]*256+$strIP[3]);
	}
	function ipcount($bip,$eip){
		return
	($eip[0]*16777216+$eip[1]*65536+$eip[2]*256+$eip[3])-($bip[0]*16777216+$bip[1]*65536+$bip[2]*256+$bip[3]);
	}
	function ipH2D($hip){
		if(strlen($hip)==7){
				$hip="0".$hip;
		}
		if(strlen($hip)==8){
				$ipsection=array();
				$j=0;
				for($i=0;$i<4;$i++){
						$ipsection[$i]=hexdec(substr($hip,$j,2));
						$j=$j+2;
				}
						return  implode(".",$ipsection);
		}//end if
		else{
	
				$input_errors[] =  _("非法的十六进制IP地址!");
		}//end if else
	}//end function
	//处理IP***************************************************************
	unset($Framed_IP_Address);
	if($GroupName){
	$query_str = "SELECT  * FROM ippool where GroupName='".$GroupName."'";
	$sql = mysql_query($query_str,$conn);
	$err = mysql_error();
	if($sql){
		while($rs =  mysql_fetch_array($sql)){	
			$start_ip=$rs["start_ip"];
			$end_ip=$rs["end_ip"];
		}
		mysql_free_result($sql);
		$bips=explode(".",$start_ip);
		$eips=explode(".",$end_ip);
		$icount=ipcount($bips,$eips)+1; 
	   for($i=0;$i<$icount;$i++){
			$currentlyIP = ipH2D(dechex($bips[0]*16777216+$bips[1]*65536+$bips[2]*256+$bips[3]+$i));
			$query_str2 = "SELECT  Value FROM radreply where Value='$currentlyIP' And Attribute='Framed-IP-Address';";
			$r = mysql_query($query_str2,$conn);
			$ip = mysql_fetch_array($r);
			if(!$ip){
				$i=9999999;
				mysql_free_result($r);
			}
		}
		$Framed_IP_Address=trim($currentlyIP) ;
	}
	}
	//********* end IP address	
	
	
	if (!$UserName) {
	$input_errors[] = _("用户名为空!必须输入一个有效的用户名.");
	}	
	
	if (($UserName && !preg_match("/^[a-zA-Z0-9_-]*$/", $UserName))) {
	$input_errors[] = _("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成");
	}
	
	if (($User_Password && !preg_match("/^[a-zA-Z0-9]*$/", $User_Password))) {
	$input_errors[] = _("必须输入一个有效的密码! 密码名由字母a-z, A-Z 或者数字0-9组成");
	}	
	
	if (!$Name) {
	$input_errors[] = _("用户实名为空! 必须输入一个有效的名字.");
	}	
	if (!$IDNumber) {
	$input_errors[] = _("身份证为空! 必须输入一个有效的身份证号码.");
	}	
	
	if (!$Address) {
	$input_errors[] = _("装机地址为空! 必须输入一个有效的装机地址.");
	}	
	if (!$Mobile) {
	$input_errors[] = _("客户移动电话号码为空! 必须输入一个有效的移动电话号码.");
	}	
	
	if (!$Manager) {
	$input_errors[] = _("客户经理姓名为空! 必须输入一个客户经理姓名.");
	}	
	if (!$Installer) {
	$input_errors[] = _("装机员姓名为空! 必须输入一个装机员姓名.");
	}		
	
	if (!$IDNumber) {
	$input_errors[] = _("证件号码为空,请输入证件号码.");
	}	
	
	if (!$Address) {
	$input_errors[] = _("装机地址为空,请输入装机地址.");
	}	
	
	//如果输入没有错误,进行如下的处理检查是否有重复的用户名，同时查看版本的上限人数
	if(!$input_errors){
	//检查输入的用户名是否重名
	$query_str = "SELECT UserName FROM userinfo WHERE UserName = '".$UserName."';";
	$sql = mysql_query($query_str,$conn);
	$user = mysql_fetch_array($sql);
	$err = mysql_error();
	if($user){
		$input_errors[] = _("该用户名已经存在.");
	}	
	$countsql="select count(*) as usernumber from userinfo ";
	$counttotal = mysql_fetch_array(mysql_query($countsql));
	if($counttotal["usernumber"]>20000){
		$input_errors[] = _("你以只能添加200个用户");
	}
	
	//检查IP地址是否有重复的	
	$query_str = "SELECT Value FROM radreply WHERE  Attribute = 'Framed-IP-Address' And Value = '".$Framed_IP_Address."';";	
	//echo $query_str;
	$sql = mysql_query($query_str,$conn);
	$ipaddr =  mysql_fetch_array($sql);
	
	if($ipaddr){
		$input_errors[] = _("该IP已经存在.");
	}	
	
	//检查是否存在项目名
	$sql="select * from usergroup where GroupName='".$GroupName."'";
	$obj=mysql_fetch_array(mysql_query($sql,$conn));
	if(!$obj){
		$input_errors[]=_("此用户所属项目名不存在");
	}	
	
	//检查是否存在产品
	$sql="select * from products where ID=".$Product_ID."";
	$pro=mysql_fetch_array(mysql_query($sql,$conn));
	if(!$pro){
		$input_errors[]=_("此用户所属产品编号的产品不存在");
	}
	}
	
	//echo print_r($input_errors);
	
	if($input_errors){
	
	}else{//当上面的检查没有错误时开如处理数据	
		//根据产品计算到期时间  $StartDate  
		$raw=mysql_fetch_array(mysql_query("select * from products where ID='".$Product_ID."'",$conn));
		if($raw['product_type']=="year"){
			$datestr = $EndDate." -".$raw['product_period']." year";
		}elseif($raw['product_type']=="month"){
			$datestr = $EndDate." -".$raw['product_period']." month";
		}elseif($raw['product_type']=="day"){
			$datestr = $EndDate." -".$raw['product_period']." day";
		}elseif($raw['product_type']=="hour"){
			$datestr = $EndDate." -1 month";
		}	
		$StartDate = date('Y-m-d',strtotime($datestr));
	
		//计算用户宽带值
		$upload_bandwidth = $raw['upload_bandwidth'];
		$download_bandwidth = $raw['download_bandwidth']; 
		
		//产品价格
		$product_price=$raw["product_price"];
			
		//得出nas_type值	
		$raw=mysql_fetch_array(mysql_query("select * from usergroup where GroupName='".$GroupName."'",$conn));
		$Vender  = $raw['nas_type'];	
		
		mysql_query("COMMIT");//执行事务--事务开始
		mysql_query("SET AUTOCOMMIT=0"); 
		
		//把用户添加到项目中去																
		$strSQL = "INSERT INTO usergroup(UserName,GroupName,nas_type) values('$UserName','$GroupName','$Vender');";	
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}	
		
		//写入到订单中
		$strSQL = "INSERT INTO bill(UserName,Product_ID) values('$UserName','$Product_ID');";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}		
		//添加IP地址属性
		if($Framed_IP_Address){
			$Attribute 	= "Framed-IP-Address";
			$op			=	":=";
			$Value		=	$Framed_IP_Address;
			$strSQL = "INSERT INTO radreply(UserName,Attribute,op,Value) values('$UserName','$Attribute','$op','$Value');";
			if(!mysql_query($strSQL,$conn)){
				mysql_query("ROOLBACK");//判断当执行失败时回滚
			}	
		}
		//添加密码属性
		if($User_Password){
			$Attribute 	= "User-Password";
			$op			= ":=";
			$Value		= $User_Password;
			$strSQL     = "INSERT INTO radcheck(UserName,Attribute,op,Value) values('$UserName','$Attribute','$op','$Value');";
			if(!mysql_query($strSQL,$conn)){
				mysql_query("ROOLBACK");//判断当执行失败时回滚
			}	
		}		
	
	//添加用户的带宽信息
	if($Vender == "natshell"){
		$upload   = $upload_bandwidth * 1024;
		$download = $download_bandwidth * 1024;
		$Up_Burst = $upload_bandwidth * 1024/8 * 1.5;
		$Down_Burst = intval($download_bandwidth  * 1024/8 * 1.5);
		$strSQL = "delete  from radreply where UserName='".$UserName."' and (Attribute='mpd-limit' or Attribute='mpd-filter')";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}					
		$strSQL = "delete  from radreply where UserName='".$UserName."' and (Attribute='Mikrotik-Rate-Limit')";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}			
																																										
		$strSQL = "insert into radreply(UserName,Attribute,op,Value) values('$UserName','mpd-limit','+=','in#1=all shape $upload  $Up_Burst pass');";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}							
		
		$strSQL = "insert into radreply(UserName,Attribute,op,Value) values('$UserName','mpd-limit','+=','out#2=all shape $download $Down_Burst pass');";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}							
		$strSQL = "delete  from radcheck  where Attribute = 'baduser' And UserName='".$UserName."';";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}		
	}		
	if($Vender == "mikrotik"){
		$strSQL = "delete  from radreply where UserName='".$UserName."' and (Attribute='mpd-limit' or Attribute='mpd-filter')";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}	
		$strSQL = "delete  from radreply where UserName='".$UserName."' and (Attribute='Mikrotik-Rate-Limit')";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}			
	
		$strSQL = "insert into radreply(UserName,Attribute,op,Value) values('$UserName','Mikrotik-Rate-Limit','+=','$upload_bandwidth"."k/".$download_bandwidth."k');";
		if(!mysql_query($strSQL,$conn)){
			mysql_query("ROOLBACK");//判断当执行失败时回滚
		}				
	}		
	//添加用户信息到用户表中
	$strSQL = "INSERT INTO userinfo(UserName,Name,HomePhone,Mail,RegisteDate,Manager,Installer,StartDate,EndDate,Mobile,Address,IDNumber,bind_mac) 														values('$UserName','$Name','$HomePhone','$Mail','$Now_Time','$Manager','$Installer','$StartDate','$EndDate','$Mobile','$Address','$IDNumber','$bind_mac')";	

	if(!mysql_query($strSQL,$conn)){
		mysql_query("ROOLBACK");//判断当执行失败时回滚
	}			
		
	//记录操作日志
	$logStr="insert into loginfo(Object,ObjectName,opTime,opContent,opEditor,opAction) values('userinfo','$UserName','".date("Y-m-j H:i:s",time())."','','".$_SESSION["username"]."','add')";
	if(!mysql_query($logStr,$conn)){
		mysql_query("ROOLBACK");
	}
	//更新续费记录表中		
	$strSQL = "INSERT INTO bill_log(UserName,Product_ID,AddTime,AddUserName,Action,Price) values('$UserName','$Product_ID','".date("Y-m-j H:i:s",time())."','".$_SESSION["username"]."','add','".$product_price."');";
	if(!mysql_query($strSQL,$conn)){
		mysql_query("ROOLBACK");//判断当执行失败时回滚
	}	
	mysql_query("COMMIT");//执行事务
	mysql_query("SET AUTOCOMMIT=1");	
	
	//成功执行的条数
	$successNum=$successNum+1;
	
	
	
	$err = mysql_error();			
	
	}// end inpput_errorss
	if($input_errors){
		foreach($input_errors as $key=>$rs){
			$status .="<div style='line-height:25px;'>".$rs."</div>";
		}
		$errNum=$errNum+1;
		if(!$data->sheets[0]['cells'][$row+1][1]){
			$status .="<div>"._("当前状态：这是最后一条了呀")."</div>";
		}else{
			$status .="<a href='?row=".($row+1)."&successNum=".$successNum."&errNum=".$errNum."&action=".$_GET["action"]."'>"._("跳转到一条")."</a>";
		}
	}else{
		if($data->sheets[0]['cells'][$row+1][1]){
			echo "<script language='javascript'>window.location.href='?row=".($row+1)."&successNum=".$successNum."&errNum=".$errNum."&action=".$_GET["action"]."';</script>";
		}else{
			$status .="<div>"._("这是最后一条了呀")."</div>";
		}		
	}	
	//}//end for
?>	</td>
	</tr>
	<tr>
	<td height="50"><?=$status?></td>
	</tr>
	<tr>
	<td height="50">
		<? echo _("用户数据共:")?> <?=$data->sheets[0]['numRows']-1?><? echo _("条")?>&nbsp;&nbsp;<? echo _("成功条数:")?><?=$successNum?> &nbsp;&nbsp; <? echo _("失败条数:")?><?=$errNum?></td>
	</tr>
	<tr>
	  <td height="50">
	  </td>
	  </tr>
	</table>
<?php
}else{
?>
<form action="excel.php?action=import" method="post" enctype="multipart/form-data" name="myform">
<table width="98%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td><? echo _("请选择你要导入文件格式只能是 .xls 文件")?></td>
  </tr>
  <tr>
    <td><input type="file" name="excelfile" />
      <input name="submit" type="submit" value="<? echo _("导入")?>" /></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><a href="user.xls"><? echo _("样表格式下载")?></a> <? echo _("(请严格按照系统提供的样表格式进行用户数据编辑，否则无法导入)")?></td>
  </tr>
</table>
</form>
<?php 
}
?>
    </td>
  </tr>
</table>
</body>
</html>