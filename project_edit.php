#!/bin/php
<?php
include("inc/conn.php");  
include_once("./ros_static_ip.php");
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("项目修改")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<body>
<?php    
if($_POST){
	$ID          =$_POST["ID"];
	$name        =$_POST["name"];
	$beginip     =$_POST["beginip"];
	$endip       =$_POST["endip"];
	$device      =$_POST["device"];
	$accounts    =$_POST["accounts"];
	$description =$_POST["description"]; 
	$mtu         =($_POST["mtu"]=="")?"1480":$_POST["mtu"];
	$status      =$_POST["type"];
	$installcharge=$_POST["installcharge"];
	$rosipaddress =$_POST["rosip"];
	$rosusername  =$_POST["username"];
	$rospassword =$_POST["pwd"];
	$inf         =$_POST["inf"];
	$remind      =$_POST["remind"];
	$ippoolID    =$_POST["ippoolID"];
	$duestatus   =$_POST["duestatus"];
	$duepoolID   =$_POST["duepoolID"];
	$days        =$_POST["days"]; 
	$userprefix  =$_POST["userprefix"];
  $userstart   =$_POST["userstart"];
  $userpwd     =$_POST["userpwd"];
  $areaID      =$_POST["areaID"];
  if(empty($areaID)){
		echo "<script language='javascript'>alert(\"" . _("区域不能为空") ."\");window.history.go(-1);</script>";
		exit;
	}
   if($device=="sla-profile"){
	   	  $status=$_POST["confstatus"];
	   	  $days  =$_POST["noticetime"]; 
	   } 
   if($_POST['link']=='1'){//测试
	   if(linktest($rosipaddress, $rosusername, $rospassword)){
	     echo "<script>alert('". _("连接成功") ." ');</script>";
	   }else{
	     echo "<script>alert('". _("连接失败") ." ');</script>";
	   } 
	}else{  
		   $poolname="";
	  if(!empty($_POST["device"])){ 
	   	 $poolnameStr=trim($_POST["poolname"]); 
	   	 $poolnameArr=explode("\r\n",$poolnameStr);
	   	 if(is_array($poolnameArr)){
	   	  foreach($poolnameArr as $rs){
	   	  	$rs = trim($rs);
	   	  	if($rs!="") $poolname .=$rs."#";   
	   	  }
	   	 }
	   	     $poolname=substr($poolname ,0,-1); 
	   } 
	//查找出该项目下所有用户的ID 
	$resultUp=$db->select_all("distinct(r.userID)","userinfo as u,radreply as r","u.ID=r.userID AND u.projectID='".$ID."'");
	////查出rosIP表该项目ID
	//$pjID=$db->select_all("projectID","ip2ros"," ");
	if(is_array($resultUp)){
			foreach($resultUp as $rs){
				$userID=$rs["userID"];
				$rs2=$db->delete_new("radreply","userID=".$userID."  and Attribute !='Framed-IP-Address' and Attribute !='Framed-Pool'"); 
			}
		}
	 $sql=array(
			"name"=>$name,
			"beginip"=>$beginip,
			"endip"=>$endip,
			"device"=>$device,
			"accounts"=>$accounts,
			"description"=>$description,
			"mtu"=>$mtu,
			"installcharge"=>$installcharge , 
			"status"=>$status ,
			"remind"=>$remind,
		  "ippoolID"=>$ippoolID,
		  "days"=>$days ,
			"userprefix"=>$userprefix,
		  "userstart"=>$userstart,
		  "userpwd"=>$userpwd,
		  "confname"=>$_POST["confname"],
		  "poolname"=>$poolname,
		  "duestatus"=>$duestatus,  
	    "duepoolID"=>$duepoolID 
		);
	$db->update_new("project","ID='".$ID."'",$sql);  
	$result=$db->select_all("u.ID","userinfo as u,project as pro","u.projectID=pro.ID");
		//更改此产品下的技术参数/查询目前使用此产品的用户
    	 
	
	if(is_array($result)){ 
		foreach($result as $rs){
			$userID=$rs["ID"]; 
			$rs2 = $db->select_one("o.*","userattribute as atr,orderinfo as o,product as p","atr.orderID=o.ID and o.productID=p.ID and atr.userID='$userID'");
		  addUserParaminfo($userID,$rs2["productID"]);//更改参数 
		}
	}
	if($device=="mikrotik"){
		$rossql=array( 
			"rosipaddress"=>$rosipaddress,
			"rosusername"=>$rosusername,
			"rospassword"=>$rospassword,
			"inf"=>$inf 
		);//IP2ros 修改项目参数
		//查找是否有该条记录
 
	if($status=='enable'){//添加或修改
			$pj=$db->select_one("projectID","ip2ros","projectID='".$ID."'");
			if($pj){
			 $db->update_new("ip2ros","projectID='".$ID."'",$rossql); 
			}else{
			 $rossql["projectID"]=$ID;
			 $db->insert_new("ip2ros",$rossql); 
			 
			} 
		} 
	} 
	 $projectID = $ID;   
	  $db->delete_new("areaandproject","projectID='".$projectID."'"); 
		$db->query("insert into areaandproject(areaID,projectID) values('$areaID','$projectID');");	
		 $db->update_new("productandproject","projectID='".$projectID."'",array("areaID"=>$areaID)); ////////////////////////////2014.05.28修改：在修改项目时同时productandproject中的areaID/////////////////////////////////////
                 $db->update_new("userinfo","projectID='".$projectID."'",array("areaID"=>$areaID));////////////////////////////2014.05.28修改：在修改项目时同时userinfo中的areaID/////////////////////////////////////
	echo "<script>alert(' " .  _("修改成功") ." ');window.location.href='project.php';</script>";
  }
  
  
}
$ID=$_REQUEST["ID"];
$rs=$db->select_one("*","project","ID='".$ID."'"); 
$ros=$db->select_one('*','ip2ros','projectID="'.$ID.'"');
$poolnmae="";
$poolnameArr = explode("#",$rs["poolname"]); 
if(is_array($poolnameArr)){
  foreach($poolnameArr as $val){
    $poolname .= $val."\r\n";
  }	
}  
$areaResult         =$db->select_all("ID,name","area",""); 
$pRs=$db->select_all("areaID","areaandproject","projectID='".$ID."'");
if(is_array($pRs)){
	foreach($pRs as $vRs){
		$pRsArr[]=$vRs["areaID"];
	}
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("项目管理")?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("项目修改")?></td>
		    <td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
    <form action="?" method="post" name="myform" onSubmit="return checkProjectForm();">
      <input type="hidden" name="ID" value="<?=$ID?>">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("项目名称：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','projectName','name','nameTXT');" class="input_out" value="<?=$rs["name"]?>"><span id="nameTXT"></span>			</td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("开始 IP： ")?></td>
		    <td align="left" class="bg"><input type="text" id="beginip" name="beginip" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','beginip','beginip','beginipTXT');" value="<?=$rs["beginip"]?>" ><span id="beginipTXT"></span></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("结束 IP：")?></td>
		    <td align="left" class="bg"><input type="text" id="endip" name="endip" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','endip','endip','endipTXT');" value="<?=$rs["endip"]?>"><span id="endipTXT"></span></td>
	      </tr>
		   <tr>
		    <td align="right" class="bg"><? echo _("用户前缀：")?></td>
		    <td align="left" class="bg"><input type="text" id="userprefix" name="userprefix"value="<?=$rs['userprefix']?>" class="input_out" onFocus="this.className='input_on'"  ></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("开始ID：")?></td>
		    <td align="left" class="bg"><input type="text" id="userstart" name="userstart"value="<?=$rs['userstart']?>"   class="input_out" onFocus="this.className='input_on'" ></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("默认密码：")?></td>
		    <td align="left" class="bg"><input type="text" id="userpwd" name="userpwd"value="<?=$rs['userpwd']?>"   class="input_out" onFocus="this.className='input_on'" ></td>
	      </tr> 
		  <tr>
		    <td align="right" class="bg"><? echo _("选择设备：")?></td>
		    <td align="left" class="bg"><?php deviceSelected($rs["device"]); ?></td>
	    </tr>
	    <tr>
		    <td align="right" class="bg"><? echo _("所属区域：")?></td>
		    <td align="left" class="bg">
		    <?php   
				$auth_areaArr=explode(",",$auth_area); 
				if(is_array($areaResult)){
					foreach($areaResult as $key=>$val){
						if(in_array($val["ID"],$auth_areaArr)){
					  	echo "<input type='radio' name='areaID' ";
					    if(is_array($pRsArr)){ 
							  if(in_array($val["ID"],$pRsArr)) echo " checked ";
						  }
              echo "value='".$val["ID"]."'> ".$val["name"]." &nbsp;";
						}
					}
				}
			?>  
		    </td>
	    </tr>
		 <tr id="account" style="display:none">
		    <td align="right" class="bg"><? echo _("记账包发送时间：")?></td>
		    <td align="left" class="bg">
				<input name="accounts" id="accounts" type="text" value="<?php if($rs['accounts'] !="" )echo $rs['accounts']; else echo '120'; ?>" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> 秒
			</td> 
		 </tr> 
		  </tbody> 
		   <tbody id="vstatus" style="display:none">
	      <tr >
		    <td align="right" class="bg"><? echo _("固定IP")?></td>
		    <td align="left" class="bg">
			    <input name="type" type="hidden" value="<?=$rs['status']?>" >
				<input  type="radio" name="status"  value='enable' id="status_yes" onClick="showROS()" <? if($rs['status']=='enable') echo "checked=\"checked\" ";?>  readonly= "readonly" disabled="disabled"><? echo _("启用")?>
				<input  type="radio"name="status"  value='disable' id="status_no"<? if($rs['status']=='disable' || $rs['status']=='' ) echo "checked=\"checked\" ";?> onClick="showROS()" readonly= "readonly" disabled="disabled"><? echo _("禁用")?>
				&nbsp;&nbsp;<font color="red" ><? echo _("请确认ROS版本在3.x以上，3以上的才支持API")?></font><span id='setStatus'></span>
			</td>
		 </tr>
		</tbody> 
		<tbody id="confstatus" style="display:none">
	      <tr >
		    <td align="right" class="bg"><? echo _("通告状态")?></td>
		    <td align="left" class="bg">
				<input name="confstatus"  type="radio" value='enable' id="confstatus_yes" <? if($rs['status']=="enable"  || $_REQUEST['status']=="") echo "checked=\"checked\""; ?> onClick="showConfig()"><? echo _("启用")?>
				<input name="confstatus"  type="radio" value='disable' id="confstatus_no"<?php  if($rs['status']=="disable" ) echo "checked=\"checked\""; ?>  onClick="showConfig()"><? echo _("禁用")?>
				&nbsp;&nbsp;<? echo _("启用或禁用到期通告")?>
				<span id='setStatus'></span>
			</td>
		 </tr>
		</tbody> 
		<tbody id="config" style="display:none">
	   <tr>
		    <td align="right" class="bg"><? echo _("通告时间")?></td>
		    <td align="left" class="bg">
				<input name="noticetime"  type="text" value='<?=$rs["days"]?>'><? echo _("大于1的正整数")?></td>
		 </tr>
	   <tr>
		    <td align="right" class="bg"><? echo _("配置名称")?></td>
		    <td align="left" class="bg">
				<input name="confname"  type="text" value='<?=$rs["confname"]?>'><? echo _("与客户端阿尔卡特配置名称必须相同")?></td>
		 </tr>
		</tbody> 
		<tbody id="ROSIP" style="display:none">
	    <tr >
		    <td align="right" class="bg">ROSIP：</td>
		    <td align="left" class="bg">
				<input name="rosip" id="rosip" type="text" onFocus="this.className='input_on'"  class="input_out" value="<?=$ros['rosipaddress']?>" >
			</td>
		 </tr>
		 <tr >
		    <td align="right" class="bg"><? echo _("接口：")?></td>
		    <td align="left" class="bg">
				<input name="inf" id="inf" type="text" onFocus="this.className='input_on'"  class="input_out" value="<?=$ros['inf']?>"  >
			</td>
		 </tr>
		  <tr  >
		    <td align="right" class="bg"><? echo _("ROS用户名：")?></td>
		    <td align="left" class="bg"> 
				<input name="username" id="username" type="text"onFocus="this.className='input_on'"  value="<?=$ros['rosusername']?>"onBlur="this.className='input_out'" class="input_out" > 
			</td>
		 </tr>
		  <tr >
		    <td align="right" class="bg"><? echo _("ROS密码：")?></td>
		    <td align="left" class="bg">
				<input name="pwd" id="pwd" type="password"onFocus="this.className='input_on'"value="<?=$ros['rospassword']?>"  onBlur="this.className='input_out'" class="input_out" size="22" > 
			</td>
		 </tr>	
		  <tr >
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit"  value="<? echo _("连接测试")?>" name='test' id="test" onClick="testShow();"><span id="testTEXT"></span> </td>
		 </tr>		 
		 </tbody>	
		  <tbody id='t_remind' style=" display:none">     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("即将到期提醒：")?></td>
			<td width="87%" align="left" class="bg">
			<input type="radio"id="remind" name="remind"  value="enable"<? if($rs['remind']=='enable') echo "checked=\"checked\" ";?>    onClick="showROS();"><? echo _("开启")?>
			<input type="radio"id="remind" name="remind"  value="disable"  <? if($rs['remind']=='disable') echo "checked=\"checked\" ";?>  onClick="showROS();" ><? echo _("禁用")?></td>
		  </td>
		  </tr>
		  <tr id="t_pool">
			<td width="13%" align="right" class="bg"><? echo _("分配地址池：")?></td>
			<td width="87%" align="left" class="bg"><? ippoolSelect($rs['ippoolID']) ;?> &nbsp;</td>
		  </tr> 
		  <tr id="t_days">
			<td width="13%" align="right" class="bg"><? echo _("提前天数：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="days" name="days" onFocus="this.className='input_on'"  value="<?=$rs['days']?>"    class="input_out"> </td>
		  </tr> 
		 </tbody>   
     <tbody id='t_duestatus' style=" display:none">     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("到期分配地址：")?></td> 
	    <td width="87%" align="left" class="bg">
			<input  type="radio" name="duestatus"   value="enable"  <? if($rs['duestatus']=='enable') echo "checked=\"checked\" ";?>  onClick="showROS();"  ><? echo _("开启")?>
			<input  type="radio" name="duestatus"   value="disable"    <? if($rs['duestatus']=='disable' || $rs['remind']=='') echo "checked=\"checked\" ";?> onClick="showROS();"   ><? echo _("禁用")?>
			 </td>
		  </tr>
		  <tr id="t_duepool">
			<td width="13%" align="right" class="bg"><? echo _("分配地址池：")?></td>
			<td width="87%" align="left" class="bg"><? duepoolSelect($rs['duepoolID']);?>  &nbsp;</td>
		  </tr>  
		 </tbody>  	 
		  <tbody id="poolnameTR">
		  <tr>
		    <td align="right" class="bg"><? echo _("地址池名：")?></td>
		    <td align="left" class="bg">
		    	<textarea name="poolname" id="poolname" rows="5" cols="15" ><?=$poolname?></textarea>
		    	<font color="red" >该地址池名需和对应设备中的地址池名相匹配,多个地址池名间用换行分割</font> 
			  </td>
		  </tr> 
     </tbody> 
		  <tbody>
		  <tr>
		    <td align="right" class="bg"><? echo _("初装费用：")?></td>
		    <td align="left" class="bg">
				<input name="installcharge" id="installcharge" type="text" class="input_out"  onFocus="this.className='input_on'"   value="<?=$rs["installcharge"]?>" size="5">
			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("项目描述：")?></td>
		    <td align="left" class="bg"><input type="text" name="description" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["description"]?>"></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("MTU值：")?></td>
		    <td align="left" class="bg"><input type="text" name="mtu" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["mtu"]?>"></td>
	      </tr>
		</tbody>  
 
		<tbody>	  
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg" >
				<input type="submit" value="<? echo _("提交")?>"  onClick="disShow();">			</td>
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
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>

<script>
　window.onload=show();
　window.onload=getDevice();
  window.onload=showConfig();
 function showROS(){
  var x= document.myform.status[0].checked;
  var y= document.myform.remind[0].checked;
  var z= document.myform.duestatus[0].checked;
   if(x==true){//启用  
     document.getElementById("ROSIP").style.display='';
   }else{
     document.getElementById("ROSIP").style.display='none';
   }
   if(y==true){
    document.getElementById("t_days").style.display=''; 
    document.getElementById("t_pool").style.display=''; 
   }else{
   document.getElementById("t_days").style.display='none'; 
   document.getElementById("t_pool").style.display='none'; 
   } 
   if(z==true){
    document.getElementById("t_duepool").style.display='';  
   }else{
    document.getElementById("t_duepool").style.display='none';  
   }
 }
 function showConfig(){
  var x= document.myform.confstatus[0].checked;  
   if(x==true){//启用  
     document.getElementById("config").style.display='';
   }else{
     document.getElementById("config").style.display='none';
   } 
 }
function show(){
  var device = document.getElementById("device").value;
  var account= document.getElementById("account");
  var ROSIP  = document.getElementById("ROSIP");
  var status = document.getElementById("vstatus");
  var remind = document.getElementById("t_remind"); 
  var confstatus = document.getElementById("confstatus");
  var config = document.getElementById("config");
  var poolname = document.getElementById("poolnameTR");
  if(device !="8021X"){
     account.style.display="";  
  }else{
     account.style.display="none";
  }
 if(device=="mikrotik"){
    ROSIP.style.display="";  
	  status.style.display=""; 
	  remind.style.display=""; 
	  poolname.style.display="";
	  showROS();
  }else{
    ROSIP.style.display="none"; 
	  poolname.style.display="none"; 
  }
  if(device =="sla-profile"){//阿尔卡特
  	confstatus.style.display=""; 
  	config.style.display=""; 
  }else{  
  	confstatus.style.display="none"; 
  	config.style.display="none"; 
  } 
  if(device=="mikrotik" || device =="ma5200f" || device =="ZTE"|| device =="sla-profile" || device =="ericsson"|| device =="Panabit"){ //地址池2014.09.15 添加 device =="Panabit"
    poolname.style.display="none"; //2014.09.19隐藏地址池，将地址池放到产品中
  }else{
	  poolname.style.display="none";
  } 
}

function getDevice(){
  var device = document.getElementById("device").value;
  var account= document.getElementById("account");
  var ROSIP  = document.getElementById("ROSIP");
  var status = document.getElementById("vstatus");
  var remind = document.getElementById("t_remind");
  var duestatus= document.getElementById("t_duestatus");
  var confstatus = document.getElementById("confstatus");
  var config = document.getElementById("config");
  var poolname = document.getElementById("poolnameTR"); 
  if(device !="8021X"){
     account.style.display="";
  }else{
     account.style.display="none";
  }
  
	if(device=="mikrotik"){ 
		status.style.display="";  
		remind.style.display=""; 
		duestatus.style.display=""; 
		document.getElementById("setStatus").innerHTML= "<input name='ROS' type='hidden'  value='1'>";  
		showROS();
		poolname.style.display="";  
	}else{
		document.getElementById("setStatus").innerHTML= "<input name='ROS' type='hidden'  value='0'>"; 
		status.style.display="none";  
		remind.style.display="none";
		if(device =="ma5200f"){
			duestatus.style.display="";
			showROS();
		}else{
			duestatus.style.display="none";
		}
		ROSIP.style.display="none"; 
		poolname.style.display="none";  
	}
        
  if(device=="bhw_radius"){ 
		status.style.display="none";  
		remind.style.display="none"; 
		duestatus.style.display=""; 
		document.getElementById("setStatus").innerHTML= "<input name='bhw_radius' type='hidden'  value='1'>";  
		showROS();
		poolname.style.display="";  
	}     
  
  
  
  if(device =="sla-profile"){//阿尔卡特
  	confstatus.style.display=""; 
  	config.style.display=""; 
  }else{  
  	confstatus.style.display="none"; 
  	config.style.display="none"; 
  }
  if(device=="mikrotik" || device =="ma5200f" || device =="ZTE" || device =="sla-profile" || device =="ericsson"|| device =="Panabit"){ //地址池2014.09.15 添加 device =="Panabit"
    poolname.style.display="none"; //2014.09.19隐藏地址池，将地址池放到产品中
  }else{
	  poolname.style.display="none"; 
  }
}

function testShow(){
   document.getElementById("testTEXT").innerHTML="<input name='link' type='hidden'  value='1'>"; 
}

function disShow(){
  document.getElementById("testTEXT").innerHTML="<input name='link' type='hidden'  value='0'>"; 
}

function changeAllareaID(){
	v=document.myform.allareaID;
	m=document.getElementsByName("areaID[]");
	for(i=0;i<m.length;i++){
		m[i].checked=v.checked;
	}
}
</script>
</body>
</html>