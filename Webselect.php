#!/bin/php
<?php
header("Content-Type:text/html; charset=utf-8");
include("inc/db_config.php");
mysql_connect($mysqlhost,$mysqluser,$mysqlpwd);
mysql_select_db($mysqldb);
mysql_query("set names utf8");
if($_POST){
$account=$_POST["username"];
$passwd =$_POST["password"];
$arr=mysql_query("select * from userinfo where account=$account and password=$passwd");//查询用户
$re=mysql_fetch_assoc($arr);
 if($re){ 
  $username=$re["account"];//账号
  $password=$re["password"];//密码
  $address=$re["address"];//联系地址
  $name=$re["name"];//姓名
  $cardid=$re["cardid"];//证件号
  $mobile=$re["mobile"];//手机号
  $money=$re["money"];//用户余额
  $userID=$re["ID"];//用户ID
  $projectID=$re["projectID"];//项目ID
  $arr1=mysql_query("select * from userrun where userID=$userID order by orderID desc");//查询到期时间
  $rs=mysql_fetch_assoc($arr1);
  $enddatetime=$rs["enddatetime"];//到期时间
  $arr2=mysql_query("select * from project where ID=$projectID ");//查询项目
  $pj=mysql_fetch_assoc($arr2);
  $pjname=$pj["name"];//项目名称
  $arr3=  mysql_query("select * from orderinfo where userID=$userID order by ID desc");//查询订单中的产品ID
  $pdID=  mysql_fetch_assoc($arr3);
  $productID=$pdID["productID"];//产品ID
  $arr4=  mysql_query("select * from product where ID=$productID");//查询产品名称
  $pd= mysql_fetch_assoc($arr4);
  $pdname=$pd["name"];//产品名称
  $json = '{"username":"'.$username.'","password":"'.$password.'","address":"'.$address.'","name":"'.$name.'","cardid":"'.$cardid.'","mobile":"'.$mobile.'","money":"'.$money.'","enddatetime":"'.$enddatetime.'","pjname":"'.$pjname.'","pdname":"'.$pdname.'"}';
    echo $json; 
 }  else {
     $json = '{"username":"'.$username.'","password":"'.$password.'"}';
echo $json;   
 } 
 
  }

?>	


