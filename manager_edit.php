#!/bin/php
<?php include("inc/conn.php");  
include_once("evn.php"); ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统管理组")?></title>
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
            WindowTitle:          '<b>系统设置</b>',
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
$ID=$_GET["ID"]; 
$rs =$db->select_one("*","manager","ID='".$ID."'");
$totalInMoney = $db->select_one("sum(money) as in_all_money","credit","operator ='".$rs['manager_account']."'"); //用户收入金额 包括，开户，续费，充值，卡片充值，用户移机===
$totalOutMoney = $db->select_one("sum(factmoney) as out_all_money","orderrefund","operator ='".$rs['manager_account']."' and type in(1,2,3)"); // 1 销户 2冲账 3恢复暂停
$totalMoney = $totalInMoney['in_all_money'] - $totalOutMoney['out_all_money']; 
if($_POST){ 
	$manager_account  =$_POST["manager_account"];
	if(base64_decode($_POST["manager_passwd"])==$rs['manager_passwd']){
	   $manager_passwd   =$rs['manager_passwd'];
	}else{
	   $manager_passwd   =$_POST["manager_passwd"];
	} 
	$manager_name 	    =$_POST["manager_name"];
	$manager_phone      =$_POST["manager_phone"];
	$manager_mobile     =$_POST["manager_mobile"];
	$addusertotalnum    =$_POST["addusertotalnum"];
	$manager_totalmoney =$_POST["manager_totalmoney"];
	$manager_groupID    =$_POST["manager_groupID"];
	$permision          =$_POST["permision"];
	$areaID             =$_POST["areaID"];
	$projectID          =$_POST["projectID"];
	$gradeID            =$_POST["gradeID"];
	
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
	    //  $str_area = substr($str_area,0,-1);
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
		"manager_account"=>$manager_account,
		"manager_passwd"=>$manager_passwd,
		"manager_name"=>$manager_name,
		"manager_phone"=>$manager_phone,
		"manager_mobile"=>$manager_mobile,
		"manager_groupID"=>$manager_groupID,
		"manager_permision"=>$str,
		"manager_area"=>$str_area,
		"manager_project"=>$str_project,
		"manager_gradeID"=>$str_grade,
		"addusertotalnum"=>$addusertotalnum,
		"manager_totalmoney"=>$manager_totalmoney
	);  
	
	$db->update_new("manager","ID='".$_GET["ID"]."'",$sql);
	echo "<script>alert('"._("操作成功")."');window.location.href='manager.php';</script>";
	
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
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统权限")?></font></td>
                        <td width="3%" height="35">
                           <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                       </td> <!------帮助--2014.06.07----->                       
		  </tr>
   		</table>	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("系统用户修改")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
