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
$rs = $db->select_one("*","sendmailConfig","id=1");
if($rs){
	$savedDate = $rs['savedDate'];
	$backupDir = $rs['backupDir'];
	$backupOption = $rs['backupOption'];
 	$server = $rs['server'];
	$tomail = $rs['tomail'];
	$frommail = $rs['frommail'];
	$frompwd = $rs['frompwd'];
	
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
      
	exec("/usr/local/usr-gui/sendmail_backup.sh $frommail $tomail $fileName $server $frompwd");
	//echo "cd /usr/local/usr-gui/ftpbackup; ftpput -u $ftpUser -p $ftpPwd $ftpHost -P $ftpPort $fileName";
        exec("rm -rf /usr/local/usr-gui/sendmailbackup/$fileName");//删除文件
	
 /*
header("content-type:text/html;charset=utf-8");
ini_set("magic_quotes_runtime",0);
require 'PHPMailer/class.phpmailer.php';
try {
$mail = new PHPMailer(true);
$mail->IsSMTP();
$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
$mail->SMTPAuth = true; //开启认证
$mail->Port = 25;
$mail->Host = $server;
$mail->Username = $frommail;
$mail->Password = $frompwd;
//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
//$mail->AddReplyTo("yh20090202@163.com","mckee");//回复地址
$mail->From = $frommail;
//$mail->FromName = "www.phpddt.com";
$to = $tomail;
$mail->AddAddress($to);
$mail->Subject = "数据库备份";
$mail->Body = "请点击附件下载备份";
//$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
$mail->WordWrap = 80; // 设置每行字符串的长度
$mail->AddAttachment("$backupDir/$fileName"); //可以添加附件
$mail->IsHTML(true);
$mail->Send();
echo '邮件已发送';
exec("rm -rf /usr/local/usr-gui/sendmailbackup/$fileName");//删除文件
} catch (phpmailerException $e) {
echo "邮件发送失败：".$e->errorMessage();
exec("rm -rf /usr/local/usr-gui/sendmailbackup/$fileName");//删除文件
}*/
}
?>