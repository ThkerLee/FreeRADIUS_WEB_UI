#!/bin/php
<?php
//include("inc/db_config.php");
//include("inc/routeros_api.class.php");
//include("inc/routeros_api_function.php"); 
include_once("evn.php"); 

function sync_ros_one_user($UserName) {
	if ( !$UserName ) {
		return false;
	}
	global $mysqlhost, $mysqluser, $mysqlpwd, $mysqldb;
	/* 连接MySQL */
	$conn   = mysql_connect($mysqlhost, $mysqluser, $mysqlpwd);
	if ( !$conn ) {
		echo "MySQL连接错误(MySQL服务地址：{$mysqlhost},用户名：{$mysqluser})!!!\n";
		exit(-1);
	}
	
	/* 选择库("radius") */
	mysql_select_db($mysqldb);
	
	/* 创建连接ROS的句柄 */
	$ROS = new routeros_api();
	
	/* 根据用户名获取密码，产品ID，项目ID，IPaddress*/
	$sql = "select userinfo.password, userinfo.projectID, radreply.Value, orderinfo.productID from userinfo, radreply, orderinfo where userinfo.UserName='$UserName' and userinfo.ID= radreply.UserID and radreply.Attribute='Framed-IP-Address' and userinfo.ID=orderinfo.userID and orderinfo.status='1';";
	$result=mysql_query($sql,$conn);
	$row = @mysql_fetch_array($result);
	/* 没有配置IPaddress，根据用户名获取密码，产品ID，项目ID */
	if ( !$row ) {
	  $sql = "select userinfo.password, userinfo.projectID, orderinfo.productID from userinfo, orderinfo where userinfo.UserName='$UserName' and userinfo.ID=orderinfo.userID and orderinfo.status='1';;";	
	  $result=mysql_query($sql,$conn);
	  $row = @mysql_fetch_array($result);
	}
	
	/* 根据项目ID，获取需要同步的ros相关的信息 */
	$sql = "select nasip, username, password from `project_ros` where projectID='{$row['projectID']}'";
	$sync_ros_info = mysql_query($sql,$conn);
	while( $sync_ros_ = @mysql_fetch_array($sync_ros_info) ) {
	  if ( !connect_ros( $ROS, $sync_ros_['nasip'], $sync_ros_['username'], $sync_ros_['password'] ) ) {
	    echo "ROS(IP:{$row['nasip']} 用户:{$row['username']})连接失败!!!\n";
	    continue;
	  }    	
	  
	  /* 根据产品ID， 获得上下行带宽 */
	  $sql = "select upbandwidth, downbandwidth from product where ID='{$row['productID']}';";	
	  $bandwidth = mysql_query($sql,$conn);
	  while( $row_bandwidth = @mysql_fetch_array($bandwidth) ) {
			$bandwidth = "{$row_bandwidth['upbandwidth']}k/{$row_bandwidth['downbandwidth']}k";
			/* 根据带宽在ROS设备上生成带宽的配置文件 */
			addpppprofile($ROS, $row['productID'], $bandwidth);	  	
	  }	
	  /* 同步用户 */
	  addpppuser($ROS,$UserName,$row['password'],$row['Value'], $row['productID']);  
	  disconnect_ros( $ROS ); 	  	  
	}
}
?> 

