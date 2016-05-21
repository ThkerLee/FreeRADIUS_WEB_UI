#!/bin/php
<?php 
include("inc/conn.php");

include_once("evn.php"); 

?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>短信设置</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<?php
if($_POST){  
 	 if(is_numeric($_POST['id'])){//修改
 	 	  $sql=array(
                      "type"=>$_POST["type"], 
		      "status"=>$_POST["status"],
		      "days"=>$_POST["days"],
		      "client"=>$_POST["client"],
		      "message"=>$_POST["message"], 
		      "content"=>$_POST["content"],
                      "userid"=>$_POST["userid"],
                      "account"=>$_POST["account"],
                      "password"=>$_POST["password"]
	        ); 
               if($_POST["userid"]==""  || $_POST["account"] == "" || $_POST["password"] == ""){//userid 用户名 密码 不能为空
                    echo "<script language='javascript'>alert('"._("数据不完整")."');window.location.href='system_message.php';</script>";
                    exit();
               }else{
               $db->update_new("message","ID=".$_POST['id'],$sql); }    
   }else{//添加
   	$sql=array(
                      "type"=>$_POST["type"], 
		      "status"=>$_POST["status"],
		      "days"=>$_POST["days"],
		      "client"=>$_POST["client"],
		      "message"=>$_POST["message"], 
		      "content"=>$_POST["content"],
                      "userid"=>$_POST["userid"],
                      "account"=>$_POST["account"],
                      "password"=>$_POST["password"]
	        ); 
        if($_POST["userid"]==""  || $_POST["account"] == "" || $_POST["password"] == ""){//userid 用户名 密码 不能为空
                    echo "<script language='javascript'>alert('"._("数据不完整")."');window.location.href='system_message.php';</script>";
                    exit();
               }else{
               $db->insert_new("message",$sql);} 
   } 
                    //写入计划任务
                  include '/usr/local/usr-gui/aaacron.php';
	 echo "<script language='javascript'>alert('"._("配置成功")."');window.location.href='system_message.php';</script>"; 
   
 }
 
 
?> 
<body>
	<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">短信设置</font></td>
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr> 
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">

