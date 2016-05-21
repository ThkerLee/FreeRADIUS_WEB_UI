#!/bin/php
<?php
if($_POST){
      shell_exec("uci set ip.eth0.defaultgw='".$_POST['ip']."'");
      shell_exec(" uci commit ip");   
}
/*$content = file_get_contents('ethers');
#$str = str_replace("\r", "",$content);
#$array = array();
parse_str($str, $array);
#print_r($content);
#echo $array["config"];

//$str     = "Line 1\nLine 2\rLine 3\r\nLine 4\n";
$order   = array("", "\n", "");
$replace = '<br />';

// 首先替换 \r\n 字符，因此它们不会被两次转换
$newstr = str_replace($order, $replace, $content);

echo $newstr;*/
echo $IP= shell_exec("uci get ip.eth0.defaultgw");
     //shell_exec("uci set ip.eth0.defaultgw=192.168.100.1");
     //shell_exec(" uci commit ip");
//$results = exec('uci show ip | head ',$data);
//$data[8]="ip.eth0.defaultgw=192.168.100.129";
//print_r($data); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理");?></title>
</head>
<body>
    <form action="?" method="post">
       IP<input type="text" name="ip" value="<?php echo $IP?>"/>
      <input type="submit" value="提交"/>
    </form>    
</body>