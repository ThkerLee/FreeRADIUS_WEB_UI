#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("限速规则")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jquery.js" type="text/javascript"></script>
</head>
<?php 
$ID=$_GET["ID"];
if($_POST){
	$projectID=$_POST["projectID"];
	$srcip    =$_POST["srcip"];
	$dstip    =$_POST["dstip"];
	$srcport  =$_POST["srcport"];
	$dstport  =$_POST["dstport"];
	$upload   =$_POST["upload"];
	$download =$_POST["download"];
	$subnet   =$_POST["subnet"];
	
	if (empty($projectID)) {
		$input_errors[] = _("您必须选择一个项目");
	}
	if (empty($dstip) && !is_ip($dstip)) {
		$input_errors[] =  _("须输入一个合法的IP地址。");
	}
	if (empty($upload) || !is_number($upload)) {
		$input_errors[] =  _("须指定一个合法的数字");
	}
	if (empty($download) || !is_number($download)) {
		$input_errors[] =  _("须指定一个合法的数字");
	}	
	if($input_errors){
		foreach($input_errors as $va){
			$strtxt .=$va."\\n";
		}
		echo "<script language='javascript'>alert('".$strtxt."');window.history.go(-1);</script>";
		exit;
	}
	//echo print_r($input_errors);	
	$dstip=$dstip."/".$subnet;
	$record=array(
				"projectID"=>$projectID,
				"dstip"=>$dstip,
				"upload"=>$upload,
				"download"=>$download
			);
	if(!$input_errors){
		$db->update_new("speedrule","ID='".$ID."'",$record);
			
		//更改此项目的下的所有技术参数
		$configRs    =$db->select_one("*","config","0=0 order by ID desc limit 0,1");
		$speedStatus =$configRs["speedStatus"];
		if($speedStatus=="1"){
			$user=$db->select_all("u.ID as userID,o.productID as productID","userinfo as u,orderinfo as o,userattribute as ur","u.projectID='".$projectID."' and u.ID=o.userID and o.ID=ur.orderID");
			if($user){
				foreach($user as $key=>$userRs){
					addUserParaminfo($userRs["userID"],$userRs["productID"]);//更改参数
				}//end foreach
			}	//end user	
		}//end speedSattus
		echo "<script>alert('" . _("修改成功") ." ');window.location.href='speedrule.php';</script>";
	}
}

$rs=$db->select_one("*","speedrule","ID='".$ID."'");
$dstip=explode("/",$rs["dstip"]);
?>
<body>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("限速规则")?></font></td>
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
        <td width="89%" class="f-bulue1"> <? echo _("限速规则添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
		<form  method="post" name="myform" id="myform" action="?action=addsave&ID=<?=$ID?>">
		  <table width="100%" border="0" cellpadding="5" cellspacing="1" class="bg1">
			<tr>
			  <td width="20%" align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("项目:")?></td>
			  <td width="80%" bgcolor="#FFFFFF" class="border_b border_l"><?=projectSelected($rs["projectID"]) ?> </td>
			</tr>
		
			<tr>
			  <td align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("目标IP:")?></td>
			  <td bgcolor="#FFFFFF" class="border_b border_l">
				<input name='dstip' type='text' id='dstip' value="<?=$dstip[0]?>" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out">
				/
				  <select name="subnet">
					<?php
						for($si=1;$si<=32;$si++){
					?>
						<option value="<?=$si?>" <?php if($si==$dstip[1]) echo "selected"; ?>><?=$si?></option>
					<?php 			
						}
					?>
				  </select>			      255.255.255.0&nbsp;<? echo _("则选择")?>24				</td>
			</tr>
			
			<tr>
			  <td align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("上传带宽:")?></td>
			  <td bgcolor="#FFFFFF" class="border_b border_l">
			  <input type="text" size="20" name='upload' id="upload" value="<?=$rs["upload"]?>" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"/>
			  kbit</td>
			</tr>
			<tr>
			  <td align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("下载带宽:")?></td>
			  <td bgcolor="#FFFFFF" class="border_b border_l">
			  <input  name='download' type="text" id="download" size="20" value="<?=$rs["download"]?>" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"/>
kbit			  </td>
			</tr>
		
			<tr>
			  <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
			  <td align="left" bgcolor="#FFFFFF"><input name="submit" type="submit" value="<? echo _("提交")?>" /></td>
			</tr>
			<tr>
			  <td colspan="2" align="left" bgcolor="#FFFFFF" class="line-20"><p ><? echo _("说明： ")?></p>
		      <p ><? echo _("此处设定的限速规则,可以针对PPPOE用户访问指定的IP地址段分配指定的限速规则. 如:需要对内网电影服务器的访问限速为10M,而电影服务器的IP地址为:192.168.100.100,则按如下方式填写:目标IP:192.168.100.0,子网掩码选择24位,上传和下载的带宽均填写:10240即可.")?></p></td>
		    </tr>
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


</body>
</html>

