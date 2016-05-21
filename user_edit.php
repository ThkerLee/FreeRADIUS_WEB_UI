#!/bin/php
<?php
@ session_start();
include("inc/conn.php"); 
require_once("ros_static_ip.php"); 
require_once("evn.php");   
date_default_timezone_set('Asia/Shanghai'); 
 ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> <? echo _("用户管理"); ?> </title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css"> 
<script src="js/jsdate.js" type="text/javascript"></script> 
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<body>
<?php
 MysqlBegin();//开始事务定义
$sql_a = true;  $sql_b = true;	$sql_c = true;	$sql_d = true;	$sql_e = true;	$sql_f = true;	$sql_g = true;	$sql_h = true;	$sql_i = true;	$sql_j = true;  $sql_k = true;	$sql_l = true;	$sql_m = true;	$sql_n = true;	$sql_o = true;	$sql_p = true; $sql_q = true; $sql_r = true; $sql_s = true; $sql_t = true; $sql_u = true;
	
$ID=$_REQUEST["ID"]; 
$orderID=$_REQUEST["orderID"];   
if((is_numeric($ID) && $ID>0) && (is_numeric($orderID) && $orderID>0)) $sql_a = true ;
else  $sql_a = false; 
$NowTime=date("Y-m-d H:i:s",time()); 
$rs=$db->select_one("*","userinfo","ID='".$ID."'");
$ipRs=$db->select_one("*","radreply","userID='".$ID."' and Attribute='Framed-IP-Address'");
$poolRs=$db->select_one("*","radreply","userID='".$ID."' and Attribute='Framed-Pool'");
$macRs=$db->select_one("vlanbind,macbind,nasbind,onlinenum","userattribute","userID='".$ID."'");
$aRs=$db->select_one("a.*,r.*","userattribute as a,userrun as r","a.userID='".$ID."' and a.userID=r.userID and a.orderID=r.orderID");
$pj=$db->select_one("device,status","project","ID='".$rs['projectID']."'"); 
$oRS=$db->select_one("productID","orderinfo","ID='".$aRs['orderID']."'");
$pd=$db->select_one("*","product","ID='".$oRS['productID']."'"); 
$areaID    =$rs["areaID"]; 
$userID    =$ID; 
if(!$rs  || !$macRs || !$aRs || !$pj || !$oRS || !$pd )  $sql_b = false; else $sql_b = true;  

