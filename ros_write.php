#!/bin/php
<?php
/*
 **文件名: sync_radius2ros 
 **功能:   同步计费用户信息到ROS
 **备注:   inc/db_config.php             MySQL参数信息(用户名，密码，IP地址，库等)
 **        inc/routeros_api.class.php    ROS的API文件
 **        inc/routeros_api_function.php ROS的API操作函数库文件
 **        project_ros表信息
			CREATE TABLE `project_ros` (
				`ID` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
				`nasip` VARCHAR(16) NULL DEFAULT NULL,
				`username` VARCHAR(16) NULL DEFAULT NULL,
				`password` VARCHAR(256) NULL DEFAULT NULL,
				`projectID` INT(4) NULL DEFAULT NULL,
				PRIMARY KEY (`ID`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=MyISAM
			ROW_FORMAT=DEFAULT
			AUTO_INCREMENT=27 
*/

include("inc/routeros_api.class.php");
include("inc/routeros_api_function.php");

$font_start="<font color='red'>";
$font_end="</font>";
/* 连接MySQL */
$conn   =mysql_connect($mysqlhost, $mysqluser, $mysqlpwd);
if ( !$conn ) {
	echo "{$font_start}MySQL连接错误(MySQL服务地址：{$mysqlhost},用户名：{$mysqluser})!!!{$font_end}<hr>\n";
	exit(-1);
}

/* 选择库("radius") */
mysql_select_db($mysqldb);

/* 创建连接ROS的句柄 */
$ROS = new routeros_api();

/* 查找需要同步的ROS所属的项目，读取表project_ros */
$sql="select * from project_ros";
$result=mysql_query($sql,$conn);
while( $row=mysql_fetch_array($result) ) {
	 /* 根据查询到的ROS地址，用户名和密码连接到ROS */
	 echo "{$font_start}建立连接{$font_end}<hr>\n";
	 
	 /* 连接失败，进行下一个项目的连接 */
	 if ( !connect_ros( $ROS, $row['nasip'], $row['username'], $row['password'] ) ) {
	   echo "{$font_start}ROS(IP:{$row['nasip']} 用户:{$row['username']})连接失败!!!<$font_end><hr>\n";
	   continue;
 	 }
 	 
 	 /* 连接成功 */
	 echo "{$font_start}ROS(IP:{$row['nasip']} 用户:{$row['username']})连接成功，开始同步!!!{$font_end}<hr>\n";
	 if ( 0 != strcmp("{$row['nasip']}-yes", $status) ) {
		/* 删除所有的ROS上的用户 */
		echo "{$font_start}第一步:删除ROS({$row['nasip']})所有用户{$font_end}<hr>\n";
		/* 这个过程有些漫长 */
		delallpppuser($ROS);	 	
	 }

   /* 确保一个地址只删除一次所有用户的标志位 */
	 $status = "{$row['nasip']}-yes";
	
	/* 查找该项目对应的产品 */
	echo "{$font_start}第二步:同步用户{$font_end}<hr>\n";
	
	/* 根据项目id查找对应的产品id(一个项目可对应多个产品),读取项目和产品的关系表productandproject的productID字段 */
	$sql="select productID from productandproject where projectID='{$row['projectID']}'";	
	$get_product_id = mysql_query($sql,$conn);
	while( $product_id = mysql_fetch_array($get_product_id) ) {
		/* 用于统计一个项目同步了多少合法用户的标志位 */
		$sum = 0;
		
		/* 根据获得的产品ID查询产品的上下行带宽值，读取表product的upbandwidth和downbandwidth字段 */
		$sql = "select upbandwidth, downbandwidth from product where ID='{$product_id['productID']}'";
		$get_bandwidth = mysql_query($sql,$conn);
		
		/* 生成带宽的配置文件 start while */
		while( $bandwidth_info = mysql_fetch_array($get_bandwidth) ) {
			/* 将带宽组合成ros可认得格式 */
			$bandwidth = "{$bandwidth_info['upbandwidth']}k/{$bandwidth_info['downbandwidth']}k";
			/* 根据带宽在ROS设备上生成带宽的配置文件 */
			addpppprofile($ROS, $product_id['productID'], $bandwidth);		
		} /* 生成带宽的配置文件 end while */
		
		/* 查找用户的名称，密码，产品ID */
		/** 
		 ** 条件: 
		 **     1 userinfo.projectID= $row['projectID']  $row['projectID']->project_ros表中所查询到的项目id 
		 **     2 userattribute.userID = uesrinfo.ID
		 **     3 userattribute.orderID = orderinfo.ID
		 **     4 orderinfo.productID = product.ID
		 **     5 orderinfo.productID = $product_id['productID'] $product_id['productID']->查询到的产品ID 
		*/
   $sql = "select u.UserName, u.password, o.productID from userinfo as u,`userattribute` as at, 
   orderinfo as o, product as p where u.projectID='{$row['projectID']}' 
   and at.userID=u.ID and at.orderID=o.ID and o.productID=p.ID and  o.productID='{$product_id['productID']}'";
   $get_userinfo = mysql_query($sql,$conn);
   

   /* 查找用户的名称，密码，产品ID和IP地址完成 start while */
	 while( $userinfo = mysql_fetch_array($get_userinfo) ) {   
     /* 根据用户的用户名查找用户的IP地址 */
     $sql = "select radreply.Value from userinfo, radreply 
     where userinfo.ID= radreply.UserID and radreply.Attribute='Framed-IP-Address' and userinfo.UserName = '{$userinfo['UserName']}'";
     $get_users_ipaddress = mysql_query($sql,$conn);
     $usersipaddress = mysql_fetch_array($get_users_ipaddress);
     
     $sql = "select UserName from userattribute where stop='1' and UserName='{$userinfo['UserName']}'";
     $get_bad_user = mysql_query($sql,$conn);
     $bad_user = mysql_fetch_array($get_bad_user);
     if ( !$bad_user ) {
       /* 在ROS上同步用户信息 */
       echo "同步用户(<font color='blue'><B>{$userinfo['UserName']}</B></font>)<hr>\n";
       addpppuser($ROS,$userinfo['UserName'],$userinfo['password'],$usersipaddress['Value'], $userinfo['productID']);  
       $sum++;  
     } else {    	   
//			 if(findpppuser($ROS,$userinfo['UserName'])<>-1){
//			    //如果该用户存在，就删除
//			    delpppuser($ROS,$userinfo['UserName']);  
//			 }     	
     }
   } /* 查找用户的名称，密码，产品ID和IP地址完成 end while */  
  } /* 查找该项目对应的产品完成 end while */ 
	echo "{$font_start}第三步:同步完成，共同步用户：{$sum}<hr><br>\n断开ROS(IP:{$row['nasip']})连接{$font_end}<hr><br>\n\n";
	/* 同步完成，断开ROS连接 */
	disconnect_ros( $ROS );  
} /* 查找ROS所属的项目完成 */



/* 同步到期用户信息 */
/*
echo "{$font_start}第三步:检测到期用户并从ROS中删除到期用户{$font_end}<hr>\n";
$sql = "select UserName from userattribute where stop='1'";
$result=mysql_query($sql,$conn);
while( $row=mysql_fetch_array($result) ) {
	if ( $row['UserName'] ) {
		 echo "用户(<font color='blue'><B>{$row['UserName']}</B></font>)已经到期<hr>\n";
		 delpppuser($ROS,$row['UserName']);
	}
}
*/
//echo "{$font_start}第三步:同步完成，断开ROS连接{$font_end}<hr>\n";
/* 同步完成，断开ROS连接 */
//disconnect_ros( $ROS );
?> 

