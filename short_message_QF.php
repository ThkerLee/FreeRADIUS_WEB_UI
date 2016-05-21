#!/bin/php
<?php //自定义短信
header("content-type:text/html;charset=utf-8");//
 $mobile=$_POST['mobile'];
$content=$_POST['content'];
$userid=$_POST['userid'];
$account=$_POST['account'];
$password=$_POST['password'];

//提交短信**********************************************************************

$post_data = array();
$post_data['userid'] = $userid;
$post_data['account'] = $account;
$post_data['password'] = $password;
$post_data['content'] = $content; //短信内容需要用urlencode编码下
$post_data['mobile'] = $mobile;
$post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
$url='http://113.11.210.120:6666/sms.aspx?action=send';
$o='';
foreach ($post_data as $k=>$v)
{
   $o.="$k=".urlencode($v).'&';
}
$post_data=substr($o,0,-1);
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
$result = curl_exec($ch);
//echo $result;
#echo "<br />" . $result ."<br />";
#var_dump(strpos($result, "Success"));
if(strpos($result, "Success")){
    echo "<script language='javascript'>alert('"._("发送成功")."');window.location.href='system_message_q.php';</script>";
}else{
    echo "<script language='javascript'>alert('"._("发送失败")."');window.location.href='system_message_q.php';</script>";			
}
?>