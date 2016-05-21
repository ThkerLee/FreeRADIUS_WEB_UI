#!/bin/php
<?php
include("inc/db_config.php");
mysql_connect($mysqlhost,$mysqluser,$mysqlpwd);
mysql_select_db($mysqldb);
mysql_query("set names utf8");
if($_POST){
$account=$_POST["username"];
$oldpwd =$_POST["oldpwd"];
$newpwd =$_POST["newpwd"];
$arr=mysql_query("select * from userinfo where account=$account and password=$oldpwd");//查询用户
$re=mysql_fetch_assoc($arr);
if($re){
    $username=$re["account"];//账号
    $userID=$re["ID"];//用户ID
    mysql_query("update userinfo set password=$newpwd where ID=$userID and account=$username");//修改密码
    $json='{"status":"success"}';//返回成功
    echo $json;
}  else { 
   $json = '{"status":"fail"}';
   echo $json;
}

}
?>
