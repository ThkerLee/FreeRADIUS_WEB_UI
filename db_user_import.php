#!/bin/php
<?php 
require_once("inc/conn.php");	
require_once("evn.php");
 global $systemconfig;
 if(file_exists("/etc/serial")){
 	$serialStr=file_get_contents("/etc/serial");
	$serialArr=explode("\n",$serialStr);
	 $systemconfig["max_project"]=(int)$serialArr[0];
	 $systemconfig["max_user"]   =(int)$serialArr[1]; 
 }else if(file_exists("/mnt/mysql/mysql/usr-gui/serial")){
    $serialStr=file_get_contents("/mnt/mysql/mysql/usr-gui/serial");
	$serialArr=explode("\n",$serialStr);
	$systemconfig["max_project"]=(int)$serialArr[0];
	$systemconfig["max_user"]   =(int)$serialArr[1];  
 }else if(file_exists("/etc/PINF")){
    $serialStr=file_get_contents("/etc/PINF");
	$serialArr=explode("\n",$serialStr);
	$systemconfig["max_project"]=(int)$serialArr[5];
	$systemconfig["max_user"]   =(int)$serialArr[6];   
 }
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户数据库导入")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<link href="inc/dialog.css" rel="stylesheet" type="text/css">
<!--这是点击帮助的脚本-2014.06.07-->
    <link href="js/jiaoben/css/chinaz.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jiaoben/js/jquery-1.4.4.js"></script>   
    <script type="text/javascript" src="js/jiaoben/js/jquery-ui-1.8.1.custom.min.js"></script> 
    <script type="text/javascript" src="js/jiaoben/js/jquery.easing.1.3.js"></script>        
    <script type="text/javascript" src="js/jiaoben/js/jquery-chinaz.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {  		
        $('#Firefoxicon').click(function() {
          $('#Window1').chinaz({
            WindowTitle:          '<b>备份恢复</b>',
            WindowPositionTop:    'center',
            WindowPositionLeft:   'center',
            WindowWidth:          500,
            WindowHeight:         300,
            WindowAnimation:      'easeOutCubic'
          });
        });		
      });
    </script>
   <!--这是点击帮助的脚本-结束-->
</head>
<body>
<table class="outTable"  width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
   
  <tr> 
    <td height="500" valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bd">
  <tr>
    <td>
		<table width="100%" align="center" class="title_bg2 border_t border_l border_r">
		  <tr>
			<td width="85%" class="f-bulue1"><?php echo _("数据导入")?></td>
			<td width="11%" align="right"></td>
                        <td width="4%" >
                           <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#15428b; line-height: 20px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                       </td> <!------帮助--2014.06.07----->                       
		  </tr></table>
		<form action="?" method="post" enctype="multipart/form-data">
		<input type="hidden" name="restorefrom" value="localpc">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
		<tr align="center" class="header"><td align="left"><? echo _("请选择你要导入文件格式,只能是 .csv 文件")?></td></tr>
		<tr><td><input type="file" name="myfile">
		  <input type="submit" name="act" value="<? echo _("导入数据")?>"></td>
		</tr>
		<tr>
		  <td align="left"><br><? echo _("样表格式:")?><A href="PHPExcel/user_date.csv" style="color:#FF0000"><? echo _("请点此下载,不能下载右键链接另存为")?></A> <br><br>( <? echo _("请严格按照系统提供的样表格式进行用户数据编辑，否则无法导入")?>)</td>
		</tr>
		<tr><td align="center">&nbsp;</td>  </tr></table>
		</form>
