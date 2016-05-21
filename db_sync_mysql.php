#!/bin/php
<?php   
session_start();
include_once("./inc/db_config.php"); 
include_once("inc/class_mysql.php");
include_once("inc/class_database.php");
include_once("inc/class_public.php"); 
require_once("evn.php"); 
 if(in_array("db_sync_mysq.php",$_SESSION['auth_permision'])==false)
{
   echo "<script language='javascript'>alert('"._("没有管理权限")."');window.history.go(-1);</script>";
   exit;
}
$db   = new Db_class($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//程序
$d    = new db($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//数据库备份 
$conn = mysql_connect($mysqlhost,$mysqluser,$mysqlpwd); 
mysql_select_db($mysqldb,$conn);
mysql_query("set names utf8"); 
$configRs=$db->select_one("*","config","ID=1 order by ID DESC"); 
$config_version=$configRs["version"];
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="Refresh"content="5;URL=<?php echo 'db_sync_mysql.php?'.rand(0,5);?>" />
 
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
<script src="js/datechooser.js" type="text/javascript"></script>
</head>
<body> 
<?  
  $rs=$db->select_one("*","sync_mysql","ID =1"); 
if($_POST){
	$sess ;
  //  MysqlBegin();//开始事务定义 
   // $sql_a =true;$sql_b =true;$sql_c=true;
	if($_GET['action']=="update"){ 
		$status      = $_POST["status"];
		$mode        = $_POST["mode"];
		$ipaddress   = $_POST["ipaddress"];
	  $username    = $_POST["username"];
		$password    = $_POST["password"];  
		} 
		 $sql=array( 
		  "ID"=>1, 
		  "status"=>$status,
		  "mode"=>$mode,
		  "ipaddress"=>$ipaddress,
		  "username"=>$username, 
		  "password"=>$password
		  );  
		  $db->delete_new("sync_mysql","ID !=0");  
	    $db->insert_new("sync_mysql",$sql);   
	    $fileStr = file_get_contents("/mnt/mysql/data/data/my.cnf");
	    $fileArr=explode("server-id=",$fileStr); 
	     if($status=="enable"){
	       if($mode=="server" ){
	       $handle= fopen("/mnt/mysql/data/data/my.cnf","w");
         $somecontent=trim($fileArr[0])."
server-id=1
log-bin=/mnt/mysql/data/data/mysql-bin
binlog-do-db=radius ";
	       if(fwrite($handle, $somecontent) === FALSE) $sql_c=false ;    
	       fclose($handle); 
	       //重启MYSQL       
	       /* echo "<script>alert('"._("设置成功")."');window.location.href='db_sync_mysql.php'</script>";  
	       */

	      // echo "<script>alert('"._("设置成功")."');</script>"; 
	        echo connection();
	         pclose(popen("/etc/init.d/rc.mysqld restart", "r"));
	       echo connection();
	       // pclose(popen("echo \"grant replication slave on *.* to ".$username."@'".$ipaddress."' identified by '".$password."'\" | mysql", "r"));   
        // pclose(popen("echo \"flush privileges\" | mysql", "r")); 
       //  pclose(popen("echo \"show master status \G\" | mysql | grep -w File | awk -F ':' '{print $2}'", "r"));  
          
         //备份主服务器的数据
       //  pclose(popen("mysqldump -uradius -pds549gGF32fdkk2Ter675wiyi23de5 radius >radius.sql","r"));
         //主服务器上的数据同步到备份服务器上
        // pclose(popen("mysql -uradius -pds549gGF32fdkk2Ter675wiyi23de5 -h".$ipaddress." radius <radius.sql","r")); 
       
	    }else{
	      $handle= fopen("/mnt/mysql/data/data/my.cnf","w");
        $somecontent=trim($fileArr[0])."
server-id=2";
	       if(fwrite($handle, $somecontent) === FALSE) $sql_c=false ;  
	       fclose($handle); 
	       
	       //重启MYSQL        
	       pclose(popen("/etc/init.d/rc.mysqld restart","r")); 
	       //关闭slave
	       pclose(popen("echo\"stop slave\" |mysql","r")); 
	       //$log=XXX;
	       pclose(popen("change master to master_host='".$ipaddress."', master_user='".$username."',master_password='".$password."',master_port='3306',master_log_file='".$log."'|mysql","r"));
	       pclose(popen("echo start slave | mysql","r")); 
	    } 
	   }else{
	    if($mode=="server" ){
	       $handle= fopen("/mnt/mysql/data/data/my.cnf","w");
         $somecontent=trim($fileArr[0]);
	       if(fwrite($handle, $somecontent) === FALSE) $sql_c=false ;    
	       fclose($handle); 
	       //重启MYSQL        
	       pclose(popen("/etc/init.d/rc.mysqld restart", "r"));  
	    }else{
	       $handle= fopen("/mnt/mysql/data/data/my.cnf","w");
         $somecontent=trim($fileArr[0]); 
	       if(fwrite($handle, $somecontent) === FALSE) $sql_c=false ;  
	       fclose($handle); 
	       //重启MYSQL        
	       pclose(popen("/etc/init.d/rc.mysqld restart","r"));
	       //关闭slave
	       pclose(popen("echo\"stop slave\" |mysql","r"));  
	    } 
	   } 
	    

	/*      
	 if($sql_a && $sql_b && $sql_c ){
      MysqlCommit();  
      echo "<script>alert('"._("设置成功")."');window.location.href='db_sync_mysql.php'</script>"; 
   }else{
      MysqRoolback();
      echo "<script>alert('"._("设置失败")."');</script>";
   }*/
    //  MysqlEnd();
} 

function connection(){
global  $mysqlhost,$mysqluser,$mysqlpwd;
 $con = mysql_connect($mysqlhost,$mysqluser,$mysqlpwd);
if (!$con){
	 connection(); 
	 echo "111"; 
	 /*
	 echo "<script>window.location.href='db_sync_mysql.php'</script>";
	 */
} 
else return "true"; 
}


?> 
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td> 
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"> 
		<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
		  <tr> 
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td> 
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td> 
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
        <td width="89%" class="f-bulue1"> <? echo _("数据库同步")?>  </td>  
		    <td width="11%" align="right">&nbsp;</td> 
      </tr> 
	</table>  
 <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1"  > 
     <form  action="?action=update" method="post" name="myform"  onSubmit="return checkSyncForm();" >
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1"  >
     <tbody>     
		  <tr> 
			 <td width="13%" align="right" class= "bg"><? echo _("状态")?></td>
			 <td width="87%" align="left" class="bg">
			   <input type="radio" name="status" value="enable"  <? if($rs['status']=="enable")echo "checked";?> ><? echo _("启用")?>
			   <input type="radio" name="status"  value="disable" <? if($rs['status']=="disable" || $rs['status']=="") echo "checked";?>/><? echo _("禁用")?>
			 </td>
		   </tr> 
		   <tr>
			   <td width="13%" align="right" class="bg"><? echo _("模式")?></td>
			   <td width="87%" align="left" class="bg">
			     <input type="radio" name="mode" value="server"  onClick="mode_change();" <? if($rs['mode']=="server"|| $rs['status']=="")echo "checked";?>  /><? echo _("服务器端")?>
			     <input type="radio" value="client" name="mode"  onClick="mode_change();" <? if($rs['mode']=="client" ) echo "checked";?>   /><? echo _("客户端")?>
			     </td>
			</tr>  
		  <tr>
			  <td width="13%" align="right" class="bg">  
				<span id="serverTR" <? if($rs['status']=="server" || $rs['status']=="")echo "style='disable:block' "; 
					   else echo " style='disable:none' "; ?> ><? echo _("客户端IP地址"); ?> </span>
			  <span id="clientTR" <? if($rs['status']=="client" )echo "style='disable=\"block\"'" ?> ><? echo _("服务端IP地址"); ?> </span> 
		    </td>
			  <td width="87%" align="left" class="bg"> <input type="text" name="ipaddress" id="ipaddress"value="<?=$rs['ipaddress']?>"> </td>
		  </tr> 
		  <tr> 
			  <td width="13%" align="right" class="bg"><? echo _("用户名")?></td>
			  <td width="87%" align="left" class="bg">
			  	<input type="text" name="username" value="<?=$rs['username']?>">
			  </td>
	   </tr> 
		  <tr> 
			<td width="13%" align="right" class="bg"><? echo _("密码")?></td> 
			<td width="87%" align="left" class="bg">
			 <input type="password" name="password"value="<?=$rs["password"]?>" /> 
			</td>
		  </tr>
		  <tr> 
		    <td align="right" class="bg">&nbsp;</td> 
		    <td align="left" class="bg"> <input  type="submit"  value="<? echo _("提交")?>" onClick="javascript:return window.confirm( '确认提交？');">	   
		    </td> 
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
        	
        	
        	
        	
<script> 
 
	window.onload=mode_change();
function mode_change(){ 
	v=document.myform.mode; 
	if(v[0].checked){
		document.getElementById("serverTR").style.display="";
		document.getElementById("clientTR").style.display="none"; 
	}else if(v[1].checked){
		document.getElementById("serverTR").style.display="none";
		document.getElementById("clientTR").style.display=""; 
	} 
}	
/*
function tj(){
alert("11111");
sleep(5);
alert("222222");
window.location.reload();
clearTimeout(refresh);
} 
function sleep(numberMillis) {
 var now = new Date();
 var exitTime = now.getTime() + numberMillis;
 while (true) {
  now = new Date();
  if (now.getTime() > exitTime)
    return;
  }
} */
</script>