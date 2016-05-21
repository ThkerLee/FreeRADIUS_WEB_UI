#!/bin/php
<?
session_start();
$db   = mysql_connect("localhost","root","");
$conn = mysql_select_db("radius");
	    mysql_query("set names utf8");
$versionRs=mysql_fetch_array(mysql_query("select * from config order by ID DESC"));

$config[]="ALTER TABLE `radius`.`config` ADD COLUMN `picLogin` varchar(256) DEFAULT '/images/login_bg.jpg'"; 

$config[]="ALTER TABLE `radius`.`config` ADD COLUMN `WEB` varchar(256) DEFAULT 'http://www.natshell.com'"; 

$config[]="ALTER TABLE `radius`.`config` ADD COLUMN `Name` varchar(256) DEFAULT '蓝海卓越系统'"; 


$config[]="ALTER TABLE `radius`.`config` ADD COLUMN `copyrightLog` varchar(256) DEFAULT '版权归属:蓝海卓越所有'"; 

$config[]="ALTER TABLE `radius`.`config` ADD COLUMN `CRStatement` TEXT DEFAULT ''";

$config[]="ALTER TABLE `radius`.`config` ADD COLUMN `Contact` varchar(256) DEFAULT ''";


$str="1、本系统为商业授权，未经授权，不得以任何方式进行对本系统进行破解、复制、传播等行为;
2、用户可自由选择是否使用本系统，任何未经授权的使用，在使用中出现的问题和由此造成的一切损失本公司不承担任何责任;
3、您可以对本系统进行修改和美化，但必须保留完整的版权信息; 
4、本系统受中华人民共和国《著作权法》《计算机软件保护条例》《商标法》《专利法》等相关法律、法规保护，星锐蓝海网络科技有限公司保留一切权利。";

$config[]="update config set CRStatement='".$str."'";
foreach($config as $sql_val){
			mysql_query($sql_val);
		}

?>    
