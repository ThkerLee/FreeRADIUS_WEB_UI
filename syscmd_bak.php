#!/bin/php
<?
require("/usr/local/www/guiconfig.inc"); 
include("inc/conn.php");
include_once("evn.php"); 
 



function close_port_($link,$ip,$http_port,$Vender,$UserName,$passwd){
		if($Vender == "natshell"){	
			//if($http_port!="") $http_port .=":".$http_port;
			//$passwd=md5(strrev(md5($radius['share_password'])));
			$passwd=md5(strrev(md5($passwd)));
			$cmd = "http://$ip:$http_port/remote_cmd.php?port=$link&passwd=$passwd";
			$fp = fopen($cmd, "r");
			fclose($fp);
			echo $cmd;
			//$fp = fopen('http://'.$ip.":".$http_port.'/remote_cmd.php?port='.$link.'&passwd='.$passwd,"r");
			//http://localhost:5006/cmd?link%20$link&close
			
		}
	if($Vender == "mikrotik"){
		echo "+++++++++++++";
	$raw = mysql_fetch_array(mysql_query("select * from radacct where AcctStopTime = '0000-00-00 00:00:00' And UserName = '".$UserName."';"));
		$AcctSessionId = $raw['AcctSessionId'];
		$FramedIPAddress = $raw['FramedIPAddress'];
		
		$raw = mysql_fetch_array(mysql_query("select * from nas where ip = '".$ip."';"));
		$secret = $raw['secret'];
		
		$packet = time();
		$fp = fopen("/tmp/".$packet,"w");
		$str = "Acct-Session-Id=".$AcctSessionId."\n";
		$str .= "User-Name=".$UserName."\n";
		$str .= "Framed-IP-Address=".$FramedIPAddress."\n";
		echo $str;
		fputs($fp, $str,1024);
		exec("/usr/local/bin/radclient -f /tmp/".$packet." ".$ip.":".$http_port." disconnect ".$secret) ;
/*
echo "Acct-Session-Id=8100000c" > packet.txt
echo "User-Name=gtr" >> packet.txt
echo "Framed-IP-Address = 10.12.255.248" >> packet.txt

cat packet.txt | radclient -x 192.168.50.180:1701 disconnect 123
*/


	}

}



$result = mysql_query("select * from radacct where AcctStopTime = '0000-00-00 00:00:00'");

while($row = mysql_fetch_array($result)){

$UserName = $row["UserName"];
$AcctStartTime = $row["AcctStartTime"];


echo "username: ".$row["UserName"];
echo " | 上线时间: ".strtotime($row["AcctStartTime"]);
echo " | 当前时间: ". Time();
echo " | 在线秒数: ". $row["AcctSessionTime"];
$updateTime =  $row["AcctSessionTime"]-(Time() - strtotime($row["AcctStartTime"]));
echo " | 上线时间距离现在的长度: ". $updateTime;
if ($updateTime < -800){
	//echo " | 删除";
	$AcctStopTime  = date("Y-m-d H:i:s",(strtotime($row["AcctStartTime"])+$row["AcctSessionTime"]));
	//echo " | 应该的结束时间: ".$AcctStopTime;
	$result2 = mysql_query("update radacct SET AcctStopTime = '$AcctStopTime' where AcctStartTime = '$AcctStartTime' AND UserName = '$UserName' AND AcctStopTime = '0000-00-00 00:00:00'");
}
echo "<br/>";

}






$result3 = mysql_query("select * from userinfo where EndDate < '".date("Y-m-d H:i:s",time())."'");
while($row = mysql_fetch_array($result3)){
	$UserName = $row['UserName'];
		//给radcheck表插入条目,使得该用户在下次登录的时候无法通过认证
		$result6 = mysql_query("select * from radcheck where UserName = '$UserName' And Attribute='baduser'");
		if(!mysql_fetch_array($result6)){
			$query_str = "INSERT INTO radcheck(UserName,Attribute,op,Value) values('$UserName','baduser',':=','1');";
			mysql_query($query_str,$conn);
		}


	$online_users=system("/usr/local/bin/radwho -i -r -u ".$UserName,$temp);
	echo "<br/>";
	if(strlen($online_users) > 2 ){
		$users = explode(",",$online_users);
		//echo "username=".$users[0]."<br/>";
		$link = explode("-",$users[1]);
		
		//echo "nasip = ".$users[5];
		$link = $link[1]."-".$link[2];
		//echo "link = ".$link."<br/>";
	$a_radius = $config['radius']['nas'];
	$count = count($a_radius);
	$ip = $users[5];
	for($i=0;$i<$count;$i++){
		if($a_radius[$i]['ipaddr'] == $ip){
			$http_port = $a_radius[$i]['port'];
			$passwd = $a_radius[$i]['share_password'];
		}
		//echo "$ip***********************\n";
		//echo $http_port;
		//echo "***********************\n";
	}
	
	$raw=mysql_fetch_array(mysql_query("select * from usergroup where UserName='".$UserName."'",$conn));
	$GroupName  = $raw['GroupName'];
	//$raw=mysql_fetch_array(mysql_query("select * from usergroup where GroupName='".$GroupName."'",$conn));
	$Vender  = $raw['nas_type'];	

echo "Vender=".$Vender;

		close_port_($link,$ip,$http_port,$Vender,$UserName,$passwd);

	}
}



?>