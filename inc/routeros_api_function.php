<?php
//require('routeros_api.class.php');


//$ROS = new routeros_api();

@$ROS->debug = false;

/*
 **函数名: connect_ros 
 **参数:   $ROS->连接ROS的句柄; 
 **        $ipaddress->ROS的IP地址;
 **        $username->ROS的用户名
 **        $password-〉ROS的用户名密码
 **功能:   连接ROS，打开同步机制
 **备注:
*/
function connect_ros( $ROS, $ipaddress, $username, $password ) {
	if ( $ROS->connect(trim($ipaddress), trim($username), trim($password)) ) {
    //echo "连接成功, 数据拷贝中...";
    return true;	
	}else{
	  //echo "连接错误！";
	  return false;
	  //echo "connected error!!!<br>";
	}	
}

/*
 **函数名: delallpppuser 
 **参数:   $ROS->连接ROS的句柄; 
 **功能:   删除所有的用户
 **备注:
*/
function delallpppuser($ROS){
   $ARRAY =$ROS->comm("/ppp/secret/getall"); 
   
   foreach($ARRAY as $key=>$users){
   	
        $ROS->comm("/ppp/secret/remove", array(
          ".id"     => "{$users['.id']}"));
     }    
     return true;
}

/*
 **函数名: disconnect_ros 
 **参数:   $ROS->连接ROS的句柄; 
 **功能:   断开ROS，关闭同步机制
 **备注:
*/
function disconnect_ros( $ROS ) {
	$ROS->disconnect();
}

//ROS的IP 地址
$ip="192.168.1.1";
//ROS的登录用户名，该用户要有管理PPP用户和队列的权限
$username="admin";
//密码
$passwd='';


function findpppuser($ROS,$pppusername){
   $ARRAY =$ROS->comm("/ppp/secret/getall"); 
   foreach($ARRAY as $key=>$users){
       if($users['name']==$pppusername){
        return $users['.id'];
        
       }    
     }    
     return -1;
}

function delpppuser($ROS,$pppusername){
    $id=trim(findpppuser($ROS,$pppusername));
     
    if($id==-1){
       
      return -1;
    }
        $ROS->comm("/ppp/secret/remove", array(
          ".id"     => "$id"));
       
      return 0;
}



function addpppuser($ROS,$pppusername,$ppppassword,$remote_address, $profile){
/*
  //查找是否存在该用户
 if(findpppuser($ROS,$pppusername)<>-1){
    //如果该用户存在，就删除
    delpppuser($ROS,$pppusername);  
  }
*/
$comment=     date("Y-m-d H:i:s(D)",time()) ;
if ( trim($remote_address) ) {
	 $ROS->comm("/ppp/secret/add", array(
          "name"     => $pppusername,
          "password" => $ppppassword,
          "remote-address" => $remote_address,
          "profile"        => $profile,
          "comment"  => $comment,
          "service"  => "pppoe",
  )); 
} else {
	 $ROS->comm("/ppp/secret/add", array(
          "name"     => $pppusername,
          "password" => $ppppassword,
          "profile"        => $profile,
          "comment"  => $comment,
          "service"  => "pppoe",
  )); 	
}
 
 
}


function findqueuerule($ROS,$ipaddress){
   $ARRAY =$ROS->comm("/queue/simple/getall");

   foreach($ARRAY as $key=>$rules){
       if($rules['name']==$ipaddress){
      
        return $rules['.id'];
        
       }                 
     }    
     return -1;
}

function delqueuerule($ROS,$ipaddress){
    $id=trim(findqueuerule($ROS,$ipaddress));
     
    if($id==-1){
       
      return -1;
    }
        $ROS->comm("/queue/simple/remove", array(
          ".id"     => "$id"));
       
      return 0;
}

function addqueuerule($ROS,$ipaddress,$max_limit){
    //查找是否存在该IP的规则
 if(findqueuerule($ROS,$ipaddress)<>-1){
    //如果该用户存在，就删除
    delqueuerule($ROS,$ipaddress);  
  }

$comment=     date("Y-m-d H:i:s(D)",time()) ;

   $ROS->comm("/queue/simple/add", array(
          "name"                => $ipaddress,
          "target-addresses"     => $ipaddress,
          //"dst-address" => "0.0.0.0/0",
          "comment"  => $comment,
          "max-limit" => $max_limit,

));  
}

