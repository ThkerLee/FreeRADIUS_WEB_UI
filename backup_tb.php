#!/bin/php
<?php
date_default_timezone_set('Asia/Shanghai');
#ignore_user_abort();//关闭浏览器,PHP脚本也可以继续执行.
#set_time_limit(0);//通过set_time_limit(0)可以让程序无限制的执行下去

include("inc/db_config.php");
$host="127.0.0.1";
mysql_connect($mysqlhost,$mysqluser,$mysqlpwd);
mysql_select_db($mysqldb);
mysql_query("set names utf8");
$arr=mysql_query("select * from tbConfig");
$re=mysql_fetch_assoc($arr);
$tbUser=$re['tbUser'];
$tbPwd =$re['tbPwd'];
$tbHost=$re['tbHost'];
$backupOption=$re['backupOption'];
$backupDir=$re['backupDir'];
#do{
	$fileName = "radius_" . "tbConfig" . ".sql";
	if(!empty($backupOption)){
		$cmd = "mysqldump -u$mysqluser -p$mysqlpwd -h $host $mysqldb ";
		$backupOptions=explode(",",$backupOption);
		foreach($backupOptions as $v){
			$cmd = $cmd." --ignore-table=$mysqldb.$v";
		}
		$cmd = $cmd." > $backupDir/$fileName";

		exec($cmd);
	}else{
		exec("mysqldump -u$mysqluser -p$mysqlpwd -h $host $mysqldb > $backupDir/$fileName");
	}
	#exec("cd /usr/local/usr-gui/ftpbackup; ftpput -u $ftpUser -p $ftpPwd $ftpHost -P $ftpPort $fileName");
	exec("mysql -u$tbUser -p$tbPwd -h$tbHost  $mysqldb < $backupDir/$fileName");//导入
	//echo "cd /usr/local/usr-gui/ftpbackup; ftpput -u $ftpUser -p $ftpPwd $ftpHost -P $ftpPort $fileName";
	exec("rm -rf /usr/local/usr-gui/tb_backup/$fileName");//删除文件
	#sleep($interval);
#}while(true);
mysql_close();
?>