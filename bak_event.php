#!/bin/php
<?php 
include("inc/scan_conn.php");  
$rs=$db->select_one("*","cron","type = 5 ");
if(!empty($rs["backTime"])){
    $backTime=$rs["backTime"]*60;//把分钟换成秒
}  else {
   $backTime=6*60;
}

 $timeLimit = '-'.$backTime;
$rs=mysql_query('CALL radius.bak_proc(@STATUS,@message,'.$timeLimit.')');//调用bak_proc存储过程
//echo 'CALL radius.bak_proc(@STATUS, @message, '.$timeLimit.')';
//query('call adius.bak_proc(@STATUS, @message, '.$timeLimit.')');

//var_dump($rs);
?>
