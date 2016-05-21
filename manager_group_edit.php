#!/bin/php
<?php include("inc/conn.php"); 
include_once("evn.php");  ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统管理组")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
</head>
<body>
<?php  
$ID=$_GET["ID"];
if($_POST){
	$group_name  =$_POST["group_name"];
	$permision   =$_POST["permision"];
	$areaID      =$_POST["areaID"];
	$gradeID     =$_POST["gradeID"];
	if(is_array($permision)){
		$str =implode("#",$permision);
	}
	if(is_array($areaID)){
		$str_area =implode(",",$areaID);
	}
	if(is_array($gradeID)){
		$str_grade =implode(",",$gradeID);
	}
	$sql=array(
		"group_name"=>$group_name,
		"group_permision"=>$str,
		"group_areaID"=>$str_area,
		"group_gradeID"=>$str_grade
	);
	$db->update_new("managergroup","ID='".$_GET["ID"]."'",$sql);
	$manRs=$db->select_all("*","manager","manager_groupID='".$_GET["ID"]."'");
	$manGs=$db->select_one("*","managergroup","ID='".$_GET["ID"]."'");
 
	foreach($manRs  as $val){
	$permision=array(
		 "manager_permision" =>$manGs['group_permision']
	 );
	$db->update_new("manager","ID='".$val['ID']."'",$permision);
	   
	}
	//$db->update_new//组用户修改成功后  组成员权限也做相应修改
	echo "<script>alert('"._("操作成功")."');window.location.href='manager_group.php';</script>";
	
}

//查询项目集合
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
   		</table>	</td>
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
<?php 
$rs=$db->select_one("*","managergroup","ID='".$ID."'");
$permisionArr=explode("#",$rs["group_permision"]);
$manager_areaArr =explode(",",$rs["group_areaID"]);
$manager_gradeArr   =explode(",",$rs["group_gradeID"]);
//查询项目集合
$areaResult         =$db->select_all("ID,name","area","");
$gradeResult        =$db->select_all("*","grade","");

?>
<form action="?ID=<?=$ID?>" method="post" name="myform">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
      <tr>
        <td width="10%" align="right" class="bg"><? echo _("组名称:")?></td>
        <td width="83%" align="left" class="bg"><input type="text" id="group_name" name="group_name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" value="<?=$rs["group_name"]?>"></td>
      </tr>
	  <tr>
		    <td align="right" class="bg"><? echo _("所属区域:")?></td>
		    <td align="left" class="bg">
			<input type="checkbox" name="allareaID" onClick="changeAllareaID();"><? echo _("所有区域")?><br><br>
			<?php 
				if(is_array($areaResult)){
					foreach($areaResult as $key=>$rs){
						if(!in_array($rs["ID"],$manager_areaArr)){
							echo "<input type='checkbox' name='areaID[]' value='".$rs["ID"]."'> ".$rs["name"]." &nbsp;";
						}else{
							echo "<input type='checkbox' name='areaID[]' value='".$rs["ID"]."' checked> ".$rs["name"]." &nbsp;";
						}
							
					}
				}
			?>
			
			</td>
		    </tr>
	  <tr>
        <td align="right" class="bg"><? echo _("查看等级:")?></td>
        <td colspan="2" align="left" class="bg">
			<?php 
				if(is_array($gradeResult)){
					foreach($gradeResult as $key=>$rs){
						if(!in_array($rs["ID"],$manager_gradeArr)){
							echo "<input type='checkbox' name='gradeID[]' value='".$rs["ID"]."'> "._($rs["name"])." &nbsp;";
						}else{
							echo "<input type='checkbox' name='gradeID[]' value='".$rs["ID"]."' checked> "._($rs["name"])." &nbsp;";
						}
							
					}
				}
			?>      
      </tr>
      <tr>
        <td align="right" class="bg"><? echo _("权限:")?></td>
        <td colspan="2" align="left" class="bg">
        <?php 
         $file = popen("license -T","r");
         $data = fgets($file);//获取授权
         pclose($file);
        //$data=3;
         if($data == 1){ //ISP版权限
           $result=$db->select_all("*","managerpermision","permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			$str=($rs["permision_parentID"]==0)?"<a href='?action=add&permision_parentID=".$rs["ID"]."'>"._("增加子权限")."</a>":"<font color='#cccccc'>"._("无子权限")."</font>";
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:20px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=160 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'";
					if(in_array($subRs["permision_param"],$permisionArr)) echo "checked";
					echo ">"._($subRs["permision_name"])."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if    
         }elseif ($data == 2) { //加强版权限
                     $result=$db->select_all("*","managerpermision","ID !=64 and ID !=165 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			$str=($rs["permision_parentID"]==0)?"<a href='?action=add&permision_parentID=".$rs["ID"]."'>"._("增加子权限")."</a>":"<font color='#cccccc'>"._("无子权限")."</font>";
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:20px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=140 and ID !=138 and ID !=161 and ID !=160 and  ID !=163 and ID !=128 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'";
					if(in_array($subRs["permision_param"],$permisionArr)) echo "checked";
					echo ">"._($subRs["permision_name"])."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if    
        }elseif ($data == 3) {
         $result=$db->select_all("*","managerpermision","ID !=58 and ID !=96 and ID !=16 and ID !=64 and ID !=165 and ID !=36 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			$str=($rs["permision_parentID"]==0)?"<a href='?action=add&permision_parentID=".$rs["ID"]."'>"._("增加子权限")."</a>":"<font color='#cccccc'>"._("无子权限")."</font>";
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:20px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=49 and ID !=80 and ID !=140 and ID !=137 and ID !=73 and ID !=84 and ID !=130 and ID !=43 and ID !=63 and ID !=35 and ID !=115 and ID !=31 and ID !=56 and ID !=162 and ID !=128 and ID !=163 and ID !=159 and ID !=160 and ID !=161 and ID !=155 and ID !=157 and ID !=158 and ID !=126 and ID !=118 and ID !=119 and ID !=120 and ID !=121 and ID !=164 and ID !=111 and ID !=113 and ID !=114 and ID !=107 and ID !=138 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'";
					if(in_array($subRs["permision_param"],$permisionArr)) echo "checked";
					echo ">"._($subRs["permision_name"])."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if       
        }
	
	?>          </tr>
      <tr>
        <td align="right" class="bg">&nbsp;</td>
        <td align="left" class="bg"><input name="submit" type="submit" value="<?php echo _("提交")?>">        </td>
      </tr>
    </tbody>
  </table>
</form>	</td>
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
function permision_change(id){
	v   =document.getElementById(id).checked;
	subv=document.getElementById("sub"+id).getElementsByTagName("input");
	for(i=0;i<subv.length;i++){
		subv[i].checked=v;
	}

}
function changeAllareaID(){
	v=document.myform.allareaID;
	m=document.getElementsByName("areaID[]");
	for(i=0;i<m.length;i++){
		m[i].checked=v.checked;
	}
}
-->
</script>
</body>
</html>

