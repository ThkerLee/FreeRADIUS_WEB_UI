<?php  
//ROS 通告发送 根据用户查数据库,得到NAS IP,用户的SESSION  ID 
$row=$db->select_one("*","radacct","AcctStopTime='0000-00-00 00:00:00' and UserName='".$UserName."'");
if($row){
	$SessionID =$row['AcctSessionId'];
  $NAS_IP    =trim($row['NASIPAddress']);
  $tmp       =explode("-",$SessionID);
  $FramedIPAddress = $row['FramedIPAddress'];
  if($tmp[2]){
	   $SessionID=$tmp[1]."-".$tmp[2];
  }else{
	   $SessionID=$tmp[1];
  }
  $nas_rs=$db->select_one("*","nas","ip='".$NAS_IP."'");
  $http_port=$nas_rs["ports"];
  $share_passwd=$nas_rs["secret"];
  //获取供应商信息  
  $dRs=$db->select_one("p.device,confname","userinfo as u,project as p","u.projectID=p.ID and u.UserName='".$UserName."'");
  $confname   = $dRs['confname'];	
  //发送通告 
  $cmd ="echo \"Acct-Session-Id ={$SessionID},Alc-SLA-Prof-Str='$confName'\"| radclient -d /etc/raddb {$NAS_IP}:{$http_port} coa {$share_passwd} &";
  pclose(popen($cmd, "r"));  
}
?> 