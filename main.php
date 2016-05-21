#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai'); 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("无标题文档")?></title>

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
            WindowTitle:          '<b>系统信息</b>',
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
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<body>
<?php  
$projectNum=$db->select_count("project"," ID in (".$_SESSION['auth_project'].")"); 
$productNum=$db->select_count("product"," ID IN (". $_SESSION['auth_product'].")"); 
//$userNum   =$db->select_count("userinfo"," projectID in (". $_SESSION["auth_project"].") and gradeID in (". $_SESSION["auth_gradeID"].")  ");
 $userNum   =$db->select_count("userinfo as u,userattribute as a,orderinfo as o,product as p"," u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") ");


$onlineNum=$db->select_count("radacct as r,userinfo as u"," r.UserName=u.UserName and r.AcctStopTime='0000-00-00 00:00:00' and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")");
//$onlineNum =$db->select_count("radacct","AcctStopTime='000-00-00 00:00:00'");
$onlineNum =empty($onlineNum)?"0":$onlineNum;

$repairNum =$db->select_count("repair","");
$nowTime        = date("Y-m-d H:i:s",time());
$upcomingTime   = date('Y-m-d H:i:s',strtotime("$nowTime +15 day")); 
$waitUser=$db->select_all('userID','userrun','status=0');//有等待运行订单 的用户ID 
$UID='';
if(is_array($waitUser)){
 foreach($waitUser as $waitUID){
   $UID.=$waitUID['userID'].","; 
 }
 $UIDs=substr($UID,0,-1); 
 $sql_Uid="and a.userID not in (".$UIDs.") ";
}else{
 $sql_Uid='';
}
// 根据用户分组,查询出用户最后执行的订单编号
$orderStr=0; 
  $pg =$db->select_all("max(orderID) as maxOrderID","userrun","0=0 group by userID ");
  if($pg){ 
    if(is_array($pg)){
    	foreach($pg as $p){
    	  $orderStr .=$p["maxOrderID"].",";
    	} 
      $orderStr = substr($orderStr,0,-1);
    }
  }  
$upcomingNum =$db->select_count("userinfo as u,userrun as r","u.ID=r.userID and r.enddatetime<'$upcomingTime' and r.enddatetime>='$nowTime'  and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and r.orderID in(".$orderStr.") ");



$maturityNum =$db->select_count("userinfo as u,userattribute as a,userrun as r","u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and r.enddatetime<'$nowTime' and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and r.enddatetime>'0000-00-00 00:00:00'");
//停机保号用户
$stopInfoNum=$db->select_count("orderrefund as o,userinfo as u","o.type = '1' and o.userID=u.ID");
$netNum =$db->select_count("userinfo as u,userattribute as a,userrun as r","u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and r.enddatetime>'$nowTime' and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and r.enddatetime>'0000-00-00 00:00:00' and r.begindatetime !='0000-00-00 00:00:00'");
//$netNum    = $userNum -$maturityNum;
//销户用户userattribute closing==1
$closingNum =$db->select_count("userinfo as u,userattribute as a,userrun as r","u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID   and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and  a.closing=1 ");

//暂停用户
$pauseSql="u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and a.status=5 and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";

