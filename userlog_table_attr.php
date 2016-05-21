#!/bin/php
<?
include("inc/db_config.php"); 
require_once("evn.php");
$db   = mysql_connect($mysqlhost,$mysqluser,$mysqlpwd);
$conn = mysql_select_db($mysqldb);
	    mysql_query("set names utf8");   

$userlog[]="ALTER TABLE `radius`.`userlog` ADD COLUMN `account` varchar(256) DEFAULT ''"; 

$userlog[]="ALTER TABLE `radius`.`userlog` ADD COLUMN `name` varchar(256) DEFAULT ''"; 
 
//$userlog[]="ALTER TABLE `radius`.`userlog` ADD COLUMN `details` varchar(256) DEFAULT ''";

$userlog[]="ALTER TABLE `radius`.`userlog` ADD COLUMN `projectID` varchar(256) DEFAULT ''"; 	
 
$userlog[]="ALTER TABLE `radius`.`userlog` ADD COLUMN `money` int(16) DEFAULT ''";

foreach($userlog as $sql_val){
			mysql_query($sql_val);
		}
 
?>    