if($_POST){ 
  $account       =$_POST["account"];  
	$old_account   =$_POST["old_account"];
  $password      =$_POST["password"]; 
	$name          =$_POST["name"]; 
	$sex           =$_POST["sex"];
	$birthday      =$_POST["birthday"];
	$projectID     =explode(",",$_POST["areaprojectID"]);
	$projectID     =$projectID[1]; 
	$areaID        =explode(",",$_POST["areaID"]);
	$areaID        =$areaID[0] ;	
	$cardid        =$_POST["cardid"];
	$workphone     =$_POST["workphone"];
	$homephone     =$_POST["homephone"];
	$mobile 	     =$_POST["mobile"]; 
	$email         =$_POST["email"];
	$iptype        =$_POST["iptype"];
	$address       =$_POST["address"];	 
	$ipaddress     =$_POST["ipaddress"];
	$timestatus    =$_POST["status"];
	$onlinetime    =$_POST["onlinetime"];
	$onlinenum     =$_POST["onlinenum"];
	$MAC           =$_POST["MAC"];
	$poolname      =$_POST["poolname"];
	$macbind	   =(empty($_POST["macbind"]))?'0':$_POST["macbind"];
	$NAS_IP		   =$_POST["NAS_IP"];
	$nasbind	   =(empty($_POST["nasbind"]))?'0':$_POST["nasbind"];
	$enddatetime =date("Y-m-d H:i:s",strtotime(date("Y-m-d",strtotime($_POST["enddatetime"]))."+ 23 hours 59 minutes  59 seconds "));
        $begindatetime=$_POST["begindatetime"];
	if(strtotime($enddatetime)==57599) $enddatetime="0000-00-00 00:00:00"; 
	$VLAN        =$_POST["VLAN"];
	$vlanbind	   =(empty($_POST["vlanbind"]))?'0':$_POST["vlanbind"];
	$gradeID	   =$_POST["gradeID"];
	$zjry		     =$_REQUEST["zjry"];
	$remark      =$_POST['remark'];  
 
	$sql=array(
		"password"		   =>$password,
		"name"			     =>$name,
		"sex"            =>$sex,
		"birthday"       =>$birthday,
		"projectID"	     =>$projectID,
		"cardid"	       =>$cardid,
		"workphone"      =>$workphone,
		"homephone"      =>$homephone,
		"mobile"         =>$mobile,
		"email"          =>$email,
		"address"        =>$address,
		"MAC"            =>$MAC,
		"VLAN"           =>$VLAN,
		"zjry"    	 =>$zjry,
		"NAS_IP"         =>$NAS_IP, 
		"gradeID"        =>$gradeID,
		"remark"         =>$remark,
		"status"         =>$timestatus,
		"onlinetime"     =>$onlinetime,
		"areaID"         =>$areaID
	); 
	$content=_("用户编辑:");
	if($password !=$rs['password'])   $content.="/"._("密码"); 
	if($name!=$rs['name']) $content.="/"._("用户名"); 
	if($sex!=$rs['sex'])  $content.="/"._("用户性别"); 
 	if($birthday!=$rs['birthday']) $content.="/"._("用户生日"); 
  if($areaID!=$rs['areaID']) $content.="/"._("所属区域"); 
  if($projectID!=$rs['projectID']) $content.="/"._("所属项目"); 
  if($cardid!=$rs['cardid']) $content.="/"._("证件号码"); 
  if($workphone!=$rs['workphone']) $content.="/"._("工作电话"); 
  if($mobile!=$rs['mobile']) $content.="/"._("手机号码"); 
  if($homephone!=$rs['homephone'])  $content.="/"._("家庭电话"); 
  if($email!=$rs['email'])  $content.="/"._("电子邮件"); 
  if($address!=$rs['address']) $content.="/"._("联系地址"); 
  if($ipaddress!=$ipRs["Value"]) $content.="/"._("分配IP"); 
  if($timestatus!=$rs['status'])  $content.="/"._("固定在线时长状态"); 
  if($onlinetime!=$rs["onlinetime"])  $content.="/"._("在线时长"); 
  if($MAC!=$rs['MAC']) $content.="/"._("MAC地址"); 
	if($macbind!=$aRs['macbind']){
		 if($macbind == 0) $content.="/"._("取消MAC绑定"); 
	   else $content.="/"._("MAC绑定");
	}
	if($VLAN!=$rs['VLAN']) $content.="/"._("VLAN 地址");
	if($vlanbind!=$aRs['vlanbind']){
		 if($vlanbind==0) $content.="/"._("取消VLAN绑定"); 
		 else $content.="/"._("VLAN绑定"); 
	}
	if($NAS_IP!=$rs['NAS_IP'])$content.="/"._("NAS 地址"); 
	if($nasbind!=$aRs['nasbind']){
		 if($nasbind==0) $content.="/"._("取消NAS绑定"); 
		 else $content.="/"._("NAS绑定");  
	}
	if($zjry!=$rs['zjry'])  $content.="/"._("装机人员"); 
  if($enddatetime!=$aRs["enddatetime"]) $content.="/"._("产品结束时间从").$aRs["enddatetime"]."改为".$enddatetime;
  if($begindatetime!=$aRs["begindatetime"]) $content.="/"._("产品开始时间从").$aRs["begindatetime"]."改为".$begindatetime."\r"."00:00:00"; 
	if($gradeID!=$rs['gradeID'])  $content.="/"._("用户等级"); 
  if($remark!=$rs['remark']) $content.="/"._("用户备注"); 
  if($content==_("用户编辑:"))  $content=""._("无任何修改");    
	if(!preg_match( "/^[a-z\d]*$/i ",$password)){ 
           echo "<script>alert('". _("密码只能是数字或字母")."');window.location.href='user_edit.php?ID=".$ID."';</script>";
	} 
	//查询下一订单的开始时间，即修改色时间不能>下一订单的开始时间
   $firstWaitOrder = $db->select_one("begindatetime","userrun","userID='".$ID."' and status =0 and begindatetime !='00-00-00 00:00:00' and enddatetime !='00-00-00 00:00:00'  order by begindatetime asc limit 0,1");
   if($firstWaitOrder){
        if(strtotime($enddatetime) > strtotime($firstWaitOrder["begindatetime"])){
		  echo "<script>alert('". _("当期订单结束时间不能能超过下一等待运行订单的开始时间:").$firstWaitOrder['begindatetime']."');window.location.href='user_edit.php?ID=".$ID."';</script>";
		  exit;
		}
   }

    $new=$db->select_one("device,status","project","ID='".$projectID."'"); //当前用户修改所选产品
	if(!$new) $sql_c = false; else $_sql_c =true;
	$newName=$db->select_one("account","userinfo",'account="'.$account.'"  and ID !="'.$ID.'"');  
	///////////////////////if(!$newName) $sql_d = false ; else $sql_d = true;
	if($new){
	  if(($new['device']!="mikrotik" && $account!=$rs['account']) || ($new['device']=="mikrotik" && $new['status']=='disable' && $account!=$rs['account']) ){         echo "<script>alert('". _("根据所属项目规则，该用户不可更改用户名")."');window.location.href='user_edit.php?ID=".$ID."';</script>";
		 exit;
	  }if($pj['device']=="mikrotik" && $projectID!=$rs['projectID']){
	     echo "<script>alert('". _("其他Linux项目不允许修改")."');window.location.href='user_edit.php?ID=".$ID."';</script>";
		 exit; 
	  } 
	  if($newName){ 
	     echo "<script>alert('". _("该用户名已存在，请重新输入")."');window.location.href='user_edit.php?ID=".$ID."';</script>";
		 exit;
	  }
	  if($new['device']=="mikrotik" && $new['status']=='enable' ){ 
	    if($ipaddress=='' || !is_ip($ipaddress)){
		 echo "<script>alert('". _("请分配有效IP")."');window.location.href='user_edit.php?ID=".$ID."';</script>";
		 exit;  
		}else{
	     $sql['account']=$account;
		   $sql['UserName']=$account;
	     $rosIP=$db->select_one("*","ip2ros","projectID='".$rs['projectID']."'");  
		 if(!$rosIP) $sql_e = false ;else $sql_e = true;
		 //判断用户当前状态是否为正常用户 即stop==0
		 if($aRs['stop']==0){
		    $sql_f =modifyarp($rosIP['rosipaddress'], $rosIP['rosusername'], $rosIP['rospassword'], $old_account, $ipaddress, $account, $rosIP['inf']); 
	     }if($aRs['stop']==1){//删除
		    $sql_f =delarp_from_ros($rosIP['rosipaddress'], $rosIP['rosusername'], $rosIP['rospassword'],$old_account); 
	     }
		  //是否有子母账号关系
		 $Msql=array("Mname"=>$account);
		 $sql_g = $db->update_new("userinfo","Mname='".$rs['account']."'",$Msql);   
		 //属性表radreply
		 $Rsql=array("UserName"=>$account);
		 $sql_h = $db->update_new("radreply","UserName='".$rs['account']."'",$Rsql);   
		 //拨号验证表 
		 $Usql=array("UserName"=>$account);
		 $sql_i = $db->update_new("userattribute","UserName='".$rs['account']."'",$Usql);
		 //删除拨号验证表 
	     $sql_j = $db->delete_new("radcheck","UserName='".$rs['account']."'");//删除用户密码 
		 }
	  }
	}  
	$sql_k = $db->update_new("userinfo","ID='".$ID."'",$sql);
	//添加用户验证密码信息
	//$account=getUserName($ID);
	$sql_l = addUserPassword($account,$password);	
	$sql_m = addUserOnline($account,$onlinenum);

	//更新用户属性状态表
	$sql_n = updateUserAttribute($userID=$ID,array("macbind"=>$macbind,"nasbind"=>$nasbind,"onlinenum"=>$onlinenum,"vlanbind"=>$vlanbind));
	if($projectID!=$rs['projectID']){//修改项目 
	    //删除用户属性
		$sql_o = $db->delete_new("radcheck","UserName='".$account."'");//删除用户技术参数表
		$sql_p = addUserPassword($account,$password);
		//重建永固属性
		$sql_q = addUserParaminfo($ID,$oRS['productID'],'','',"userAdd"); 
	 }
	
	// 更改IP地址
	$sql_r = addUserIpaddress($userID,$iptype,$ipaddress,$poolname);
	
	//增加用户操作记录
	$sql_s = addUserLogInfo($userID,2,$content,$name);
	
	//更新订单时间  
	//$sql="update userrun set enddatetime='".$enddatetime."' where orderID='".$orderID."' and userID='".$ID."'"; 
        $sql="update userrun set enddatetime='".$enddatetime."',begindatetime='".$begindatetime."' where orderID='".$orderID."' and userID='".$ID."'";//2014.07.16 修改订单 开始和结束
	$sql_t = $db->query($sql);  
    if(strtotime($enddatetime) > time() && $aRs["status"]!=5) {
	 $db->update_new("userattribute","orderID='".$orderID."'",array("stop"=>0,"status"=>1));
	 $db->update_new("orderinfo","ID='".$orderID."'",array("status"=>1));
	 $db->update_new("userrun","orderID='".$orderID."'",array("status"=>1));
	 }
	 if($enddatetime=="0000-00-00 00:00:00"){
	    $db->update_new("userattribute","orderID='".$orderID."'",array("stop"=>0,"status"=>1));
	    $db->update_new("orderinfo","ID='".$orderID."'",array("status"=>1));
	    $db->update_new("userrun","orderID='".$orderID."'",array("status"=>1)); 
	 } 
	
	//添加在线时长自动下线功能
	//排除上线计时用户
	if($aRs['begindatetime']!="0000-00-00 00:00:00"){
	   $sql_u = adduserOnlinetime($timestatus,$ID,$account,$onlinetime);  
	} 
	if ($sql_a &&  $sql_b && $sql_c && $sql_d && $sql_e && $sql_f && $sql_g && $sql_h && $sql_i && $sql_j &&  $sql_k && $sql_l && $sql_m && $sql_n && $sql_o && $sql_p && $sql_q && $sql_r && $sql_s && $sql_t && $sql_u){
          MysqlCommit(); 
          //2014.02.24增加修改密码时把用户踢下线，用新密码重新拨号
          if($password!=$rs["password"]){
                                        $UserName=$rs['UserName'];
                                //根据用户查数据库,得到NAS IP,用户的SESSION  ID 
                            $row=$db->select_one("*","radacct","AcctStopTime='0000-00-00 00:00:00' and UserName='".$UserName."'");
                $SessionID =$row['AcctSessionId'];
                $NAS_IP    =trim($row['NASIPAddress']);
                $tmp       =explode("-",$SessionID);
                $FramedIPAddress = $row['FramedIPAddress'];
                if($tmp[2]){
                        $SessionID=$tmp[1]."-".$tmp[2];
                }else{
                        $SessionID=$tmp[1];
                }

                $nas_rs=$db->select_one("*","nas","ip='".$NAS_IP."'");
                $http_port=$nas_rs["ports"];
                $share_passwd=$nas_rs["secret"];

                //获取供应商信息  
                $dRs=$db->select_one("p.device","userinfo as u,project as p","u.projectID=p.ID and u.UserName='".$UserName."'");
                $GroupName  = $dRs['GroupName'];
                $Vender     = $dRs['device'];	

                if( $NAS_IP ) {
                  $SessionID = $row['AcctSessionId'];
                  down_user_linux_radius($UserName, $FramedIPAddress, $NAS_IP, $http_port, $share_passwd, $SessionID, $Vender);
                }	 
                echo "<script>alert('你修改了用户的上网密码，系统将使该用户下线以使新密码生效')</script>"; 
          }
          //----------------2014.02.24增加上面代码
          echo "<script>alert('"._("修改成功")."');window.location.href='user.php?UserName=".$_POST['RUserName']."&name=".$_POST['Rname']."&endDateTime=".$_POST['RendDateTime']."&projectID=".$_POST['RprojectID']."&sex=".$_POST['Rsex']."&productID=".$_POST['RproductID']."&birthday=".$_POST['Rbirthday']."&address=".$_POST['Raddress']."&MAC=".$_POST['RMAC']."&operator=".$_POST['Roperator']."&receipt=".$_POST['Rreceipt']."';</script>";
          
    } else{
         MysqRoolback(); 
         echo "<script>alert('"._("修改失败")."');window.location.href='user.php?UserName=".$_POST['RUserName']."&name=".$_POST['Rname']."&endDateTime=".$_POST['RendDateTime']."&projectID=".$_POST['RprojectID']."&sex=".$_POST['Rsex']."&productID=".$_POST['RproductID']."&birthday=".$_POST['Rbirthday']."&address=".$_POST['Raddress']."&MAC=".$_POST['RMAC']."&operator=".$_POST['Roperator']."&receipt=".$_POST['Rreceipt']."';</script>";
/* 
       
*/

   } //.$sql_a.":::".$sql_b.":::".	$sql_c.":::".	$sql_d.":::".	$sql_e.":::".	$sql_f.":::".	$sql_g.":::".	$sql_h.":::".	$sql_i.":::".	$sql_j.":::".$sql_k.":::".	$sql_l.":::".	$sql_m.":::".	$sql_n.":::".	$sql_o.":::".	$sql_p.":::".$sql_q.":::".$sql_r.":::".$sql_s.":::".$sql_t.":::". $sql_u.":::"

MysqlEnd();//关闭连接
	
	 
  // $sql_3=" UNLOCK TABLES ";
  //   mysql_query($sql_3);
	 

} 
?> 
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理"); ?></font></td>
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> <? echo _("用户编辑"); ?></td>
		    <td width="11%" align="right">&nbsp;</td>
      </tr>
 </table>