<?php
$userOld   =$db->select_count("userinfo as u,userattribute as a,orderinfo as o,product as p"," u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") ");
$successNum=0;
$errNum    =0;
if($_REQUEST['restorefrom']=="localpc" && !empty($_FILES['myfile']['tmp_name'])){/**************/
	$name     = 'userinfo.csv';   
	$path     = '/usr/local/usr-gui/PHPExcel/';
	$fileName =$path.$name;  
	
	switch ($_FILES['myfile']['error'])
	{
	case 1:
	case 2:
	$msgs[]=_("您上传的文件大于服务器限定值，上传未成功");
	break;
	case 3:
	$msgs[]=_("未能从本地完整上传备份文件");
	break;
	case 4:
	$msgs[]=_("从本地上传备份文件失败");
	break;
    case 0:
	break;
	} 
	if (is_uploaded_file($_FILES['myfile']['tmp_name'])) { 
		@copy($_FILES['myfile']['tmp_name'],$fileName);
	  @exec("chmod +x ".$fileName);
	}  

$row = 0;
$handle = fopen ($fileName,"r");
while ($data = fgetcsv ($handle, 1000, ",")) {
  $num = count ($data); 
  //print_r($data); 
  //echo $num."<hr>";
  $row++;  
  
  if ($row >=2 ) {
  //if ($row >=2 && $row<=$num) {
  // print_r($data);     
      $UserName  = trim($data[0]); 
      $password  = trim($data[1]);
     $name      = mb_convert_encoding(trim($data[2]), "UTF-8","GBK");//转换编码
     $cardid    = trim($data[3]);
       $mobile    = trim($data[4]);
       $address   = mb_convert_encoding(trim($data[5]), "UTF-8","GBK");
         $money     = is_numeric(trim($data[6]))?trim($data[6]):0;
          $ipaddress = trim($data[7]);
      
  
	 $stopdatetime  = trim($data[9]);
	 $areaID        = trim($data[10]);
	$projectID     = trim($data[12]);
	 $productID     = trim($data[14]);
	 $manager       = trim($data[16]);
	 $remark        = mb_convert_encoding(trim($data[17]), "UTF-8","GBK");
	
	$macbind       = 0;
	$vlanbind      = 0;
	$macbind       = 0;
	$onlinenum     = 1;
	$product= $db->select_one("price","product","ID='".$productID."'");
	$price=$product["price"];
	$money = $money+$price; 
  $stime     =trim($stopdatetime);//原始获取的文本到期时间值  判断是否为0000-00-00  即上线计时用户
	$timetype  =1;//立即计时  
  $stopdatetime  = date("Y-m-d",strtotime($stopdatetime)); 
  if($stopdatetime!="0000-00-00 00:00:00")
  $stopdatetime = date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($stopdatetime))."+ 23 hours 59 minutes  59 seconds ")); 
   
	$status        =1;//表示立立即执行订单 
	if($stopdatetime=="0000-00-00 00:00:00"){//视为上线计时用户
	   $status        =1;//表示立立即执行订单 
	}else{
	    if($stopdatetime<=date("Y-m-d H:i:s",time())){//该订单已到期
		    $status  =4;//表示立订单结束 
		} 
	}
	if($stime =="0000-00-00" || $stime =="0000-00-00 00:00:00"){//上线计时用户
	   $timetype  =0;//上线计时
	   $status        =1;//表示立立即执行订单 
	}else{
	   $timetype  =1;//立即计时 
	} 
	unset($input_errors);
	$addUserTotalNum    = $num -1;//导入的用户数
	$userTotalNum       = $db->select_count("userinfo",""); 
	$userTotal          = (int)$userTotalNum;    //总共用户数 
	if($userTotal>$systemconfig["max_user"] && $systemconfig["max_user"]!=0) //超出授权范围用户
	  $input_errors[] = _("导入用户与系统存在用户总和超过系统授权用户").":".$systemconfig["max_user"]."，"._("请调整或联系蓝海卓越");
  if (!$UserName) $input_errors[] = _("用户名为空!必须输入一个有效的用户名");
  
  if (($User_Password && !preg_match("/^[a-zA-Z0-9]*$/", $User_Password))) 
		$input_errors[] = _("必须输入一个有效的密码! 密码名由字母a-z, A-Z 或者数字0-9组成");
 
 	if (!$name)  $input_errors[] = _("用户实名为空! 必须输入一个有效的名字");
  if (!$cardid) $input_errors[] = _("身份证为空! 必须输入一个有效的身份证号码");
	if ($money<0) $input_errors[] = _("用户帐户金额不能少于0元，并且不能少于购买当前产品的价格=").$money."";
 

	//检查是否存在用户名
	$userCount=$db->select_count("userinfo","UserName='".$UserName."'"); 
	if($userCount>0) $input_errors[]=$UserName._("此用户名存在"); 
	//检查是否存在项目名
	$projectCount=$db->select_count("project","ID='".$projectID."'"); 
	if($projectCount<=0){
		$input_errors[]=_("此用户所属项目名不存在");
	}	
	
	//检查是否存在产品
	$productRs=$db->select_one("*","product","ID='".$productID."'"); 
	if(empty($productRs)){
		$input_errors[]=_("此用户所属产品编号的产品不存在");
	}else{
		if($money<$productRs["price"]){
			$input_errors[]=_("帐号当前金额不足以购买当前所选产品");
		}
	}
	$nowDateTime=date("Y-m-d H:i:s",time());
	if(!$input_errors){
		$sql=array(
			"UserName"		 =>$UserName,
			"account"    	 =>$UserName,
			"password"		 =>$password,
			"name"			   =>$name,
			"projectID"	   =>$projectID,
			"cardid"	     =>$cardid, 
			"mobile"         =>$mobile, 
			"address"        =>$address,
			"money"          =>$money,
			"adddatetime"    =>$nowDateTime,
			"closedatetime"  =>'0000-00-00 00:00:00', 
			"gradeID"        =>'1',
			"remark"         =>$remark,
			"areaID"         =>$areaID
		);
		 
		if(!$db->insert_new("userinfo",$sql)){
			mysql_query("ROOLBACK");
		}
		//$userID=$db->insert_id();
		$user= $db->select_one("ID","userinfo","UserName='".$UserName."'");  
		$userID=$user['ID'];
		//添加用户验证密码信息
		addUserPassword($UserName,$password); 
		//更新用户帐单
		addUserBillInfo($userID,"0",$money);//记录用户帐单 
		//查入订单,成功则返回订单编号
		$orderID=addOrder($userID,$productID,$status); 
		//插入用户属性，以作为用户拨号的凭据,1=正常用户
		addUserAttribute($userID,$orderID,$status,$macbind,$onlinenum,$nasbind,$vlanbind); 
		//用户技术参数,当订单立即起效果写入或更改用户技术参数的~
		addUserParaminfo($userID,$productID); 
		//用户运行时间 
		 addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime,$stopdatetime);//上线计时
                   if(!empty($ipaddress)){
		 addUserIpaddress($userID,1,$ipaddress); //添加用户IP
                   }
		//记录用户操作记录
		addUserLogInfo($userID,"0",$_SERVER['REQUEST_URI'],$UserName); 
		//增加订单记录
		addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI']); 
		//记录财务记录
		addCreditInfo($userID,"0",$money,$projectID);
	}//end if input_errors 
 }if($input_errors) break; 
 $successNum = $row - 1;
}
 if($input_errors){
 	 $errNum = 0;
		foreach($input_errors as $key=>$va){
			$now_status .="<div style='line-height:25px; color=red;'>".$va."</div>"; 
		}
		if($row<=2){
			exec("rm PHPExcel/err.txt");
		}
		exec("echo 'line ".$row." \n ' >> PHPExcel/err.txt");
		$errNum=$errNum++;
		/*if(!$data->sheets[0]['cells'][$row+1][1]){
			$now_status .="<div>"._("当前状态：导入完成")."</div>";
		}else{
		*/
			$now_status .="<a href='?row=".($row+1)."&successNum=".$successNum."&errNum=".$errNum."&restorefrom=".$_REQUEST["restorefrom"]."'>"._("跳转到一条")."</a>";
		//} 
	}else{
		$now_status .="<a href='?row=".($row+1)."&successNum=".$successNum."&errNum=".$errNum."&restorefrom=".$_REQUEST["restorefrom"]."'>"._("跳转到一条")."</a>";
		 if($row == $num) {
			$now_status .="<div>"._("导入完成")."</div>";
		}	 
	}    
