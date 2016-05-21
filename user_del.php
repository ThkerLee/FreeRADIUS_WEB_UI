#!/bin/php
<?php 
include_once("inc/conn.php"); 
require_once("ros_static_ip.php");
require_once("evn.php");
$ID=$_REQUEST["ID"];
date_default_timezone_set('Asia/Shanghai'); 
/*
 echo "<script>alert('request".$_REQUEST["ID"]."');</script>";
*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jsdate.js" type="text/javascript"></script>
</head>
<body>
<?php 
function delEmpty($v){    
if ($v){   //当数组中存在$v值时，换回false，也就是去掉该数组中的$v值
  return false;   
 }   
 return true;  
} 

if(is_array($ID) || strpos($ID,",")){//数组或者字符  字符是当前页面传参
     //遍历得到批量删除用户的是否有母账号  且得到母账号的account
   if(is_array($ID)){//user.php提交过来
	   foreach($ID as $IDvalue){
		  $UserName=getUserName($IDvalue);
		  $child=$db->select_all('ID','userinfo','Mname="'.$UserName.'"');//该帐号下的所有子账号 
		  if($child){
			$MName[]=getUserName($IDvalue);//所有母账号的用户名
			$MID[]  =$IDvalue;//母账号ID 
		  }
	   } 
   }
   if(is_array($MName)){
     $UserName = implode(",",$MName);//母账号用户名
	 $UID      = implode(",",$ID);//用户管理页面提交的ID
     echo "<script>if(window.confirm('".$UserName._("存在子账号且子账号一同删除！是否确认删除？")."')){window.location.href='user_del.php?ID=".$UID."&del=1&action=one'}else{window.location.href='user_del.php?ID=".$UID."&del=0&action=one'}</script>";
	 exit;   
   
   }else if(!isset($MName) && is_array($ID)){//全部用户为非母账号
     foreach($ID as $userID){
	    $ID=$userID;
		$DELchilds=$db->select_one('Mname','userinfo',"Mname !='' and ID='".$userID."'");//子账号
	    //修改该子账号的母账号的Mname的值  即去掉当前删除的子账号的ID
	    $Mname=$db->select_one("Mname","userinfo","account='".$DELchilds['Mname']."'");//母账号对应的子账号ID
		$str=$userID."#";
	    $mMname=str_replace($str,"",$Mname['Mname']);//母账号去掉当前删除的子账号的ID
	    $db->update_new('userinfo',"account='".$DELchilds['Mname']."'",array("Mname"=>$mMname)); 
	    include('del.php');//删除 用户提交时全部为非母账号的所有用户的删除
	 }//end foreach
   }// end !isset($MName)
	//有母账号，删除确认
	if(isset($_REQUEST['del'])){
	  if($_REQUEST['del']=='1'){//确认删除
	    $ID=explode(',',$ID);//del 提交传的所有删除用户页面提交的ID  需获取子ID
		foreach($ID as $userID){
		    $DELchild=$db->select_one('Mname','userinfo',"Mname like'%#%' and ID='".$userID."'");//该帐号下的所有子账号
		    $ID=$userID;
			
			$DELchilds=$db->select_one('Mname','userinfo',"Mname !='' and ID='".$userID."'");//子账号
			//修改该子账号的母账号的Mname的值  即去掉当前删除的子账号的ID
			$Mname=$db->select_one("Mname","userinfo","account='".$DELchilds['Mname']."'");//母账号对应的子账号ID
			$str=$userID."#";
			$mMname=str_replace($str,"",$Mname['Mname']);//母账号去掉当前删除的子账号的ID
			$db->update_new('userinfo',"account='".$DELchilds['Mname']."'",array("Mname"=>$mMname)); 
			
			include('del.php');//删除母账号 
		 if($DELchild){//母账号 
		   $childID=explode("#",$DELchild['Mname']);//该母账号下的子账号
		   foreach($childID as $userID){
			   $ID=$userID;
			   include('del.php');//删除子账号
		   } //end foreach $childID
		 } //end if $DELchild
		}//end $_REQUEST['del']=='1' 
	    echo "<script>alert('"._("删除成功")."');window.location.href='user.php';</script>";
	    exit;
	  }else{//取消删除
	    echo "<script>window.location.href='user.php';</script>";
	    exit;
	  }//end isset($_REQUEST['del']);
	}
}else if(strpos($ID,",")===false && !is_array($ID)){ //单账号删除 
	 //是否为母账号	 
	$UserName=getUserName($ID);
	$child=$db->select_one('Mname','userinfo','account="'.$UserName.'"');
	if(!isset($_REQUEST['del'])){
		if($child){
		  if(strpos($child['Mname'],"#")){//母账号
			echo "<script>if(window.confirm('".$UserName._("存在子账号且子账号一同删除！是否确认删除？")."')){window.location.href='user_del.php?ID=".$ID."&del=1&action=one'}else{window.location.href='user_del.php?ID=".$ID."&del=0&action=one'}</script>";
			exit;
		  }else{ //非母账号
			//修改母账号的子账号关联ID
			$DELchilds=$db->select_one('Mname','userinfo',"Mname !='' and ID='".$ID."'");//子账号
			//修改该子账号的母账号的Mname的值  即去掉当前删除的子账号的ID
			$Mname=$db->select_one("Mname","userinfo","account='".$DELchilds['Mname']."'");//母账号对应的子账号ID
			$str=$userID."#";
			$mMname=str_replace($str,"",$Mname['Mname']);//母账号去掉当前删除的子账号的ID
			$db->update_new('userinfo',"account='".$DELchilds['Mname']."'",array("Mname"=>$mMname)); 
			include('del.php');//删除子账号 
		 }
	  }//end if $child 
	}//end isset($_REQUEST['del'])
	
	if(isset($_REQUEST['del'])){
	  if($_REQUEST['del']=='1'){//删除
	    if(strpos($child['Mname'],"#")){//母账号
		 $CID=explode("#",$ID);
		 foreach($CID  as $ID){
		    include('del.php');//同时删除子母账号
		 }
		}
	  }else{
	    echo "<script>window.location.href='user.php';</script>";
	    exit;
	  }// end isset($_REQUEST['del']=='1'
	} //end isset($_REQUEST['del']
} 
echo "<script>alert('"._("删除成功")."');window.location.href='user.php';</script>";
?> 
</body>
</html>
