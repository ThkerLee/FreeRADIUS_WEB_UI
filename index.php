#!/bin/php
<?php
include("inc/conn.php"); 
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai'); 
if( $systemconfig["max_project"]== 0){
  $systemconfig["max_project"] = _("无限制");
} if( $systemconfig["max_user"]==0){
  $systemconfig["max_user"] = _("无限制");
}
$rs=$db->select_one("*","config","");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title><?=$rs["site"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=7" /> 
<style type="text/css">
<!--
.STYLE1 {
	font-size: 14px
}
-->
</style>
<style type="text/css">
body {
	font-family: arial, 宋体, serif;
	font-size:12px;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
* {
	margin:0px;
	padding:0px;
}
#nav {
	width:160px;
	line-height: 32px;
	list-style-type: none;
	text-align:left;
	color:#FFFFFF; /*定义整个ul菜单的行高和背景色*/
}
/*==================一级目录===================*/
#nav a {
	width: 160px;
	height:32xp;
	display: block;
	text-align:center; /*Width(一定要)，否则下面的Li会变形*/
}
 
#nav li {
	float:left;
	background:url(images/li_r8_c2.jpg) bottom;
	color:#DD1336;
	font-size:14px;
	font-family:宋体;
}
#nav li a:hover {
	background:url(images/li_r5_c2.jpg) bottom; /*一级目录onMouseOver显示的背景色*/
}
#nav a:link {
	color:#fff;
	text-decoration:none;
}
#nav a:visited {
	color:#fff;
	text-decoration:none;
}
#nav a:hover {
	color:#FFF;
	text-decoration:none;
}
/*==================二级目录===================*/
#nav li ul {
	list-style:none;
	text-align:left;
}
#nav li ul li {
	background:#E1EEFF; /*二级目录的背景色*/
	font-weight:normal;
}
#nav li ul a {
	text-align:center;
	color:#333333;
	width:160px;/* padding-left二级目录中文字向右移动，但Width必须重新设置=(总宽度-padding-left)*/
}
/*下面是二级目录的链接样式*/
#nav li ul a:link {
	color:#666;
	text-decoration:none;
}
#nav li ul a:visited {
	color:#666;
	text-decoration:none;
}
#nav li ul a:hover {
	color:#000;
	text-decoration:none;
	font-weight:normal;
	background:#ffffff;/* 二级onmouseover的字体颜色、背景色*/
}
/*==============================*/
#nav li:hover ul {
	left: auto;
}
#nav li.sfhover ul {
	left: auto;
}
#content {
	clear: left;
}
#nav ul.collapsed {
	display: none;
}
#PARENT {
	width:160px;
}
li{
display:block;
overflow:hidden;

}
</style>　　
<script type="text/javascript">

　　//** iframe自动适应页面 **//

　　//输入你希望根据页面高度自动调整高度的iframe的名称的列表

　　//用逗号把每个iframe的ID分隔. 例如: ["myframe1", "myframe2"]，可以只有一个窗体，则不用逗号。

　　//定义iframe的ID

　　var iframeids=["frame"]

　　//如果用户的浏览器不支持iframe是否将iframe隐藏 yes 表示隐藏，no表示不隐藏

　　var iframehide="yes"

　　function dyniframesize() 

　　{

　　var dyniframe=new Array()

　　for (i=0; i<iframeids.length; i++)

　　{

　　if (document.getElementById)

　　{

　　//自动调整iframe高度

　　dyniframe[dyniframe.length] = document.getElementById(iframeids[i]);

　　if (dyniframe[i] &&!window.opera)

　　{

　　dyniframe[i].style.display="block"

　　if (dyniframe[i].contentDocument &&dyniframe[i].contentDocument.body.offsetHeight) //如果用户的浏览器是NetScape

　　dyniframe[i].height = dyniframe[i].contentDocument.body.offsetHeight; 

　　else if (dyniframe[i].Document &&dyniframe[i].Document.body.scrollHeight) //如果用户的浏览器是IE

　　dyniframe[i].height = dyniframe[i].Document.body.scrollHeight;

　　}

　　}

　　//根据设定的参数来处理不支持iframe的浏览器的显示问题

　　if ((document.all || document.getElementById) &&iframehide=="no")

　　{

　　var tempobj=document.all? document.all[iframeids[i]] : document.getElementById(iframeids[i])

　　tempobj.style.display="block"

　　}

　　}

　　}

　　if (window.addEventListener) 
　　window.addEventListener("load", dyniframesize, false) 
　　else if (window.attachEvent) 
　　window.attachEvent("onload", dyniframesize) 
　　else 
　　window.onload=dyniframesize 
　　</script>
<script language="javascript" type="text/javascript">
 window.onload=function (){ //时间秒数自动跳动效果的JS
setInterval("document.getElementById('time').innerHTML=new Date().toLocaleString()+''.charAt(new Date().getDay());",1000);
 };
</script>
</head><body>
 
<?php  
//$rs=$db->select_one("*","config","");

//$auth_permision = $_SESSION['auth_permision'];//用户权限 即用户权限范围内的操作的文件
$managerAccount  = $_SESSION['manager'];//管理员账号
//$managerID 
$auth_permision=$db->select_one("*","manager","manager_account='".$managerAccount."' and ID= '".$_SESSION['managerID']."'"); 
$addusertotalnum =$auth_permision["addusertotalnum"];//允许开户人数
$addusernum      =$auth_permision["addusernum"];//已开户人数  
$auth_permision = $auth_permision['manager_permision']; //登录用户的所有权限
$auth_permision_arr = explode("#",$auth_permision);//登录用户权限数组
@array_pop($auth_permision_arr);
//print_r($auth_permision_arr);exit;
$manager = $db->select_all("permision_param","managerpermision","");//所有权限 
foreach($manager as $uValue){ 
    if(strpos($uValue['permision_param'],".php") ==true){//证明是一个xxx.php  所有用户的权限配置文件 
      //   echo $uValue['permision_param']."all<hr>"; //系统所有权限
		 if(!in_array($uValue['permision_param'], $auth_permision_arr)  ){//没有权限
		  //  echo $uValue['permision_param']. "：没有权限<hr>";
		   $authID = substr($uValue['permision_param'],0,-4);
		  /// echo $authID."<hr>"; 
		  // echo "<style>#{$authID}{display:none;overflow:hidden;} </style>"; 
		 }// else{
//		  echo "<style>#$authID{visibility:hidden;} </style>"; 
//		 }
	} 
}

//print_r($user);

//$index = array("")
//----------------------------------------2014.04.23修改版本划分：$data=1为ISP版、$data=2为加强版、$data=3为基础版-------------------------------------------------------// 
                 $file = popen("license -T","r");
                 $data = fgets($file);
                  pclose($file);
                 //$data=1;