<?php 
//查询项目集合
//$projectResult      =$db->select_all("*","project","");
$gradeResult        =$db->select_all("*","grade","");
$group              =$db->select_all("*","managergroup","group_name!=''");
//$rs                 =$db->select_one("*","manager","ID='".$ID."'");
$permisionArr       =explode("#",$rs["manager_permision"]);
$manager_areaArr    =explode(",",$rs["manager_area"]);
$manager_projectArr =explode(",",$rs["manager_project"]);
$manager_gradeArr   =explode(",",$rs["manager_gradeID"]);  
$areaResult         =$db->select_all("ID,name","area",""); 
?>
<form action="?ID=<?=$ID?>" method="post" name="myform">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
      <tr>
        <td align="right" class="bg"><? echo _("系统帐号:")?></td>
        <td align="left" class="bg"><input type="text" name="manager_account" value="<?=$rs["manager_account"]?>" readonly='true'></td>
      </tr> 
      <tr>
        <td width="10%" align="right" class="bg"><? echo _("所属管理组:")?></td>
        <td width="83%" align="left" class="bg">
			<?php 
					echo '<select name="manager_groupID" id="manager_groupID" onChange="ajaxInput(\'ajax_check.php\',\'manager_groupID\',\'manager_groupID\',\'accountTXT\');">';
					if(is_array($group)){
						foreach($group as $gKey=>$gRs){
							echo "<option value='".$gRs["ID"]."'";
							if($gRs["ID"]==$rs["manager_groupID"]) echo "selected";
							echo ">"._($gRs["group_name"])."</option>";
						}
					} 
					echo "</select>&nbsp;"._("注:在此修改用户权限该用户权限可生效，但修改组权限时该组所有用户的权限都会默认为修改后所在组的权限");
			?>		</td>
      </tr>
      <tr >
        <td align="right" class="bg"><? echo _("密码修改:")?></td>
        <td colspan="2" align="left" class="bg">
			<input type="password" name="manager_passwd" value="<?=base64_encode($rs["manager_passwd"])?>">		</td>
      </tr>
	  <tr>
        <td align="right" class="bg"><? echo _("允许收费金额:")?></td> 
        <td colspan="2" align="left" class="bg">
			<input type="text" name="manager_totalmoney" value="<?=$rs["manager_totalmoney"]?>"> <? echo _("元");?>		</td>
      </tr>
	   <tr>
        <td align="right" class="bg"><? echo _("实际收费金额:")?></td>
        <td colspan="2" align="left" class="bg">
			<input type="text"   value="<?=$totalMoney ?>" disabled="disabled"  readonly="reaadonly"> <? echo _("元");?>		</td>
      </tr>
	  <tr>
        <td align="right" class="bg"><? echo _("允许开户人数:")?></td>
        <td colspan="2" align="left" class="bg"> 
			<input type="text" name="addusertotalnum" value="<?=$rs["addusertotalnum"]?>"> <? echo _("人");?>	</td>     
      </tr>
	  	<tr>
        <td align="right" class="bg"><? echo _("已开户人数:")?></td> 
        <td colspan="2" align="left" class="bg"> 
			<input type="text"  value="<?=$rs["addusernum"]?>" disabled="disabled"  readonly="reaadonly">  <? echo _("人");?>	</td>     
      </tr>	
      <tr>
        <td align="right" class="bg"><? echo _("真实姓名:")?></td>
        <td colspan="2" align="left" class="bg">
			<input type="text" name="manager_name" value="<?=$rs["manager_name"]?>">		</td>
      </tr>
      <tr>
        <td align="right" class="bg"><? echo _("电话号码:")?></td>
        <td colspan="2" align="left" class="bg">  
			<input type="text" name="manager_phone" value="<?=$rs["manager_phone"]?>">		</td>    
      </tr>
      <tr>
        <td align="right" class="bg"><? echo _("手机号码:")?></td>
        <td colspan="2" align="left" class="bg"> 
			<input type="text" name="manager_mobile" value="<?=$rs["manager_mobile"]?>">		</td>     
      </tr>
      <tr>
		    <td align="right" class="bg"><? echo _("所属区域:")?></td>
		    <td align="left" class="bg">
			<input type="checkbox" name="allareaID" onClick="changeAllareaID();"><? echo _("所有区域")?><br><br>
			<?php 
			if(is_array($areaResult)){ 
                            $i=0;//选中全部时用
					foreach($areaResult as $key=>$rs){ 
                                                        $i++;
							echo "<input type='checkbox' name='areaID[]' ";
							if(in_array($rs["ID"],$manager_areaArr)) echo " checked "; 
							echo "value='".$rs["ID"]."' id='ckall_".$i."' onclick=\"checkEvent('ck_".$i."','ckall_".$i."')\"> "._($rs["name"])." &nbsp;";
							$projectRs =$db->select_all("p.ID,p.name","areaandproject as ap,project as p","ap.areaID ='".$rs['ID']."' and p.ID=ap.projectID");
						 	if(count($projectRs)>0){
							  echo "&nbsp;"; 
							  echo "&nbsp;&nbsp;&nbsp;&nbsp;<div style='background:#8DB2E3; width='100%'>所属项目&nbsp;&nbsp;&nbsp;&nbsp; ";
						    foreach($projectRs as $pval){
						       echo "<input type='checkbox' name='projectID[]' ";
						       if(in_array($pval["ID"],$manager_projectArr)) echo " checked "; 
						       echo "value='".$pval["ID"]."' class='ck_".$i."'> ".$pval["name"]." &nbsp;";
							  }
						     echo "<br>".'</div>';
							 } 
						}
			}
			/*
				if(is_array($areaResult)){
					foreach($areaResult as $key=>$rs){
						if(!in_array($rs["ID"],$manager_areaArr)){
							echo "<input type='checkbox' name='areaID[]' value='".$rs["ID"]."'> "._($rs["name"])." &nbsp;";
						}else{
							echo "<input type='checkbox' name='areaID[]' value='".$rs["ID"]."' checked> "._($rs["name"])." &nbsp;";
						} 
					}
				}
				*/
			?>			</td> 
		    </tr> 
      <tr>
