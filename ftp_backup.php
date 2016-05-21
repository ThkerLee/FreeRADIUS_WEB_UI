#!/bin/php
<?php
date_default_timezone_set('Asia/Shanghai');
#ignore_user_abort();//关闭浏览器,PHP脚本也可以继续执行.
#set_time_limit(0);//通过set_time_limit(0)可以让程序无限制的执行下去
include("inc/db_config.php"); 
include("inc/class_mysql.php");     
$db   = new Db_class($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//程序

mysql_select_db($mysqldb);
$dbHost = 'localhost';

$fileName = "radius_" . date('YmdHis',time()) . ".sql";

$rs = $db->select_one("*","ftpConfig","id=1");
if($rs){
	$savedDate = $rs['savedDate'];
	$backupDir = $rs['backupDir'];
	$backupOption = $rs['backupOption'];
	$ftpPort = $rs['ftpPort'];
	$ftpHost = $rs['ftpHost'];
	$ftpUser = $rs['ftpUser'];
	$ftpPwd = $rs['ftpPwd'];
	
	if(is_array($backupOption) && count($backupOption)>0){
		$cmd = "mysqldump -u$mysqluser -p$mysqlpwd -h $dbHost radius ";
		foreach($backupOption as $v){
			$cmd = $cmd." --ignore-table=radius.$v";
		}
		$cmd = $cmd." > $backupDir/$fileName";
	}else{
		$cmd = "mysqldump -u$mysqluser -p$mysqlpwd -h $dbHost radius > $backupDir/$fileName";
	}
	exec($cmd);
	
	exec("cd $backupDir; ftpput -u $ftpUser -p $ftpPwd $ftpHost -P $ftpPort $fileName");
	//echo "cd /usr/local/usr-gui/ftpbackup; ftpput -u $ftpUser -p $ftpPwd $ftpHost -P $ftpPort $fileName";

	if (is_dir($backupDir)) {
	
		if ($dh = @opendir($backupDir)) {
			while(($file = readdir($dh)) !== false){
				
				if($file == "." || $file == "..")
				{
					continue;
				}
				
				$filepath = $backupDir.'/'.$file;//文件绝对路径
				
				if(filemtime($filepath)<strtotime("-$savedDate days")){//如果文件过了本地保存期则删除该文件
					unlink($filepath);
				}
			}
			closedir($dh);
		}
	}
}
?>