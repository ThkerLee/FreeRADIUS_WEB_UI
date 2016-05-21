<?php 
@ session_start(); 
@ header("content-type:text/html; charset=utf-8");
include("db_config.php"); 
include("class_mysql.php");
include("class_database.php");
include("class_public.php");
//include_once("evn.php");     
$db   = new Db_class($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//程序
$d    = new db($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);//数据库备份 
$conn = mysql_connect($mysqlhost,$mysqluser,$mysqlpwd); 
mysql_select_db($mysqldb,$conn);
mysql_query("set names utf8"); 
$configRs=$db->select_one("*","config","0=0 order by ID DESC"); 
$config_version=$configRs["version"];
/**
 *===========================================
 * 设置用户的权限
 * 
 *===========================================
 */ 
$_SESSION["auth_permision"]["managerGropID"]    =$mpID;
$_SESSION["auth_permision"]["login"]            ="login.php";
$_SESSION["auth_permision"]["getip"]            ="getip.php";
$_SESSION["auth_permision"]["ajax_check"]       ="ajax_check.php"; 
$_SESSION["auth_permision"]["system_scan"]      ="system_scan.php";
$_SESSION["auth_permision"]["clients"]          ="clients.php";
$_SESSION["auth_permision"]["manager_permision"]="manager_permision.php";
$_SESSION["auth_permision"]["user_down_line"]   ="user_down_line.php";
$_SESSION["auth_permision"]["index"]            ="index.php";
$_SESSION["auth_permision"]["main"]             ="main.php";
$_SESSION["auth_permision"]["card_sold_print"]  ="card_sold_print.php";
$_SESSION["auth_permision"]["chart_user_data"]  ="chart_user_data.php";
$_SESSION["auth_permision"]["chart_report_pie_data"]  ="chart_report_pie_data.php";
$_SESSION["auth_permision"]["chart_product_data"]  ="chart_product_data.php"; 
//$_SESSION["auth_permision"]["manager_pwd_edit"]    ="manager_pwd_edit.php";
$_SESSION["auth_permision"]["checkout"]            ="user_checkout_loaduser.php";
@$auth_project =$_SESSION["auth_project"];
@$auth_area    =$_SESSION["auth_area"];
@$auth_grade   =$_SESSION["auth_grade"];
@$url          =$_SERVER['PHP_SELF']; 
@$filename     =end(explode('/',$url)); 
if((empty($_SESSION["managerID"]) || empty($_SESSION["manager"]) || empty($_SESSION["auth_permision"])) && ($filename!="login.php")){
	if($filename!="clients.php"){
		echo "<script language='javascript'>window.parent.location.href='login.php';</script>";
		exit;	
	}
}
if(is_array($_SESSION["auth_permision"])){
	if(!in_array($filename,$_SESSION["auth_permision"])){
	    if($filename=='excel_userinfo.php'){
		 echo "<script language='javascript'>alert('"._("没有管理权限")."');window.location.href='../user.php';</script>";
		}else if($filename=="excel_userlog.php"){
		 echo "<script language='javascript'>alert('"._("没有管理权限")."');window.location.href='../operate_userlog.php';</script>";
		}else if($filename=="excel_credit.php")
		 echo "<script language='javascript'>alert('"._("没有管理权限")."');window.location.href='../finance_report.php';</script>";
		else{
		  echo "<script language='javascript'>alert('"._("没有管理权限")."');window.history.go(-1);</script>";
		}
		exit;
	}
}
//------------------------------2014.04.23修改计费划分为3个版本访问权限---------------------------------------//
$file = popen("license -T","r");
$data = fgets($file);//获取授权 $data=3为基础，$data=2为企业版,$data=1为ISP版
pclose($file);

if($data == 3){ //基础版页面显示权限
    if($filename=="manager_add.php" || $filename=="system_del_dial_log.php" || $filename=="alcatel_notice.php" || $filename=="system_publicnotice.php" || $filename=="order_ticket.php" || $filename=="db_user_import.php" || $filename=="db_auto.php" || $filename=="operate_userlog.php" || $filename=="operate_login_log.php" || $filename=="user_bill.php" || $filename=="user_hours_show.php" || $filename=="recharge_reverse.php" || $filename=="system_config.php" || $filename=="cron.php" || $filename=="db_backup_tb.php" || $filename=="mail_backup.php" || $filename=="db_backup_ftp.php" || $filename=="instantPaymen.php" || $filename=="area.php" || $filename=="project_ros.php" || $filename=="ippool.php" || $filename=="ippool_add.php" || $filename=="more_add.php" || $filename=="user_Mname_info.php" || $filename=="financial_subjects.php" || $filename=="finance_MTC_add.php" || $filename=="user_flow_monitor.php" || $filename=="finance_details.php" || $filename=="system_message_k.php" || $filename=="system_message_x.php" || $filename=="system_message_j.php" || $filename=="system_message_d.php" || $filename=="system_message_z.php" || $filename=="repair_add.php" || $filename=="repair.php" || $filename=="repair_disposal_log.php" || $filename=="card_add.php" || $filename=="card.php" || $filename=="card_search.php" || $filename=="card_sold_show.php"){
     echo "<script language='javascript'>alert('"._("没有管理权限")."');window.history.go(-1);</script>"; 
     exit;
    }
}elseif ($data == 2) { //企业版
   if($filename=="alcatel_notice.php" || $filename=="finance_details.php" || $filename=="instantPaymen.php" || $filename=="mail_backup.php" || $filename=="db_backup_tb.php" || $filename=="system_message_k.php" || $filename=="system_message_x.php" || $filename=="system_message_j.php" || $filename=="system_message_d.php" || $filename=="system_message_z.php" || $filename=="repair_add.php" || $filename=="repair.php" || $filename=="repair_disposal_log.php"){
           echo "<script language='javascript'>alert('"._("没有管理权限")."');window.history.go(-1);</script>"; 
     exit; 
   } 
}
//---------------------------------------------------------------------------------------------------------//
/* returns true if $ipaddr is a valid dotted IPv4 address */

function is_ip($ipaddr) {
	if (!is_string($ipaddr))
		return false;
	$ip_long = ip2long($ipaddr);
	$ip_reverse = long2ip($ip_long);
 
	if ($ipaddr == $ip_reverse)
		return true;
	else
		return false;
}

function is_number($arg) {
	return (preg_match("/[^0-9]/", $arg) ? false : true);
}

//得到客户端的IP信息

function getClientIp() 
{
   $realip = '';
   if (isset($_SERVER)) 
   {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $realip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
      $realip = $_SERVER['REMOTE_ADDR'];
    }
   }
   else 
   {
    if (getenv('HTTP_X_FORWARDED_FOR')) 
    {
      $realip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_CLIENT_IP')) {
      $realip = getenv('HTTP_CLIENT_IP');
    } else {
      $realip = getenv('REMOTE_ADDR');
    }
   }
   return $realip;
}

