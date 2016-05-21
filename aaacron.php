<?php 
//写入计划任务
$rs1=$db->select_one("*","cron","type = 1 ");//scan_db_backup.php自动备份
   $cronstatus1=$rs1['status'];
   if($rs1["newtime"]==""){
       $newtime1=2;
   }  else {
       $newtime1=$rs1["newtime"];
}
       
   
$rs2=$db->select_one("*","cron","type = 2 ");//scan_time_len.php停机少苗
$cronstatus2=$rs2['status'];
   if($rs2["newtime"]==""){
       $newtime2=1;
   }  else {
       $newtime2=$rs2["newtime"];
}
 
$rs3=$db->select_one("*","cron","type = 3 ");//scan_1自定义1
$cronstatus3=$rs3['status'];
$newtime3=$rs3["newtime"];
$files3=$rs3["files"];

$rs4=$db->select_one("*","cron","type = 4 ");//scan_2自定义2
$cronstatus4=$rs4['status'];
$newtime4=$rs4["newtime"];
$files4=$rs4["files"]; 

$rs5=$db->select_one("*","cron","type = 5 ");//scan_3自定义3
       $cronstatus5=$rs5['status'];
       //$newtime5=$rs5["newtime"];
          if($rs5["newtime"]==""){
       $newtime5=5;
   }  else {
       $newtime5=$rs5["newtime"];
}
        if($rs5["files"] == ""){
          $files5="bak_event.php";  
        }  else {
          $files5=$rs5["files"];  
            }
       

 $rs6 = $db->select_one("*","sendmailConfig","id=1");  //邮箱备份    
    $mailstatus=$rs6["status"];  
  $savedDate=$rs6["savedDate"];
$re3=$db->select_one("*","message","type = 3 ");//即将到期
    $Status3=$re3['status'];
    $day3=$re3['days']; 
$re4=$db->select_one("*","message","type = 4 ");//到期
    $Status4=$re4['status'];  
    $day4=$re4['days']; 
$tb=$db->select_one("*","tbConfig","id = 1 ");//同步
    $tbStatus=$tb['status'];
    $tbInterval=$tb['interval'];
$ftp=$db->select_one("*","ftpConfig","id = 1 ");//FTP
    $ftpStatus=$ftp['status'];
    $ftpInterval=$ftp['interval'];

    $str="echo -e \"SHELL=/bin/sh\nPATH=/sbin:/bin:/usr/sbin:/usr/bin\nMAILTO=root\nHOME=/\n";
    $endstr = "\" | crontab - 2>&-";
if($cronstatus1== "enable" ||$cronstatus1== ""){
   if($rs1["time"]=="fen"){
        $str = $str."*/$newtime1 * * * * /bin/php /usr/local/usr-gui/scan_db_backup.php\n";
   }elseif ($rs1["time"]=="shi"||$rs1["time"]=="") {
        $str = $str."0 */$newtime1 * * * /bin/php /usr/local/usr-gui/scan_db_backup.php\n";
      }
}


if($cronstatus2== "enable" ||$cronstatus2== ""){
   if($rs2["time"]=="fen"){
        $str = $str."*/$newtime2 * * * * /bin/php /usr/local/usr-gui/scan_time_len.php\n";
   }elseif ($rs2["time"]=="shi"||$rs2["time"]=="") {
        $str = $str."0 */$newtime2 * * * /bin/php /usr/local/usr-gui/scan_time_len.php\n";
      }
}

if($cronstatus3== "enable"){
   if($rs3["time"]=="fen"){
        $str = $str."*/$newtime3 * * * * /bin/php /usr/local/usr-gui/$files3\n";
   }elseif ($rs3["time"]=="shi") {
        $str = $str."0 */$newtime3 * * * /bin/php /usr/local/usr-gui/$files3\n";
      }
}
  
if($cronstatus4== "enable"){
   if($rs4["time"]=="fen"){
        $str = $str."*/$newtime4 * * * * /bin/php /usr/local/usr-gui/$files4\n";
   }elseif ($rs4["time"]=="shi") {
        $str = $str."0 */$newtime4 * * * /bin/php /usr/local/usr-gui/$files4\n";
      }
}
  
if($cronstatus5== "enable" ||$cronstatus5 == ""){
   if($rs5["time"]=="fen" || $rs5["time"]== ""){
        $str = $str."*/$newtime5 * * * * /bin/php /usr/local/usr-gui/$files5\n";
   }elseif ($rs5["time"]=="shi") {
        $str = $str."0 */$newtime5 * * * /bin/php /usr/local/usr-gui/$files5\n";
      }
}




if($mailstatus =="enable"){
 $str = $str."0 */$savedDate * * * /bin/php /usr/local/usr-gui/sendmail_backup.php\n";   
}


if($tbStatus == "enable"){
  $str = $str."0 */$tbInterval * * * /bin/php /usr/local/usr-gui/backup_tb.php\n";
//  echo "321"."<br/>";
  }

if($Status3 == "enable"){
  $str = $str."0 */$day3 * * * /bin/php /usr/local/usr-gui/short_message.php\n";
}
if($Status4 == "enable"){
  $str = $str."0 */$day4 * * * /bin/php /usr/local/usr-gui/short_messages.php\n";
}                    
if($ftpStatus == "enable"){
  $str = $str."0 */$ftpInterval * * * /bin/php /usr/local/usr-gui/ftp_backup.php\n";
}
$str = $str.$endstr;
exec($str );	
?>