fclose ($handle);
}
$userTotal   =$db->select_count("userinfo as u,userattribute as a,orderinfo as o,product as p"," u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") "); 
?>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td align="left"><?=$now_status?></td>
  </tr>
  <tr>
    <td align="left"><?php echo _("已开户用户数："); echo $userOld;  echo _("条");?> &nbsp;&nbsp;<?php echo _("用户数据共:")?><?=$userTotal?><?php echo _("条")?> &nbsp;&nbsp;<?php echo _("成功条数:")?> <?=$successNum?> &nbsp;&nbsp; <?php echo _("失败条数:")?> <? if ($errNum > 0)echo "<a href='PHPExcel/err.txt'>$errNum</a>";  else echo "0"; ?></></td>
  </tr>
</table>  
  </td>
  </tr>
</table> 
	</td> 
  </tr>   
</table>
  <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        备份恢复-> <strong>数据导入</strong>
      </p>
      <ul>
          <li>可以安照系统中给出的表格式填入用户信息后将表导入，不用再单个添加，方便快捷。</li>
          <li>实用于不同设备间的用户数据恢复。</li>
          <li>在系统中导出的数据不能直接导入，须去掉前面的特殊符号才可导入。</li>
      </ul>

    </div>
<!---------------------------------------------->  
</body>
</html>