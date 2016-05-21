#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>到期公告</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
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
<?php 
$tableName ="";
$tables=mysql_query("show table status from $mysqldb");
while($row =mysql_fetch_array($tables,MYSQL_ASSOC ) ){
 $tableName[] = $row['Name']; 
} 
if(!in_array("ros_notice",$tableName)){
	mysql_query("
	CREATE TABLE `ros_notice` (
	`ID` INT(255) NULL DEFAULT NULL,
	`status` VARCHAR(50) NULL DEFAULT NULL COMMENT '启用或禁用通告',
	`title` VARCHAR(255) NULL DEFAULT NULL COMMENT '标题',
	`client` VARCHAR(255) NULL DEFAULT NULL COMMENT '客户尊称',
	`img` VARCHAR(255) NULL DEFAULT NULL COMMENT '背景图片',
	`content` VARCHAR(1024) NULL DEFAULT NULL COMMENT '通告内容',
	`description` VARCHAR(1024) NULL DEFAULT NULL COMMENT '描述'
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;");
}
$rs=$db->select_one("*","ros_notice","0=0 order by ID desc limit 0,1");
if($_POST){ 
  
 if($rs){
 	 $sql=array(
 	  "status"=>$_POST["status"],
		"title"=>$_POST["title"],
		"client"=>$_POST["client"],
		"img"=>"ros_bg.jpg",
		"content"=>$_POST["content"],
		"description"=>$_POST["description"]
	);
	 $db->update_new("ros_notice","ID=1",$sql); 
 }else{
 	$sql=array(
 	  "ID"=>1,
 	  "status"=>$_POST["status"],
		"title"=>$_POST["title"],
		"client"=>$_POST["client"],
		"img"=>"ros_bg.jpg",
		"content"=>$_POST["content"],
		"description"=>$_POST["description"]
	);
 	$db->insert_new("ros_notice",$sql); 
  }
   if(!empty($_POST["img"])){
     @ copy("./images/".$_POST["img"], "../ros-notice/ros_bg.jpg"); 
     @ exec("rm -rf ./images/".$_POST["img"]) ;
   }
   if($_POST["status"]=="enable"){  
     system("mongoose_rosnotice -C \"**.lua$|**.lp$|**.cgi$|**.php$\"  -i index.php -r /usr/local/ros-notice/ -t 128 -p 6666 2>/dev/null >/dev/null &"); 
   }else {  
     $pid = system("ps -elf | grep -w \"/usr/local/ros-notice\" | grep -v grep | awk '{print $1}'");
     if( strlen($pid) > 0){
     $pid = trim($pid);
     exec("kill -9 ".$pid); 
    }  
   }   
	 echo "<script language='javascript'>alert('"._("修改成功")."');window.location.href='system_publicnotice.php';</script>"; 
   
 }
?> 
<body>
<form action="?action=editsave" method="post" name="myform"  enctype="multipart/form-data" >
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">系统设置</font></td>
                        <td width="3%" height="35">
                            <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                        </td> <!------帮助--2014.06.07----->                        
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
        <td width="89%" class="f-bulue1">到期公告配置</td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
		
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
  	    <tbody>
  	  <tr> 
			 <td align="left" class="bg"><? echo _("状态")?></td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <? if($rs['status']=="enable")echo"checked";?> ><? echo _("启用")?>
			 <input name="status" type="radio" value="disable"<? if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?> ><?  echo _("禁用")?></td>
		  </tr> 
		  <tr> 
			 <td align="left" class="bg"><? echo _("页面标题")?></td>
		   <td  align="left" class="bg">
			 <input name="title" type="text"    id="title"  value="<?=$rs['title']?>" size="50"></td>
		  </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("客户尊称")?></td>
		    <td align="left" class="bg">
				<input name="client" type="text"    id="client"  value="<?=$rs['client']?>" size="50"> <? echo _("提示：尊敬的客户您好：")?>	</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><p ><? echo _("背景图片")?></p></td>
		    <td align="left" class="bg">
		    <div id="picTopLeftView"></div>
			  <input type="hidden" name="img" id="img" value="<?=$rs["img"]?>">
		    <!--<iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=logo&upFileID=picTopLeftID&viewID=picTopLeftView" scrolling="no" frameborder="0"></iframe>-->
		    <iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=logo&upFileID=img&viewID=picTopLeftView" scrolling="no" frameborder="0"></iframe>	 
		   <br>
		  <? echo _("注:上传图片将平铺整个窗口，请自定义设置好图片大小")?>  
		  </td> 
		  </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("公告内容")?></td>
		    <td align="left" class="bg">
				<textarea  name="content" id="content"   rows="10" style="width:400px;" ><?=$rs['content'];?></textarea><br>
			 <? echo _("提示：公告内容不能超过1024字节")?>
			  <font color="red" ><? echo _("支持HTML标签")?></font>
			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><p ><? echo _("描述")?></p></td>
		    <td align="left" class="bg">
				<textarea  name="description" id="description"   rows="10" style="width:400px;" ><?=$rs['description'];?></textarea><br>
			   <? echo _("提示：描述不能超过1024字节  仅供管理员可见描述，不显示在通告页面")?>	</td>
		    </tr>  
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="保存"></td>
		  </tr> 
        </tbody>      
    </table>	
	</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr> 
</form>  
</table>
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>ROS通告</strong>
      </p>
      <ul>
          <li>提醒用户到期公告。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