<form action="?" method="post" name="myform"  onSubmit="return checkUserForm();">
<input type="hidden" name="ID" value="<?=$ID?>">
<input type="hidden" name="orderID" value="<?=$aRs["orderID"]?>">
<input  type="hidden" value="<?=$pd['ID']?>" name="productID">
<input type="hidden" name="old_account" value="<?=$rs["account"]?>">
<input type="hidden" name="installcharge" value="0" > 
<input  type="hidden" name="status" value="<?=$pj['status']?>">
<!-- 编辑获取搜索信息 -->
<input  type="hidden" name="RUserName" value="<?=$_GET['UserName']?>">
<input  type="hidden" name="RstartDateTime" value="<?=$_GET['startDateTime']?>"> 
<input  type="hidden" name="RendDateTime" value="<?=$_GET['endDateTime']?>">
<input  type="hidden" name="RprojectID" value="<?=$_GET['projectID']?>">
<input  type="hidden" name="Rname" value="<?=$_GET['name']?>">
<input  type="hidden" name="Rsex" value="<?=$_GET['sex']?>"> 
<input  type="hidden" name="RproductID" value="<?=$_GET['productID']?>">
<input  type="hidden" name="Rbirthday" value="<?=$_GET['birthday']?>">
<input  type="hidden" name="Raddress" value="<?=$_GET['address']?>"> 
<input  type="hidden" name="RMAC" value="<?=$_GET['MAC']?>">
<input  type="hidden" name="Roperator" value="<?=$_GET['operator']?>">
<input  type="hidden" name="Rreceipt" value="<?=$_GET['receipt']?>"> 
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("用户帐号");?> ：</td> 
			<td width="87%" align="left" class="bg">
				<input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','user_edit_account','account','accountTXT')" class="input_out" value="<?=$rs["account"]?>" >
			<span id="accountTXT"></span><? echo _("当用户所选项目为[其他Linux]用户名可更改");?>		</td>
		  </tr>
	  <tr>
		    <td align="right" class="bg"><? echo _("用户密码"); ?> ：</td>
		    <td align="left" class="bg"><input type="password" id="password" name="password" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["password"]?>"></td>
		</tr> 
		<tr>  
		   <td align="right" class="bg"><? echo _("确认密码"); ?>：</td> 
		    <td align="left" class="bg"><input type="password" id="pwd" name="pwd" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["password"]?>"> <? echo _("密码不能为中文"); ?></td> 
		</tr> 
		    <!--
		   <tr> 
		    <td align="right" class="bg"><? echo _("所属项目"); ?> ：</td>
		    <td align="left" class="bg"><?=projectSelected($rs["projectID"]);?> 
		    <span id="productSelectDIV"></span> 
			 </td>
		    </tr>
		    -->
		  <tr> 
		     <td align="right" class="bg">* <? echo _("所属区域")?>:</td> 
	       <td align="left" class="bg"><? selectArea($areaID,$userID);?></td> 
		  </tr> 
      <tr> 
		    <td align="right" class="bg">* <? echo _("所属项目")?>:</td> 
		    <td align="left" class="bg" id="projectSelectDIV"> <select><option><? echo _("选择项目");?></option></select>
		    </td> 
		 </tr>  
		  <tr>
		    <td align="right" class="bg"><? echo _("用户名称"); ?> ： </td>
		    <td align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["name"]?>"></td>
	      </tr> <tr> 
		    <td align="right" class="bg"><? echo _("用户性别")?> : </td> 
		    <td align="left" class="bg">
			<input type="radio" name="sex" value="male"  <? if($rs['sex']=='male') echo "checked=\"checked\" "; ?> ><? echo _("男");?>&nbsp;&nbsp;
			<input type="radio" name="sex" value="female" <? if($rs['sex']=='female') echo "checked=\"checked\" "; ?> ><? echo _("女");?>  </td> 
	      </tr> 
		    <tr> 
		    <td align="right" class="bg"><? echo _("用户生日")?> : </td> 
		    <td align="left" class="bg"><input type="text" id="birthday" name="birthday"  value="<?=$rs['birthday'];?>"   onFocus="HS_setDate(this)"onBlur="this.className='input_out';" class="input_out"></td> 
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("证件号码"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" id="cardid" name="cardid" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';" value="<?=$rs["cardid"]?>"></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("工作电话"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" id="workphone" name="workphone" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';" value="<?=$rs["workphone"]?>"> </td>
	      </tr>
		  <tr id="unitpriceTR">
		    <td align="right" class="bg"><? echo _("家庭电话"); ?> ：</td>
		    <td align="left" class="bg"><input type="homephone" name="homephone" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["homephone"]?>"></td>
	      </tr>
		  <tr id="cappingTR">
		    <td align="right" class="bg"><? echo _("手机号码"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" name="mobile" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["mobile"]?>"></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("电子邮件"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" name="email" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["email"]?>">			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("联系地址"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" name="address" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["address"]?>"></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("装机人员"); ?> ：</td>
		    <td align="left" class="bg">
			<input type="text" name="zjry" value="<?=$rs["zjry"]?>">
						</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("帐号金额"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" name="money" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["money"]?>" disabled="disabled"></td>
		    </tr>
                     <?php
                    if(in_array("user_add_onlinenum",$_SESSION["auth_permision"])){ //2014.06.27 修改允许同时在线人数权限问题。
                    ?>              
		<tr>
		    <td align="right" class="bg"><? echo _("允许同时在线人数"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" name="onlinenum" value="<?=$macRs["onlinenum"]?>"> 
		     <? echo _("0或留空表示不限制"); ?></td>
		</tr>
                <?php 
                    }else{
                  ?> 
                		<tr>
		    <td align="right" class="bg"><? echo _("允许同时在线人数"); ?> ：</td>
		    <td align="left" class="bg"><input type="text" name="onlinenum" value="<?=$macRs["onlinenum"]?>" readonly="readonly"> 
		     <? echo _("0或留空表示不限制"); ?></td>
		</tr>
                        
                  <?php  }
                    
                ?>
		  <tr>
		    <td align="right" class="bg"><? echo _("地址分配"); ?> ：</td>
		    <td align="left" class="bg">
				<input type="radio" name="iptype" value="0" onClick="iptype_change();" <?php if(empty($ipRs["Value"])) echo "checked"; ?>><? echo _("不分配");?>&nbsp;&nbsp;
				<input type="radio" name="iptype" value="1" onClick="iptype_change();" <?php if(!empty($ipRs["Value"])) echo "checked"; ?>><? echo _("系统分配");?> 
		  	<input type="radio" name="iptype" value="2" onClick="iptype_change();" <?php if(!empty($poolRs["Value"])) echo "checked"; ?>><? echo _("地址池分配");?></td>
	    </tr>
		  <tr id="ipaddressTR">
		    <td align="right" class="bg"><? echo _("I P 地址"); ?> ：</td>
		    <td align="left" class="bg"> 
				<input type="text" name="ipaddress" id="ipaddress" value="<?=$ipRs["Value"]?>">
				<input type="button" value="<?php echo _("分配IP")?>" onClick="ajaxGetIp('ajax/getip.php','projectID','projectID','ipaddress');">			</td>
		  </tr>
		  <tr id="poolnameTR"> 
		    <td align="right" class="bg"><? echo _("地址池")?>:</td> 
		    <td align="left" class="bg" id="poolnameSelectDIV"><?=poolnameSelect($pd['ID'],$rs["projectID"],$poolRs["Value"])?> </td>
			</tr>  
		 <tr> 
		    <td align="right" class="bg"><? echo _("固定在线时长")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="radio" name="status" value="enabled" <?php if($rs["status"]=="enabled") echo "checked"; ?>  onClick="status_change();"><? echo _("启用")?> 
				<input type="radio" name="status" value="disable" <?php if($rs["status"]=="disable" || $rs["status"]=='') echo "checked"; ?>  onClick="status_change();"><? echo _("禁用")?></td> 
		    </tr> 
			<tr id="statusTR" style="display:block"> 
		    <td align="right" class="bg"><? echo _("在线时长")?>:</td> 
		    <td align="left"  class="bg"><input type="text" name="onlinetime"  value="<?=$rs['onlinetime']?>"onBlur="this.className='input_out'"> <? echo _("分")?></td> 
		    </tr>  
		  <tr>
		    <td align="right" class="bg"><? echo _("MAC 地址"); ?> ：</td>
		    <td align="left" class="bg">
			<input type="text" name="MAC" value="<?=$rs["MAC"]?>">
			<input type="checkbox" name="macbind" value="1" <?php if($macRs["macbind"]==1) echo "checked"; ?> onClick="macbind_change();"><? echo _("是否绑定MAC地址");?>	</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("NAS 地址"); ?>： </td>
		    <td align="left" class="bg"><input type="text" name="NAS_IP" value="<?=$rs["NAS_IP"]?>">
			<input type="checkbox" name="nasbind" value="1" <?php if($macRs["nasbind"]==1) echo "checked"; ?> onClick="nasbind_change();"><? echo _("是否绑定NAS地址");?></td>
		  </tr>
		  <?php
		  if(in_array("update_vlan",$_SESSION["auth_permision"])){
		  ?> 
			<tr> 
			<td align="right" class="bg"><? echo _("VLAN 地址"); ?> ：</td> 
			<td align="left" class="bg"> 
				<input type="text" name="VLAN"  value="<?=$rs["VLAN"]?>" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="checkbox" name="vlanbind" value="1"<?php if($macRs["vlanbind"]==1) echo "checked"; ?> onClick="VLANbind_change()"> 
				<? echo _("是否绑定VLAN地址"); ?></td> 
			</tr> 
			<?php }else{
			?>		   
			<tr> 
			<td align="right" class="bg"><? echo _("VLAN 地址"); ?> ：</td> 
			<td align="left" class="bg"> 
				<input type="text" name="VLAN"   value="<?=$rs["VLAN"]?>" readonly="readonly" disable="disable"  >
                                <input type="hidden" name='VLAN' value="<?=$rs["VLAN"]?>">
				<input type="checkbox" name="vlanbind"value="1"<?php if($macRs["vlanbind"]==1) echo "checked"; ?> readonly="readonly" disabled="disabled" >
                                <input type="hidden" name='vlanbind' value="<?=$macRs["vlanbind"]?>">
				 <? echo _("是否绑定VLAN地址"); ?></td> 
			</tr>  
			<?php
			}
		  if(in_array("update_user_endtime",$_SESSION["auth_permision"])){  //原来的
                  //if($_SESSION["manager"] == "admin"){  //2014.04.10修改
		  ?>
                     <tr id="manager_tr" >  <!-----2014.07.16添加修改用户开始时间------>
		    <td align="right" class="bg"><?php echo _("产品开始时间"); ?> ：</td>
		    <td align="left" class="bg">
			<input type="hidden" value="<?=$_SESSION["manager"]?>"  name="manager" id="manager" >
			<input type="text" name="begindatetime" value="<?=$aRs["begindatetime"]?>"  onFocus="HS_setDate(this)">
			<?php echo _("此地方的时间为当前帐号正在使用中的产品开始时间"); ?>
			</td>
		    </tr>
		    <tr id="manager_tr" >
		    <td align="right" class="bg"><? echo _("产品结束时间"); ?> ：</td>
		    <td align="left" class="bg">
			<input type="hidden" value="<?=$_SESSION["manager"]?>"  name="manager" id="manager" >
			<input type="text" name="enddatetime" value="<?=$aRs["enddatetime"]?>"  onFocus="HS_setDate(this)">
			<?php echo _("此地方的结束时间为当前帐号正在使用中的产品结束时间"); ?>
			</td>
		    </tr>
		  <?php
		   }else{ 
		   ?>
                    <tr id="manager_tr" ><!-----2014.07.16添加修改用户开始时间------>
		    <td align="right" class="bg"><? echo _("产品开始时间"); ?> ：</td>
		    <td align="left" class="bg"> 
			  <input type="hidden" value="<?=$_SESSION["manager"]?>"  name="manager" id="manager" >
			  <input type="text" value="<?=$aRs["begindatetime"]?>" readonly="readonly" disabled="disabled">
			  <input type="hidden" name='begindatetime' value="<?=$aRs["begindatetime"]?>">
			<?php echo _("此地方的时间为当前帐号正在使用中的产品开始时间"); ?>
			  </td>
		    </tr>
		   <tr id="manager_tr" >
		    <td align="right" class="bg"><? echo _("产品结束时间"); ?> ：</td>
		    <td align="left" class="bg"> 
			  <input type="hidden" value="<?=$_SESSION["manager"]?>"  name="manager" id="manager" >
			  <input type="text" value="<?=$aRs["enddatetime"]?>" readonly="readonly" disabled="disabled">
			  <input type="hidden" name='enddatetime' value="<?=$aRs["enddatetime"]?>">
			<? echo _("此地方的结束时间为当前帐号正在使用中的产品结束时间"); ?>
			  </td>
		    </tr>
		   <?php
		   }
		   ?>  
		    
		    
		  <tr>
		    <td align="right" class="bg"><? echo _("用户等级"); ?> ：</td>
		    <td align="left" class="bg"><?php gradeSelected($rs["gradeID"]); ?></td>
		    </tr> 
		 <tr > 
		    <td align="right" class="bg" height="30px"><? echo _("用户备注"); ?> ：</td> 
                    <td align="left" class="bg" height="30px"><input type="text" name="remark"id="remark" style="width:500px;" value="<?=$rs['remark']?>"/></td> 
                
		 </tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="<?php echo _("提交"); ?>" onClick="javascript:return window.confirm( '<?php echo _("确认修改"); ?> ？ ');"></td>
	      </tr>
        </tbody>      
    </table>	
</form>
	</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14" ><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>

<script language="javascript">
window.onLoad=ajaxInput('ajax/project.php','areaID','areaID','projectSelectDIV');
<!--
//window.onLoad=product_type_change();
window.onLoad=iptype_change(); 
//window.onLoad=imanager_change(); 
//window.onLoad=ajaxInput('ajax_check.php','user_edit_account','account','accountTXT');
window.onLoad=status_change();
 
function imanager_change(){
  var man=document.getElementById('manager').value; 
  if(man=='admin'){
   document.getElementById("manager_tr").style.display="";  
  }else{
   document.getElementById("manager_tr").style.display="none"; 
  }

}
function macbind_change(){
	v=document.myform.macbind;
	if(v.checked==false){
		document.myform.MAC.value="";
	} 
}

function nasbind_change(){
	v=document.myform.nasbind;
	if(v.checked==false){
		document.myform.NAS_IP.value="";
	} 
}
function VLANbind_change(){ 
	v=document.myform.vlanbind; 
	if(v.checked==false){ 
		document.myform.VLAN.value="";  
	} 
}
function iptype_change(){
	v=document.myform.iptype;
  if( v[0].checked){  
		  document.getElementById("ipaddressTR").style.display="none"; 
		  document.getElementById("poolnameTR").style.display="none";  
	}else if( v[1].checked){  
		  document.getElementById("ipaddressTR").style.display=""; 
		  document.getElementById("poolnameTR").style.display="none"; 
	}else if( v[2].checked){ 
		  document.getElementById("ipaddressTR").style.display="none"; 
		  document.getElementById("poolnameTR").style.display=""; 
		  document.getElementById("radio_poolname").style.display="";
	}
}
//function product_type_change(){
//	v=document.myform.type.value;
//	if(v=="year"){
//		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>" <? echo _("年");?>"</font>";
//		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>" <? echo _("天");?>"</font>";
//		document.getElementById("unitpriceTR").style.display="none";
//		document.getElementById("cappingTR").style.display="none";
//	}else if(v=="month"){
//		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>" <? echo _("月");?>"</font>";
//		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>"<? echo _("天");?>"</font>";
//		document.getElementById("unitpriceTR").style.display="none";
//		document.getElementById("cappingTR").style.display="none";
//	}else if(v=="hour"){
//		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>"<? echo _("时");?>"</font>";
//		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>"<? echo _("元");?>"</font>";
//		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>"<? echo _("元/时");?>"</font>";
//		document.getElementById("unitpriceTR").style.display="";
//		document.getElementById("cappingTR").style.display="";
//	}else if(v=="flow"){
//		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>"<? echo _("M");?>"</font>";
//		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>"<? echo _("元");?>"</font>";
//		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>"<? echo _("元/M");?>"</font>";
//		document.getElementById("unitpriceTR").style.display="";
//		document.getElementById("cappingTR").style.display="";
//	}
//}
function status_change(){
     var x = document.myform.status[1].checked;1
     if(x==true){
	     document.getElementById("statusTR").style.display="";  
	 }else{
	     document.getElementById("statusTR").style.display="none";
	 } 
}

-->
</script>
<script src="js/ajax.js" type="text/javascript"></script>
</body>
</html>

