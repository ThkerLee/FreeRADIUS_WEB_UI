#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
include_once("inc/ajax_js.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("产品管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
</head>
<body>
<?php 
$ID=$_REQUEST["ID"];
if($_POST){
	$name          =$_POST["name"];
	$unitprice     =$_POST["unitprice"];
	//$capping       =$_POST["capping"];
	//$creditline    =$_POST["creditline"];
	$price         =$_POST["price"];
	$freeProduct   =$_POST["freeProduct"];
	$penalty       =$_POST["penalty"];
	$upbandwidth   =$_POST["upbandwidth"];
	$downbandwidth =$_POST["downbandwidth"];
	//$areaID        =$_POST["areaID"];
	$projectID     =$_POST["projectID"];
	$description   =$_POST["description"]; 
	$begintime     =str_replace("：", ":",$_POST["begintime"]);
	$endtime       =str_replace("：", ":",$_POST["endtime"]);
	$enddatetime   =isset($_POST["enddatetime"])? $_POST["enddatetime"] :"";
	$limitupbandwidth  =$_POST["limitupbandwidth"];
	$limitdownbandwidth=$_POST["limitdownbandwidth"];
	$dayparting    =$_POST["dayparting"];
	$starttime     =str_replace("：", ":",$_POST["starttime"]); 
	$stoptime      =str_replace("：", ":",$_POST["stoptime"]); 
	$partingupbandwidth  =$_POST["partingupbandwidth"];
	$partingdownbandwidth=$_POST["partingdownbandwidth"];
        $pool = $_POST["pool"];
	if($price == 0) $auto = $_POST["auto"];
	else $auto          = "";
	if($_POST["type"]=="flow"){
		$capping   =$_POST["cappingflow"]; 
		//$creditline=0;
	}else{
		$capping   =$_POST["capping"];
		//$creditline=$_POST['creditline'];
	} 
	if(empty($projectID)){
		echo "<script language='javascript'>alert(\"" . _("项目不能为空") ."\");window.history.go(-1);</script>";
		exit;
	}
	$sql=array(
		"name"=>$name,
		"unitprice"=>$unitprice,
		"capping"=>$capping, 
		"price"=>$price,   
		//"creditline"=>$creditline,
		"upbandwidth"=>$upbandwidth,
		"downbandwidth"=>$downbandwidth,
		"description"=>$description,
		"begintime"=>$begintime,
		"endtime"=>$endtime,
		"limitupbandwidth"=>$limitupbandwidth,
		"limitdownbandwidth"=>$limitdownbandwidth,
		"dayparting"=>$dayparting,
		"starttime"=>$starttime,
		"stoptime"=>$stoptime,
		"partingupbandwidth"=>$partingupbandwidth,
		//"creditline"=>$creditline,
		"partingdownbandwidth"=>$partingdownbandwidth,
		"penalty"=>$penalty,
		"auto"=>$auto,
		"enddatetime"=>$enddatetime,
               "pool" => $pool
	);	
	 
	 if($freeProduct=='' && $price==0){
	    echo "<script>alert('"._("没有更改产品为免费产品的权限")."');window.location.href='product_edit.php?ID=".$ID."';</script>";
	    exit; 
	 }
	 
	//更改此产品下的技术参数/查询目前使用此产品的用户
	/*$resultUp=$db->select_all("u.userID as userID","userattribute as u,orderinfo as o","u.orderID=o.ID and o.productID='".$ID."'");
	
 if(is_array($resultUp)){
		foreach($resultUp as $rs){
			$userID=$rs["userID"];
			$rs2=$db->delete_new("radreply","userID=".$userID." and Attribute !='Framed-IP-Address' and Attribute !='Framed-Pool' ");//删除用户
		}
	} */
	$rs2=mysql_query("delete from radreply where Attribute !='Framed-IP-Address' and userID in(select userID from orderinfo where productID='".$ID."')");

	$db->update_new("product","ID='".$ID."'",$sql);
	$db->delete_new("productandproject","productID='".$ID."'"); 
	if(is_array($projectID)){
		  $projectStr = implode(",",$projectID);
		foreach($projectID as $key=>$projectVal){
	   	$projectRs = $db->select_all("distinct(projectID),areaID","areaandproject","projectID in (".$projectStr.") ");
	    if(is_array($projectRs)){
	       foreach($projectRs as $key=>$val){
	       	//判断该数据是否存在，不存在添加
	       	$select_old = $db->select_count("productandproject","productID=".$ID." and projectID=".$val["projectID"]." and areaID=".$val["areaID"]);
	       	if($select_old<=0){
	 	       $db->query("insert into productandproject(productID,projectID,areaID) values('$ID',".$val["projectID"].",".$val["areaID"].");");
	 	     }	 
	       } 
	    }  
	  } 
  }  
	/*
	
	if(is_array($areaID)){  
		$areaStr = implode(",",$areaID);
	 	$projectRs = $db->select_all("distinct(projectID)","areaandproject","areaID in (".$areaStr.") ");
	 if(is_array($projectRs)){
	   foreach($projectRs as $key=>$val){
	 	  $db->query("insert into productandproject(productID,projectID,areaID) values('$ID',".$val["projectID"].","..");");	
	   } 
	 }
	  
	}
	*/
	
	//更改此产品下的技术参数/查询目前使用此产品的用户
	$user=$db->select_all("u.userID as userID","userattribute as u,orderinfo as o","u.orderID=o.ID and o.productID='".$ID."'");
	if($user){
            if(!empty($pool)){ //如果地址池不为空，将该产品下的用的添加地址池参数
                foreach($user as $key=>$userRs){
                addUserIpaddress($userRs["userID"],2,"",$pool);//修改地址池参数
                     addUserParaminfo($userRs["userID"],$ID);//更改参数 
                }
            }  else {
           	foreach($user as $key=>$userRs){
              //  addUserIpaddress($userRs["userID"],0,"",$pool);//修改地址池参数 
              $db->delete_new("radreply","userID=".$userRs["userID"]." and Attribute='Framed-Pool'");
			 addUserParaminfo($userRs["userID"],$ID);//更改参数 
		} 
            }

	}  
 	echo "<script>alert('"._("修改成功")."');window.location.href='product.php';</script>";
}

//查询项目集合
$areaResult=$db->select_all("distinct(a.ID),a.name","area as a,areaandproject as ap","a.ID in (".$_SESSION["auth_area"].") and ap.projectID in(".$_SESSION["auth_project"].")  and a.ID=ap.areaID");
$rs=$db->select_one("*","product","ID='".$ID."'");
$pRs=$db->select_all("pj.projectID,ap.areaID","productandproject as pj ,areaandproject as ap","pj.productID='".$ID."' and pj.projectID=ap.projectID"); 
if(is_array($pRs)){
	foreach($pRs as $vRs){
		$pRsArr[]=$vRs["areaID"];
		$pjRsArr[]=$vRs["projectID"];
	}
}
$pRsArr = array_unique($pRsArr); 
$pjRsArr = array_unique($pjRsArr);  

if(in_array("freeProduct",$_SESSION['auth_permision'])) 
$freeProduct= 'freeProduct';
//echo $ferrProduct."<hr>";
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("产品管理")?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("产品修改")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform"  onSubmit="return checkProductForm();">
<input type="hidden" name="ID" value="<?=$ID?>">
<input type="hidden" name="type" value="<?=$rs["type"]?>"> 
<input type="hidden" id="freeProduct" name="freeProduct" value="<?=$freeProduct?>" >
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("产品名称：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["name"]?>"></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("产品类型： ")?></td>
		    <td align="left" class="bg">
				<select name="type" onChange="product_type_change();" id='type' disabled="disabled">  
				    <option value="year" <?php if($rs["type"]=="year") echo "selected";?>><? echo _("包年")?></option>
					<option value="month" <?php if($rs["type"]=="month") echo "selected";?>><? echo _("包月")?></option>
					<option value="days" <?php if($rs["type"]=="days") echo "selected";?>><? echo _("包天")?></option> 
					<option value="hour" <?php if($rs["type"]=="hour") echo "selected";?>><? echo _("包时")?></option>
					<option value="flow" <?php if($rs["type"]=="flow") echo "selected";?>><? echo _("计流量")?></option>
					<option value="netbar_hour" <?php if($rs["type"]=="netbar_hour") echo "selected";?>><? echo _("时长计费")?></option>
				</select>
			</td>
			 
	<?php 
	  if($rs['type']=='flow'){ //包流量 计费类型和周期
	 ?>
	   </tr>
		    <tr  id="typeText">
		    <td align="right" class="bg"><? echo _("计费类型：")?></td>
		    <td align="left" class="bg"><input type='radio' value='days' name='timetype' id='day'<?php if($rs["timetype"]=="days"){ echo "  checked"; }?>   disabled="disabled" readonly="readonly" > <? echo _("天")?> &nbsp;&nbsp;<input type='radio' value='months' name='timetype' id='months' <? if($rs["timetype"]=="months"){ echo "checked"; }  ?>  disabled="disabled" readonly="readonly"> <? echo _("月")?>  </td>
	      </tr>
		  <tr id='periodFlow'>
		    <td align="right" class="bg"><? echo _("计费周期：")?></td>
		    <td align="left" class="bg">
			<input type="text" id="periodFlow" size="10" name="periodflow" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';" value="<?=$rs['period']?>" disabled="disabled" readonly="readonly"> <font color='#0000ff'>/</font> <input type="text" id="periodtime" size="10" name="periodtime"   value="<?=$rs['periodtime']?>"  disabled="disabled" readonly="readonly" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <span id="periodFlowTXT"><font color='#0000ff'>M/<? if($rs["timetype"]=="days") echo _("天");else _("月"); ?></font></span> <!-- 天/M --> 
			</td>
	      </tr>
	 <?php
	  }else{
	    if($rs['type']=='netbar_hour'){
	 ?>
	     <tr>
		    <td align="right" class="bg"><? echo _("计费周期：")?></td>
		    <td align="left" class="bg"><input type="text" id="period" name="period" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';" value="<?=$rs["period"]?>"> <span id="periodTXT"></span></td>
	  </tr>
	 <?php
	    }else{
	?> 	<!-- 其他 计费类型和周期-->
		 <tr>
		    <td align="right" class="bg"><? echo _("计费周期：")?></td>
		    <td align="left" class="bg"><input type="text" id="period" name="period" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';" value="<?=$rs["period"]?>" disabled="disabled" readonly="readonly"> <span id="periodTXT"></span></td>
	      </tr>
	<?php
	   }
	  }
	  if($rs['type']!='netbar_hour'){
	?>   <tr>
		    <td align="right" class="bg"><? echo _("周期价格：")?></td>
		    <td align="left" class="bg">
			<input type="text" id="price" name="price" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';changAuto();"   onClick=""value="<?=$rs["price"]?>" > <? echo _("元")?>&nbsp;&nbsp;<font color="#FF0000">  <? echo _("注：修改周期价格，新建用户才生效")?></font>
			</td>  
	     </tr>
		<? }?> 
		  <tr id='autoRenew' style="display:none">
		    <td align="right" class="bg"><? echo _("自动续费：")?></td>
		    <td align="left" class="bg">
			<input  type="radio" id="auto" name="auto"value ="1" <? if($rs['auto']==1) echo "checked=\"checked\"";?>> <? echo _("启用")?>
			<input  type="radio" id="auto" name="auto"value ="0" <? if($rs['auto']==0) echo "checked=\"checked\"";?>> <? echo _("禁用")?></td>
	      </tr>
	<?php 
	  if($rs['type']!='netbar_hour'){
	?> 
		 <tr>
	
		    <td align="right" class="bg"><? echo _("违约金：")?></td>
		    <td align="left" class="bg"><input type="text" id="penalty" name="penalty"  value="<?=$rs["penalty"]?>"class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> <? echo _("元")?></td>
	      </tr>
	<?php 
	  }
	?>
		  <tr id="unitpriceTR">
		    <td align="right" class="bg"><? echo _("产品费率：")?></td>
		    <td align="left" class="bg"><input type="unitprice" name="unitprice" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["unitprice"]?>"> <span id="unitpriceTXT"></span> </td>
	      </tr> 
		  <?php if($rs['type']=='hour'){
		  ?>
		  <tr id="cappingTR">
		    <td align="right" class="bg"><? echo _("封顶金额：")?></td>
		    <td align="left" class="bg"><input type="capping" name="capping" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["capping"]?>"> <? echo _("元")?></td>
		    </tr>
		  <?php
		  }?>
                    
		  <?php if($rs['type']!='flow' && $rs['type']!='netbar_hour'  && $rs['type']!='hour' ){
		  ?>
		  <tr id="creditlineTR">
		    <td align="right" class="bg"><? echo _("信用额度：")?></td>
		    <td align="left" class="bg"><input type="text" name="creditline" disabled="disabled" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["creditline"]?>"> <span id="creditlineTXT"></span></td>
                   </tr>
		  <?php 
		  }
		  ?>
                   <!---------------------2014.07.20添加截止时间-------------------------->
                   <tr id='TRenddatetime' >
		    <td align="right" class="bg"><? echo _("到期时间：")?></td>
		     <td align="left" class="bg" height="30px"><input type="text" name="enddatetime" onBlur="this.className='input_out'" value="<?=$rs["enddatetime"]?>"  onFocus="HS_setDate(this)"> 
		    <? echo _("产品到期时间为空则没有到期时间，该产品到期后不能开户和续费")?></td>  
                     </tr>
                   <!---------------------2014.09.19添加地址池-------------------------->	
                    <tr>
		    <td align="right" class="bg">地址池名：</td>
		    <td align="left" class="bg"><input type="text" name="pool" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["pool"]?>"> <span style=" color: red;"> 请确认NAS设备支持该功能，否则请留空</span></td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("上传速率：")?></td>
		    <td align="left" class="bg"><input type="text" name="upbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["upbandwidth"]?>"> kbit</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("下载速率：")?></td>
		    <td align="left" class="bg"><input type="text" name="downbandwidth" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["downbandwidth"]?>"> kbit </td>
		    </tr>

     <?php if($rs['type']=='flow'){
	 ?>
	     <tr id="notMoney"  >
		    <td align="right" style="background-color:#FFFFFF"><? echo _("不计费时间段")?></td> 
		    <td align="left" class="bg"><input type="text" name="begintime" value="<?=$rs['begintime']?>" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> - <input type="text" name="endtime"  value="<?=$rs['endtime']?>"  onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out">  23:00-05:00 <? echo _("留空或相等表示全时段计费")?></td>
		  </tr> 
		  <tr id="billingType"  >
		    <td align="right" class="bg" ><? echo _("超流量计费类型")?></td> 
		    <td align="left" class="bg"><input type="radio" name="limittype"  value="1"<? if($rs['limittype']=='1'){ echo "checked";}?>  disabled="disabled" readonly="readonly"  ><? echo _("限速")?>&nbsp;&nbsp;<input type="radio" name="limittype" value="0" <? if($rs['limittype']=='0'){ echo "checked ";}?> disabled="disabled" readonly="readonly" ><? echo _("封顶消费")?>&nbsp;&nbsp;<input type="radio" name="limittype" value="2" <? if($rs['limittype']=='2'){ echo "checked ";}?> disabled="disabled" readonly="readonly" ><? echo _("停机")?></td>
		  </tr>
		  <? if($rs['limittype']=='1'){
		  ?>
		   <tr id="limit_up"  >
		    <td align="right" class="bg" ><? echo _("限速上传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="limitupbandwidth" onFocus="this.className='input_on'" value="<?=$rs['limitupbandwidth']?>" onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr>
		   <tr id="limit_down"  >
		    <td align="right" class="bg" ><? echo _("限速下传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="limitdownbandwidth" onFocus="this.className='input_on'"value="<?=$rs['limitdownbandwidth']?>"  onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr> 
		  <?php
		  }if($rs['limittype']=='0'){
		  ?>
		  <tr id="cappingFlow">
		    <td align="right" class="bg"><? echo _("封顶金额：")?></td>
		    <td align="left" class="bg"><input type="text" name="cappingflow" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["capping"]?>"> <? echo _("元")?></td>
		    </tr>
		  
		  <?php
		  }
		  ?>
		  
	 <?php	 
	 }if($rs['type']!='flow' &&  $rs['type']!='netbar_hour'){ 
	 ?>    <tr id="dayparting">
		    <td align="right" class="bg"><?php echo _("分时段流量：")?></td>
		    <td align="left" class="bg">
			<input type="radio" name="dayparting" value="1" <?php if($rs['dayparting']==1) echo "checked";?> id="dayparting_yes" onClick="daypartingChange();"><? echo _("启用")?>&nbsp;&nbsp;
			<input type="radio" name="dayparting" value="0" <?php if($rs['dayparting']==0 || $rs['dayparting']=="" ) echo "checked";?> id="dayparting_no" onClick="daypartingChange();"><? echo _("禁用")?>&nbsp;&nbsp; <? echo _("注：非蓝海卓越设备需踢用户下线")?></td>
		  </tr>
	      <tr id="daypartingTime">
		    <td align="right" class="bg"><? echo _("时间段：")?></td>
		    <td style="background-color:#FFFFFF" align="left"  ><input type="text" name="starttime" onFocus="this.className='input_on'" value="<?=$rs['starttime']?>" onBlur="this.className='input_out'" class="input_out"> - <input type="text" name="stoptime" onFocus="this.className='input_on'" value="<?=$rs['stoptime']?>" onBlur="this.className='input_out'" class="input_out"> 23:00-05:00 <? echo _("留空或相等与禁用无异")?></td> 
		  </tr> 
		   <tr id="parting_up"  >
		    <td align="right" class="bg" ><? echo _("分时上传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="partingupbandwidth"  value="<?=$rs['partingupbandwidth']?>" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr>
		   <tr id="parting_down"  >
		    <td align="right" class="bg" ><? echo _("分时下传速率：")?></td> 
		    <td align="left" class="bg"><input type="text" name="partingdownbandwidth" onFocus="this.className='input_on'" value="<?=$rs['partingdownbandwidth']?>"onBlur="this.className='input_out'" class="input_out"> kbit</td>
		  </tr> 	
	 <?php
	  }else if($rs['type']=='netbar_hour'){
	  ?>
	 </tr> 
		  <tr id='chargingType' >
		    <td align="right" class="bg"><? echo _("计费类型：")?></td>
		    <td align="left" class="bg">
		    	<!--<input type="radio" name="chargingtype" value="1"<? if($rs['limittype']=='1'){ echo "checked";}?>  disabled="disabled" readonly="readonly"   id="chargingtype_1" ><? echo _("结账下机")?>&nbsp;&nbsp;
		    	-->
		    	<input type="radio" name="chargingtype" value="2"id="chargingtype_2"<? if($rs['limittype']=='2'){ echo "checked";}?>  disabled="disabled" readonly="readonly"  ><? echo _("自动下机")?>
	<!--<input type="radio" name="chargingtype" value="0"<? if($rs['limittype']=='0'){ echo "checked";}?>  disabled="disabled" readonly="readonly"  id="chargingtype_0"  ><? echo _("封顶自动下机")?>&nbsp;&nbsp;--></td>
	</tr>
	 </tr> 
		   <tr id='TRenddatetime' >
		    <td align="right" class="bg"><? echo _("截止时间：")?></td>
		     <td align="left" class="bg" height="30px"><input type="text" name="enddatetime" onBlur="this.className='input_out'" value="<?=$rs["enddatetime"]?>"  onFocus="HS_setDate(this)"> 
		    <? echo _("截止时间为空即无截止时间")?></td>  
                     </tr>
	 <?php
	  }
	 ?>
	 
		  <tr>
		    <td align="right" class="bg"><? echo _("所属区域：")?></td>
		    <td align="left" class="bg"> 
			<?php 
			/*
				if(is_array($areaResult)){
					foreach($areaResult as $key=>$prs){
						echo "<input type='radio' name='areaID' value='".$prs["ID"]."'";
						if(is_array($pRsArr)){
							if(in_array($prs["ID"],$pRsArr)) echo "checked";
						}
						echo " > ".$prs["name"]." &nbsp;";
					}
				}
				echo "<hr>";
				*/
			?>
				<?php  
			//	$auth_areaArr=explode(",",$auth_area);  
			  //$prodandprojRs =$db->select_all("projectID","productandproject","productID='".$ID."'"); 
				if(is_array($areaResult)){ 
                                    $i=0;//选中全部时用
                                    foreach($areaResult as $key=>$prs){ 
						   	$projectRs =$db->select_all("p.ID,p.name","areaandproject as ap,project as p","ap.areaID ='".$prs['ID']."' and p.ID=ap.projectID and ap.projectID in(".$_SESSION["auth_project"].")"); 
                                                        $i++;
							  echo "<input type='checkbox' name='areaID[]' value='".$prs["ID"]."' id='ckall_".$i."' onclick=\"checkEvent('ck_".$i."','ckall_".$i."')\"> ".$prs["name"]."&nbsp;"; 
							  echo "&nbsp;&nbsp;&nbsp;&nbsp;<div style='background:#8DB2E3; width='100%'>所属项目&nbsp;&nbsp;&nbsp;&nbsp; ";
						    foreach($projectRs as $val){ 
						       echo "<input type='checkbox' name='projectID[]' ";
						       if(in_array($val["ID"],$pjRsArr)) echo " checked ";
						       echo " value='".$val["ID"]."' class='ck_".$i."'> ".$val["name"]." &nbsp;"; 
							  }
						     echo "<br>".'</div>';  
					}
				}
			?> 
			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("产品描述：")?></td>
		    <td align="left" class="bg">
				<input type="text" name="description" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="<?=$rs["description"]?>">
			</td>
		    </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="<? echo _("提交")?>">			
			</td>
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
 
window.onLoad=product_type_change(); 
window.onLoad=daypartingChange(); 
window.onLoad =changAuto();
function daypartingChange(){
  var  timeYes = document.getElementById("dayparting_yes").checked; 
   if(timeYes==true){
    	document.getElementById("parting_up").style.display="";
		  document.getElementById("parting_down").style.display="";	
		  document.getElementById("daypartingTime").style.display=""; 
   }else if(timeYes==false) {
      document.getElementById("parting_up").style.display="none";
		  document.getElementById("parting_down").style.display="none";
		  document.getElementById("daypartingTime").style.display="none"; 
   }    
}
function product_type_change(){
	//var v=document.myform.type.value;
	v=document.getElementById("type").value 
	if(v=="year"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>年</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>天</font>";
		document.getElementById("unitpriceTR").style.display="none";
		//document.getElementById("cappingTR").style.display="none";
	}else if(v=="month"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>月</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>天</font>";
		document.getElementById("unitpriceTR").style.display="none";
		//document.getElementById("cappingTR").style.display="none";
	}else if(v=="days"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>天</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>天</font>";
		document.getElementById("unitpriceTR").style.display="none";
		//document.getElementById("cappingTR").style.display="none";	
	}else if(v=="hour"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>时/月</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>元</font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>元/时</font>";
		document.getElementById("unitpriceTR").style.display="";
		document.getElementById("cappingTR").style.display="none";//不显示封顶金额2014.03.22
	}else if(v=="flow"){
		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'>M/月</font>";
		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'>元</font>";
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'>元/M</font>";
		document.getElementById("unitpriceTR").style.display="";
		//document.getElementById("cappingTR").style.display="none"; 
		document.getElementById("creditlineTR").style.display="none";
	}else if(v=="netbar_hour"){ 
	  document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("分钟")?></font>"; 
		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/时")?></font>";
		document.getElementById("price").value='0';	
		document.getElementById("penalty").value='0';		
		document.getElementById("cappingTR").style.display="";
		document.getElementById("unitpriceTR").style.display=""; 
		document.getElementById("creditlineTR").style.display="none"; 
		document.getElementById("typeText").style.display="none";
 		document.getElementById('notMoney').style.display="none"; 
 		document.getElementById('billingType').style.display="none";
		document.getElementById('periodOther').style.display="none"; 
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
function changAuto(){  
    if(document.getElementById('price').value=="0") 
	    document.getElementById('autoRenew').style.display=''; 
    else              
	    document.getElementById('autoRenew').style.display='none';  
} 
function changeAllareaID(){
	v=document.myform.allareaID;
	m=document.getElementsByName("areaID[]");
	for(i=0;i<m.length;i++){
		m[i].checked=v.checked;
	}
}
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
</body>
</html>

