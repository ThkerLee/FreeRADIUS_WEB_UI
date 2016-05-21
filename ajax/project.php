#!/bin/php
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-ridate");
header("Pragma: no-cache");
header('Content-Type:text/html;charset=utf-8');
include_once("../inc/conn.php");
//@include("../evn.php");
//@include("evn.php");
if ( file_exists("/etc/LANG" ) ) {
  $lang = trim(file_get_contents("/etc/LANG"));
} else {
  $lang = "zh_CN";
}
putenv("LANG={$lang}");
setlocale(LC_ALL,'$lang');
bindtextdomain("greetings", "../locale/");  
textdomain("greetings"); 
if($_GET["projectIDuser"]){//获取用户名前缀
 $projectID=trim($_GET["projectIDuser"]);   
 $pRs      =$db->select_one("userprefix,userstart,userpwd,device","project","ID='".$projectID."'"); 
 $user = $db->select_all("account","userinfo","UserName like '%".$pRs['userprefix']."%' order by UserName ASC"); 
 
 if(is_array($user)){//以该项目前缀的为头的用户已存在有添加遍历 
   $length1 = strlen($pRs["userstart"]); //获取前缀的长度
   $i=0;
   for($k=1;$k<=count($user)+1;$k++){//获取数组的个数 
      $nums =$pRs["userstart"] + $i ;// 编号
	  $length2 = strlen($nums);//+1后的字符的长度
	  $len = $length1 - $length2;
	  $zero ='0';
	  for($j=0;$j<$len;$j++) $zero .="0";  
	  $sss = substr($zero,0,-1);  
	  $UserName['account'] = $pRs["userprefix"].$sss.$nums;//用户名 
	  if(!in_array($UserName,$user))  break;  
	  $i++;
   } 
 }else{//以该项目前缀为头的用户名开为创建
  $UserName['account'] = $pRs['userprefix'].$pRs["userstart"];
 }  
  echo "<input type=\"text\" id=\"account\" name=\"account\" value='".$UserName['account']."' onFocus=\"this.className='input_on'\" onBlur=\"this.className='input_out';ajaxInput('ajax_check.php','check_account','account','accountTXT');\" class=\"input_out\"> 
   <input type='hidden' value='".$pRs["userpwd"]."' id='userpwd' name='userpwd'> ";
   echo "<input type='hidden' id='device' name='device' value='".$pRs["device"]."'>";
 
}
 

if($_GET["projectID"]){//用户添加页面的产品显示
$projectID=trim($_GET["projectID"]);
$Now_Time  = date("Y-m-d",time());
$pRs      =$db->select_one("installcharge,device","project","ID='".$projectID."'");
//2014.07.22修改and (p.enddatetime >='".$Now_Time."' or p.enddatetime = '0000-00-00')//产品时间到期后 不能显示该产品
$result   =$db->select_all("p.name,p.ID,p.price","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0 and (p.enddatetime >='".$Now_Time."' or p.enddatetime = '0000-00-00') and pj.projectID='".$projectID."' and p.type!='netbar_hour'");
echo "<input type='hidden' value='".$pRs["device"]."' name='device' id='device'>";
?>
<select name="productID" id="productID" onchange="ajaxInput('ajax_check.php','productID','productID','productTXT');" onblur="ajaxInput('ajax_check.php','productIDPeriod','productID','productPeriodTXT');">
<option value=''>请您选择产品</option>
<?php
if(is_array($result)){
	foreach($result as $key=>$rs){ 
?>
<option value="<?=$rs["ID"]?>"><?=$rs["name"]?></option>
<?php 
}
?>
</select>  
<span id='productTXT'></span> 
<input type="hidden" name="installchargeold" id="installchargeold" value="<?=$pRs["installcharge"]?>" />
<input type="hidden" value="<?=showProjectStatus($projectID)?>" name="status" id="status" > 
<?php
}
} 
if($_GET["userProjectID"]){//用户续费
$projectGet=trim($_GET["userProjectID"]); 
$arr       =explode(",",$projectGet);
$Now_Time  = date("Y-m-d",time());
$projectID = $arr[0];
$areaID    = $arr[1];
//2014.07.22修改and (p.enddatetime >='".$Now_Time."' or p.enddatetime = '0000-00-00')//产品时间到期后 不能显示该产品
$result    =$db->select_all("p.name,p.ID","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0 and (p.enddatetime >='".$Now_Time."' or p.enddatetime = '0000-00-00') and  pj.projectID='".$projectID."' and pj.areaID = ".$areaID);
?>
	<select name="productID" id="productID" onchange=ajaxInput('ajax_check.php','productID','productID','productTXT') onblur="ajaxInput('ajax_check.php','productIDPeriod','productID','productPeriodTXT');">
	<option value=''>请您选择产品</option>
	<?php
	if(is_array($result)){
		foreach($result as $key=>$rs){
	?>
	<option value="<?=$rs["ID"]?>"><?=$rs["name"]?></option>
    <?php
		 }
	 }
	 ?>
	</select> 
	<span id="productTXT"></span> 

<?php
}

