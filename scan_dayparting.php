#!/bin/php
<?php
//include("inc/scan_conn.php"); 
//非流量产品 分时间段 流量分配  
//非natshell项目产品在线修改属性值在下下线，离线用户修改属性值
//$nowTime=date("H:i:s",time());//当前时间
  $pAll=$db->select_all("*","product","type!='flow'");  
  
    $sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID ";//and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")
		   $result=$db->select_all("u.ID as userID,u.account,u.projectID,a.orderID,p.ID as product_ID,p.name as product_name,p.upbandwidth ,p.downbandwidth,p.dayparting,p.starttime,p.stoptime,p.partingupbandwidth,p.partingdownbandwidth","userinfo as u,userattribute as a,orderinfo as o,product as p ",$sql); 
  if($result){//用户信息
  	foreach($result as $value){
		if($value['dayparting']==1){//启用分时分配流量
			 $starttime=$value['starttime'];
			 $starttimeT=date("H:i:s",strtotime($value['starttime']."+".runtime()." minutes"));//开始时间+扫描间隔时间
			 $stoptime=$value['stoptime'];
			 $stoptimeT =date("H:i:s",strtotime($value['stoptime']."+".runtime()." minutes")); //结束时间+扫描间隔时间
			 $userID=$value['ID'];
			 $productID=$value['product_ID'];
			 $UserName=$value['account'];
			 $supbandwidth=$value['partingupbandwidth'];//时间段上传速率
			 $sdownbandwidth=$value['partingdownbandwidth'];//时间段下载速率
			 $upbandwidth=$value['upbandwidth'];//正常上传速率
			 $downbandwidth=$value['downbandwidth'];//正常下载速率
			 //查询项目类型
			 $Ps=$db->select_one("device",'project',"ID='".$value['projectID']."'");
			 //在开始时间进行扫描 
			 if($nowTime>$starttime && $nowTime<$starttimeT){//开始时间  用户下线 更改带宽
			 	if($Ps['device']=='natshell'){//natshell项目
				    natshell($userID,$productID,$supbandwidth,$sdownbandwidth);  
					 
				}else{//非natshell项目
					$radacctRs=$db->select_one('UserName','radacct',"UserName='".$value['account']."'");
					if($radacctRs){//在线
					     //踢用户下线
					    include('inc/scan_down_line.php'); 
                                            //--------在t.php记录下线记录2014.03.17----------
                                           $file = fopen('t.php','a');
                                            $name="scan_dayparting.php*1非natshell项目踢用户下线";
                                            $time=date("Y-m-d H:i:s",time())."||";
                                            fwrite($file,$name.$time);
                                            fclose($file);
                                            //-----------------------------------------------
					}
					//更改属性值 改为时间段上下带宽值
					addUserParaminfo($userID,$productID,$supbandwidth,$sdownbandwidth);
				}
			 }else if($nowTime>$stoptime && $nowTime<$stoptimeT){//在和结束时间进行扫描 
			 	if($Ps['device']=='natshell'){//natshell项目 
					//更改属性值 改为正常上下带宽值  
					 natshell($userID,$productID,$upbandwidth,$downbandwidth);
					
				}else{//非natshell项目
					$radacctRs=$db->select('UserName','radacct',"UserName='".$value['account']."'");
					if($radacctRs){//在线
					     //踢用户下线
					    include('inc/scan_down_line.php');
                                            //--------在t.php记录下线记录2014.03.17----------
                                           $file = fopen('t.php','a');
                                            $name="scan_dayparting.php*2非natshell项目踢用户下线";
                                            $time=date("Y-m-d H:i:s",time())."||";
                                            fwrite($file,$name.$time);
                                            fclose($file);
                                            //-----------------------------------------------
					}
					//更改属性值 改为正常上下带宽值
					addUserParaminfo($userID,$productID,$upbandwidth,$downbandwidth);
				} 
			 }                                             
		}
	} 
  }
 function natshell($userID,$productID,$supbandwidth,$sdownbandwidth){
 	  //更改属性值 改为时间段上下带宽值
     addUserParaminfo($userID,$productID,$supbandwidth,$sdownbandwidth);
	 $radacctRs=$db->select_one('*','radacct',"UserName='".$value['account']."'");
	 $repalyRsIn=$db->select_one('Value','radreply',"userID='".$userID."' and UserName='".$UserName."'   and `Attribute`='mpd-limit' and Value like '%in%'");
	 $repalyRsOut=$db->select_one('Value','radreply',"userID='".$userID."' and UserName='".$UserName."'  and `Attribute`='mpd-limit' and Value like '%out%'");
	 $NAS_IP    =trim($radacctRs['NASIPAddress']);
	 $nas_rs=$db->select_one("*","nas","ip='".$NAS_IP."'");
	 $http_port=$nas_rs["ports"];
	 $share_passwd=$nas_rs["secret"];
	 $FramedIPAddress=$radacctRS['FramedIPAddress']; 
	 
	 $cmd="echo \"User-Name={$UserName}, Framed-IP-Address='".$FramedIPAddress."', mpd-limit='".$repalyRsIn['Value']."', mpd-limit='".$repalyRsOut['Value']."'\" | /usr/local/bin/radclient -x {$NAS_IP} coa {$share_passwd}";
	 pclose(popen($cmd, "r"));
	
 }
  
?>