?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="1%"><img src="images/spacer.gif" width="14" height="1" border="0" alt="" /></td>
    <td width="12%" class="STYLE1"><img src="images/spacer.gif" width="160" height="1" border="0" alt="" /></td>
    <td width="1%"><img src="images/spacer.gif" width="14" height="1" border="0" alt="" /></td>
    <td width="1%"><img src="images/spacer.gif" width="14" height="1" border="0" alt="" /></td>
    <td width="1%"><img src="images/spacer.gif" width="5" height="1" border="0" alt="" /></td>
    <td width="5%"><img src="images/spacer.gif" width="68" height="1" border="0" alt="" /></td>
    <td width="1%"><img src="images/spacer.gif" width="8" height="1" border="0" alt="" /></td>
    <td width="9%"><img src="images/spacer.gif" width="113" height="1" border="0" alt="" /></td>
    <td width="1%"><img src="images/spacer.gif" width="11" height="1" border="0" alt="" /></td>
    <td width="1%"><img src="images/spacer.gif" width="11" height="1" border="0" alt="" /></td>
    <td width="19%"><img src="images/spacer.gif" width="237" height="1" border="0" alt="" /></td>
    <td width="14%"><img src="images/spacer.gif" width="142" height="1" border="0" alt="" /></td>
    <td width="14%"><img src="images/spacer.gif" width="178" height="1" border="0" alt="" /></td>
    <td width="1%"><img src="images/spacer.gif" width="14" height="1" border="0" alt="" /></td>
    <td width="7%"><img src="images/spacer.gif" width="10" height="1" border="0" alt="" /></td>
    <td width="12%"><img src="images/spacer.gif" width="10" height="1" border="0" alt="" /></td>
    <td width="0%"><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
  </tr>
  <tr>
    <td colspan="16"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="40%" align="left" valign="top" background="images/li_r1_c7.jpg">
		  <img name="li_r1_c1" src="<?php echo  (!empty($rs["picTopLeft"]))?'images/'.$rs["picTopLeft"]:'images/li_r1_c1.jpg'; ?>" width="275" height="111" border="0" id="li_r1_c1" alt="" /> 
		  </td>
          <td width="60%" align="right" valign="top" background="images/li_r1_c10.jpg"><img name="li_r1_c12" src="<?php echo  (!empty($rs["picTopRight"]))?'images/'.$rs["picTopRight"]:'images/li_r1_c12.jpg';?>" width="354" height="111" border="0" id="li_r1_c12" alt="" /></td>
        </tr>
      </table></td>
    <td rowspan="4"><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
  </tr>
  <tr>
    <td colspan="16"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="14" background="images/li_r2_c1.jpg"><img name="li_r2_c1" src="images/li_r2_c1.jpg" width="14" height="100%" border="0" id="li_r2_c1" alt="" /></td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="160" valign="top"><table width="160" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="center" valign="middle"><img name="li_r2_c2" src="images/li2.jpg" width="160" border="0" id="li_r2_c2" alt="" /></td>
                    </tr>
                    <tr>
                      <td height="50" align="left" valign="middle" background="images/li3.jpg" class="STYLE1" style="line-height:16px;"> 
					<div  style="padding-top:5px;">&nbsp;&nbsp;<?php echo _("计费管理系统")?>
                                               <?php
                                               if($data ==1 ){
                                                   echo "<b style='font-size:13px;'>"."ISP版"."</b>";
                                               }elseif ($data == 2 ) {
                                                        echo "<b style='font-size:13px;'>"."企业版"."</b>";
                                                        }elseif ($data == 3) {
                                                         echo "<b style='font-size:13px;'>"."基础版"."</b>";           
                                                        }
                                               ?>
                                        </div>
					<div style="font-size:12px;color:#8897AA;">
					&nbsp;&nbsp; <? echo _("管 理 员：")?><?=$_SESSION["manager"]?>  <br>
					<div id="time"style="font-size:11px;color:#8897AA;"></div><!----时间秒数自动跳动效果的JS-2014.03.31---->
                                       &nbsp;&nbsp;
                                       <a href="login_out.php" target="frame" style=" text-decoration: none;color:red;"><? echo _("退出登录")?></a>
					</div> 
					  </td>
                    </tr>
                 <!-----ISP版----->  
                 <?php
                if($data == 1){
                 ?>
                    <tr>
                      <td height="28" align="center" valign="middle"><div id="PARENT">
					 <ul id="nav"> 
                            <li><a href="#Menu=ChildMenu1" onClick="DoMenu('ChildMenu1')"><? echo _("系统信息")?></a>
                               <ul id="ChildMenu1" class="collapsed">
                                         <li id="main"><a href="main.php" target="frame"><? echo _("系统信息")?></a></li>
					 <li id="guestbook"><a href="guestbook.php" target="frame"><? echo _("留言管理")?></a></li>
                              </ul>
                            </li> 
                             <li><a href="#Menu=ChildMenu12" onClick="DoMenu('ChildMenu12')"><? echo _("区域管理")?></a>
                              <ul id="ChildMenu12" class="collapsed">
                                  <?php 
                                  if(in_array("area_add.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>
                                <li id="area_add"><a href="area_add.php" target="frame"><? echo _("添加区域")?></a></li>
                                  <?php }
                                  if(in_array("area.php",$_SESSION["auth_permision"])){
                                  ?>
                                <li id="area"><a href="area.php" target="frame"><? echo _("区域管理")?></a></li> 
                                  <?php }?>
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu2" onClick="DoMenu('ChildMenu2')"><? echo _("项目管理")?></a>
                              <ul id="ChildMenu2" class="collapsed">
                                  <?php 
                                  if(in_array("project_add.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>
                                        <li id="project_add"><a href="project_add.php" target="frame"><? echo _("添加项目")?></a></li>
                                  <?php }
                                  if(in_array("project.php",$_SESSION["auth_permision"])){
                                  ?>
                                        <li id="project"><a href="project.php" target="frame"><? echo _("项目管理")?></a></li>
                                  <?php }
                                  if(in_array("project_ros.php",$_SESSION["auth_permision"])){
                                  ?>
                                       <!-- <li id="project_ros"><a href="project_ros.php" target="frame"><? echo _("NAS 同步")?></a></li> -->
                                  <?php }
                                  if(in_array("ippool.php",$_SESSION["auth_permision"])){
                                  ?>
                                        <li id="ippool"><a href="ippool.php" target="frame"><? echo _("地址池管理")?></a></li>
                                  <?php }?>
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu3" onClick="DoMenu('ChildMenu3')"><? echo _("产品管理")?></a>
                              <ul id="ChildMenu3" class="collapsed">
                               <?php 
                                  if(in_array("product_add.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                ?>  
                                <li id="product_add"><a href="product_add.php" target="frame"><? echo _("添加产品")?></a></li>
                                  <?php }
                                  if(in_array("product.php",$_SESSION["auth_permision"])){
                                  ?>
                                <li id="product"><a href="product.php" target="frame"><? echo _("产品管理")?></a></li>
                                  <?php }
                                 if(in_array("speedrule_add.php",$_SESSION["auth_permision"])){ 
                                  ?>
                              <!--  <li id="speedrule_add"><a href="speedrule_add.php" target="frame"><? echo _("内网限速")?></a></li>-->
                                 <?php }
                                 if(in_array("speedrule.php",$_SESSION["auth_permision"])){
                                 ?>
                              <!--  <li id="speedrule"><a href="speedrule.php" target="frame"><? echo _("规则管理")?></a></li>-->
                                 <?php }
                                 if(in_array("chart_product.php",$_SESSION["auth_permision"])){
                                 ?>
				<!--<li id="chart_product"><a href="chart_product.php" target="frame"><? echo _("图表分析")?></a></li>-->
                                 <?php }?>
                              </ul>
                            </li> 					
                            <li><a href="#Menu=ChildMenu4" onClick="DoMenu('ChildMenu4')"><? echo _("用户管理")?></a>
                              <ul id="ChildMenu4" class="collapsed">
                                  <?php 
                                  if(in_array("user_add.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                ?>
                                  
                                                                <li id="user_add"><a href="user_add.php" target="frame"><? echo _("添加用户")?></a></li> 
                                  <?php }
                                  if(in_array("more_add.php",$_SESSION["auth_permision"])){
                                  ?>
                                                                <li id="more_add"><a href="more_add.php" target="frame"><? echo _("批量添加")?></a></li>
                                  <?php }
                                   if(in_array("user.php",$_SESSION["auth_permision"])){
                                  ?>
                                                                <li id="user"><a href="user.php" target="frame"><? echo _("用户管理")?></a></li>
                                   <?php }
                                   if(in_array("user_Mname_info.php",$_SESSION["auth_permision"])){
                                   ?>
							<!--	
							  <li id="user_netbar"><a href="user_netbar.php" target="frame"><? echo _("时长计费")?></a></li>
							-->
								<li id="user_Mname_info"><a href="user_Mname_info.php" target="frame"><? echo _("子母账号")?></a></li>
                                   <?php }
                                   if(in_array("user_shutdown.php",$_SESSION["auth_permision"])){
                                   ?>
								<li id="user_shutdown"><a href="user_shutdown.php" target="frame"><? echo _("停机恢复")?></a></li>
                                   <?php }
                                   if(in_array("user_closing.php",$_SESSION["auth_permision"])){
                                   ?>
								<li id="user_closing"><a href="user_closing.php" target="frame"><? echo _("用户销户")?></a></li>
                                   <?php }
                                   if(in_array("user_closing_info.php",$_SESSION["auth_permision"])){
                                   ?>
								<li id="user_closing_info"><a href="user_closing_info.php" target="frame"><? echo _("销户用户")?></a></li> 
                                   <?php }
                                   if(in_array("user_shutdown.php",$_SESSION["auth_permision"])){
                                   ?>
								<li id="user_stop_info"><a href="user_shutdown.php?action=pause" target="frame"><? echo _("停机保号")?></a></li> 
                                    <?php }
                                   if(in_array("user_pause.php",$_SESSION["auth_permision"])){
                                   ?>                                  
								<li id="user_pause"><a href="user_pause.php" target="frame"><? echo _("暂停用户")?></a></li>
                                     <?php }
                                   if(in_array("user_normal_info.php",$_SESSION["auth_permision"])){
                                   ?>                                                                
								<li id="user_normal_info"><a href="user_normal_info.php" target="frame"><? echo _("在网用户")?></a></li>
                                     <?php }
                                   if(in_array("user_upcoming.php",$_SESSION["auth_permision"])){
                                   ?>                                                                
								<li id="user_upcoming"><a href="user_upcoming.php" target="frame"><? echo _("即将到期")?></a></li>
                                     <?php }
                                   if(in_array("user_maturity.php",$_SESSION["auth_permision"])){
                                   ?>                                                                 
								<li id="user_maturity"><a href="user_maturity.php" target="frame"><? echo _("到期用户")?></a></li>
                                      <?php }
                                   if(in_array("user_show_passwprd.php",$_SESSION["auth_permision"])){
                                   ?>                                                                
								<li id="user_show_passwprd"><a href="user_show_passwprd.php" target="frame"><? echo _("密码查询")?></a></li>
                                      <?php }
                                   if(in_array("chart_user.php",$_SESSION["auth_permision"])){
                                   ?>                                                                  
								<li id="chart_user"><a href="chart_user.php" target="frame"><? echo _("图表分析")?></a></li> 
                                   <?php }?>
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu7" onClick="DoMenu('ChildMenu7')"><? echo _("营帐管理")?></a>
                              <ul id="ChildMenu7" class="collapsed"> 
                                  <?php 
                                  if(in_array("recharge_user.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>                                  
							    <li id="recharge_user"><a href="recharge_user.php" target="frame"><? echo _("用户充值")?></a></li> 
                                      <?php }
                                   if(in_array("financial_subjects.php",$_SESSION["auth_permision"])){
                                   ?>                                     
							    <li id="financial_subjects"><a href="financial_subjects.php" target="frame"><? echo _("财务科目")?></a></li> 
                                      <?php }
                                   if(in_array("finance_MTC_add.php",$_SESSION["auth_permision"])){
                                   ?>                                    
							    <li id="finance_MTC_add"><a href="finance_MTC_add.php" target="frame"><? echo _("人工收费")?></a></li>
                                      <?php }
                                   if(in_array("order_add.php",$_SESSION["auth_permision"])){
                                   ?>                                                             
                                                            <li id="order_add"><a href="order_add.php" target="frame"><? echo _("用户续费")?></a></li>
                                      <?php }
                                   if(in_array("recharge_reverse.php",$_SESSION["auth_permision"])){
                                   ?>                                    
                                                            <li id="recharge_reverse"><a href="recharge_reverse.php" target="frame"><? echo _("用户冲帐")?></a></li>
                                       <?php }
                                   if(in_array("user_pledgemoney.php",$_SESSION["auth_permision"])){
                                   ?>                                                            
                                                            <li id="user_pledgemoney"><a href="user_pledgemoney.php" target="frame">押金退费</a></li> 
                                       <?php }
                                   if(in_array("user_flow_monitor.php",$_SESSION["auth_permision"])){
                                   ?>                                                             
                                                            <li id="user_flow_monitor"><a href="user_flow_monitor.php" target="frame"><? echo _("流量监控")?></a></li> 
                                       <?php }
                                   if(in_array("user_hours_show.php",$_SESSION["auth_permision"])){
                                   ?>                                                             
                                                            <li id="user_hours_show"><a href="user_hours_show.php" target="frame"><? echo _("时长监控")?></a></li>
                                       <?php }
                                   if(in_array("endtime_modification.php",$_SESSION["auth_permision"])){
                                   ?>                                                             
                                                            <li id="endtime_modification"><a href="endtime_modification.php" target="frame">批量修改</a></li> 
                                       <?php }
                                   if(in_array("order_run.php",$_SESSION["auth_permision"])){
                                   ?>                                                            
                                                            <li id="order_run"><a href="order_run.php" target="frame"><? echo _("运行订单")?></a></li>
                                       <?php }
                                   if(in_array("order.php",$_SESSION["auth_permision"])){
                                   ?>                                                              
                                                            <li id="order"><a href="order.php" target="frame"><? echo _("订单记录")?></a></li>
                                        <?php }
                                   if(in_array("recharge_log.php",$_SESSION["auth_permision"])){
                                   ?>                                                            
                                                            <li id="recharge_log"><a href="recharge_log.php" target="frame"><? echo _("充值记录")?></a></li>  
                                        <?php }
                                   if(in_array("finance_report.php",$_SESSION["auth_permision"])){
                                   ?>                                                              
                                                            <li id="finance_report"><a href="finance_report.php" target="frame"><? echo _("营业报表")?></a></li>
                                        <?php }
                                   if(in_array("user_bill.php",$_SESSION["auth_permision"])){
                                   ?>                                                              
                                                            <li id="user_bill"><a href="user_bill.php" target="frame"><? echo _("用户帐单")?></a></li>
                                         <?php }
                                   if(in_array("finance_details.php",$_SESSION["auth_permision"])){
                                   ?>                                                             
                                                            <li id="finance_details"><a href="finance_details.php" target="frame"><?php echo _("用户对账")?></a></li>
                                         <?php }
                                   if(in_array("chart_report.php",$_SESSION["auth_permision"])){
                                   ?>                                                             
                                                          <!--  <li id="chart_report"><a href="chart_report.php" target="frame"><? echo _("图形报表")?></a></li>-->
                                          <?php }
                                   if(in_array("chart_report_pie.php",$_SESSION["auth_permision"])){     
                                   ?>                                                             
                                                            <li id="chart_report_pie"><a href="chart_report_pie.php" target="frame"><?php echo _("图表分析")?></a></li>
                                   <?php }?>
                              </ul>
                            </li>
                            	
                            <li><a href="#Menu=ChildMenu101" onClick="DoMenu('ChildMenu101')"><? echo _("工单管理")?></a>
                              <ul id="ChildMenu101" class="collapsed">
                                  <?php 
                                  if(in_array("repair_add.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>                                   
                                                                <li id="repair_add"><a href="repair_add.php" target="frame"><? echo _("工单录入")?></a></li>
                                  <?php }
                                   if(in_array("repair.php",$_SESSION["auth_permision"])){     
                                   ?>                                                                  
                                                                <li id="repair"><a href="repair.php" target="frame"><? echo _("工单记录")?></a></li>
                                  <?php }
                                   if(in_array("repair_disposal_log.php",$_SESSION["auth_permision"])){     
                                   ?>                                                                 
								<li id="repair_disposal_log"><a href="repair_disposal_log.php" target="frame"><? echo _("处理记录")?></a></li>
                                   <?php }?>
                              </ul>
                            </li>													
                            <li><a href="#Menu=ChildMenu8" onClick="DoMenu('ChildMenu8')"><? echo _("运营管理")?></a>
                              <ul id="ChildMenu8" class="collapsed">
							  	<!--<li id="user_hour_bill"><a href="user_hour_bill.php" target="frame">记时帐单</a></li>-->
                                    <?php 
                                  if(in_array("operate_online.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>         
                                            <li id="operate_online"><a href="operate_online.php" target="frame"><? echo _("在线管理")?></a></li>
                                  <?php }
                                   if(in_array("operate_netplay_log.php",$_SESSION["auth_permision"])){     
                                   ?>                                        
                                            <li id="operate_netplay_log"><a href="operate_netplay_log.php" target="frame"><? echo _("上网记录")?></a></li>
                                   <?php }
                                   if(in_array("radius_log.php",$_SESSION["auth_permision"])){     
                                   ?>                                           
                                            <li id="radius_log"><a href="radius_log.php" target="frame"><? echo _("失败记录")?></a></li>
                                   <?php }
                                   if(in_array("operate_userlog.php",$_SESSION["auth_permision"])){     
                                   ?>                                            
                                            <li id="operate_userlog"><a href="operate_userlog.php" target="frame"><? echo _("操作日志")?></a></li>
                                   <?php }
                                   if(in_array("operate_login_log.php",$_SESSION["auth_permision"])){     
                                   ?>                                            
                                            <li id="operate_login_log"><a href="operate_login_log.php" target="frame"><? echo _("登录记录")?></a></li>
                                   <?php }?>
                              </ul>
                            </li>
                            <li><a href="#Menu=ChildMenu11" onClick="DoMenu('ChildMenu11')"><? echo _("卡片管理")?></a>
                              <ul id="ChildMenu11" class="collapsed">
                                  <?php 
                                  if(in_array("card_add.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>                                    
                                                                <li id="card_add"><a href="card_add.php" target="frame"><? echo _("生成卡片")?></a></li>
                                    <?php }
                                   if(in_array("card.php",$_SESSION["auth_permision"])){     
                                   ?>                                                               
                                                                <li id="card"><a href="card.php" target="frame"><? echo _("卡片销售")?></a></li>
                                     <?php }
                                   if(in_array("card_search.php",$_SESSION["auth_permision"])){     
                                   ?>                                                                
								<li id="card_search"><a href="card_search.php" target="frame"><? echo _("卡片查询")?></a></li>
                                   <?php }
                                   if(in_array("card_sold_show.php",$_SESSION["auth_permision"])){     
                                   ?>                                                                 
								<li id="card_sold_show"><a href="card_sold_show.php" target="frame"><? echo _("销售预览")?></a></li>
                                   <?php }?>
                              </ul>
                            </li>

                           <li><a href="#Menu=ChildMenu18" onClick="DoMenu('ChildMenu18')"><? echo _("网银管理")?></a>
                               <ul id="ChildMenu18" class="collapsed">
                                  <?php 
                                  if(in_array("instantPaymen.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>                                     
                                             <li id="instantPaymen"><a href="instantPaymen.php" target="frame"><? echo _("支付宝设置")?></a></li>
                                  <?php }?>
                              </ul>
                            </li> 
                                             
                          <li><a href="#Menu=ChildMenu19" onClick="DoMenu('ChildMenu19')"><? echo _("短信管理")?></a>
                               <ul id="ChildMenu19" class="collapsed">
                                  <?php 
                                  if(in_array("system_message_k.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>                                    
                               <li id="system_message_k"><a href="system_message_k.php" target="frame"><? echo _("开户短信")?></a></li>
                                   <?php }
                                   if(in_array("system_message_x.php",$_SESSION["auth_permision"])){     
                                   ?>                               
			       <li id="system_message_x"><a href="system_message_x.php" target="frame"><? echo _("续费短信")?></a></li>
                                   <?php }
                                   if(in_array("system_message_d.php",$_SESSION["auth_permision"])){     
                                   ?>                               
                               <li id="system_message_d"><a href="system_message_d.php" target="frame"><? echo _("到期短信")?></a></li>
                                    <?php }
                                   if(in_array("system_message_j.php",$_SESSION["auth_permision"])){     
                                   ?>                              
                               <li id="system_message_j"><a href="system_message_j.php" target="frame"><? echo _("即将到期短信")?></a></li>
                                    <?php }
                                   if(in_array("system_message_z.php",$_SESSION["auth_permision"])){     
                                   ?>                                 
                               <li id="system_message_z"><a href="system_message_z.php" target="frame"><? echo _("自定义短信")?></a></li>
                                   <?php }?>
                              <!-- <li id="system_message_q"><a href="system_message_q.php" target="frame"><? echo _("群发短信")?></a></li>-->
                              </ul>
                            </li> 
                                             
                            <li><a href="#Menu=ChildMenu9" onClick="DoMenu('ChildMenu9')"><? echo _("备份恢复")?></a>
                              <ul id="ChildMenu9" class="collapsed">
                                   <?php 
                                  if(in_array("db_auto.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>                                   
                                <li id="db_auto"><a href="db_auto.php" target="frame"><? echo _("自动备份")?></a></li>
                                    <?php }
                                   if(in_array("db_backup_ftp.php",$_SESSION["auth_permision"])){     
                                   ?>                                
				<li id="db_backup_ftp"><a href="db_backup_ftp.php" target="frame"><? echo _("FTP 备份")?></a></li>
                                    <?php }
                                   if(in_array("db_backup.php",$_SESSION["auth_permision"])){     
                                   ?>                                  
                                <li id="db_backup"><a href="db_backup.php" target="frame"><? echo _("数据备份")?></a></li>
                                    <?php }
                                   if(in_array("mail_backup.php",$_SESSION["auth_permision"])){     
                                   ?>                                 
                                <li id="mail_backup"><a href="mail_backup.php" target="frame"><? echo _("邮箱备份")?></a></li>
                                    <?php }
                                   if(in_array("db_backup_tb.php",$_SESSION["auth_permision"])){     
                                   ?>                                 
                                <li id="db_backup"><a href="db_backup_tb.php" target="frame"><? echo _("数据同步")?></a></li> 
                                    <?php }
                                   if(in_array("db_restore.php",$_SESSION["auth_permision"])){     
                                   ?>                                 
                                <li id="db_restore"><a href="db_restore.php" target="frame"><? echo _("数据恢复")?></a></li>
                                    <?php }
                                   if(in_array("db_user_import.php",$_SESSION["auth_permision"])){     
                                   ?>                                 
                                 <li id="db_user_import"><a href="db_user_import.php" target="frame"><? echo _("数据导入")?></a></li>
                                   <?php }?>
                              </ul>
                            </li>
					
                            <li><a href="#Menu=ChildMenu10" onClick="DoMenu('ChildMenu10')"><? echo _("系统设置")?></a>
                              <ul id="ChildMenu10" class="collapsed">
                                    <?php 
                                  if(in_array("manager.php",$_SESSION["auth_permision"])){  //2014.09.1 修改没的权限 就不显示改功能
                                  ?>                                 
                                <li id="manager"><a href="manager.php" target="frame"><? echo _("系统用户")?></a></li>
                                    <?php }
                                   if(in_array("manager_group.php",$_SESSION["auth_permision"])){     
                                   ?>                                 
                                <li id="manager_group"><a href="manager_group.php" target="frame"><? echo _("用户角色")?></a></li>
                                   <?php }
                                   if(in_array("accept_user.php",$_SESSION["auth_permision"])){ 
                                   ?>
                                <li id="accept_user"><a href="accept_user.php" target="frame"><? echo _("受理人员")?></a></li>
                                    <?php }
                                   if(in_array("manager_pwd_edit.php",$_SESSION["auth_permision"])){     
                                   ?>                                
                                <li id="manager_pwd_edit"><a href="manager_pwd_edit.php" target="frame"><? echo _("密码修改")?></a></li>
                                     <?php }
                                   if(in_array("system_upgrade.php",$_SESSION["auth_permision"])){     
                                   ?>                                
                                <li id="system_upgrade"><a href="system_upgrade.php" target="frame"><? echo _("系统升级")?></a></li>
				<!--<li id="system_message"><a href="system_message.php" target="frame"><? echo _("短信设置")?></a></li>-->
                                  <?php }
                                   if(in_array("order_ticket.php",$_SESSION["auth_permision"])){     
                                   ?>                                
				<li id="order_ticket"><a href="order_ticket.php" target="frame"><? echo _("票据设置")?></a></li> 
                                   <?php }
                                   if(in_array("system_publicnotice.php",$_SESSION["auth_permision"])){     
                                   ?>                                
				<li id="system_publicnotice"><a href="system_publicnotice.php" target="frame">ROS 通告</a></li>
                                    <?php }
                                   if(in_array("alcatel_notice.php",$_SESSION["auth_permision"])){     
                                   ?>                               
				<li id="alcatel_notice"><a href="alcatel_notice.php" target="frame">贝尔通告</a></li>
								<!-- 
								<li id="system_client_notice"><a href="system_client_notice.php" target="frame">自助通告</a></li>
								<li id="system_ros"><a href="system_ros.php" target="frame">到期通告</a></li>
								<li id='system_billing'><a href="system_billing.php" target="frame">计费通告</a></li> 
								<li id="system_mac"><a href="system_mac.php" target="frame"><? echo _("MAC 配置")?></a></li>
								<li id="system_speed"><a href="system_speed.php" target="frame"><? echo _("内网配置")?></a></li>
								<li id="system_online"><a href="system_online.php" target="frame"><? echo _("在线人数")?></a></li> 
								-->
                                                                <?php }
                                                               if(in_array("system_configuration.php",$_SESSION["auth_permision"])){     
                                                               ?>  								
								<li id="system_database"><a href="system_configuration.php" target="frame"><? echo _("批量配置")?></a></li>
                                                                <?php }
                                                               if(in_array("system_config.php",$_SESSION["auth_permision"])){     
                                                               ?>                                                                 
                                                                <li id="system_config"><a href="system_config.php" target="frame"><? echo _("界面配置")?></a></li>
                                                               <?php }
                                                               if(in_array("system_database.php",$_SESSION["auth_permision"])){     
                                                               ?>                                                               
								 <li id="system_database"><a href="system_database.php" target="frame"><? echo _("数据库管理")?></a></li> 
								<!--
								 <li id="db_sync_mysql"><a href="db_sync_mysql.php" target="frame"><? echo _("数据库同步")?></a></li>
						
								<li id="system_database_XMLA"><a href="system_database_XMLA.php" target="frame"><? echo _("数据库同步")?></a></li>
						  	-->
                                                               <?php }
                                                               if(in_array("truncate_alltable.php",$_SESSION["auth_permision"])){     
                                                               ?>                                                         
								<li id="truncate_alltable"><a href="truncate_alltable.php" target="frame"><? echo _("数据库还原")?></a></li>
                                                               <?php }
                                                               if(in_array("cron.php",$_SESSION["auth_permision"])){     
                                                               ?>                                                                 
								<li id="cron"><a href="cron.php" target="frame">计划任务</a></li>
                                                                <?php }
                                                               if(in_array("system_del_dial_log.php",$_SESSION["auth_permision"])){     
                                                               ?>                                                                
								<li id="system_del_dial_log"><a href="system_del_dial_log.php" target="frame"><? echo _("清空记录")?></a></li>
                                                                 <?php }    ?> 
                                                                                                                                          
                                                                <li id="login_out"><a href="login_out.php" target="frame"><? echo _("退出登录")?></a></li>
                              </ul>
                            </li>
                          </ul>
                        </div>
<script type=text/javascript><!--
var LastLeftID = "";
function menuFix() {
 var obj = document.getElementById("nav").getElementsByTagName("li");
 
 for (var i=0; i<obj.length; i++) {
  obj[i].onmouseover=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onMouseDown=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onMouseUp=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onmouseout=function() {
   this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"), "");
  }
 }
}
function DoMenu(emid)
{
 var obj = document.getElementById(emid); 
 obj.className = (obj.className.toLowerCase() == "expanded"?"collapsed":"expanded");
 if((LastLeftID!="")&&(emid!=LastLeftID)) //关闭上一个Menu
 {
  document.getElementById(LastLeftID).className = "collapsed";
 }
 LastLeftID = emid;
}
function GetMenuID()
{
 var MenuID="";
 var _paramStr = new String(window.location.href);
 var _sharpPos = _paramStr.indexOf("#");
 
 if (_sharpPos >= 0 && _sharpPos < _paramStr.length - 1)
 {
  _paramStr = _paramStr.substring(_sharpPos + 1, _paramStr.length);
 }
 else
 {
  _paramStr = "";
 }
 
 if (_paramStr.length > 0)
 {
  var _paramArr = _paramStr.split("&");
  if (_paramArr.length>0)
  {
   var _paramKeyVal = _paramArr[0].split("=");
   if (_paramKeyVal.length>0)
   {
    MenuID = _paramKeyVal[1];
   }
  }
  /*
  if (_paramArr.length>0)
  {
   var _arr = new Array(_paramArr.length);
  }
  
  //取所有#后面的，菜单只需用到Menu
  //for (var i = 0; i < _paramArr.length; i++)
  {
   var _paramKeyVal = _paramArr[i].split('=');
   
   if (_paramKeyVal.length>0)
   {
    _arr[_paramKeyVal[0]] = _paramKeyVal[1];
   }  
  }
  */
 }
 
 if(MenuID!="")
 {
  DoMenu(MenuID)
 }
}
GetMenuID(); //*这两个function的顺序要注意一下，不然在Firefox里GetMenuID()不起效果
menuFix();
--></script>
                      </td>
                    </tr>
 <!-----ISP版完-----> 
 <!---基础版开始--->
                <?php                 
                }elseif ($data == 3) {  //基础版
                ?>
                 <tr>
                      <td height="28" align="center" valign="middle"><div id="PARENT">
					 <ul id="nav"> 
                            <li><a href="#Menu=ChildMenu1" onClick="DoMenu('ChildMenu1')"><? echo _("系统信息")?></a>
                               <ul id="ChildMenu1" class="collapsed">
                                <li id="main"><a href="main.php" target="frame"><? echo _("系统信息")?></a></li>
                              </ul>
                            </li> 
                             <li><a href="#Menu=ChildMenu12" onClick="DoMenu('ChildMenu12')"><? echo _("区域管理")?></a>
                              <ul id="ChildMenu12" class="collapsed">
                                <li id="area_add"><a href="area_add.php" target="frame"><? echo _("添加区域")?></a></li>                              
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu2" onClick="DoMenu('ChildMenu2')"><? echo _("项目管理")?></a>
                              <ul id="ChildMenu2" class="collapsed">
                                <li id="project_add"><a href="project_add.php" target="frame"><? echo _("添加项目")?></a></li>
                                <li id="project"><a href="project.php" target="frame"><? echo _("项目管理")?></a></li>
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu3" onClick="DoMenu('ChildMenu3')"><? echo _("产品管理")?></a>
                              <ul id="ChildMenu3" class="collapsed">
                                <li id="product_add"><a href="product_add.php" target="frame"><? echo _("添加产品")?></a></li>
                                <li id="product"><a href="product.php" target="frame"><? echo _("产品管理")?></a></li>
                              </ul>
                            </li> 					
                            <li><a href="#Menu=ChildMenu4" onClick="DoMenu('ChildMenu4')"><? echo _("用户管理")?></a>
                              <ul id="ChildMenu4" class="collapsed"> 
                                <li id="user_add"><a href="user_add.php" target="frame"><? echo _("添加用户")?></a></li> 
                                <li id="user"><a href="user.php" target="frame"><? echo _("用户管理")?></a></li>
								<li id="user_shutdown"><a href="user_shutdown.php" target="frame"><? echo _("停机恢复")?></a></li>
								<li id="user_closing"><a href="user_closing.php" target="frame"><? echo _("用户销户")?></a></li>  
								<li id="user_closing_info"><a href="user_closing_info.php" target="frame"><? echo _("销户用户")?></a></li> 
								<li id="user_stop_info"><a href="user_shutdown.php?action=pause" target="frame"><? echo _("停机保号")?></a></li> 
								<li id="user_pause"><a href="user_pause.php" target="frame"><? echo _("暂停用户")?></a></li>
								<li id="user_normal_info"><a href="user_normal_info.php" target="frame"><? echo _("在网用户")?></a></li>
								<li id="user_upcoming"><a href="user_upcoming.php" target="frame"><? echo _("即将到期")?></a></li>
								<li id="user_maturity"><a href="user_maturity.php" target="frame"><? echo _("到期用户")?></a></li>
								<li id="user_show_passwprd"><a href="user_show_passwprd.php" target="frame"><? echo _("密码查询")?></a></li>
								<li id="chart_user"><a href="chart_user.php" target="frame"><? echo _("图表分析")?></a></li> 
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu7" onClick="DoMenu('ChildMenu7')"><? echo _("营帐管理")?></a>
                              <ul id="ChildMenu7" class="collapsed"> 
							    <li id="recharge_user"><a href="recharge_user.php" target="frame"><? echo _("用户充值")?></a></li> 								
							  	<li id="order_add"><a href="order_add.php" target="frame"><? echo _("用户续费")?></a></li>  
								<li id="order_run"><a href="order_run.php" target="frame"><? echo _("运行订单")?></a></li>	
								<li id="order"><a href="order.php" target="frame"><? echo _("订单记录")?></a></li>							 
                                <li id="recharge_log"><a href="recharge_log.php" target="frame"><? echo _("充值记录")?></a></li>  		
								<li id="finance_report"><a href="finance_report.php" target="frame"><? echo _("营业报表")?></a></li>
                              </ul>
                            </li>                            														
                            <li><a href="#Menu=ChildMenu8" onClick="DoMenu('ChildMenu8')"><? echo _("运营管理")?></a>
                              <ul id="ChildMenu8" class="collapsed">
							  	<!--<li id="user_hour_bill"><a href="user_hour_bill.php" target="frame">记时帐单</a></li>-->
                                <li id="operate_online"><a href="operate_online.php" target="frame"><? echo _("在线管理")?></a></li>
                                <li id="operate_netplay_log"><a href="operate_netplay_log.php" target="frame"><? echo _("上网记录")?></a></li>
                              </ul>
                            </li>                                            
                            <li><a href="#Menu=ChildMenu9" onClick="DoMenu('ChildMenu9')"><? echo _("备份恢复")?></a>
                              <ul id="ChildMenu9" class="collapsed">
                                <li id="db_backup"><a href="db_backup.php" target="frame"><? echo _("数据备份")?></a></li>
                           
                                <li id="db_restore"><a href="db_restore.php" target="frame"><? echo _("数据恢复")?></a></li>
                              </ul>
                            </li>
					
                            <li><a href="#Menu=ChildMenu10" onClick="DoMenu('ChildMenu10')"><? echo _("系统设置")?></a>
                              <ul id="ChildMenu10" class="collapsed">
                                <li id="manager"><a href="manager.php" target="frame"><? echo _("系统用户")?></a></li>
                                <li id="manager_pwd_edit"><a href="manager_pwd_edit.php" target="frame"><? echo _("密码修改")?></a></li>
                                <li id="system_upgrade"><a href="system_upgrade.php" target="frame"><? echo _("系统升级")?></a></li>
				<li id="system_database"><a href="system_configuration.php" target="frame"><? echo _("批量配置")?></a></li>
				<li id="system_database"><a href="system_database.php" target="frame"><? echo _("数据库管理")?></a></li> 
				<li id="truncate_alltable"><a href="truncate_alltable.php" target="frame"><? echo _("数据库还原")?></a></li>
                                <li id="login_out"><a href="login_out.php" target="frame"><? echo _("退出登录")?></a></li>
                              </ul>
                            </li>
                          </ul>
                        </div>
<script type=text/javascript><!--
var LastLeftID = "";
function menuFix() {
 var obj = document.getElementById("nav").getElementsByTagName("li");
 
 for (var i=0; i<obj.length; i++) {
  obj[i].onmouseover=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onMouseDown=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onMouseUp=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onmouseout=function() {
   this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"), "");
  }
 }
}
function DoMenu(emid)
{
 var obj = document.getElementById(emid); 
 obj.className = (obj.className.toLowerCase() == "expanded"?"collapsed":"expanded");
 if((LastLeftID!="")&&(emid!=LastLeftID)) //关闭上一个Menu
 {
  document.getElementById(LastLeftID).className = "collapsed";
 }
 LastLeftID = emid;
}
function GetMenuID()
{
 var MenuID="";
 var _paramStr = new String(window.location.href);
 var _sharpPos = _paramStr.indexOf("#");
 
 if (_sharpPos >= 0 && _sharpPos < _paramStr.length - 1)
 {
  _paramStr = _paramStr.substring(_sharpPos + 1, _paramStr.length);
 }
 else
 {
  _paramStr = "";
 }
 
 if (_paramStr.length > 0)
 {
  var _paramArr = _paramStr.split("&");
  if (_paramArr.length>0)
  {
   var _paramKeyVal = _paramArr[0].split("=");
   if (_paramKeyVal.length>0)
   {
    MenuID = _paramKeyVal[1];
   }
  }
  /*
  if (_paramArr.length>0)
  {
   var _arr = new Array(_paramArr.length);
  }
  
  //取所有#后面的，菜单只需用到Menu
  //for (var i = 0; i < _paramArr.length; i++)
  {
   var _paramKeyVal = _paramArr[i].split('=');
   
   if (_paramKeyVal.length>0)
   {
    _arr[_paramKeyVal[0]] = _paramKeyVal[1];
   }  
  }
  */
 }
 
 if(MenuID!="")
 {
  DoMenu(MenuID)
 }
}
GetMenuID(); //*这两个function的顺序要注意一下，不然在Firefox里GetMenuID()不起效果
menuFix();
--></script>
                      </td>
                    </tr>
<!---基础版结束---> 
<!---加强版开始--->
                <?php
                }elseif ($data == 2) {
                ?>
                    <tr>
                      <td height="28" align="center" valign="middle"><div id="PARENT">
					 <ul id="nav"> 
                            <li><a href="#Menu=ChildMenu1" onClick="DoMenu('ChildMenu1')"><? echo _("系统信息")?></a>
                               <ul id="ChildMenu1" class="collapsed">
                                <li id="main"><a href="main.php" target="frame"><? echo _("系统信息")?></a></li>
								<li id="guestbook"><a href="guestbook.php" target="frame"><? echo _("留言管理")?></a></li>
                              </ul>
                            </li> 
                             <li><a href="#Menu=ChildMenu12" onClick="DoMenu('ChildMenu12')"><? echo _("区域管理")?></a>
                              <ul id="ChildMenu12" class="collapsed">
                                <li id="area_add"><a href="area_add.php" target="frame"><? echo _("添加区域")?></a></li>
                                <li id="area"><a href="area.php" target="frame"><? echo _("区域管理")?></a></li> 
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu2" onClick="DoMenu('ChildMenu2')"><? echo _("项目管理")?></a>
                              <ul id="ChildMenu2" class="collapsed">
                                <li id="project_add"><a href="project_add.php" target="frame"><? echo _("添加项目")?></a></li>
                                <li id="project"><a href="project.php" target="frame"><? echo _("项目管理")?></a></li>
								                <li id="project_ros"><a href="project_ros.php" target="frame"><? echo _("NAS 同步")?></a></li> 
								                <li id="ippool"><a href="ippool.php" target="frame"><? echo _("地址池管理")?></a></li>
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu3" onClick="DoMenu('ChildMenu3')"><? echo _("产品管理")?></a>
                              <ul id="ChildMenu3" class="collapsed">
                                <li id="product_add"><a href="product_add.php" target="frame"><? echo _("添加产品")?></a></li>
                                <li id="product"><a href="product.php" target="frame"><? echo _("产品管理")?></a></li>
                                <li id="speedrule_add"><a href="speedrule_add.php" target="frame"><? echo _("内网限速")?></a></li>
                                <li id="speedrule"><a href="speedrule.php" target="frame"><? echo _("规则管理")?></a></li>
				<li id="chart_product"><a href="chart_product.php" target="frame"><? echo _("图表分析")?></a></li>
                              </ul>
                            </li> 					
                            <li><a href="#Menu=ChildMenu4" onClick="DoMenu('ChildMenu4')"><? echo _("用户管理")?></a>
                              <ul id="ChildMenu4" class="collapsed"> 
                                <li id="user_add"><a href="user_add.php" target="frame"><? echo _("添加用户")?></a></li> 
                                <li id="more_add"><a href="more_add.php" target="frame"><? echo _("批量添加")?></a></li>
                                <li id="user"><a href="user.php" target="frame"><? echo _("用户管理")?></a></li>
							<!--	
							  <li id="user_netbar"><a href="user_netbar.php" target="frame"><? echo _("时长计费")?></a></li>
							-->
								<li id="user_Mname_info"><a href="user_Mname_info.php" target="frame"><? echo _("子母账号")?></a></li>
								<li id="user_shutdown"><a href="user_shutdown.php" target="frame"><? echo _("停机恢复")?></a></li>
								<li id="user_closing"><a href="user_closing.php" target="frame"><? echo _("用户销户")?></a></li>  
								<li id="user_closing_info"><a href="user_closing_info.php" target="frame"><? echo _("销户用户")?></a></li> 
								<li id="user_stop_info"><a href="user_shutdown.php?action=pause" target="frame"><? echo _("停机保号")?></a></li> 
								<li id="user_pause"><a href="user_pause.php" target="frame"><? echo _("暂停用户")?></a></li>
								<li id="user_normal_info"><a href="user_normal_info.php" target="frame"><? echo _("在网用户")?></a></li>
								<li id="user_upcoming"><a href="user_upcoming.php" target="frame"><? echo _("即将到期")?></a></li>
								<li id="user_maturity"><a href="user_maturity.php" target="frame"><? echo _("到期用户")?></a></li>
								<li id="user_show_passwprd"><a href="user_show_passwprd.php" target="frame"><? echo _("密码查询")?></a></li>
								<li id="chart_user"><a href="chart_user.php" target="frame"><? echo _("图表分析")?></a></li> 
                              </ul>
                            </li> 
                            <li><a href="#Menu=ChildMenu7" onClick="DoMenu('ChildMenu7')"><? echo _("营帐管理")?></a>
                              <ul id="ChildMenu7" class="collapsed"> 
							    <li id="recharge_user"><a href="recharge_user.php" target="frame"><? echo _("用户充值")?></a></li> 								
							    <li id="financial_subjects"><a href="financial_subjects.php" target="frame"><? echo _("财务科目")?></a></li> 
							    <li id="finance_MTC_add"><a href="finance_MTC_add.php" target="frame"><? echo _("人工收费")?></a></li>
							  	<li id="order_add"><a href="order_add.php" target="frame"><? echo _("用户续费")?></a></li>
								<li id="recharge_reverse"><a href="recharge_reverse.php" target="frame"><? echo _("用户冲帐")?></a></li> 
								<li id="user_flow_monitor"><a href="user_flow_monitor.php" target="frame"><? echo _("流量监控")?></a></li>   
								<li id="user_hours_show"><a href="user_hours_show.php" target="frame"><? echo _("时长监控")?></a></li>    
								<li id="order_run"><a href="order_run.php" target="frame"><? echo _("运行订单")?></a></li>	
								<li id="order"><a href="order.php" target="frame"><? echo _("订单记录")?></a></li>							 
                                <li id="recharge_log"><a href="recharge_log.php" target="frame"><? echo _("充值记录")?></a></li>  		
								<li id="finance_report"><a href="finance_report.php" target="frame"><? echo _("营业报表")?></a></li>
								<li id="user_bill"><a href="user_bill.php" target="frame"><? echo _("用户帐单")?></a></li>
								<li id="chart_report"><a href="chart_report.php" target="frame"><? echo _("图形报表")?></a></li>
								<li id="chart_report_pie"><a href="chart_report_pie.php" target="frame"><? echo _("图表分析")?></a></li>
                              </ul>
                            </li>
     													
                            <li><a href="#Menu=ChildMenu8" onClick="DoMenu('ChildMenu8')"><? echo _("运营管理")?></a>
                              <ul id="ChildMenu8" class="collapsed">
							  	<!--<li id="user_hour_bill"><a href="user_hour_bill.php" target="frame">记时帐单</a></li>-->
                                <li id="operate_online"><a href="operate_online.php" target="frame"><? echo _("在线管理")?></a></li>
                                <li id="operate_netplay_log"><a href="operate_netplay_log.php" target="frame"><? echo _("上网记录")?></a></li>                               
                                <li id="operate_userlog"><a href="operate_userlog.php" target="frame"><? echo _("操作日志")?></a></li>
								<li id="operate_login_log"><a href="operate_login_log.php" target="frame"><? echo _("登录记录")?></a></li>
                              </ul>
                            </li>
							<li><a href="#Menu=ChildMenu11" onClick="DoMenu('ChildMenu11')"><? echo _("卡片管理")?></a>
                              <ul id="ChildMenu11" class="collapsed">
                                <li id="card_add"><a href="card_add.php" target="frame"><? echo _("生成卡片")?></a></li>
                                <li id="card"><a href="card.php" target="frame"><? echo _("卡片销售")?></a></li>
								<li id="card_search"><a href="card_search.php" target="frame"><? echo _("卡片查询")?></a></li>
								<li id="card_sold_show"><a href="card_sold_show.php" target="frame"><? echo _("销售预览")?></a></li>
                              </ul>
                            </li>          
                            <li><a href="#Menu=ChildMenu9" onClick="DoMenu('ChildMenu9')"><? echo _("备份恢复")?></a>
                              <ul id="ChildMenu9" class="collapsed">
                                <li id="db_auto"><a href="db_auto.php" target="frame"><? echo _("自动备份")?></a></li>
				<li id="db_backup_ftp"><a href="db_backup_ftp.php" target="frame"><? echo _("FTP 备份")?></a></li>
                                <li id="db_backup"><a href="db_backup.php" target="frame"><? echo _("数据备份")?></a></li>
                           
                                <li id="db_restore"><a href="db_restore.php" target="frame"><? echo _("数据恢复")?></a></li>
				<li id="db_user_import"><a href="db_user_import.php" target="frame"><? echo _("数据导入")?></a></li>
                              </ul>
                            </li>
					
                            <li><a href="#Menu=ChildMenu10" onClick="DoMenu('ChildMenu10')"><? echo _("系统设置")?></a>
                              <ul id="ChildMenu10" class="collapsed">
                                <li id="manager"><a href="manager.php" target="frame"><? echo _("系统用户")?></a></li>
                                <li id="manager_group"><a href="manager_group.php" target="frame"><? echo _("用户角色")?></a></li> 
                                <li id="manager_pwd_edit"><a href="manager_pwd_edit.php" target="frame"><? echo _("密码修改")?></a></li>
                                <li id="system_upgrade"><a href="system_upgrade.php" target="frame"><? echo _("系统升级")?></a></li>
				<!--<li id="system_message"><a href="system_message.php" target="frame"><? echo _("短信设置")?></a></li>-->
				<li id="order_ticket"><a href="order_ticket.php" target="frame"><? echo _("票据设置")?></a></li>  
				<li id="system_publicnotice"><a href="system_publicnotice.php" target="frame">ROS 通告</a></li>
								<li id="system_database"><a href="system_configuration.php" target="frame"><? echo _("批量配置")?></a></li>
                                                                <li id="system_config"><a href="system_config.php" target="frame"><? echo _("界面配置")?></a></li>
								 <li id="system_database"><a href="system_database.php" target="frame"><? echo _("数据库管理")?></a></li> 
								<li id="truncate_alltable"><a href="truncate_alltable.php" target="frame"><? echo _("数据库还原")?></a></li>
								<li id="cron"><a href="cron.php" target="frame">计划任务</a></li>
								<li id="system_del_dial_log"><a href="system_del_dial_log.php" target="frame"><? echo _("清空记录")?></a></li>
                                <li id="login_out"><a href="login_out.php" target="frame"><? echo _("退出登录")?></a></li>
                              </ul>
                            </li>
                          </ul>
                        </div>
<script type=text/javascript><!--
var LastLeftID = "";
function menuFix() {
 var obj = document.getElementById("nav").getElementsByTagName("li");
 
 for (var i=0; i<obj.length; i++) {
  obj[i].onmouseover=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onMouseDown=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onMouseUp=function() {
   this.className+=(this.className.length>0? " ": "") + "sfhover";
  }
  obj[i].onmouseout=function() {
   this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"), "");
  }
 }
}
function DoMenu(emid)
{
 var obj = document.getElementById(emid); 
 obj.className = (obj.className.toLowerCase() == "expanded"?"collapsed":"expanded");
 if((LastLeftID!="")&&(emid!=LastLeftID)) //关闭上一个Menu
 {
  document.getElementById(LastLeftID).className = "collapsed";
 }
 LastLeftID = emid;
}
function GetMenuID()
{
 var MenuID="";
 var _paramStr = new String(window.location.href);
 var _sharpPos = _paramStr.indexOf("#");
 
 if (_sharpPos >= 0 && _sharpPos < _paramStr.length - 1)
 {
  _paramStr = _paramStr.substring(_sharpPos + 1, _paramStr.length);
 }
 else
 {
  _paramStr = "";
 }
 
 if (_paramStr.length > 0)
 {
  var _paramArr = _paramStr.split("&");
  if (_paramArr.length>0)
  {
   var _paramKeyVal = _paramArr[0].split("=");
   if (_paramKeyVal.length>0)
   {
    MenuID = _paramKeyVal[1];
   }
  }
  /*
  if (_paramArr.length>0)
  {
   var _arr = new Array(_paramArr.length);
  }
  
  //取所有#后面的，菜单只需用到Menu
  //for (var i = 0; i < _paramArr.length; i++)
  {
   var _paramKeyVal = _paramArr[i].split('=');
   
   if (_paramKeyVal.length>0)
   {
    _arr[_paramKeyVal[0]] = _paramKeyVal[1];
   }  
  }
  */
 }
 
 if(MenuID!="")
 {
  DoMenu(MenuID)
 }
}
GetMenuID(); //*这两个function的顺序要注意一下，不然在Firefox里GetMenuID()不起效果
menuFix();
--></script>
                      </td>
                    </tr>
                <?php
                }
                ?>
                    <tr>
                      <td height="19" align="center" valign="middle"><img name="li_r16_c2" src="images/li_r16_c2.jpg" width="160" height="23" border="0" id="li_r16_c2" alt="" /></td>
                    </tr>
                  </table></td>
                <td valign="top"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="15" align="center" valign="top"><img name="li_r2_c3" src="images/li_r2_c3.jpg" width="100%" height="14" border="0" id="li_r2_c3" alt="" /></td>
                    </tr>
                    <tr>
                      <td align="right" valign="top">
                      	<iframe width=98% height=450 id="frame" name=frame frameborder=0 scrolling=auto marginheight=0 marginwidth=0  src="main.php" onload="this.height=frame.document.body.scrollHeight"></iframe>
						
					
                      </td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
          <td width="10" background="images/li_r2_c16.jpg">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="12" valign="middle" background="images/li_r18_c5.jpg" class="STYLE1"><img src="<?php echo  (!empty($rs["picBottomLeft"]))?'images/'.$rs["picBottomLeft"]:'images/li_r18_c1.jpg';?>" alt="" name="li_r18_c1" width="202" height="91" border="0" align="absmiddle" id="li_r18_c1" />
	<?php
            if($data ==1 ){
            $dataName = "ISP版";
        }elseif ($data == 2 ) {
                 $dataName = "企业版";
                 }elseif ($data == 3) {
                 $dataName ="基础版";           
                 }
        echo _("当前版本为：".$dataName."".'&nbsp;&nbsp;'."支持最大项目数为：") . $systemconfig["max_project"] ."&nbsp;&nbsp;" . _("最大用户数为：") . $systemconfig["max_user"];
                                                 
                ?>	</td>
    <td colspan="4" align="right" valign="middle" background="images/li_r18_c5.jpg"><img name="li_r18_c13" src="<?php echo  (!empty($rs["picBottomRight"]))?'images/'.$rs["picBottomRight"]:'images/li_r18_c13.jpg';?>" width="212" height="91" border="0" id="li_r18_c13" alt="" /></td>
  </tr>
  <tr>
    <td height="50" colspan="16" align="center" valign="middle" class="STYLE1"><?=$rs["copyright"]?></td>
  </tr>
</table>
</body>
</html>
 