$pauseNum=$db->select_count("userinfo as u,userattribute as a,userrun as r",$pauseSql); 
$managerNum=$db->select_one("addusertotalnum,addusernum","manager","manager_account='".$_SESSION["manager"]."'"); 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">
			  <? echo _("系统信息")?></font></td>
                        <td width="3%" height="35"><div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div></td> <!------帮助--2014.06.07----->
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top"> 
	<!--<table class="bd" cellspacing="1" cellpadding="5" width="100%" align="center" border="0">
		<tr>
			<td colspan="2" align="left" class="title_bg2">系统信息</td>
		</tr>
		<tr>
			<td class="bd_b" width="50%" height="23">PHP版本：<font size=2><?php echo phpversion();?></font></td>
			<td class="bd_b" width="50%">PHP运行方式：<font size=2><?php echo strtoupper(php_sapi_name());?></font></td>
		</tr>
		<tr>
			<td class="bd_b" width="50%" height="23">Zend引擎版本：<font size=2><?php echo zend_version();?></font></td>
			<td class="bd_b" width="50%">自动定义全局变量register_globals： <font size=2><?php echo get_cfg_var("register_globals")?'On':'Off';?></font></td>
		</tr>
		<tr>
			<td class="bd_b" width="50%" height="23">允许使用的最大内存量：<font size=2><?php echo get_cfg_var("memory_limit");?></font></td>
			<td class="bd_b" width="50%">POST最大字节数：<font size=2><?php echo get_cfg_var("post_max_size");?></font></td>
		</tr>
		<tr>
			<td class="bd_b" width="50%" height="23">允许最大上传文件：<font size=2><?php echo get_cfg_var("file_uploads")?get_cfg_var("upload_max_filesize"):$error?></font></td>
			<td class="bd_b" width="50%">程序限制：<font size=2><?php echo get_cfg_var("max_execution_time")."秒"?></font></td>
		</tr>
		<tr>
		  <td class="bd_b" height="23">被禁用的函数：<font size=2><?php echo get_cfg_var("disable_functions")?get_cfg_var("disable_functions"):"没有"?></font></td>
		  <td class="bd_b">浮点运算有效数字显示位数：<font size=2><?php echo get_cfg_var("precision");?></font></td>
	  </tr>
		
		<tr>
			<td class="TableRow2" id="newaspservice">MySQL数据库版本：<font size="2"><?php echo mysql_get_server_info(); ?></font></td>
			<td class="TableRow2" id="newaspservice">MySQL最大连接数：<font size="2"><?php echo get_cfg_var("mysql.max_links")==-1?"不限":get_cfg_var("mysql.max_links");?></font></td>
		</tr>
	</table>-->
	<table class="bd" cellspacing="1" cellpadding="5" width="100%" align="center" border="0">
	<tr>
		<td colspan="6" align="left" class="title_bg2"><? echo _("系统统计")?>
	
		</td>

	    </tr>
	<tr>
	  <td class="title_bg2" width="14%" height="23"><? echo _("管 理 员")?></td>
		<td class="bd_b" width="12%" colspan="2"><?=$_SESSION["manager"]?></td>
		 
		<td class="title_bg2" width="12%"><? echo _("开户权限")?></td>
	    <td class="bd_b" width="16%" colspan="3"><? echo _("允许开户人数")?>:<font color="red"> <?=$managerNum['addusertotalnum']?></font>&nbsp;<? echo _("已开户人数")?>: <font color="red"><?=$managerNum['addusernum']?></font></td>
		 
	</tr>
	<tr>
	  <td class="title_bg2" width="14%" height="23"><? echo _("项目统计")?></td>
		<td class="bd_b" width="12%"><font size=2><?=$projectNum?></font></td>
		<td class="bd_b bd_l" width="24%"><font size=2><a href="project.php"><? echo _("查看详情")?></a></font></td>
		<td class="title_bg2" width="12%"><? echo _("即将到期用户：")?></td>
	    <td class="bd_b" width="16%"><?=$upcomingNum?></td>
	    <td class="bd_b bd_l" width="22%"><a href="user_upcoming.php"><? echo _("查看详情")?></a>
	</tr>
	<tr>
	  <td class="title_bg2" width="14%" height="23"><? echo _("产品数量")?></td>
		<td class="bd_b" width="12%"><font size=2><?=$productNum?></font></td>
		<td class="bd_b bd_l" width="24%"><font size=2><a href="product.php"><? echo _("查看详情")?></a></font></td>
		 <td class="title_bg2" width="12%"><? echo _("停机用户数：")// echo _("停机(含销户)用户数：")?></td>
	    <td class="bd_b" width="16%"><?=$maturityNum?></td>
	    <td class="bd_b bd_l" width="22%"><a href="user_maturity.php"><? echo _("查看详情")?></a></td>
	</tr>
	<tr>
	  <td class="title_bg2" width="14%" height="23"><? echo _("客户人数")?></td>
		<td class="bd_b" width="12%"><font size=2><?=$userNum?></font></td>
		<td class="bd_b bd_l" width="24%"><font size=2><a href="user.php"><? echo _("查看详情")?></a></font></td>
		<td class="title_bg2" width="12%"><? echo _("报修用户数")?>：</td>
	    <td class="bd_b" width="16%"><?=$repairNum?></td>
	    <td class="bd_b bd_l" width="22%"><a href="repair.php"><? echo _("查看详情")?></a></td>
	</tr>
	<tr>
	  <td class="title_bg2" width="14%" height="23"><? echo _("在网用户数")?></td>
		<td class="bd_b" width="12%"><font size=2><?=$netNum?></font></td>
		<td class="bd_b bd_l" width="24%"><font size=2><a href="user_normal_info.php"><? echo _("查看详情")?></a></font></td>
		<td class="title_bg2" width="12%"><? echo _("当前在线人数：")?></td>
	    <td class="bd_b" width="16%"><font size=2><?=$onlineNum?></font></td>
	    <td class="bd_b bd_l" width="22%"><font size=2><a href="operate_online.php"><? echo _("查看详情")?></a></font></td>
		
		
		<!--<td class="title_bg2" width="12%"><? echo _("停机保号用户数：")?></td><!--(销户)  暂停用户  
	    <td class="bd_b" width="16%"><font size=2><?=$stopInfoNum?></font></td>
	    <td class="bd_b bd_l" width="22%"><font size=2><a href="user_stop_info.php"><? echo _("查看详情")?></a></font>
		--></td>
	</tr>
	<tr>
	  <td class="title_bg2" width="14%" height="23"><? echo _("销户用户")?></td>
		<td class="bd_b" width="12%"><font size=2><?=$closingNum?></font></td>
		<td class="bd_b bd_l" width="24%"><font size=2><a href="user_closing_info.php"><? echo _("查看详情")?></a></font></td>
		<td class="title_bg2" width="12%"><? echo _("暂停用户")?></td><!--(销户)  暂停用户    -->
	    <td class="bd_b" width="16%"><font size=2><?=$pauseNum?></font></td>
	    <td class="bd_b bd_l" width="22%"><font size=2><a href="user_pause.php"><? echo _("查看详情")?></a></font></td>
	</tr>
	 
	<tr>
	  <td class="title_bg2" height="23"><? echo _("系统最大上传数")?></td>
	  <td class="bd_b"><?php echo get_cfg_var("file_uploads")?get_cfg_var("upload_max_filesize"):$error?></td>
	  <td class="bd_b bd_l">&nbsp;</td>
	  <td class="title_bg2 bd_b"><? echo _("POST最大字节数：")?></td>
          <td class="bd_b"><?php echo get_cfg_var("post_max_size");?></td>
          <td class="bd_b">&nbsp;</td>
	</tr>
        <!--2014.09.11添加数据备份跳转 -->
       <tr>
	  <td class="title_bg2" height="23">数据备份</td>
          <td class="bg">
               <font color="red"  style="font-size:14px"><b>点击转到—><a href="db_backup.php" style="font-size:14px; color:#CC0066; font-weight:bold" >数据备份</a></b></font>			  
          </td>
	  <td class="bg">&nbsp;</td>
	  <td class="bg">&nbsp;</td>
          <td class="bg">&nbsp;</td>
          <td class="bg">&nbsp;</td>
	</tr>
	<!--<tr>
		<td class="TableRow2" id="newaspservice">MySQL数据库版本：<font size="2"><?php echo mysql_get_server_info(); ?></font></td>
	    <td class="TableRow2" id="newaspservice">MySQL最大连接数：<font size="2"><?php echo get_cfg_var("mysql.max_links")==-1?"不限":get_cfg_var("mysql.max_links");?></font></td>
	</tr>-->