/*
 **函数名: addpppprofile 
 **参数:   $ROS->连接ROS的句柄; 
 **        $name->PPPoE用户加载的配置文件名;
 **        $rate_limit->上下行带宽
 **功能:   根据name和带宽为PPP添加一个新的配置文件
 **备注:
*/
function addpppprofile($ROS, $name, $rate_limit) {
  //查找是否存在该用户
 if( ($id = findpppprofileid($ROS,$pppusername))<>-1 ) {
    //如果该用户存在，就删除
    delpppprofile($ROS, $id);  
  }	
	 $comment=     date("Y-m-d H:i:s(D)",time());
   $ROS->comm("/ppp/profile/add", array(
          "name"                => $name,
          "rate-limit="     => $rate_limit,
          "comment"  => $comment,

));  
}

/*
 **函数名: findpppuser 
 **参数:   $ROS->连接ROS的句柄; 
 **        $profilename->PPP配置文件名;
 **功能:   根据profilename查找出ID
 **返回值: 成功:profilename所在记录的id, 失败:-1
 **备注:
*/
function findpppprofileid($ROS,$profilename){
   $ARRAY =$ROS->comm("/ppp/profile/getall");
   foreach($ARRAY as $key=>$profile){
       if($profile['name']==$profilename){     
        return $profile['.id'];       
       }    
     }    
     return -1;
}

/*
 **函数名: delpppprofile 
 **参数:   $ROS->连接ROS的句柄; 
 **        $id->PPPoE用户加载的配置文件名的id;
 **功能:   根据id删除相应的配置文件名
 **备注:
*/
function delpppprofile($ROS, $id) {
    if($id==-1){      
      return -1;
    }	
	$ROS->comm("/ppp/profile/remove", array(
	  ".id"     => trim($id)));
}

/*
if ($ROS->connect('192.168.1.1', 'admin', '')) {
    addpppuser($ROS,"chenhong", "chenhong", "3.3.3.3", "24");
    addpppuser($ROS,"kkk", "chenhong", "", "24");
    echo "连接成功, 数据拷贝中...";
   $ROS->disconnect();

}else{
  echo "连接错误！";
  //echo "connected error!!!<br>";
}
*/

/* IP/MAC绑定 */
function find_arpid($ROS, $mac){
   $ARRAY =$ROS->comm("/ip/arp/getall"); 
/*   
   print_r($ARRAY);
   return 0;
    [0] => Array
        (
            [.id] => *2
            [address] => 192.168.100.199
            [mac-address] => BC:AE:C5:61:C9:52
            [interface] => ether2
            [invalid] => false
            [DHCP] => false
            [dynamic] => true
            [disabled] => false
        )

    [1] => Array
        (
            [.id] => *5
            [address] => 192.168.100.1
            [mac-address] => 1A:7B:3C:5D:60:10
            [interface] => ether2
            [invalid] => false
            [DHCP] => false
            [dynamic] => true
            [disabled] => false
        )

    [2] => Array
        (
            [.id] => *6
            [address] => 192.168.0.100
            [mac-address] => 00:0C:29:86:FE:2F
            [interface] => ether1
            [invalid] => false
            [DHCP] => false
            [dynamic] => false
            [disabled] => false
        )

    [3] => Array
        (
            [.id] => *7
            [address] => 192.168.100.188
            [mac-address] => 00:0C:29:CF:B4:D4
            [interface] => ether2
            [invalid] => false
            [DHCP] => false
            [dynamic] => true
            [disabled] => false
        )
   
*/   
   foreach($ARRAY as $key=>$arps){   
       if($arps['mac-address']==trim($mac)) {
        return $arps['.id']; 
       }    
     }    
     return -1;
}


function addarp($ROS, $ipaddress, $mac, $inf) {
  //查找是否存在该绑定
  //ip arp  add address=192.168.0.100 mac-address=00:0C:29:86:FE:2F interface=ether1
// if( ($id = find_arpid($ROS,$mac))<>-1 ) {
    //如果该绑定，就删除
//    delarp($ROS, $id);  
//  }	
	 $comment=     date("Y-m-d H:i:s(D)",time());
   $ROS->comm("/ip/arp/add", array(
          "address"                => $ipaddress,
          "mac-address"     => $mac,
          "interface"       => $inf,
          "comment"  => $comment,

));  
}

function delarp($ROS, $id) {
    if($id==-1){      
      return -1;
    }	
	$ROS->comm("/ip/arp/remove", array(
	  ".id"     => trim($id)));	
}
?>
