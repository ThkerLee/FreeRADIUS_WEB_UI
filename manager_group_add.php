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
if($_POST){
	$group_name  =$_POST["group_name"];
	$permision   =$_POST["permision"];
	$areaID      =$_POST["areaID"];
	$projectID   =$_POST["projectID"];
	$gradeID     =$_POST["gradeID"];
	
	if(empty($group_name)){
		echo "<script language='javascript'>alert('"._("组名不能为空")."');window.history.go(-1);</script>";
		exit;
	}
	if(is_array($permision)){
		$str =implode("#",$permision); 
	} 
	if(is_array($projectID)){
		$str_project =implode(",",$projectID);
	  $area = $db->select_all("areaID","areaandproject","projectID in (".$str_project.")");
	  if($area){
	    foreach($area as $val){ 
	    	$str_area  .=$val['areaID'].","; 
	    }
	     // $str_area = substr($str_area,0,-1);
	  } 
	}
	if(is_array($areaID)){
		foreach($areaID as $aval){ 
	    if(strrpos($str_area,$aval)===false){
	     $str_area  .=$aval.",";
	    }
		}   
	}  
	$strarea= substr($str_area,strlen($str_area)-1,strlen($str_area)); //获取最后一位字符串
	if($strarea==",") $str_area   = substr($str_area,0,-1);
	if(is_array($gradeID)){
		$str_grade =implode(",",$gradeID);
	}
	$sql=array(
		"group_name"=>$group_name,
		"group_permision"=>$str,
		"group_areaID"=>$str_area,
		"group_gradeID"=>$str_grade,		
		"group_project"=>$str_project
	);
	
	$db->insert_new("managergroup",$sql); 
	echo "<script>alert('"._("操作成功")."');window.location.href='manager_group.php';</script>";

}

//查询项目集合
//$projectResult      =$db->select_all("*","project","");
$areaResult      =$db->select_all("ID,name","area","");
$gradeResult        =$db->select_all("*","grade","");
$manager_areaArr =array();
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统设置")?></font></td>
		  </tr>
   		</table>	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("角色添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<form action="?" method="post" name="myform">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
      <tr>
        <td width="13%" align="right" class="bg"><? echo _("组名称:")?></td>
        <td width="83%" align="left" class="bg"><input type="text" id="group_name" name="group_name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"></td>
      </tr>
	  <tr>
		    <td align="right" class="bg"><? echo _("所属区域:")?></td>
		    <td align="left" class="bg">
			<input type="checkbox" name="allareaID" onClick="changeAllareaID();"><? echo _("所有区域")?><br><br>
			<?php 
				if(is_array($areaResult)){
                                     $i=0;//选中全部时用 2014.05.22
					foreach($areaResult as $key=>$rs){
						if(!in_array($rs["ID"],$manager_areaArr)){
                                                    $i++;
							echo "<input type='checkbox' name='areaID[]' value='".$rs["ID"]."'  id='ckall_".$i."' onclick=\"checkEvent('ck_".$i."','ckall_".$i."')\"> "._($rs["name"])." &nbsp;";
							$projectRs =$db->select_all("p.ID,p.name","areaandproject as ap,project as p","ap.areaID ='".$rs['ID']."' and p.ID=ap.projectID");
						 	if(count($projectRs)>0){
							  echo "&nbsp;"; 
							  echo "&nbsp;&nbsp;&nbsp;&nbsp;<div style='background:#8DB2E3; width='100%'>所属项目&nbsp;&nbsp;&nbsp;&nbsp; ";
						    foreach($projectRs as $pval){
						       echo "<input type='checkbox' name='projectID[]' value='".$pval["ID"]."'  class='ck_".$i."'> ".$pval["name"]." &nbsp;";
							  }
						     echo "<br>".'</div>';
							 }
						}
						/*else
						{
							echo "<input type='checkbox' name='areaID[]' value='".$rs["ID"]."' checked> "._($rs["name"])." &nbsp;";
						}*/
							
					}
				}
			?>			</td>
		    </tr>
	   <tr>
        <td align="right" class="bg"><? echo _("查看等级:")?></td>
        <td colspan="2" align="left" class="bg">
			<?php 
				if(is_array($gradeResult)){
					foreach($gradeResult as $key=>$rs){
						echo "<input type='checkbox' name='gradeID[]' value='".$rs["ID"]."' checked='checked'> "._($rs["name"])." &nbsp;";		
					}
				}
			?>
				</td>
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
			
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."')>"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=160 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'>"._($subRs["permision_name"])."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if   
           }elseif ($data == 2 ) { ////加强版权限
         $result=$db->select_all("*","managerpermision","ID !=64 and ID !=165 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			$str=($rs["permision_parentID"]==0)?"<a href='?action=add&permision_parentID=".$rs["ID"]."'>"._("增加子权限")."</a>":"<font color='#cccccc'>"._("无子权限")."</font>";
			
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."')>"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=140 and ID !=138 and ID !=161 and ID !=160 and  ID !=163 and ID !=128 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'>"._($subRs["permision_name"])."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if    
        }elseif ($data == 3) { //基础版权限
          $result=$db->select_all("*","managerpermision","ID !=58 and ID !=96 and ID !=16 and ID !=64 and ID !=165 and ID !=36 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			$str=($rs["permision_parentID"]==0)?"<a href='?action=add&permision_parentID=".$rs["ID"]."'>"._("增加子权限")."</a>":"<font color='#cccccc'>"._("无子权限")."</font>";
			
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."')>"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=49 and ID !=80 and ID !=140 and ID !=137 and ID !=73 and ID !=84 and ID !=130 and ID !=43 and ID !=63 and ID !=35 and ID !=115 and ID !=31 and ID !=56 and ID !=162 and ID !=128 and ID !=163 and ID !=159 and ID !=160 and ID !=161 and ID !=155 and ID !=157 and ID !=158 and ID !=126 and ID !=118 and ID !=119 and ID !=120 and ID !=121 and ID !=164 and ID !=111 and ID !=113 and ID !=114 and ID !=107 and ID !=138 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'>"._($subRs["permision_name"])."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if   
        }
  
	?>          </tr>
      <tr>
        <td align="right" class="bg">&nbsp;</td>
        <td align="left" class="bg"><input name="submit" type="submit" value="<? echo _("提交")?>">        </td>
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

