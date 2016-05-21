#!/bin/php
<?php 
include("inc/conn.php");

//include_once("evn.php"); 

?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>计划任务</title>
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
<?php
if($_POST){ 
 	 if(is_numeric($_POST['id'])){//修改
 	 	  $sql=array(
                      "type"=>$_POST["type"], 
		      "status"=>$_POST["status"],
		      "files"=>$_POST["files"],
		      "newtime"=>$_POST["newtime"],
		      "time"=>$_POST["time"],
                     "backTime"=>$_POST["backTime"]
	        ); 
               if($_POST["newtime"]==""  || $_POST["files"] == "" || $_POST["time"] == ""){
                    echo "<script language='javascript'>alert('"._("数据不完整")."');window.location.href='cron.php';</script>";
                    exit();
               }else{
               $db->update_new("cron","ID=".$_POST['id'],$sql); }    
   }else{//添加
   	$sql=array(
                      "type"=>$_POST["type"], 
		      "status"=>$_POST["status"],
		      "files"=>$_POST["files"],
		      "newtime"=>$_POST["newtime"],
		      "time"=>$_POST["time"],
                      "backTime"=>$_POST["backTime"]
	        ); 
        if($_POST["files"]==""  || $_POST["newtime"] == "" || $_POST["time"] == ""){
                    echo "<script language='javascript'>alert('"._("数据不完整")."');window.location.href='cron.php';</script>";
                    exit();
               }else{
               $db->insert_new("cron",$sql);} 
   }
   //写入计划任务
    include '/usr/local/usr-gui/aaacron.php';
	 echo "<script language='javascript'>alert('"._("配置成功")."');window.location.href='cron.php';</script>"; 
   
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
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">计划任务</font></td>
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

<?php if($_REQUEST['mess']=="" || $_REQUEST['mess']=='add'){//自动备份
   $rs=$db->select_one("*","cron","type = 1 ");
	
?>
<form action="?action=editadd" method="post" name="myform" >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<?php if($_REQUEST['mess']=='' || $_REQUEST['mess']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" > 自动备份
         <input type="radio" name="mess" id="orderadd"  value="once"<?php if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" > 停机扫描
	 <input type="radio" name="mess" id="upcoming"  value="once"<?php if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" > 包小时扫描
	 <input type="radio" name="mess" id="maturity"  value="more" <?php if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" > 包流量扫描
         <input type="radio" name="mess" id="maturity1"  value="more" <?php if($_REQUEST['mess']=='maturity1') echo "checked";?> onClick="window.location.href='?mess=maturity1'" > 在线检测
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >  
         <input name="days" type="hidden"id="days"  value="" size="50"></td>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg">自动备份</td>
		   <td align="left" class="bg">
                       <input type="text"name="files" value="scan_db_backup.php" readonly="readonly"/>
                       <input type='hidden' value='1' name='type'/>
                   </td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg">状态</td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <?php if($rs['status'] =="" ||$rs['status']=="enable")echo"checked";?> >启用
			 <input name="status" type="radio" value="disable"<?php if( $rs['status']=="disable")echo"checked";?> >禁用
		  </tr> 
                       <tr>
		    <td align="left" class="bg">时间</td>
		    <td align="left" class="bg">
                        <input name="newtime" type="text"  value="<?php if($rs['newtime']==""){echo "2";}  else {echo $rs['newtime'];}?>" >&nbsp;
                          <select name="time" >
                            <option value="fen" <?php if($rs['time']=="fen")echo"selected='selected'";?>>分钟</option>
                            <option value="shi" <?php if($rs['time']=="shi" ||$rs['time'] == "") echo"selected='selected'";?>>小时</option>
                            </select>
                        &nbsp;提示：分钟范围为“1-59”分钟，小时范围为“1-23”小时。
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
}else if($_REQUEST['mess']=="orderadd"){//停机扫描
   $rs=$db->select_one("*","cron","type = 2 ");
?>
<form action="?action=editorderadd" method="post" name="myform"  >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
           <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<?php if($_REQUEST['mess']=='' || $_REQUEST['mess']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" > 自动备份
         <input type="radio" name="mess" id="orderadd"  value="once"<?php if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" > 停机扫描
	 <input type="radio" name="mess" id="upcoming"  value="once"<?php if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" > 包小时扫描
	 <input type="radio" name="mess" id="maturity"  value="more" <?php if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" > 包流量扫描
         <input type="radio" name="mess" id="maturity1"  value="more" <?php if($_REQUEST['mess']=='maturity1') echo "checked";?> onClick="window.location.href='?mess=maturity1'" > 在线检测
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >  
         <input name="days" type="hidden"id="days"  value="" size="50"></td>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg">停机扫描</td>
		   <td align="left" class="bg">
                       <input type="text"name="files" value="scan_time_len.php" readonly="readonly"/>
                       <input type='hidden' value='2' name='type'/>
                   </td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg">状态</td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <?php if($rs['status'] =="" || $rs['status']=="enable")echo"checked";?> >启用
			 <input name="status" type="radio" value="disable"<?php if($rs['status']=="disable")echo"checked";?> >禁用</td>
		  </tr> 
                       <tr>
		    <td align="left" class="bg">时间</td>
		    <td align="left" class="bg">
                            <input name="newtime" type="text"  value="<?php if($rs['newtime']==""){echo "1";}  else {echo $rs['newtime'];}?>"> 
                          <select name="time" >
                            <option value="fen" <?php if($rs['time']=="fen")echo"selected='selected'";?>>分钟</option>
                            <option value="shi" <?php if($rs['time']=="shi" ||$rs['time'] == "") echo"selected='selected'";?>>小时</option>
                            </select>
                            &nbsp;提示：分钟范围为“1-59”分钟，小时范围为“1-23”小时。
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
}else if ($_REQUEST['mess']=="upcoming"){//包小时扫描
 $rs=$db->select_one("*","cron","type = 3 ");
?>
<form action="?action=editmaturity" method="post" name="myform" >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<?php if($_REQUEST['mess']=='' || $_REQUEST['mess']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" > 自动备份
         <input type="radio" name="mess" id="orderadd"  value="once"<?php if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" > 停机扫描
	 <input type="radio" name="mess" id="upcoming"  value="once"<?php if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" > 包小时扫描
	 <input type="radio" name="mess" id="maturity"  value="more" <?php if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" > 包流量扫描
         <input type="radio" name="mess" id="maturity1"  value="more" <?php if($_REQUEST['mess']=='maturity1') echo "checked";?> onClick="window.location.href='?mess=maturity1'" > 在线检测
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >  
         <input name="days" type="hidden"id="days"  value="" size="50"></td>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg">包小时扫描</td>
		   <td align="left" class="bg">
                       <input type="text"name="files" value="<?php if(!empty($rs['files'])){echo $rs['files'];}  else {echo "scan_everyone_hour.php";}?>" readonly="readonly"/>&nbsp;
                       <input type='hidden' value='3' name='type'/>
                   </td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg">状态</td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <?php if($rs['status']=="enable")echo"checked";?> >启用
			 <input name="status" type="radio" value="disable"<?php if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?> >禁用</td>
		  </tr> 
                       <tr>
		    <td align="left" class="bg">时间</td>
		    <td align="left" class="bg">
				<input name="newtime" type="text"  value="<?=$rs['newtime']?>">
                          <select name="time" >
                            <option value="fen" <?php if($rs['time']=="fen" ||$rs['time'] == "")echo"selected='selected'";?>>分钟</option>
                            <option value="shi" <?php if($rs['time']=="shi") echo"selected='selected'";?>>小时</option>
                            </select>
                                &nbsp;提示：分钟范围为“1-59”分钟，小时范围为“1-23”小时。
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
}else if($_REQUEST['mess']=="maturity"){//包流量扫描
   $rs=$db->select_one("*","cron","type = 4 ");
?>
<form action="?action=editmaturity" method="post" name="myform"  >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
       <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<?php if($_REQUEST['mess']=='' || $_REQUEST['mess']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" > 自动备份
         <input type="radio" name="mess" id="orderadd"  value="once"<?php if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" > 停机扫描
	 <input type="radio" name="mess" id="upcoming"  value="once"<?php if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" > 包小时扫描
	 <input type="radio" name="mess" id="maturity"  value="more" <?php if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" > 包流量扫描
         <input type="radio" name="mess" id="maturity1"  value="more" <?php if($_REQUEST['mess']=='maturity1') echo "checked";?> onClick="window.location.href='?mess=maturity1'" > 在线检测
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >  
         <input name="days" type="hidden"id="days"  value="" size="50"></td>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg">包流量扫描</td>
		   <td align="left" class="bg">
                       <input type="text"name="files" value="<?php if(!empty($rs['files'])){echo $rs['files'];}  else {echo "scan_flow_limit.php";}?>" readonly="readonly"/>&nbsp;
                       <input type='hidden' value='4' name='type'/>
                   </td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg">状态</td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <?php if($rs['status']=="enable")echo"checked";?> >启用
			 <input name="status" type="radio" value="disable"<?php if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?> >禁用</td>
		  </tr> 
                       <tr>
		    <td align="left" class="bg">时间</td>
		    <td align="left" class="bg">
				<input name="newtime" type="text"  value="<?=$rs['newtime']?>"> 
                          <select name="time" >
                            <option value="fen" <?php if($rs['time']=="fen" || $rs['time'] == "")echo"selected='selected'";?>>分钟</option>
                            <option value="shi" <?php if($rs['time']=="shi") echo"selected='selected'";?>>小时</option>
                            </select>
                                &nbsp;提示：分钟范围为“1-59”分钟，小时范围为“1-23”小时。
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
}else if($_REQUEST['mess']=="maturity1"){//在线检测
 $rs=$db->select_one("*","cron","type = 5 ");
?>
        <form action="?action=editmaturity1" method="post" name="myform"  >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
       <tr>
        <td width="89%" class="f-bulue1"> 
         <input type="radio" name="mess" id="add"  value="once"<?php if($_REQUEST['mess']=='' || $_REQUEST['mess']=='add') echo "checked";?> onClick="window.location.href='?mess=add'" > 自动备份
         <input type="radio" name="mess" id="orderadd"  value="once"<?php if($_REQUEST['mess']=='orderadd') echo "checked";?> onClick="window.location.href='?mess=orderadd'" > 停机扫描
	 <input type="radio" name="mess" id="upcoming"  value="once"<?php if($_REQUEST['mess']=='upcoming') echo "checked";?> onClick="window.location.href='?mess=upcoming'" > 包小时扫描
	 <input type="radio" name="mess" id="maturity"  value="more" <?php if($_REQUEST['mess']=='maturity') echo "checked";?> onClick="window.location.href='?mess=maturity'" > 包流量扫描
         <input type="radio" name="mess" id="maturity1"  value="more" <?php if($_REQUEST['mess']=='maturity1') echo "checked";?> onClick="window.location.href='?mess=maturity1'" > 在线检测
         <input type="hidden" name="id" id="id"  value="<?=$rs['id']?>"  >  
         <input name="days" type="hidden"id="days"  value="" size="50"></td>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
  	  <tr> 
			 <td align="left" class="bg">在线检测</td>
		   <td align="left" class="bg">
                       <input type="text"name="files" value="<?php if(!empty($rs['files'])){echo $rs['files'];}  else {echo "bak_event.php";}?>" readonly="readonly"/>
                       <input type='hidden' value='5' name='type'/>
                   </td>
		  </tr> 
  	  <tr> 
			 <td align="left" class="bg">状态</td>
		   <td  align="left" class="bg">
			 <input name="status" type="radio" value="enable" <?php if($rs['status'] =="" || $rs['status']=="enable")echo"checked";?> >启用
			 <input name="status" type="radio" value="disable"<?php if($rs['status']=="disable")echo"checked";?> >禁用</td>
		  </tr> 
                  <tr style="display: none;">
		    <td align="left" class="bg">时间</td>
		    <td align="left" class="bg">
				<input name="newtime" type="text"  value="<?php if($rs['newtime']==""){echo "5";}  else {echo $rs['newtime'];}?>" > 	
                          <select name="time" >
                            <option value="fen" <?php if($rs['time']=="fen" ||$rs['time'] == "")echo"selected='selected'";?>>分钟</option>
                            <option value="shi" <?php if($rs['time']=="shi") echo"selected='selected'";?>>小时</option>
                          </select>
                                &nbsp;提示：分钟范围为“1-59”分钟，小时范围为“1-23”小时。
                    </td>
		 </tr>
            <tr> 
		 <td align="left" class="bg"> 检测时间</td>
		   <td align="left" class="bg">
                       <input type="text"name="backTime" value="<?php if(!empty($rs['backTime'])){echo $rs['backTime'];}  else {echo "6";}?>"/>&nbsp;提示：单位为分钟
                   </td>
		  </tr> 
  	  <tr> 
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>计划任务</strong>
      </p>
      <ul>
          <li>自动备份：系统默认2小时备份一次数据。</li>
          <li>停机扫描：系统默认1小时扫描一次到期用户。</li>
          <li>包小时扫描：有包小时用户须开启次功能才能对用户计费。</li>
          <li>包流量扫描：有包流量用户须开启次功能才能对用户计费。</li>
          <li>在线检测：系统默认6分钟检测一次在线用户。</li>
          <li>无特殊需求请勿改动，以免造成数据异常。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

