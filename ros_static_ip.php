<?php
/*
 ** 功能描述: 本脚本是针对ROS的静态IP添加，修改和删除IP/MAC记录的函数库, 
              实现从计费添加IP/MAC到ROS，从ROS修改IP/MAC, 从ROS删除IP/MAC，所以功能都过ROS PHP API实现
 ** 函数描述: addarp2ros 添加ros的IP/MAC绑定
              delarp_from_ros 根据MAC地址删除已绑定的arp信息
              modifyarp 修改已绑定的arp记录
              linktest  测试ROS的连通性
 ** 具体实现: 
             1. ROS版本在3.x以上(因为3.x以上的才支持API)， 打开ROS API功能->ip service enable api  
             2. ROS的接口arp必须设置成proxy-arp模式， 提示用户自己设置相应的接口 ,  假设是第一个接口 interface ethernet set arp=reply-only ether1  
             3. 添加表ip2ros, 字段id, rosipaddress, rosusername, rospassword, inf, projectid
             4. 当项目为 "其他Linux" 时， 出现静态IP功能选项，如果用户启用改功能， 
                出现输入框运行用户输入ROS的IP地址，用户名，密码和需要绑定的接口名（接口名用户输入）并提示用户修改ROS接口的名称，
                将对应的信息添加进表ip2ros             
             5. ROS API连接测试，调用函数linktest
             6. 当用户的项目是静态IP功能时， 根据需要调用函数addarp2ros，delarp_from_ros， modifyarp     
  */


include("inc/routeros_api.class.php");
include("inc/routeros_api_function.php");
//include("inc/db_config.php");

//linktest("192.168.100.111", "admin", $rospwd);
/*
 ** 函数名: addarp2ros
 ** 功能:   添加ros的IP/MAC绑定
 ** 参数:   $rosipaddress ROS的IP地址
            $rosusername 登陆ROS的用户名
            $rospwd 登陆ROS的密码
            $ipaddress 需要绑定的IP地址
            $mac 需要绑定的MAC地址
            $inf 需要绑定的接口 
 ** 返回值: 成功->true 失败->false
  */
function addarp2ros($rosipaddress, $rosusername, $rospwd, $ipaddress, $mac, $inf) {
  /* 连接ROS的API */
  $ROS = new routeros_api();
  if ( !$rosipaddress || !connect_ros( $ROS, $rosipaddress, $rosusername, $rospwd ) ) { /* 连接失败返回false */
    disconnect_ros( $ROS );
    return false;
  }    
  
  /* 添加arp到ros */
  addarp($ROS, $ipaddress, $mac, $inf);
     
  /* 断开ROS的API连接 */
  disconnect_ros( $ROS );  
  
  return true; 
}

/*
 ** 函数名: delarp_from_ros
 ** 功能:   根据MAC地址删除已绑定的arp信息
 ** 参数:   $rosipaddress ROS的IP地址
            $rosusername 登陆ROS的用户名
            $mac 需要匹配的mac地址
 ** 返回值: 成功->true 失败->false
  */
function delarp_from_ros($rosipaddress, $rosusername, $rospwd, $mac) {
  /* 连接ROS的API */
  $ROS = new routeros_api();
  if ( !$rosipaddress || !connect_ros( $ROS, $rosipaddress, $rosusername, $rospwd ) ) { /* 连接失败返回false */
    disconnect_ros( $ROS );
    return false;
  }  
  /* 根据mac地址查询id */
  $id = find_arpid($ROS, $mac);
  
  /* 删除id记录 */
  delarp($ROS, $id);
     
  /* 断开ROS的API连接 */
  disconnect_ros( $ROS );  
  
  return true; 
}

/*
 ** 函数名: modifyarp
 ** 功能:   修改已绑定的arp记录
 ** 参数:   $rosipaddress ROS的IP地址
            $rosusername 登陆ROS的用户名
            $rospwd 登陆ROS的密码
            $overduemac 需匹配的MAC地址， 用于查询是否存在改MAC地址的信息 
            $ipaddress 需要绑定的IP地址
            $mac 需要绑定的MAC地址
            $inf 需要绑定的接口 
 ** 返回值: 成功->true 失败->false
  */