if($_GET["period"]){ //根据续费周期 续费产品 开始时间 获取时间周期
  $productID = $_GET['pdoID'];
  $timetype = $_GET["timetype"];  
  $period=$_GET["period"];
  $beigndatetime = $_GET['begintime'];
  //if($timetype == 1){
   // if($begintime =='')  $time = date("Y-m-d H:i:s",time()); else $time = $begintime;
	
    $todaytime     =(!empty($beigndatetime))?$beigndatetime:date("Y-m-d",time());	  
	if($timetype=='true'){
		$productRs=$db->select_one("*","product","ID='".$productID."'");// 查询出产品的信息 
	       $periods = $productRs['period'] * $period;
			if($productRs['type']=="year"){
				$datestr =  mysqlGteDate($todaytime,$periods,"year");//mysql 添加时间方法必须连接数据库 
			}elseif($productRs['type']=="month"){
			    $datestr =  mysqlGteDate($todaytime,$periods,"month"); 
			}elseif($productRs['type']=="days"){
			    $datestr =  mysqlGteDate($todaytime,$periods,"day"); 
			}elseif($productRs['type']=="hour"){  
			    $datestr =  mysqlGteDate($todaytime,1,"month"); 
			}elseif($productRs['type']=="flow"){
			//产品 流量计费周期  和计费类型 天/月 添加修改时间 2012-02-09
			    $periods = $productRs['periodtime'] * $period; 
			    if($productRs['timetype']=="days"){ 
			        $datestr =  mysqlGteDate($todaytime,$periods,"day"); 
				}else if($productRs['timetype']=="months"){
			        $datestr =  mysqlGteDate($todaytime,$periods,"month"); 
				} 
			
			}  
			$begindatetime     = $todaytime;//开进运行时间 
			$enddatetime       = $datestr;//date('Y-m-d H:i:s',strtotime($datestr));//运行结束时间 
			
	echo "<br><font color='red'>"._("时间周期：").$begindatetime." 到 ".$enddatetime."</font>"; 	
  }else{
		 $begindatetime     = "0000-00-00";//开进运行时间 
		 $enddatetime       = "0000-00-00"  ;
		 
	echo "<br><font color='red'>"._("时间周期：").$begindatetime."  结束时间：上线时间加周期时间</font>"; 	
  }    
 }
 
if($_GET['projectIDNetbar']){//时长计费
  $projectID=trim($_GET["projectIDNetbar"]);
  $pRs      =$db->select_one("installcharge,userpwd","project","ID='".$projectID."'");
  $result   =$db->select_all("p.name,p.ID","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0 and  pj.projectID='".$projectID."' and p.type='netbar_hour' and limittype=1"); 
?>
  <select name="productID" id="productID" onchange="ajaxInput('ajax_check.php','productID','productID','productTXT');" onblur="ajaxInput('ajax_check.php','productIDPeriod','productID','productPeriodTXT');">
  <option value=''>请您选择产品</option>
<?php
 if(is_array($result)){
	foreach($result as $key=>$rs){
?>
 <option value="<?=$rs["ID"]?>"><?=$rs["name"]?></option>
<?php
   }
 } 
?>
 </select>   
 <span id='productTXT'></span> 
 <input type='hidden' value='<?=$pRs["userpwd"]?>' id='userpwd' name='userpwd'>  
<?php  
}//end projectIDNetbar


if($_GET['projectIDNetbarLimit']){//时长计费
  $projectID=trim($_GET["projectIDNetbarLimit"]);
  $pRs      =$db->select_one("installcharge,userpwd","project","ID='".$projectID."'");
  $result   =$db->select_all("p.name,p.ID","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0 and  pj.projectID='".$projectID."' and p.type='netbar_hour' and limittype=2"); 
?>
  <select name="productID" id="productID" onchange="ajaxInput('ajax_check.php','productID','productID','productTXT');" onblur="ajaxInput('ajax_check.php','productIDPeriod','productID','productPeriodTXT');">
  <option value=''>请您选择产品</option>
<?php
 if(is_array($result)){
	foreach($result as $key=>$rs){
?>
 <option value="<?=$rs["ID"]?>"><?=$rs["name"]?></option>
<?php
   }
 } 
?>
 </select>   
 <span id='productTXT'></span> 
 <input type='hidden' value='<?=$pRs["userpwd"]?>' id='userpwd' name='userpwd'>  
<?php  
}//end projectIDNetbarLimit
//其他linux选择地址池
//2014.09.20修改地址池
if($_GET["poolnameShow"]){ 
	$poolnameShow =$_GET["poolnameShow"]; 
  $arr       =explode(",",$poolnameShow);
  $projectID = $arr[1];  
  $rs = $db->select_one("poolname,device","project","ID=".$projectID);
//  $poolname="";
 // echo "<input type='radio' name='poolname'  id='poolname' value='' checked >不选地址池";
 // if($rs["poolname"]!=""){
//    $poolnameArr= explode("#",$rs["poolname"]);
//    foreach($poolnameArr as $val){ 
 //     echo "<input type='radio' name='poolname'  id='poolname'value='".$val."' >".$val;
 //   }
//  }
   echo "<input type='hidden' id='device' name='device' value='".$rs["device"]."'>";
   
}
//2014.09.20添加选择产品显示改产品的地址池
if($_GET["poolShow"]){
 $productID =  $_GET["poolShow"];
  $rs = $db->select_one("pool","product","ID=".$productID);
  echo "<input type='radio' name='poolname'  id='poolname' value='' checked >不选地址池";
  if(!empty($rs["pool"])){
      echo "<input type='radio' name='poolname'  id='poolname'value='".$rs["pool"]."' >".$rs["pool"];
  }
  
}

