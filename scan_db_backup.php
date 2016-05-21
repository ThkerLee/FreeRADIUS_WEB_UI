#!/bin/php
<?php 
include("inc/excel_conn.php");
$rs=$db->select_one("scannum","config","ID >0 ORDER BY ID DESC"); 
$dir = "/usr/local/usr-gui/backup";
if($rs) $num = $rs["scannum"]-1; else $num = 10;//指定保留的备份文件个数   因为顺序是先删除在备份所以要 -1
$delbak = "bak_acct";
make_dir($dir);//判断备份指定文件夹是否存在
scan_del_sql($dir,$num);//删除指定过早的备份文件
make_db_sql($dir,$mysqldb,$delbak);//备份数据库  
function scan_del_sql($dir,$num){ //$num 保留文件的个数
$i = 1;
 if (is_dir($dir)) { 
   if ($dh = opendir($dir)) {   
       while (($file = readdir($dh)) !== false) { 
          if(eregi("^[0-9]{8,8}([0-9a-z_]+)(\.sql)$",$file)){
		      $time =filectime($dir."/".$file);
			    $fileTime[] = $time ;
			    $fileName[$time] = $file;    
	       }   
       }//end wile   
	 if(is_array($fileTime)){ 
	 	 arsort($fileTime);//根据时间降序排序 
	   foreach($fileTime as $tVal){
	     if($i <= $num)  $timeVal[] = $tVal;//获取的最新备份的数据库 ￥num 条 
	     $i++;
	    }  
	   //获取当前数组中count > 指定文件数的文件 
	   if(is_array($fileName) && count($fileName)>$num){//是数组，并且文件的个数超过默认保留的备份的、文件的个数  删除多余的早期备份的文件
	      foreach($fileName as $key=>$fileVal){ 
		   if(!in_array($key,$timeVal))@unlink($dir."/".$fileVal);  
	      } 
	   } //end is_array 
    } 
   }//end is_array 
  }//end is_dir 
closedir($dh);
}

 

/*
make_dir();//判断备份指定文件夹是否存在
make_db_sql();//备份数据库
//查看文件类型并删除保留指定的最新的备份数据文件
*/ 
function make_dir($dir){
  if(!is_dir($dir)){
     @mkdir($dir, 0700); 
  }
}
 
function write_file($dir,$sql,$filename)
{
	$re=true;
	if(!@$fp=fopen($dir."/".$filename,"w+")) {$re=false; echo "failed to open target file";}
	if(!@fwrite($fp,$sql)) {$re=false; echo "failed to write file";}
	if(!@fclose($fp)) {$re=false; echo "failed to close target file";}
	return $re;
} 
 
function make_header($table)
{global $d;
$sql="DROP TABLE IF EXISTS ".$table."\n";
$d->query("show create table ".$table);
$d->nextrecord();
$tmp=preg_replace("/\n/","",$d->f("Create Table"));
$sql.=$tmp."\n";
return $sql;
}

function make_record($table,$num_fields)
{global $d;
$comma="";
$sql .= "INSERT INTO ".$table." VALUES(";
for($i = 0; $i < $num_fields; $i++) 
{$sql .= ($comma."'".mysql_escape_string($d->record[$i])."'"); $comma = ",";}
$sql .= ")\n";
return $sql;
}

function show_msg($msgs)
{ 
while (list($k,$v)=each($msgs))
	{
	echo $v ;
	} 
} 
function pageend()
{
exit();
}
function make_db_sql($dir="/usr/local/usr-gui/backup",$mysqldb="radius",$delbak=""){
 global $d; 
		 /*
		  if(!empty($delbak)){
		     $d->query("truncate table `bak_acct`");//清空上网记录表
		  } 		  
			if(!$tables=$d->query("show table status from $mysqldb")) {
				$msgs[]=_("读数据库结构错误"); show_msg($msgs); pageend();
			}
			$sql="";
			while($d->nextrecord($tables)) {
				$table=$d->f("Name");
				$sql.=make_header($table);
				$d->query("select * from $table");
				$num_fields=$d->nf();
				while($d->nextrecord()) {
					$sql.=make_record($table,$num_fields);
				}
			}
			$sql="";
			*/
			$needTable = array("area","areaandproject","card","client_notice","config","credit","finance","grade","manager","managergroup","managerpermision","maturity_notice","nas", "orderinfo","orderrefund","product","productandproject","project","project_ros","radcheck","radreply","runinfo","sync_mysql","ticket","userattribute","userbill","userinfo","userrun","userlog"); 
			foreach ($needTable as $tables){ 
				$table=$tables;
				$sql.=make_header($table);
				$d->query("select * from $table");
				$num_fields=$d->nf();
				while($d->nextrecord())
				{
					$sql.=make_record($table,$num_fields);
				}
			}
			
			
			$filename=date("Ymd",time())."_".time()."_scan.sql"; 
			$filename=date("Ymd_His",time())."_scan.sql"; 
		/*----不要卷结束*/
		 
		if($sql!=""){ //$filename.=("_v".$p.".sql");	
			if(write_file($dir,$sql,$filename)) {
				$msgs[]=_("success").$dir."'/$filename'";
			}
			show_msg($msgs);
		}/*--------------------------------------*//*--------备份全部表结束*/  
  }
 
?> 