</table>
	<br>
	<table class="bd" cellspacing="1" cellpadding="5" width="100%" align="center" border="0">
		<tr>
			<td colspan="2" align="left" class="title_bg2"><? echo _("管理系统版本")?></td>
		</tr>
		<tr>
			<td class="bd_b" width="13%" height="23"><? echo _("当前版本")?></td>
			<td class="bd_b bd_l" width="87%"><? echo _("计费管理系统").":"?><?=$config_version?></td>
		</tr>
		<?
		 $rs=$db->select_one("*","config","0=0 order by ID desc limit 0,1");
		 $text=$rs['CRStatement'];
		 $texts = str_replace("\n", "<br>", $text);
		?>
		<tr>
			<td width="13%" height="23" class="bd_b"><span class="TableRow2"><? echo _("版权声明")?></span></td>
			<td width="87%" class="line-20 bd_b  bd_l"><?=$texts;?>&nbsp;</td>
		</tr>
		<tr>
		  <td height="23" class="bd_b"><span class="TableRow1"><? echo _("程序制作")?></span></td>
		  <td class="bd_b  bd_l"><? echo _("计费管理系统")?></td>
	    </tr>
		<tr>
		  <td height="23"><span class="TableRow2"><? echo _("联系方式")?></span></td>
		  <td class=" bd_l">&nbsp;<?=$rs['Contact']?></td>
	    </tr>
    </table>	 
	</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统信息-> <strong>系统信息</strong>
      </p>
      <ul>
          <li>可以查看当前系统运行情况。</li>
          <li>可以查看对应的详细信息。</li>
          <li>点击各项后的查看详情。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>