//添加用户选择区域
if(isset($_GET["areaID"])){ 
	  $areaID    = $_GET["areaID"];
	  $projectID = "";  
	 if(strrpos($areaID,",")>0){
	  $areaStr   = explode(",",$areaID);
	  $areaID    = $areaStr[0];
	  $userID    = $areaStr[1];  
	  $projectRs = $db->select_one("projectID","userinfo","ID='".$userID."'");
	  $projectID = $projectRs["projectID"];  
	  //便于修改产品时分配IP
	  echo "<input type='hidden' name='projectID' id ='projectID' value='".$projectID."'>";
	 } 
	 $sql ="ap.areaID=".$areaID." and ap.projectID =p.ID and pj.projectID =p.ID  and ap.projectID in(".$_SESSION["auth_project"].") order by convert(p.name using gbk) asc";
	 $areaRs = $db->select_all("distinct(p.ID),p.name,ap.areaID","areaandproject as ap,productandproject as pj,project as p",$sql);
	 echo "<select name='areaprojectID' id='areaprojectID' onchange='ajaxInput(\"ajax/project.php\",\"areaprojectID\",\"areaprojectID\",\"productSelectDIV\")'  onblur='ajaxInput(\"ajax/project.php\",\"poolnameShow\",\"areaprojectID\",\"poolnameSelectDIV\")'><option value=''>选择项目</option>";
	 if(is_array($areaRs)){ 
	  foreach($areaRs as $key=>$rs){ 
      echo "<option value='".$areaID.",".$rs["ID"]."'";
      if($rs['areaID']==$areaID && $rs["ID"]==$projectID ) echo "selected";
      echo ">".$rs["name"]."</option>"; 
    }    
	 }
	 echo "</select>"; 
}
//添加产品
if($_GET["areaprojectID"]){
   $areaprojectID = $_GET["areaprojectID"];
   $areaprojectArr= explode(",",$areaprojectID);
   $areaID        = $areaprojectArr[0];
   $projectID     = $areaprojectArr[1];
   $Now_Time  = date("Y-m-d",time());
   //2014.07.22修改and (p.enddatetime >='".$Now_Time."' or p.enddatetime = '0000-00-00')//产品时间到期后 不能显示该产品
   $productRs = $db->select_all("distinct(p.ID),p.name,p.price",
  "productandproject as pj,product as p","p.ID=pj.productID and p.hide=0 and (p.enddatetime >='".$Now_Time."' or p.enddatetime = '0000-00-00') and pj.areaID=$areaID and projectID=$projectID order by convert(p.name using gbk) asc" );  //2014.05.20修改 解决隐藏产品后添加用户页面 还是要显示隐藏的产品 and p.hide=0
   $projectRs = $db->select_one("installcharge,device,status","project","ID =".$projectID);
   //2014.09.20添加选择产品时调用地址池
   echo "<select name='productID' id='productID' onchange='ajaxInput(\"ajax_check.php\",\"productID\",\"productID\",\"productTXT\")' onblur='ajaxInput(\"ajax_check.php\",\"productIDPeriod\",\"productID\",\"productPeriodTXT\")'>"; 
   echo "<option  value=''>选择产品</option>";
	 if(is_array($productRs)){ 
	  foreach($productRs as $key=>$rs){ 
      echo "<option value='".$rs["ID"]."'>".$rs["name"]."</option>"; 
    }    
	 }
	 echo "</select>";
	 echo "<input type='hidden' name='installchargeold' id='installchargeold' value='".$projectRs["installcharge"]."' />";
   echo "<input type='hidden' value='".$projectRs["device"]."' name='device' id='device'>";
   echo "<input type='hidden' name='projectID' id ='projectID' value='".$projectID."'>";  
   echo "<input type='hidden' value='".$projectRs["status"]."' name='status' id='status'>";
   echo "<span id='productTXT'></span>"; 
}
?> 
