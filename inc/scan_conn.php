<?php  
date_default_timezone_set('Asia/Shanghai');  
@session_start();
include("db_config.php");
include("class_mysql.php");
include("class_database.php");
include("class_public.php");
@include("../evn.php");
@include("evn.php");
include_once("ajax_js.php");
$db   = new Db_class($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//程序
$d    = new db($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//数据库备份 
$conn = mysql_connect($mysqlhost,$mysqluser,$mysqlpwd); 
mysql_select_db($mysqldb,$conn);
mysql_query("set names utf8"); 
//获得utf8字符串的长度 
function strlen_utf8($str) {
	$i = 0;
	$count = 0;
	$len = strlen ($str);
	while ($i < $len) {
		$chr = ord ($str[$i]);
		$count++;
		$i++;
		if($i >= $len) break;
			if($chr & 0x80) {
				$chr <<= 1;
				while ($chr & 0x80) {
					$i++;
					$chr <<= 1;
				}
			}
		}
	return $count;
}
function runtime(){
	$str= file_get_contents("/etc/crontab");
	$array=explode("\n",$str);
	$array1=explode("*/",$array[3]);
	$array2=explode("*",$array1[1]);
	$time=(int)trim($array2[0]);//scan_hour_flow.php扫描程序执行间隔时间
	return $time; 
}


?>