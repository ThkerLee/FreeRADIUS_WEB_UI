#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("产品管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
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
            WindowTitle:          '<b>产品管理</b>',
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
if($_POST){ 
	$name          =$_POST["name"];
	$type          =$_POST["type"]; 
	$price         =$_POST["price"];
	$penalty       =$_POST["penalty"];
	$unitprice     =$_POST["unitprice"];
	//$creditline    =$_POST["creditline"];
	$upbandwidth   =$_POST["upbandwidth"];
	$downbandwidth =$_POST["downbandwidth"];
	//$areaID        =$_POST["areaID"];
	$projectID     =$_POST["projectID"];
	$description   =$_POST["description"];  
	$begintime     =str_replace("：", ":",$_POST["begintime"]);
        $endtime       =str_replace("：", ":",$_POST["endtime"]); 
	$dayparting    =$_POST["dayparting"];//分时间段
	$starttime     =str_replace("：", ":",$_POST["starttime"]);//分时段开始时间
	$stoptime      =str_replace("：", ":",$_POST["stoptime"]);
	$partingupbandwidth=$_POST["partingupbandwidth"];
	$partingdownbandwidth=$_POST["partingdownbandwidth"];  
	$enddatetime   =$_POST["enddatetime"];
        $pool = $_POST["pool"];
        
	if($price == 0) $auto = $_POST["auto"];
	else $auto          = ""; 
	if($type=="flow"){
		$timetype  =$_POST["timetype"];//流量产品类型 包天 包月
		$limittype =$_POST["limittype"];//超流量计费 类型  1 限速 0 封顶  2停机
		$period    =$_POST["periodflow"];//流量产品  流量总值  
		$periodtime=$_POST["periodtime"];//流量产品  包流量时长
		$capping   =$_POST["cappingflow"];
		if($limittype=='1'){//限速
			  $limitupbandwidth  =$_POST["limitupbandwidth"];
	 		  $limitdownbandwidth=$_POST["limitdownbandwidth"];
		}else{
		    $limitupbandwidth  ="";
	 		  $limitdownbandwidth="";
		} 
	}else{ 
	    $timetype  ="";//非流量产品 
		  $period    =$_POST["periodOther"];//非流量产品	周期
		  $periodtime="";//非流量产品 不存在  流量总值
		  $limitupbandwidth  ="";
	   	$limitdownbandwidth=""; 
		if($type=="hour"){ 
			$capping   =$_POST["cappinghour"];
			$limittype ="";//非流量产品 不存在  默认为封顶消费 
		}if($type=="netbar_hour"){
		  $limittype =$_POST["chargingtype"] ;//非流量产品 不存在  默认为封顶消费 chargingtype			
			$capping   = 0; 
			$price     = 0;//价格
		}
	}  
	if(empty($projectID)){
		echo "<script language='javascript'>alert(\"" . _("项目不能为空") ."\");window.history.go(-1);</script>";
		exit;
	}
	 
	$sql=array(
		"name"=>$name,
		"type"=>$type,
		"period"=>$period,
		"price"=>$price,
		"unitprice"=>$unitprice,
		"capping"=>$capping,
		//"creditline"=>$creditline,
		"upbandwidth"=>$upbandwidth,
		"downbandwidth"=>$downbandwidth,
		"description"=>$description,
		"hide"=>0,
		"begintime"=>$begintime,
		"endtime"=>$endtime,
		"timetype"=>$timetype,
		"limittype"=>$limittype,
		"periodtime"=>$periodtime,
		"limitupbandwidth"=>$limitupbandwidth,
		"limitdownbandwidth"=>$limitdownbandwidth,
		"dayparting"=>$dayparting,
		"starttime"=>$starttime,
		"stoptime"=>$stoptime,
		"partingupbandwidth"=>$partingupbandwidth,
		"partingdownbandwidth"=>$partingdownbandwidth,
		"penalty"=>$penalty,
		"auto"=>$auto,
		"enddatetime"=>$enddatetime,
                "pool"  => $pool
	);   
	$db->insert_new("product",$sql);
	//$productID=$db->insert_id();//得到新增产品的ID地址
	$productRs = $db->select_one("ID","product","name='".$name."' order by ID desc limit 0,1");
	$productID = $productRs['ID'];
	$projectStr = implode(",",$projectID);
	$area      = $db->select_all("areaID,projectID","areaandproject","projectID in (".$projectStr.")");
   if(is_array($area)){ 
		 foreach($area as $key=>$areaVal){   
	 	   $db->query("insert into productandproject(productID,projectID,areaID) values('$productID',".$areaVal["projectID"].",".$areaVal["areaID"].");");	 
	   }
   } 
/*
	if(is_array($areaID)){//所属区域
		$areaIDStr = implode(",",$areaID);
		$projectRs = $db->select_all("projectID","areaandproject","areaID in (".$areaIDStr.")");
	  if(is_array($projectRs)){  
			 foreach($projectRs as $key=>$val){
		   	 $db->query("insert into productandproject(productID,projectID) values('$productID',".$val["projectID"].");");	
		   }
		}
	}
	*/
	echo "<script>alert('"._("添加成功")."');window.location.href='product.php';</script>";
	$_SESSION["auth_product"] .=",".$productID;	
} 
//查询项目集合
$areaResult=$db->select_all("distinct(a.ID),a.name","area as a,areaandproject as ap"," ap.projectID in(".$_SESSION["auth_project"].") and a.ID in (".$_SESSION["auth_area"].") and a.ID=ap.areaID"); 
if(in_array("freeProduct",$_SESSION['auth_permision'])) 
 $freeProduct= 'freeProduct'; 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("产品管理")?></font></td>
                        <td width="3%" height="35">
                            <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                        </td> <!------帮助--2014.06.07----->
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
        <td width="89%" class="f-bulue1"><? echo _("产品添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform"  onSubmit="return checkProductForm();">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("产品名称：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("产品类型： ")?></td>
		    <td align="left" class="bg">
				<select name="type" onChange="product_type_change();daypartingChange();">
					<option value="year"><? echo _("包年")?></option>
					<option value="month"><? echo _("包月")?></option>
					<option value="days"><? echo _("包天")?></option>
					<option value="hour"><? echo _("包时")?></option>
					<option value="flow"><? echo _("计流量")?></option>
				<!--
					<option value="netbar_hour"><? echo _("时长计费")?></option>
					-->
				</select> 
			</td> 
	      </tr>
		  <tr  id="typeText">
		    <td align="right" class="bg"><? echo _("计费类型：")?></td>
		    <td align="left" class="bg"><input type='radio' value='days' name='timetype' id='day' onClick="typeTimeChange();"> <? echo _("天")?> &nbsp;&nbsp;<input type='radio' value='months' name='timetype' id='months' checked onClick="typeTimeChange();" > <? echo _("月")?>  </td>
	      </tr>
		  <tr id='periodFlow'>
		    <td align="right" class="bg"><? echo _("计费周期：")?></td>
		    <td align="left" class="bg">
			<input type="text" id="periodFlow" size="10" name="periodflow" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <font color='#0000ff'>/</font> <input type="text" id="periodtime" size="10" name="periodtime" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <span id="periodFlowTXT"><font color='#0000ff'>M/<? echo _("月")?></font></span> <!-- 天/M --> 
			</td>
	      </tr>
		  <tr id='periodOther'>
		    <td align="right" class="bg"><? echo _("计费周期：")?></td>
		    <td align="left" class="bg"><input type="text" id="periodOther" name="periodOther" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <span id="periodTXT"></span></td>
	      </tr>
		  <tr id='pricrTR'>
		    <td align="right" class="bg"><? echo _("周期价格：")?></td>
		    <td align="left" class="bg">
			<input type="text" id="price" name="price" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';changAuto(this);"> <? echo _("元")?>
			<input type="hidden" id="freeProduct" name="freeProduct" value="<?=$freeProduct?>" >
			</td> 
	      </tr>
		   <tr id='autoRenew' style="display:none">
		    <td align="right" class="bg"><? echo _("自动续费：")?></td>
		    <td align="left" class="bg">
			<input  type="radio" id="auto" name="auto"value ="1"> <? echo _("启用")?>
			<input  type="radio" id="auto" name="auto"value ="0" checked="checked"> <? echo _("禁用")?></td>
	      </tr>
		    <tr id='penaltyTR'>
		    <td align="right" class="bg"><? echo _("违约金：")?></td>
		    <td align="left" class="bg"><input type="text" id="penalty" value="0" name="penalty" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <? echo _("元")?></td>
	      </tr>
		  <tr id="unitpriceTR">
		    <td align="right" class="bg"><? echo _("产品费率：")?></td>
		    <td align="left" class="bg"><input type="text" name="unitprice" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> <span id="unitpriceTXT"></span>&nbsp;</td>
	      </tr>
		  
              <tr id="cappingTR">
		    <td align="right" class="bg"><? echo _("封顶金额：")?></td>
		    <td align="left" class="bg"><input type="text" name="cappinghour" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> <? echo _("元")?></td>
		    </tr>
		<!--  <tr id="creditlineTR">
		    <td align="right" class="bg"><? echo _("信用额度：")?></td>
		    <td align="left" class="bg"><input type="text" name="creditline" value="0" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> <span id="creditlineTXT"></span></td>
	    </tr>--->
                  <!-----------2014.09.19添加地址池------------->
                  <tr>
		    <td align="right" class="bg">地址池名：</td>
                    <td align="left" class="bg"><input type="text" name="pool" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"><span style=" color: red;"> 请确认NAS设备支持该功能，否则请留空</span> </td>
		 </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("上传速率：")?></td>
		    <td align="left" class="bg"><input type="text" name="upbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit</td>
		 </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("下载速率：")?></td>
		    <td align="left" class="bg"><input type="text" name="downbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit </td>
		  </tr> 
		  <tr id='chargingType' >
		    <td align="right" class="bg"><? echo _("计费类型：")?></td>
		    <td align="left" class="bg">
		    	<!--<input type="radio" name="chargingtype" value="1" id="chargingtype_1" ><? echo _("结账下机")?>&nbsp;&nbsp;-->
		    	<input type="radio" name="chargingtype" value="2"id="chargingtype_2" checked  ><? echo _("自动下机")?></td>
			<!--&nbsp;&nbsp;<input type="radio" name="chargingtype" value="0"id="chargingtype_0"  ><? echo _("封顶自动下机")?>-->
		  </tr> 
		   <tr id='TRenddatetime' >
		    <td align="right" class="bg"><?php echo _("到期时间：")?></td>
		     <td align="left" class="bg" height="30px"><input type="text" name="enddatetime" onBlur="this.className='input_out'"   onFocus="HS_setDate(this)"> 
		    <?php echo _("产品到期时间为空则没有到期时间，该产品到期后不能开户和续费")?></td>  
		  </tr>
		   <tr id="notMoney"  >
		    <td align="right" style="background-color:#FFFFFF"><? echo _("不计费时间段")?></td>
			<td style="background-color:#FFFFFF" align="left"  ><input type="text" name="begintime" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> - <input type="text" name="endtime" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> 23:00-05:00 <? echo _("留空或相等表示全时段计费")?></td> 
		  </tr> 
		   
		  <tr id="billingType"  >
		    <td align="right" class="bg" ><? echo _("超流量计费类型")?></td> 
		    <td align="left" class="bg"><input type="radio" name="limittype" value="1" id="limit_1" onClick="limit();"><? echo _("限速")?>&nbsp;&nbsp;<input type="radio" name="limittype" value="0"id="limit_0"   onClick="limit();"><? echo _("封顶消费")?>&nbsp;&nbsp;<input type="radio" name="limittype" value="2"id="limit_2" checked onClick="limit();"><? echo _("停机")?></td>
		  </tr>
		   <tr id="limit_up"  >
		    <td align="right" class="bg" ><? echo _("限速上传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="limitupbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr>
		   <tr id="limit_down"  >
		    <td align="right" class="bg" ><? echo _("限速下传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="limitdownbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr>
		  <tr id="cappingFlow">
		    <td align="right" class="bg"><? echo _("封顶金额：")?></td>
		    <td align="left" class="bg"><input type="text" name="cappingflow" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> <? echo _("元")?></td>
		    </tr> 
		  <tr id="dayparting">
		    <td align="right" class="bg"><? echo _("分时段流量：")?></td>
		    <td align="left" class="bg"><input type="radio" name="dayparting" value="1" id="dayparting_yes" onClick="daypartingChange();"><? echo _("启用")?>&nbsp;&nbsp;<input type="radio" name="dayparting" value="0"id="dayparting_no" checked="checked"onClick="daypartingChange();"><? echo _("禁用")?> &nbsp;&nbsp;<? echo _("注：非蓝海卓越设备需踢用户下线")?></td>
		  </tr>
		  <tr id="daypartingTime">
		    <td align="right" class="bg"><? echo _("时间段：")?></td>
		    <td style="background-color:#FFFFFF" align="left"  ><input type="text" name="starttime" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> - <input type="text" name="stoptime" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> 23:00-05:00 <? echo _("留空或相等与禁用无异")?></td> 
		  </tr> 
		   <tr id="parting_up"  >
		    <td align="right" class="bg" ><? echo _("分时上传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="partingupbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr>
		   <tr id="parting_down"  >
		    <td align="right" class="bg" ><? echo _("分时下传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="partingdownbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr> 		  
			
		   <tr>		   
		    <td align="right" class="bg"><? echo _("所属区域：")?></td>
		    <td align="left" class="bg">
		 	<?php  
				$auth_areaArr=explode(",",$auth_area); 
				if(is_array($areaResult)){
                                    $i=0;
					foreach($areaResult as $key=>$rs){
						if(in_array($rs["ID"],$auth_areaArr)){
						 	$projectRs =$db->select_all("p.ID,p.name","areaandproject as ap,project as p","ap.areaID ='".$rs['ID']."' and p.ID=ap.projectID and ap.projectID in(".$_SESSION["auth_project"].") ");
						 	if(count($projectRs)>0){
                                                            $i++;
							  echo "<input type='checkbox' name='areaID[]' value='".$rs["ID"]."' id='ckall_".$i."' onclick=\"checkEvent('ck_".$i."','ckall_".$i."')\"> ".$rs["name"]."&nbsp;"; 
							  echo "&nbsp;&nbsp;&nbsp;&nbsp;<div style='background:#8DB2E3; width='100%'>所属项目&nbsp;&nbsp;&nbsp;&nbsp; ";
						    foreach($projectRs as $pval){
						       echo "<input type='checkbox' name='projectID[]' value='".$pval["ID"]."'class='ck_".$i."'> ".$pval["name"]." &nbsp;";
							  }
						     echo "<br>".'</div>';
							 } 
						    
						}
					}
				}
			?> 
			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("产品描述：")?></td>
		    <td align="left" class="bg">
				<input type="text" name="description" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out">
			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="<? echo _("提交")?>">			</td>
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

<script language="javascript">
<!--
window.onLoad=product_type_change();
window.onLoad=typeTimeChange(); 
window.onLoad=limit();
window.onLoad=daypartingChange();
function daypartingChange(){
   timeYes = document.getElementById("dayparting_yes").checked;
   timeNo = document.getElementById("dayparting_no").checked;
   if(timeYes==true){ 
    	document.getElementById("parting_up").style.display="";
		  document.getElementById("parting_down").style.display="";	
		  document.getElementById("daypartingTime").style.display=""; 
   }else if(timeNo==true){ 
      document.getElementById("parting_up").style.display="none";
		  document.getElementById("parting_down").style.display="none";
		  document.getElementById("daypartingTime").style.display="none"; 
   }    
}
function changeAllareaID(){
	v=document.myform.allareaID;
	m=document.getElementsByName("areaID[]");
	for(i=0;i<m.length;i++){
		m[i].checked=v.checked;
	}
}
function changeAllprojectID(){
	v=document.myform.allprojectID;
	m=document.getElementsByName("projectID[]");
	for(i=0;i<m.length;i++){
		m[i].checked=v.checked;
	}
}

function typeTimeChange(){
  day   = document.getElementById("day").checked;
  month = document.getElementById("months").checked; 
  if(day==true){ 
	document.getElementById("periodFlowTXT").innerHTML="<font color='#0000ff'><? echo _("M/天")?></font>";
  }else if(month==true){ 	
	document.getElementById("periodFlowTXT").innerHTML="<font color='#0000ff'><? echo _("M/月")?></font>";
  } 
}

function limit(){
    limitV       = document.getElementById("limit_1").checked;
    limitCapping = document.getElementById("limit_0").checked; 
    limitStop    = document.getElementById("limit_2").checked;   
  if(limitV==true){ 
  	document.getElementById("limit_down").style.display=""; 
  	document.getElementById("limit_up").style.display=""; 
	  document.getElementById("cappingFlow").style.display="none";  
  }else if(limitCapping==true){ 
  	document.getElementById("limit_up").style.display="none"; 
  	document.getElementById("limit_down").style.display="none"; 
	  document.getElementById("cappingFlow").style.display="";   
  }else if(limitStop==true){
    document.getElementById("limit_up").style.display="none"; 
   	document.getElementById("limit_down").style.display="none"; 
	  document.getElementById("cappingFlow").style.display="none";  
  } 
} 
function product_type_change(){
	v=document.myform.type.value; 
	
	if(v=="year"){ 
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("年")?></font>";
		//document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none"; 
		document.getElementById("typeText").style.display="none";
 		document.getElementById('notMoney').style.display="none"; 
 		document.getElementById('billingType').style.display="none";  
		document.getElementById('periodFlow').style.display="none";
		document.getElementById("dayparting").style.display="";
		document.getElementById("daypartingTime").style.display="";
		document.getElementById("parting_up").style.display="";
		document.getElementById("parting_down").style.display="";
		document.getElementById("limit_up").style.display="none"; 
                document.getElementById("limit_down").style.display="none"; 
                document.getElementById("cappingFlow").style.display="none";  
		document.getElementById("chargingType").style.display="none";
		document.getElementById("TRenddatetime").style.display=""; 
		document.getElementById("pricrTR").style.display="";  
		document.getElementById("penaltyTR").style.display="";
		//document.getElementById("creditlineTR").style.display="";      
	
		
	}else if(v=="month"){ 
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("月")?></font>";
		//document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";
		document.getElementById("typeText").style.display="none";
 		document.getElementById('notMoney').style.display="none"; 
 		document.getElementById('billingType').style.display="none"; 
		document.getElementById('periodFlow').style.display="none";
		document.getElementById("dayparting").style.display="";
		document.getElementById("daypartingTime").style.display="";
		document.getElementById("parting_up").style.display="";
		document.getElementById("parting_down").style.display="";
		document.getElementById("limit_up").style.display="none"; 
                document.getElementById("limit_down").style.display="none"; 
                document.getElementById("cappingFlow").style.display="none"; 
		document.getElementById("chargingType").style.display="none";  
		document.getElementById("TRenddatetime").style.display="";
		document.getElementById("pricrTR").style.display="";  
		document.getElementById("penaltyTR").style.display="";
		//document.getElementById("creditlineTR").style.display=""; 
	}else if(v=="days"){ 
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";
		//document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";
		document.getElementById("unitpriceTR").style.display="none";
		document.getElementById("cappingTR").style.display="none";	
		document.getElementById("typeText").style.display="none";
 		document.getElementById('notMoney').style.display="none"; 
 		document.getElementById('billingType').style.display="none"; 
		document.getElementById('periodFlow').style.display="none";
		document.getElementById("dayparting").style.display="";
		document.getElementById("daypartingTime").style.display="";
		document.getElementById("parting_up").style.display="";
		document.getElementById("parting_down").style.display="";
		document.getElementById("limit_up").style.display="none"; 
                document.getElementById("limit_down").style.display="none"; 
                document.getElementById("cappingFlow").style.display="none";  
		document.getElementById("chargingType").style.display="none";
		document.getElementById("TRenddatetime").style.display="";
		document.getElementById("pricrTR").style.display="";  
		document.getElementById("penaltyTR").style.display="";
		//document.getElementById("creditlineTR").style.display=""; 
	}else if(v=="hour"){ 
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("时/月")?></font>";
		//document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元")?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/时")?></font>";
		document.getElementById("unitpriceTR").style.display="";
		document.getElementById("cappingTR").style.display="none";//不显示封顶金额2014.03.22
		document.getElementById("typeText").style.display="none";
 		document.getElementById('notMoney').style.display="none"; 
 		document.getElementById('billingType').style.display="none"; 
		document.getElementById('periodFlow').style.display="none"; 
		document.getElementById("dayparting").style.display="";
		document.getElementById("daypartingTime").style.display="";
		document.getElementById("parting_up").style.display="";
		document.getElementById("parting_down").style.display="";
		document.getElementById("limit_up").style.display="none"; 
                document.getElementById("limit_down").style.display="none"; 
                document.getElementById("cappingFlow").style.display="none"; 
		document.getElementById("chargingType").style.display="none"; 
		document.getElementById("TRenddatetime").style.display="";
		document.getElementById("pricrTR").style.display="";  
		document.getElementById("penaltyTR").style.display="";
		//document.getElementById("creditlineTR").style.display="none"; 
	}else if(v=="flow"){  
                document.getElementById("typeText").style.display="";
 		document.getElementById('notMoney').style.display="none"; 
 		document.getElementById('billingType').style.display="none"; 
		document.getElementById('periodOther').style.display="none"; 
		document.getElementById("cappingTR").style.display="none"; 
		document.getElementById('periodFlow').style.display=""; 
		//document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>M/月</font>";
		//document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元")?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元")?>/M</font>";
		document.getElementById("unitpriceTR").style.display="none"; 
		//document.getElementById("creditlineTR").style.display="none"; 
		document.getElementById("dayparting").style.display="none";
		document.getElementById("daypartingTime").style.display="none";
		document.getElementById("parting_up").style.display="none";
		document.getElementById("parting_down").style.display="none"; 
		document.getElementById("chargingType").style.display="none";
		document.getElementById("TRenddatetime").style.display="";
		document.getElementById("pricrTR").style.display="";  
		document.getElementById("penaltyTR").style.display=""; 
	}else if(v=="netbar_hour"){  
		document.getElementById("periodOther").style.display=""; 
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("分")?></font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/时")?></font>";
		document.getElementById("price").value='0';	
		document.getElementById("penalty").value='0';  
		document.getElementById('periodOther').value="1"; 
		document.getElementById("chargingType").style.display="";
		document.getElementById("TRenddatetime").style.display=""; 
		document.getElementById("unitpriceTR").style.display=""; 
		//document.getElementById("creditlineTR").style.display="none"; 
		document.getElementById("typeText").style.display="none";
 		document.getElementById('notMoney').style.display="none"; 
 		document.getElementById('billingType').style.display="none"; 
		document.getElementById('periodFlow').style.display="none"; 
		document.getElementById("dayparting").style.display="none";
		document.getElementById("daypartingTime").style.display="none";
		document.getElementById("parting_up").style.display="none";
		document.getElementById("parting_down").style.display="";
		document.getElementById("pricrTR").style.display="none";  
		document.getElementById("penaltyTR").style.display="none";  
		document.getElementById("limit_up").style.display="none"; 
                document.getElementById("limit_down").style.display="none"; 
                document.getElementById("cappingFlow").style.display="none";  
	
	} 
}
function changAuto(val){  
	var v=document.myform.type.value; 
    if(val.value=="0" && v!=='netbar_hour') 
	    document.getElementById('autoRenew').style.display=''; 
    else              
	    document.getElementById('autoRenew').style.display='none';  
} 
-->
</script>
<script type="text/javascript">

function checkEvent(name,allCheckId)
{
  var allCk=document.getElementById(allCheckId);
  if(allCk.checked==true)
  checkAll(name);
  else
  checkAllNo(name);
  
}

//全选
function checkAll(className)//修改名称
{
  var names=document.getElementsByClassName(className);//修改名称
  var len=names.length;
  if(len>0)
  {
   var i=0;
   for(i=0;i<len;i++)
   names[i].checked=true;
    
  }
}

//全不选
function checkAllNo(name)
{
  var names=document.getElementsByClassName(name);
 var len=names.length;
 if(len>0)
  {
    var i=0;
    for(i=0;i<len;i++)
    names[i].checked=false;
  }
}




function HS_setDate(inputObj){
	var calenderObj = document.createElement("span");
	calenderObj.innerHTML = HS_calender(new Date());
	calenderObj.style.position = "absolute";
	calenderObj.targetObj = inputObj;
	inputObj.parentNode.insertBefore(calenderObj,inputObj.nextSibling);
}
</script>
<!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        产品管理-> <strong>添加产品</strong>
      </p>
      <ul>
          <li>可以对所销售的计费套餐产品进行添加操作。</li>
          <li>可建立不同的套餐类型。</li>
          <li>产品名称：自定义的，便于识别的产品名称。</li>
          <li>产品类型：可选包年，包月和计时，其中包年和包月均以月为单位计算，计时以小时为单位计算。</li>
          <li>计费周期：本产品从开始到结束，共允许使用多少时间。</li>
          <li>周期价格：根据产品不同，所定义的产品价格。</li>
          <li>上传速率：购买本产品用户所能使用的上传带宽，1M 则填写1024。</li>
          <li>上传速率：购买本产品用户所能使用的上传带宽，1M 则填写1024。</li>
          <li>所属项目：本产品所属的项目，在添加用户时，选择区域、项目后，只会出现该项目对应的产品。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

