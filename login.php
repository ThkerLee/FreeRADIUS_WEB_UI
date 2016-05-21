#!/bin/php
<?php

include("inc/conn.php");
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai'); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2800.1528" name=GENERATOR>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<link href="style/bule/tooltip.css" rel="stylesheet" type="text/css">	

</HEAD>
<?php 

$rs=$db->select_one("*","config","0=0 order by ID desc limit 0,1");

if($_POST){
	$manager_account=$_POST["username"];
	$manager_passwd =$_POST["pwd"]; 
	$record         =$db->select_one("*","manager","manager_account='".$manager_account."' and manager_passwd='".$manager_passwd."'"); 

	if($record){
		$_SESSION["managerID"]         =$record["ID"];
		$_SESSION["manager"]           =$record["manager_account"];
		$_SESSION["auth_permision"]	   =explode("#",$record["manager_permision"]);
		$_SESSION["auth_project"]	   =empty($record["manager_project"])?"0":$record["manager_project"];	
		$_SESSION["auth_area"]	       =empty($record["manager_area"])?"0":$record["manager_area"];	
		$_SESSION["auth_gradeID"]	     =empty($record["manager_gradeID"])?"0":$record["manager_gradeID"];	
		$_SESSION["addusernum"]	       =$record["addusernum"];
		$_SESSION["addusertotalnum"]   =$record["addusertotalnum"];		 
		$auth_project	    	       =$_SESSION["auth_project"];		 
		$auth_area	    	         =$_SESSION["auth_area"];
		$auth_grade 	    	       =$_SESSION["auth_gradeID"];
   /*
   	$project        =$db->select_all('projectID',"areaandproject ","areaID in (".$_SESSION["auth_area"].") order by ID ASC");
    
    
		if(is_array($project)){ 
			foreach($project as $prs){  
				$pjArr[] =$prs['projectID'];
			}  
		 }else{  
				$pjArr[] =0;
		 } 
     $pjArr=array_unique($pjArr); 
		 $pjArr = implode(",",$pjArr);
		 $_SESSION["auth_project"]=  $pjArr;
		 */
		 $product        =$db->select_all('productID',"productandproject","projectID in (".$_SESSION["auth_project"].") order by productID ASC");
	 if(is_array($product)){
			foreach($product as $prs){
				  $pID  .=$prs['productID'].",";  
			}
			 $pID   = substr($pID,0,-1); 
			 $_SESSION["auth_product"]=empty($pID)?"0":$pID ; 
		 }else{
		   $_SESSION["auth_product"]=0; 
		 }  	 
		$sql=array(
				"name"=>$_SESSION["manager"],
				"logindatetime"=>date("Y-m-d H:i:s",time()),
				"loginip"=>getClientIp(),
				"content"=>$_SERVER['REQUEST_URI']
				);  
		$db->insert_new("loginlog",$sql);
		echo "<script language='javascript'>window.location.href='index.php';</script>";
	}else{
		echo "<script language='javascript'>alert('". _("输入有误") ." ');window.history.go(-1);</script>";
	}
}
?>	
<form action="?action=check" method="post" name="myform" enctype="multipart/form-data"> 
<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="5">
  <tr><td>
<table width="511" height="352" border="0" align="center" cellpadding="0" cellspacing="0" id="table" background=<?="./images/".$rs['picLogin']?> ><!--images/login_bg.jpgbackground="<?//="./images/".$rs['picLogin'];?>"-->
  <tr>
    <td valign="bottom">
        <table width="100%" height="340" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr>
            <td height="130" align="left">&nbsp;</td>
            <td  align="left">&nbsp;</td>
            <td align="left"><table width="100%" height="75" border="0" cellpadding="5" cellspacing="0" >
              <tr>
                <td width="40%" align="center" class="STYLE4">&nbsp;<!--®--></td>
                <td width="60%"><span class="STYLE3"><?=$rs['WEB']?><!--http://www.natshell.com--></span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td valign="top"><span class="STYLE3"><?=$rs['Name']?><? // echo _("计费管理系统");?><!--蓝海卓越计费管理系统--></span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="30" align="left">&nbsp;</td>
            <td height="25" align="right" class="f-bulue2"><? echo _("帐号：")?></td>
            <td width="64%" align="left"><input type="text" name="username" class="input_out"> </td>
          </tr>
          <tr>
            <td width="14%" height="30" align="left">&nbsp;</td>
            <td width="22%" height="34" align="right" class="f-bulue2"><? echo _("密码：")?></td>
            <td align="left"><input type="password" name="pwd"  title="<? echo _("输密码")?>" class="input_out">            </td>
          </tr> 
          <tr>
            <td height="51" align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left"><input type="image" value="<? echo _("提交")?>" src="images/login.jpg"><!--images/login.jpg---></td>
          </tr>
          <tr>
            <td height="30" align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td height="10" align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right" valign="baseline" class="f-gray"><?=$rs['copyrightLog']?><!--版权所有：星锐蓝海网络科技有限公司-->&nbsp;&nbsp;</td>
          </tr>
        </table>
</td>
  </tr>
</table>
</td></tr>
</table>
</form>
</html>