class ChineseNumber
{
// var $basical=array(0=>"零","一","二","三","四","五","六","七","八","九");
 var $basical=array(0=>"零","壹","贰","叁","肆","伍","陆","柒","捌","玖");
 //var $advanced=array(1=>"十","百","千");
 var $advanced=array(1=>"拾","佰","仟");
 var $top=array(1=>"万","亿");

var $level;        // 以4位为一级


// 先实现万一下的数的转换
function ParseNumber($number)
{
   if ($number>999999999999)    // 只能处理到千亿。
       return _("数字太大，无法处理。抱歉！");
   if ($number==0)
       return "零";
   for($this->level=0;$number>0.0001;$this->level++,$number=floor($number / 10000))
   {
       // 对于中文来说，应该是4位为一组。
       // 四个变量分别对应 个、十、百、千 位。
       $n1=substr($number,-1,1);
       if($number>9)
          $n2=substr($number,-2,1);
       else
          $n2=0;
       if($number>99)
          $n3=substr($number,-3,1);
       else
          $n3=0;
       if($number>999)
          $n4=substr($number,-4,1);
       else
          $n4=0;

       if($n4)
          $parsed[$this->level].=$this->basical[$n4].$this->advanced[3];
       else
          if(($number/10000)>=1)    // 千位为0，数值大于9999的情况
             $parsed[$this->level].="零";
       if($n3)
          $parsed[$this->level].=$this->basical[$n3].$this->advanced[2];
       else
          if(!preg_match("/零"."$/",$parsed[$this->level]) && ($number / 1000)>=1) // 不出现连续两个“零”的情况
             $parsed[$this->level].="零";
       if($n2)
          $parsed[$this->level].=$this->basical[$n2].$this->advanced[1];
       else
          if(!preg_match("/零"."$/",$parsed[$this->level]) && ($number / 100)>=1) // 不出现连续两个“零”的情况
             $parsed[$this->level].="零";
       if($n1)
          $parsed[$this->level].=$this->basical[$n1];
   }
   for($this->level-=1;$this->level>=0;$this->level--)
   {
       $result.=$parsed[$this->level].$this->top[$this->level];
   }	
   if(preg_match("/零"."$/",$result))
       $result=substr($result,0,strlen($result)-3);

   return $result;
   
}
};


//获得utf8字符串的长度
function strlen_utf8($str) {
	$i = 0;
	$count = 0;
	$len = strlen ($str);
	while ($i < $len) {
		$chr = ord ($str[$i]);
		$count++;
		$i++;
		if($i >= $len) break;
			if($chr & 0x80) {
				$chr <<= 1;
				while ($chr & 0x80) {
					$i++;
					$chr <<= 1;
				}
			}
		}
	return $count;
}
//2014.03.07添加限制用户登录->A在用户登录中 B用户用同一账号登录使A用户强行退出登录
/* $Newname=$_SESSION["manager"];
$re=$db->select_one("name,loginip","loginlog","name='".$Newname."'  order by ID DESC limit 0,1");
if(!empty($re["name"])){
    $loginip=$re["loginip"];
    $Newip=getClientIp();
    if($loginip != $Newip){
        unset($_SESSION["manager"]);
        unset($_SESSION["managerID"]);
        unset($_SESSION["permision"]);
echo "<script language='javascript'>alert('" . _("该账号已在别处登录......") ." ');window.parent.location.href='login.php';</script>";
    }
}*/
?>