<!--
	    <tr>
		    <td align="right" class="bg"><? echo _("所属项目:")?></td>
		    <td align="left" class="bg">
			<input type="checkbox" name="allProjectID" onClick="changeAllProjectID();"><? echo _("所有项目")?><br><br>
			<?php 
				if(is_array($projectResult)){
					foreach($projectResult as $key=>$rs){
						if(!in_array($rs["ID"],$manager_projectArr)){
							echo "<input type='checkbox' name='projectID[]' value='".$rs["ID"]."'> "._($rs["name"])." &nbsp;";
						}else{
							echo "<input type='checkbox' name='projectID[]' value='".$rs["ID"]."' checked> "._($rs["name"])." &nbsp;";
						}
							
					}
				}
			?>			</td> 
		    </tr> 
-->
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
        <td colspan="2" align="left" class="bg"> <span id='accountTXT'>
		 <?php 
                 $file = popen("license -T","r");
                 $data = fgets($file);//获取授权
                  pclose($file);
                // $data=3;
                  if($data ==1){ //ISP版权限
                 $result=$db->select_all("*","managerpermision","permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
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
                  }elseif ($data ==2) { //加强版权限
                 $result=$db->select_all("*","managerpermision","ID !=64 and ID !=165 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
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
                 }elseif ($data == 3) { //基础版
               $result=$db->select_all("*","managerpermision","ID !=58 and ID !=96 and ID !=16 and ID !=64 and ID !=165 and ID !=36 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">"._($rs["permision_name"])."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
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
 
	?>      </span>    </tr>
	  
	
	
	
	
	
      <tr>
        <td align="right" class="bg">&nbsp;</td>
        <td align="left" class="bg"><input name="submit" type="submit" value="<? echo _("提交")?>">        </td>
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
function permision_change(id){
	v   =document.getElementById(id).checked;
	subv=document.getElementById("sub"+id).getElementsByTagName("input");
	for(i=0;i<subv.length;i++){
		subv[i].checked=v;
	}

}
function changeAllProjectID(){
	v=document.myform.allProjectID;
	m=document.getElementsByName("projectID[]");
	for(i=0;i<m.length;i++){
		m[i].checked=v.checked;
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
<!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>权限修改</strong>
      </p>
      <ul>
          <li>系统帐号:管理员账号。</li>
          <li>所属管理组:所在的管理组。</li>
          <li>密码修改:修改该管理员的密码。</li>
          <li>允许收费金额:允许该管理员总共的收费金额上限。</li>
          <li>实际收费金额:该管理员目前的实际收费总金额。</li>
          <li>允许开户人数:管理员允许最大开户总用户数。</li>
          <li>已开户人数:实际开户人数。</li>
          <li>所属区域:若不勾选该管理员则不能看到其区域。</li>
          <li>查看等级:若不勾选则该管理员不能看到开户时用户等级选择为隐藏了的用户。</li>
          <li>权限:不勾选的功能，管理员则不能操作次功能。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