<?php if($_REQUEST['mess']=="" || $_REQUEST['mess']=='add'){//开户
   $rs=$db->select_one("*","message","type = 1 ");
	
?>
<form action="?action=editadd" method="post" name="myform" >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<?php if($_REQUEST['mess']=='' || $_REQUEST['mess']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" >  <? echo _("开户短信设置")?>
         <input type="radio" name="mess" id="orderadd"  value="once"<?php if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" >  <? echo _("续费短信设置")?>
	       <input type="radio" name="mess" id="upcoming"  value="once"<?php if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" >  <? echo _("即将到期短信设置")?>
	       <input type="radio" name="mess" id="maturity"  value="more" <?php if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" >  <? echo _("到期短信设置")?> 
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >  
         <input name="days" type="hidden"id="days"  value="" size="50"></td>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg"><? echo _("类型")?></td>
		   <td align="left" class="bg">开户<input type='hidden' value='1' name='type'></td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg"><? echo _("状态")?></td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <? if($rs['status']=="enable")echo"checked";?> ><? echo _("启用")?>
			 <input name="status" type="radio" value="disable"<? if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?> ><?  echo _("禁用")?></td>
		  </tr> 
                       <tr>
		    <td align="left" class="bg"><? echo _("端口")?></td>
		    <td align="left" class="bg">
				<input name="userid" type="text"  value="<?=$rs['userid']?>" size="50"> <? echo _("提示：端口、用户名、密码由蓝海提供才可用此功能")?>	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("用户名")?></td>
		    <td align="left" class="bg">
				<input name="account" type="text"  value="<?=$rs['account']?>" size="50"> 	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("密码")?></td>
		    <td align="left" class="bg">
				<input name="password" type="text"  value="<?=$rs['password']?>" size="50"> 	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("客户尊称")?></td>
		    <td align="left" class="bg">
				<input name="client" type="text"    id="client"  value="<?=$rs['client']?>" size="50"> <? echo _("提示：尊敬的")?>	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("公司信息")?></td>
		    <td align="left" class="bg">
				<textarea  name="message" id="message"   rows="2" style="width:365px;" ><?=$rs['message'];?></textarea><? echo _("提示：感谢使用**公司的**宽带，")?> 
			  </td>
		  </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("内容")?></td>
		    <td align="left" class="bg">
				<textarea  name="content" id="content"   rows="2" style="width:365px;" ><?=$rs['content'];?></textarea><? echo _("提示：如需要更名密码请及时登录或于我们联系,联系电话：028-*******")?> 
			</td>
		  </tr> 
		  <tr>
		      <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="保存"></td>
		  </tr> 
     </tbody>
  </table>	 
     </form>
 <?php
}else if($_REQUEST['mess']=="orderadd"){//续费
   $rs=$db->select_one("*","message","type = 2 ");
?>
<form action="?action=editorderadd" method="post" name="myform"  >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<? if($_REQUEST['mess']=='' || $_REQUEST['act']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" >  <? echo _("开户短信设置")?>
		     <input type="radio" name="mess" id="orderadd"  value="once"<? if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" >  <? echo _("续费短信设置")?>
	       <input type="radio" name="mess" id="upcoming"  value="once"<? if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" >  <? echo _("即将到期短信设置")?>
	       <input type="radio" name="mess" id="maturity"  value="more" <? if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" >  <? echo _("到期短信设置")?> 
         <input type="hidden" name="id"  id="id"  value="<?=$rs['id']?>"  >  
         <input name="days" type="hidden"id="days"  value="" size="50">	</td>
		  </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg"><? echo _("类型")?></td>
		   <td align="left" class="bg">续费<input type='hidden' value='2' name='type'></td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg"><? echo _("状态")?></td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <? if($rs['status']=="enable")echo"checked";?> ><? echo _("启用")?>
			 <input name="status" type="radio" value="disable"<? if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?> ><?  echo _("禁用")?></td>
		  </tr>
                   <tr>
		    <td align="left" class="bg"><? echo _("端口")?></td>
		    <td align="left" class="bg">
				<input name="userid" type="text"  value="<?=$rs['userid']?>" size="50"> <? echo _("提示：端口、用户名、密码由蓝海提供才可用此功能")?>	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("用户名")?></td>
		    <td align="left" class="bg">
				<input name="account" type="text"  value="<?=$rs['account']?>" size="50"> 	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("密码")?></td>
		    <td align="left" class="bg">
				<input name="password" type="text"  value="<?=$rs['password']?>" size="50"> 	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("客户尊称")?></td>
		    <td align="left" class="bg">
				<input name="client" type="text"    id="client"  value="<?=$rs['client']?>" size="50"> <? echo _("提示：尊敬的")?>	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("公司信息")?></td>
		    <td align="left" class="bg">
				<textarea  name="message" id="message"   rows="2" style="width:365px;" ><?=$rs['message'];?></textarea><? echo _("提示：感谢使用**公司的**宽带，")?> 
			  </td>
		  </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("内容")?></td>
		    <td align="left" class="bg">
				<textarea  name="content" id="content"   rows="2" style="width:365px;" ><?=$rs['content'];?></textarea><? echo _("提示：如需要更名密码请及时登录或于我们联系,联系电话：028-*******")?> 
			</td>
		  </tr> 
		  <tr>
		      <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="保存"></td>
		  </tr> 
     </tbody>
  </table>	 
     </form>  
<?php
}else if ($_REQUEST['mess']=="upcoming"){//即将到期
 $rs=$db->select_one("*","message","type = 3 ");
?>
<form action="?action=editmaturity" method="post" name="myform" >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<? if($_REQUEST['mess']=='' || $_REQUEST['act']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" >  <? echo _("开户短信设置")?>
                <input type="radio" name="mess" id="orderadd"  value="once"<? if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" >  <? echo _("续费短信设置")?>
	       <input type="radio" name="mess" id="upcoming"  value="once"<? if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" >  <? echo _("即将到期短信设置")?>
	       <input type="radio" name="mess" id="maturity"  value="more" <? if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" >  <? echo _("到期短信设置")?> 
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >   
        </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg"><?php echo"类型";?></td>
		   <td align="left" class="bg">即将到期<input type='hidden' value='3' name='type'></td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg"><? echo _("状态")?></td>
		   <td align="left" class="bg">
			 <input name="status" type="radio" value="enable" <? if($rs['status']=="enable")echo"checked";?> ><? echo _("启用")?>
			 <input name="status" type="radio" value="disable"<? if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?> ><?  echo _("禁用")?></td>
		  </tr> 
		 <tr>
		    <td align="left" class="bg"><? echo _("端口")?></td>
		    <td align="left" class="bg">
				<input name="userid" type="text"  value="<?=$rs['userid']?>" size="50"> <? echo _("提示：端口、用户名、密码由蓝海提供才可用此功能")?>	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("用户名")?></td>
		    <td align="left" class="bg">
				<input name="account" type="text"  value="<?=$rs['account']?>" size="50"> 	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("密码")?></td>
		    <td align="left" class="bg">
				<input name="password" type="text"  value="<?=$rs['password']?>" size="50"> 	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("短信间隔周期")?></td>
		    <td align="left" class="bg">
				<input name="days" type="text"  value="<?=$rs['days']?>" size="50"> <? echo _("提示：单位为小时")?>	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("客户尊称")?></td>
		    <td align="left" class="bg">
				<input name="client" type="text"    id="client"  value="<?=$rs['client']?>" size="50"> <? echo _("提示：尊敬的")?>	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("公司信息")?></td>
		    <td align="left" class="bg">
				<textarea  name="message" id="message" rows="2" style="width:365px;" ><?=$rs['message'];?></textarea><? echo _("提示：感谢使用**公司的**宽带，")?> 
			  </td>
		  </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("内容")?></td>
		    <td align="left" class="bg">
				<textarea name="content" id="content"   rows="2" style="width:365px;" ><?=$rs['content'];?></textarea><? echo _("提示：如需正常使用请到营业厅续费,联系电话：028-*******")?> 
			</td>
		  </tr> 
		  <tr>
		      <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="保存"></td>
		  </tr> 
     </tbody> 
  </table>	
     </form>  
<?php
}else if($_REQUEST['mess']=="maturity"){//到期 
   $rs=$db->select_one("*","message","type = 4 ");
?>
<form action="?action=editmaturity" method="post" name="myform"  >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<? if($_REQUEST['mess']=='' || $_REQUEST['act']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" >  <? echo _("开户短信设置")?>
                <input type="radio" name="mess" id="orderadd"  value="once"<? if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" >  <? echo _("续费短信设置")?>
	       <input type="radio" name="mess" id="upcoming"  value="once"<? if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" >  <? echo _("即将到期短信设置")?>
	       <input type="radio" name="mess" id="maturity"  value="more" <? if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" >  <? echo _("到期短信设置")?> 
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >
         <input name="days" type="hidden"id="days"  value="" size="50">	</td>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg"><? echo _("类型")?></td>
		   <td align="left" class="bg">到期<input type='hidden' value='4' name='type'></td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg"><? echo _("状态")?></td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <? if($rs['status']=="enable")echo"checked";?> ><? echo _("启用")?>
			 <input name="status" type="radio" value="disable"<? if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?> ><?  echo _("禁用")?></td>
		  </tr> 
                  <tr>
		    <td align="left" class="bg"><? echo _("端口")?></td>
		    <td align="left" class="bg">
				<input name="userid" type="text"  value="<?=$rs['userid']?>" size="50"> <? echo _("提示：端口、用户名、密码由蓝海提供才可用此功能")?>	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("用户名")?></td>
		    <td align="left" class="bg">
				<input name="account" type="text"  value="<?=$rs['account']?>" size="50"> 	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("密码")?></td>
		    <td align="left" class="bg">
				<input name="password" type="text"  value="<?=$rs['password']?>" size="50"> 	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("短信间隔周期")?></td>
		    <td align="left" class="bg">
				<input name="days" type="text"  value="<?=$rs['days']?>" size="50"> <? echo _("提示：单位为小时")?>	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("客户尊称")?></td>
		    <td align="left" class="bg">
				<input name="client" type="text"    id="client"  value="<?=$rs['client']?>" size="50"> <? echo _("提示：尊敬的")?>	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("公司信息")?></td>
		    <td align="left" class="bg">
				<textarea  name="message" id="message"   rows="2" style="width:365px;" ><?=$rs['message'];?></textarea><? echo _("提示：感谢使用**公司的**宽带，")?> 
			  </td>
		  </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("内容")?></td>
		    <td align="left" class="bg">
				<textarea  name="content" id="content"   rows="2" style="width:365px;" ><?=$rs['content'];?></textarea><? echo _("提示：如需正常使用请到营业厅续费，欢迎来电咨询：联系电话：028-*******")?> 
			</td>
		  </tr> 
		  <tr>
		      <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="保存"></td>
		  </tr> 
     </tbody> 
  </table>	
     </form>  
<?php
}?>
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

