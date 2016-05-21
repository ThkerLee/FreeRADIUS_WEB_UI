#!/bin/php
<?php require_once("inc/conn.php"); 
include_once("./ros_static_ip.php");
require_once("evn.php");
include_once("inc/ajax_js.php");
 ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("无标题文档")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
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
            WindowTitle:          '<b>项目管理</b>',
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
<?php   
$_REQUEST; 
if($_POST){ 
  $IP=$_POST['rosip'];
	$username=$_POST['username'];
	$pwd=$_POST['pwd']; 
	$areaID =$_POST["areaID"];
	if(empty($areaID)){
		echo "<script language='javascript'>alert(\"" . _("区域不能为空") ."\");window.history.go(-1);</script>";
		exit;
	}
	$projectID=$db->select_one("max(ID) as ID",'project','');
	$pID=(int)$projectID['ID']+1; 
	
    if($_POST['link']=='1'){//测试
	   if(linktest($IP, $username, $pwd)){
	     echo "<script>alert('".  _("连接成功")."');</script>";
	   }else{
	     echo "<script>alert('".  _("连接失败")."');</script>";
	   } 
	}else{
	   $inf=$_POST['inf'];  
	   if($_POST["device"]=="sla-profile"){
	   	  $_POST["status"]=$_POST["confstatus"];
	   	  $_POST["days"]  =$_POST["noticetime"]; 
	   }        
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
		 $sql=array(
		 "name"=>$_POST["name"],
		 "beginip"=>$_POST["beginip"],
		 "endip"=>$_POST["endip"],
		 "device"=>$_POST["device"],
		 "accounts"=>$_POST["accounts"],
		 "installcharge"=>$_POST["installcharge"],
		 "description"=>$_POST["description"], 
		 "mtu"=>($_POST["mtu"]=='')?'1480':$_POST["mtu"],
		 "status"=>$_POST["status"],    
		 "remind"=>$_POST["remind"],
		 "ippoolID"=>$_POST["ippoolID"],
		 "days"=>$_POST["days"] ,
		 "userprefix"=>$_POST["userprefix"],
		 "userstart"=>$_POST["userstart"],
		 "userpwd"=>$_POST["userpwd"],
		 "confname"=>$_POST["confname"],
		 "poolname"=>$poolname,
		 "duestatus"=>$_POST["duestatus"],
		 "duepoolID"=>$_POST["duepoolID"]
		 
		);  
		if($_POST["status"]=='enable'){
			$inlist=array(
			 "rosipaddress"=>$IP,
			 "rosusername"=>$username ,
			 "rospassword"=>$pwd,
			 "inf"=>$inf,
			 "projectID"=>$pID
			);
			//添加rosIP认证
			$db->insert_new("ip2ros", $inlist); 
		}
		$result=projectAddSave($sql);
		$projectRs = $db->select_one("ID","project","name='".$_POST["name"]."' order by ID desc limit 0,1");
                 $projectID = $projectRs['ID'];   
                $db->query("insert into areaandproject(areaID,projectID) values('$areaID','$projectID');");	
                //---------------------------------2014.03.27修改admin账号项目默认权限-------------------------------------
                if($_SESSION["manager"] != "admin"){
                $db->update_new("manager","manager_account='".$_SESSION["manager"]."'",array("manager_project"=>$_SESSION["auth_project"])); //更新管理员权限
                }
                    $re = $db->select_one("*","manager","manager_account='admin'");
                    $pj = $db->select_one("ID","project","0=0 order by ID desc limit 0,1");
                    if($re["manager_project"]==""){
                       $manager_project=$re["manager_project"].$pj["ID"];
                    }  else {
                         $manager_project=$re["manager_project"].",".$pj["ID"];
                    }
                   
                 $db->update_new("manager","manager_account='admin'",array("manager_project"=>$manager_project));//更新admin权限
                //-----------------------------------------------------------------------------------------------------
		echo "<script>alert('".$result."');window.location.href='project.php';</script>";
 
	}
    
}
$areaResult         =$db->select_all("ID,name","area","");
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("项目管理")?></font></td>
                        <td width="3%" height="35"><div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div></td> <!------帮助--2014.06.07----->
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
        <td width="89%" class="f-bulue1"><? echo _("项目添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform"  onSubmit="return checkProjectForm();">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("项目名称：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'"  value="<?=$_REQUEST['name']?>"  onBlur="this.className='input_out';ajaxInput('ajax_check.php','projectName','name','nameTXT');" class="input_out"><span id="nameTXT"></span>			</td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("开始 IP： ")?></td>
		    <td align="left" class="bg"><input type="text" id="beginip" name="beginip" value="<?=$_REQUEST['beginip']?>" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','beginip','beginip','beginipTXT');" ><span id="beginipTXT"></span></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("结束 IP：")?></td>
		    <td align="left" class="bg"><input type="text" id="endip" name="endip"value="<?=$_REQUEST['endip']?>"   class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','endip','endip','endipTXT');"><span id="endipTXT"></span></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("用户前缀：")?></td>
		    <td align="left" class="bg"><input type="text" id="userprefix" name="userprefix"value="<?=$_REQUEST['userprefix']?>" class="input_out" onFocus="this.className='input_on'"  ></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("开始ID：")?></td>
		    <td align="left" class="bg"><input type="text" id="userstart" name="userstart"value="<?=$_REQUEST['userstart']?>"   class="input_out" onFocus="this.className='input_on'" ></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("默认密码：")?></td>
		    <td align="left" class="bg"><input type="text" id="userpwd" name="userpwd"value="<?=$_REQUEST['userpwd']?>"   class="input_out" onFocus="this.className='input_on'" ></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("选择设备：")?></td>
		    <td align="left" class="bg"><?php if($_REQUEST['device']==''){deviceSelected();} else{ deviceSelected($_REQUEST['device']); } ?></td>
	    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("所属区域：")?></td>
		    <td align="left" class="bg"> 
			<?php  
				$auth_areaArr=explode(",",$auth_area); 
				if(is_array($areaResult)){
					foreach($areaResult as $key=>$rs){
						if(in_array($rs["ID"],$auth_areaArr)){
						echo "<input type='radio' name='areaID' value='".$rs["ID"]."'> ".$rs["name"]." &nbsp;";
						}
					}
				}
			?>  
		    </td>
	    </tr> 
      <tr id="account" style="display:none">
		    <td align="right" class="bg"><? echo _("记账包发送时间：")?></td>
		    <td align="left" class="bg">
				<input name="accounts" id="accounts" value="120"  type="text" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" >
			</td>
		 </tr> 
		 </tbody> 
		 <tbody id="vstatus" style="display:none">
	      <tr >
		    <td align="right" class="bg"><? echo _("固定IP")?></td>
		    <td align="left" class="bg">
				<input name="status"  type="radio" value='enable' id="status_yes" <? if($_REQUEST['status']=="enable") echo "checked=\"checked\""; ?> onClick="showROS()"><? echo _("启用")?>
				<input name="status"  type="radio" value='disable' id="status_no"<?php  if($_REQUEST['status']=="disable" || $_REQUEST['status']=="" ) echo "checked=\"checked\""; ?>  onClick="showROS()"><? echo _("禁用")?>
				&nbsp;&nbsp;<font color="red" ><? echo _("请确认ROS版本在3.x以上，3以上的才支持API")?></font>
				<span id='setStatus'></span>
			</td>
		 </tr>
		</tbody>
		 <tbody id="confstatus" style="display:none">
	      <tr>
		    <td align="right" class="bg"><? echo _("通告状态")?></td>
		    <td align="left" class="bg">
				<input name="confstatus"  type="radio" value='enable' id="confstatus_yes" <? if($_REQUEST['confstatus']=="enable") echo "checked=\"checked\""; ?> onClick="showConfig()"><? echo _("启用")?>
				<input name="confstatus"  type="radio" value='disable' id="confstatus_no"<?php  if($_REQUEST['confstatus']=="disable"  || $_REQUEST['confstatus']=="" ) echo "checked=\"checked\""; ?>  onClick="showConfig()"><? echo _("禁用")?>
				&nbsp;&nbsp;<? echo _("启用或禁用到期通告")?>
				<span id='setStatus'></span>
			</td>
		 </tr>
		</tbody> 
		<tbody id="config" style="display:none">
	   <tr>
		    <td align="right" class="bg"><? echo _("通告时间")?></td>
		    <td align="left" class="bg">
				<input name="noticetime"  type="text" value='<?=$_REQUEST["noticetime"]?>'><? echo _("大于1的正整数")?></td>
		 </tr>
	   <tr>
		    <td align="right" class="bg"><? echo _("配置名称")?></td>
		    <td align="left" class="bg">
				<input name="confname"  type="text" value='<?=$_REQUEST["confname"]?>'><? echo _("与客户端阿尔卡特配置名称必须相同")?></td>
		 </tr>
		</tbody> 
		<tbody id="ROSIP" style="display:none">
	    <tr >
		    <td align="right" class="bg">ROSIP：</td>
		    <td align="left" class="bg">
				<input name="rosip" id="rosip" type="text" value="<?=$_REQUEST['rosip']?>"   onFocus="this.className='input_on'"  class="input_out" onBlur="this.className='input_out';ajaxInput('ajax_check.php','nasip','nasip','nasipTXT');" ><span id="nasipTXT"></span> 
			</td>
		 </tr>
		 <tr >
		    <td align="right" class="bg"><? echo _("接口：")?></td>
		    <td align="left" class="bg">
				<input name="inf" id="inf" type="text"  value="<?=$_REQUEST['inf']?>" onFocus="this.className='input_on'"  class="input_out" onBlur="this.className='input_out';ajaxInput('ajax_check.php','nasip','nasip','nasipTXT');" ><span id="nasipTXT"></span> 
			</td>
		 </tr>
		  <tr  >
		    <td align="right" class="bg"><? echo _("ROS用户名：")?></td>
		    <td align="left" class="bg">
				<input name="username" id="username" value="<?=$_REQUEST['username']?>" type="text"onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" > 
			</td>
		 </tr>
		  <tr >
		    <td align="right" class="bg"><? echo _("ROS密码：")?></td>
		    <td align="left" class="bg">
				<input name="pwd" id="pwd" type="password"  value="<?=$_REQUEST['pwd']?>" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" size="22" > 
			</td>
		 </tr>	 
		 <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit"  value="<?php echo _("连接测试")?>" name='test' id="test" onClick="testShow();"><span id="testTEXT"></span> </td>
			</tr>
		 </tbody>
		 <tbody id='t_remind' style=" display:none">     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("即将到期提醒：")?></td> 
	    	<td width="87%" align="left" class="bg">
			<input  type="radio"id="remind" name="remind"   value="enable"  <? if($_REQUEST['remind']=='enable') echo "checked=\"checked\" ";?>  onClick="showROS();"  ><? echo _("开启")?>
			<input  type="radio"id="remind" name="remind"   value="disable"    <? if($_REQUEST['remind']=='disable' || $_REQUEST['remind']=='') echo "checked=\"checked\" ";?> onClick="showROS();"   ><? echo _("禁用")?>
			 </td>
		  </tr>
		  <tr id="t_pool">
			<td width="13%" align="right" class="bg"><? echo _("分配地址池：")?></td>
			<td width="87%" align="left" class="bg"><? ippoolSelect() ;?>  &nbsp;</td>
		  </tr>
		  <tr id="t_days">
			<td width="13%" align="right" class="bg"><? echo _("提前天数：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="days" name="days" onFocus="this.className='input_on'"  value="<?=$_REQUEST['days']?>"    class="input_out"> </td>
		  </tr>
		 </tbody>
	   <tbody id='t_duestatus' style=" display:none">
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("到期分配地址：")?></td>
			<td width="87%" align="left" class="bg">
			<input  type="radio" name="duestatus"   value="enable"  <? if($_REQUEST['duestatus']=='enable') echo "checked=\"checked\" ";?>  onClick="showROS();"  ><? echo _("开启")?>
			<input  type="radio" name="duestatus"   value="disable"    <? if($_REQUEST['duestatus']=='disable' || $_REQUEST['remind']=='') echo "checked=\"checked\" ";?> onClick="showROS();"   ><? echo _("禁用")?>
				
				</td>
			</tr>
			<tr id="t_duepool">
				<td width="13%" align="right" class="bg"><? echo _("分配地址池：")?></td>
				<td width="87%" align="left" class="bg">
					
					<? duepoolSelect();?>  &nbsp;
					
				</td>
			</tr>
		 </tbody>   
		 <tbody id="poolnameTR">
		  <tr>
		    <td align="right" class="bg"><? echo _("地址池名：")?></td>
		    <td align="left" class="bg">
		    	<textarea name="poolname" id="poolname" rows="5" cols="15" ></textarea>
		    	<font color="red" >该地址池名需和对应设备中的地址池名相匹配,多个地址池名间用换行分割</font>
			  </td>
			</tr> 
		</tbody>
		<tbody>
		  <tr>
		    <td align="right" class="bg"><? echo _("初装费用：")?></td>
		    <td align="left" class="bg">
				<input name="installcharge"  type="text" class="input_out"   value="<? if(isset($_REQUEST['installcharge']))echo $_REQUEST['installcharge']; else echo '0'; ?>"  onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','endip','endip','endipTXT');"   size="5">
			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("项目描述：")?></td>
		    <td align="left" class="bg"><input type="text" name="description"  value="<?=$_REQUEST['description']?>" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("MTU值：")?></td>
		    <td align="left" class="bg"><input  name="mtu" type="text"  value="<? if(isset($_REQUEST['mtu']))echo $_REQUEST['mtu']; else echo '1480';?>" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out'"  ></td>
	      </tr> 
        </tbody>   
		<tbody>	  
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit"  value="<? echo _("提交")?>"  onClick="disShow();">			</td>
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

<script type="text/javascript">
window.onload=showROS();  
window.onload=getDevice();
window.onload=testShow(); 
window.onload=showConfig() ;
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
   }if(x==false){ 
     document.getElementById("config").style.display='none';
   } 
 }
 function getDevice(){
	var device = document.getElementById("device").value;
	var account= document.getElementById("account");
	var accounts = document.getElementById("accounts");
	var ROSIP  = document.getElementById("ROSIP");
	var status = document.getElementById("vstatus");
	var remind = document.getElementById("t_remind");
	var duestatus= document.getElementById("t_duestatus");
	var confstatus = document.getElementById("confstatus");
	var config = document.getElementById("config");
	var poolname = document.getElementById("poolnameTR");
	//console.debug(device);
	if(device !="8021X"){
		account.style.display="";
	}else{
		account.style.display="none";
		accounts.value=120;
	}
	
	if(device=="mikrotik"){ 

		status.style.display=""; 
		remind.style.display="";
		duestatus.style.display="";
		document.getElementById("setStatus").innerHTML= "<input name='ROS' type='hidden'  value='1'>"; 
		poolname.style.display="";
		showROS();
	}else{
		document.getElementById("setStatus").innerHTML= "<input name='ROS' type='hidden'  value='0'>"; 
		status.style.display="none";
		remind.style.display="none";
		if(device == "ma5200f"){
			duestatus.style.display="";
		}else{
			duestatus.style.display="none";
		}
	  ROSIP.style.display="none"; 
	  poolname.style.display="none";
  }
  
 	if(device=="bhw_radius"){ 
            //alert("bhw_radius");
		status.style.display="none"; 
		remind.style.display="none";
		duestatus.style.display="";
		document.getElementById("setStatus").innerHTML= "<input name='bhw_radius' type='hidden'  value='1'>"; 
		poolname.style.display="";
		showROS();
	}
  
  
  if(device =="sla-profile"){//阿尔卡特
  	confstatus.style.display=""; 
  	//config.style.display=""; 
  }else{  
  	confstatus.style.display="none"; 
  	config.style.display="none"; 
  }
  if(device=="mikrotik" || device =="ma5200f" || device =="ZTE"|| device =="sla-profile" ||device =="ericsson" || device =="Panabit"){  //地址池2014.09.15 添加 device =="Panabit"
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
<!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        项目管理-> <strong>添加项目</strong>
      </p>
      <ul>
          <li>项目名称：需添加的项目的名称，可以采用便于识别的中文、英文及字母组合。</li>
          <li>开始IP、结束IP：分配给该项目用户的IP地址池，在建立用户时，系统会自动从此地址池中进行地址的查找并分配相应的IP给用户。</li>
          <li>用户前缀：在设定自动生成用户时，使用的用户前缀。</li>
          <li>开始 ID：在设定自动生成用户时，用户名的开始 ID。</li>
          <li>默认密码：在自动生成用户时，用户的默认密码。</li>
          <li>选择设备：选择项目所使用的 NAS 设备。</li>
          <li>所属区域：把添加的项目归属到所选区域中。</li>
          <li>记帐包发送时间：指定 BRAS 设备发送记帐包时间，通常不超过300，不低于60。</li>
          <li>初装费用：该项目如果有初装费用，请在此填写，在开户时进行选择。</li>
          <li>MTU：分配给用户的 MTU 值，默认为 1480，请谨慎修改，否则有可能导致网页无法打开或打开缓慢。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