function modifyarp($rosipaddress, $rosusername, $rospwd, $overduemac, $ipaddress, $mac, $inf) {
  /* 连接ROS的API */
  $ROS = new routeros_api();
  if ( !$rosipaddress || !connect_ros( $ROS, $rosipaddress, $rosusername, $rospwd ) ) { /* 连接失败返回false */
    disconnect_ros( $ROS );
    return false;
  }    
  
  /* 根据mac地址查询id */
  $id = find_arpid($ROS, $overduemac);
  
  /* 删除id记录 */
  delarp($ROS, $id);
  
  /* 添加arp到ros */
  addarp($ROS, $ipaddress, $mac, $inf);
  
  /* 断开ROS的API连接 */
  disconnect_ros( $ROS );   
  
  return true;   
}

/*
 ** 函数名: linktest
 ** 功能:   测试ROS的连接
 ** 参数:   $rosipaddress ROS的IP地址
            $rosusername 登陆ROS的用户名
            $rospwd 登陆ROS的密码
 ** 返回值: 成功->true 失败->false
  */
function linktest($rosipaddress, $rosusername, $rospwd) {
  /* 连接ROS的API */
  $ROS = new routeros_api();
  if ( !$rosipaddress || !connect_ros( $ROS, $rosipaddress, $rosusername, $rospwd ) ) { /* 连接失败返回false */
    disconnect_ros( $ROS );
    return false;
  }
  disconnect_ros( $ROS );
  //addarp2ros("192.168.100.111", "admin", "", "192.168.0.2", "00:03:47:9A:BA:56", "lan");
  //delarp_from_ros("192.168.100.111", "admin", "", "00:03:47:9A:BA:56");
  //modifyarp("192.168.100.111", "admin", "", "00:03:47:9A:BA:56", "192.168.0.3", "00:03:47:9A:BA:56", "lan");
  
  return true;  
}

//$rosipaddress = "192.168.100.11";
//$rosusername = "admin";
//$rospwd = "";
//$ROS = new routeros_api();
/* 连接ROS失败， 返回false */

//if ( !$rosipaddress || !connect_ros( $ROS, $rosipaddress, $rosusername, $rospwd ) ) {
//  disconnect_ros( $ROS );
//  return false;
//}

//find_arpid($ROS,$mac);
//addarp($ROS, "192.168.0.100", "00:0C:29:86:FE:2F", "LAN");

/* 同步静态IP信息到ROS 
   在项目表(project)中添加字段nasip， nasusername， naspwd
   当项目选择Other时， 允许用户输入nasip，nasusername，naspwd
*/

/*
 ** 函数名: staticip2ros
 ** 功能：  通过API同步静态IP用户信息到ROS， 若参数为空则同步到期静态IP用户到ROS
 ** 参数:   $username 用户名
 **         $action 动作，add为添加， del为删除
 ** 返回值: 成功返回true， 失败返回false, 同步到期静态IP用户无返回值
 ** 注:     修改用户需要在修改数据库之前staticip2ros($username, "del"); 然后修改数据库，再staticip2ros($username, "add");
 **         删除用户需要在修改数据库之前staticip2ros($username, "del")
 **         添加用户需要在修改数据库之后staticip2ros($username, "add")
  */
