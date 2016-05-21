#!/bin/php
<?php include("inc/conn.php"); 
include_once("evn.php");  ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("权限管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
</head>
<?php 
if($_POST){
	$ID  			    =$_POST["ID"];
	$permision_name     =$_POST["permision_name"];
	$permision_param    =$_POST["permision_param"];
	$permision_param_old=$_POST["permision_param_old"];
	$permision_rank     =$_POST["permision_rank"];
	$permision_parentID =(empty($_POST["permision_parentID"]))?"0":$_POST["permision_parentID"];
	$sql=array(
		"permision_name"=>$permision_name,
		"permision_param"=>$permision_param,
		"permision_rank"=>$permision_rank,
		"permision_parentID"=>$permision_parentID	
	);
	if($permision_param!=$permision_param_old){
		$result=$db->select_one("*","managerpermision","permision_param='".$permision_param."'");
		if($result){
			$input_errors[]=_("系统已存在有相同的参数");
		}
	}
	if($input_errors){
		foreach($input_errors as $va){
			$strtxt .=$va."\\n";
		}
		echo "<script language='javascript'>alert('".$strtxt."');window.history.go(-1);</script>";
		exit;
	}	
	if($_GET["action"]=="addsave"){
		$db->insert_new("managerpermision",$sql);
	}else if($_GET["action"]=="editsave"){
		$db->update_new("managerpermision","ID='".$ID."'",$sql);
	}
}
if($_GET["action"]=="del"){
	$db->delete_new("managerpermision","ID='".$_GET["ID"]."'");
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
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统权限")?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("系统权限管理")?></td>
		<td width="11%" align="center"><a href="?action=add"><? echo _("增加大类")?></a></td>
      </tr>
	  </table>
<?php
if($_GET["action"]=="add"){
?>  
<form action="?action=addsave" method="post" name="myform">
<input type="hidden" name="permision_parentID" value="<?=$_GET["permision_parentID"]?>">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">      	     
        <tbody>
		  <tr>
		    <td width="18%" align="right" class="bg"><? echo _("权限名称:")?></td>
		    <td width="82%" align="left" class="bg">
				<input type="text" name="permision_name">
			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("权限参数:")?></td>
		    <td align="left" class="bg">
				<input type="text" name="permision_param">
			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("排序位置:")?></td>
		    <td align="left" class="bg">
				<input type="text" name="permision_rank">
			</td>
	      </tr>
		  <tr>
		    <td align="center" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="<? echo _("提交")?>">
			</td>
	      </tr>
        </tbody>      
    </table>
</form>
<?php }else if($_GET["action"]=="edit"){ 
$rs=$db->select_one("*","managerpermision","ID='".$_GET["ID"]."'");
?>
<form action="?action=editsave" method="post" name="myform">
<input type="hidden" name="permision_parentID" value="<?=$rs["permision_parentID"]?>">
<input type="hidden" name="ID" value="<?=$rs["ID"]?>">
<input type="hidden" name="permision_param_old" value="<?=$rs["permision_param"]?>">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">      	     
        <tbody>
		  <tr>
		    <td width="18%" align="right" class="bg"><? echo _("权限名称:")?></td>
		    <td width="82%" align="left" class="bg">
				<input type="text" name="permision_name" value="<?=$rs["permision_name"]?>">
			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("权限参数:")?></td>
		    <td align="left" class="bg">
				<input type="text" name="permision_param" value="<?=$rs["permision_param"]?>">
			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("排序位置:")?></td>
		    <td align="left" class="bg">
				<input type="text" name="permision_rank" value="<?=$rs["permision_rank"]?>">
			</td>
	      </tr>
		  <tr>
		    <td align="center" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit" value="<? echo _("提交")?>">
			</td>
	      </tr>
        </tbody>      
    </table>
</form>

<?php }else{ ?>

	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
			<thead>
				  <tr>
					<th width="4%" align="center" class="bg f-12"><? echo _("编号")?></th>
					<th width="19%" align="center" class="bg f-12"><? echo _("权限名称")?></th>
					<th width="11%" align="center" class="bg f-12"><? echo _("权限参数")?></th>
					<th width="10%" align="center" class="bg f-12"><? echo _("排序")?></th>
					<th width="10%" align="center" class="bg f-12">&nbsp;</th>
					<th width="11%" align="center" class="bg f-12"><? echo _("操作")?></th>
				  </tr>
			</thead>	     
			<tbody>  
	<?php 
	$result=$db->select_all("*","managerpermision","permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			$str=($rs["permision_parentID"]==0)?"<a href='?action=add&permision_parentID=".$rs["ID"]."'>"._("增加子权限")."</a>":"<font color='#cccccc'>"._("无子权限")."</font>";
			
	?>  
			  <tr>
				<td align="center" class="bg1"><?=$rs['ID'];?></td>
				<td align="center" class="bg1"><?=$rs['permision_name'];?></td>
				<td align="center" class="bg1"><?=$rs["permision_param"]?></td>
				<td align="center" class="bg1"><?=$rs["permision_rank"]?></td>
				<td align="center" class="bg1"><?=$str?></td>
				<td align="center" class="bg1">
				  <a  href="?action=edit&ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
				  <a  href="?aciton=del&ID=<?=$rs['ID'];?>"><img src="images/del.png" width="12" height="12" border="0" /></a>		</td>
			  </tr>
			  
	<?php  
			$subResult=$db->select_all("*","managerpermision","permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
	?>
			  <tr>
				<td align="center" class="bg"><?=$subRs['ID'];?></td>
				<td align="center" class="bg"><?=$subRs['permision_name'];?></td>
				<td align="center" class="bg"><?=$subRs["permision_param"]?></td>
				<td align="center" class="bg"><?=$subRs["permision_rank"]?></td>
				<td align="center" class="bg"></td>
				<td align="center" class="bg">
				  <a  href="?action=edit&ID=<?=$subRs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
				  <a  href="?aciton=del&ID=<?=$subRs['ID'];?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
			  </tr>
	<?php 
				}//end sub foreach
			}//end sub if 
	
		}//end foreach parent
	}//end if  
	
	?>
	
			  <tr>
				<td colspan="6" align="center" class="bg">
					<?php $db->page(); ?>			</td>
		      </tr>
			</tbody>      
		</table>
<?php } ?>
  	  	
	
	
	
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