function staticip2ros($username = NULL, $action = NULL) {
  global $mysqlhost, $mysqluser, $mysqlpwd, $mysqldb;
  
  /* 连接MySQL */ 
  $conn   = mysql_connect($mysqlhost, $mysqluser, $mysqlpwd);
  
  /* 连接MySQL失败，返回false */
  if ( !$conn ) {
   	return mysql_error;
  } 
  
  /* 选择库("radius") */
  mysql_select_db($mysqldb);  
  
  if ( $username ) { /* 添加, 修改, 删除用户的相关操作 */
    /* 根据用户名获取用户名ID， 带宽， IP地址 */
    $sql="select radreply.Attribute, radreply.value, radreply.UserID from radreply where radreply.UserName = '{$username}'";
    $result = mysql_query($sql);
    while( $row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
      if ( $row ) {
        foreach( $row as $key=>$value) {      
          if ( is_ipaddr($value) ) {
            $ipaddress = $value;
          } else if ( count(explode("k/", $value)) > 1 ) {
            $bandwidth = $value;  
          } else {
            $userid = $value; 
          }      
        }
      }
    }
    /* 没有获取到正确的IP地址信息， 不能完成同步操作， 返回false */
    if ( !$ipaddress ) {
      return false;
    }
     /* 根据用户名ID获取项目对应的ROS地址，用户名和密码 */
    $nasinfo = getnasinfo($username);  
    if ( 0 == strcmp("mikrotik", $nasinfo['device']) ) { 
      /* 同步到ROS */
      /* 获取ROS的IP地址，用户名和密码 */
      $rosipaddress = $nasinfo['nasip'];
      $rosusername = $nasinfo['nasusername'];
      $rospwd = $nasinfo['naspwd'];
      
      /* 连接ROS的API */
      $ROS = new routeros_api();
      /* 连接ROS失败， 返回false */
      
      if ( !$rosipaddress || !connect_ros( $ROS, $rosipaddress, $rosusername, $rospwd ) ) {
        disconnect_ros( $ROS );
        return false;
      }
  
      /* 同步 */
      if ( 0 == strcmp("add", trim($action))) {
        addnatrule($ROS, $ipaddress);
        addqueuerule($ROS, $ipaddress, $bandwidth);    
      } else if( 0 == strcmp("del", trim($action)) ) {
        delnatrule($ROS,$ipaddress);
        delqueuerule($ROS,$ipaddress);        
      }
      
      /* 断开ROS的API连接 */
      disconnect_ros( $ROS );
    }
    return true;
  } else { /* 到期用户操作 */
    /* 获取所有项目对应的ROS信息 */
    $nasinfo = getnasinfo();
    if ( $nasinfo ) {
      /* 当前日期 */
      $currentdate = strtotime(date("Y-m-d h:i:s"));
      $ROS = new routeros_api();
      
      foreach( $nasinfo as $key=>$value ) {
        if ( 0 == strcmp("mikrotik", $value['device']) ) {
          /* 获取项目ID, ROS地址，用户名和密码 */
          $projectid = $value['ID'];
          $rosipaddress = $value['nasip'];
          $rosusername = $value['nasusername'];
          $rospwd = $value['naspwd'];
          
          /* 连接ROS的API */
          if ( !$rosipaddress || !connect_ros( $ROS, $rosipaddress, $rosusername, $rospwd ) ) { /* ROS的API连接失败， 连接下一个API */
            continue;
          }          
          /* 根据项目ID查找该项目的用户id */
          $sql = "select userinfo.id from userinfo where userinfo.projectID = '{$projectid}'";
          $result_userinfo = mysql_query($sql);
          while( $row_userinfo = mysql_fetch_array($result_userinfo, MYSQL_ASSOC) ) { 
            $userid = $row_userinfo['id']; 
            /* 根据用户ID查找用户到期时间 */
            $sql = "select userrun.enddatetime from userrun where userrun.UserID='{$userid}'";
            $result_userrun = mysql_query($sql);
            $row_userrun = mysql_fetch_array($result_userrun, MYSQL_ASSOC);
            $enddate = strtotime($row_userrun['enddatetime']);     
            if ( $currentdate >= $enddate ) { /* 到期用户 */
              /* 获取到期用户的IP地址 */ 
              $sql = "select  radreply.value from radreply where radreply.UserID = '{$userid}' and radreply.Attribute = 'Framed-IP-Address'"; 
              $result_radreply = mysql_query($sql);
              $row_radreply = mysql_fetch_array($result_radreply, MYSQL_ASSOC);
              $ipaddress = $row_radreply['value'];
              
              /* 同步ROS操作 */
              delnatrule($ROS,$ipaddress);
              delqueuerule($ROS,$ipaddress);
            }       
          } /* end while 用户到期 */          
        }
      } /* end foreach */
      /* 断开ROS的API连接 */
      disconnect_ros( $ROS );
    }    
  } 
}

/* 获取项目信息， 如果有用户名，则获取用户名对应的项目，否则获取所有的项目 */
function getnasinfo($username = NULL ) {
  if ( $username ) { /* 根据用户查找项目 */
    $sql = "select userinfo.projectID from userinfo where userinfo.UserName='{$username}'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result, MYSQL_ASSOC);
    $projectID = $row['projectID'];
    $sql = "select * from project where ID = '{$projectID}'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result, MYSQL_ASSOC);
    $result = $row;
  } else { /* 查找所有项目 */
    $sql = "select * from project";
    $result = mysql_query($sql);
    $tmp_array = array();
    while( $row=mysql_fetch_array($result, MYSQL_ASSOC) ) { 
      $tmp_array[] = $row; 
    }    
    $result = $tmp_array;
    unset($tmp_array);
  }
  return $result;
}


function is_ipaddr($ipaddr) {
  if ( filter_var($ipaddr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ) {
     $result = true ;
  } else if ( filter_var($ipaddr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ) {
    $result = true ;
  } else {
    $result = false ;
  }

  return $result;
}
  
  
function disstr( $str ) {
  if ( is_array($str) ) {
    print_r($str);
    echo "<hr>";
  } else {
    echo "{$str}<hr>"; 
  }
}   
?>
