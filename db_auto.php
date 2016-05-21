#!/bin/php
<?php 
include("inc/conn.php");  
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("数据库管理")?></title>
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
            WindowTitle:          '<b>备份恢复</b>',
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
$rs=$db->select_one("ID,scannum","config","ID >0 ORDER BY ID DESC"); 
if($_POST){
  $scannum = trim($_POST["scannum"]);
   if(!is_numeric($scannum)){
      echo "<script>alert('"._("自动备份保留数必须为数字")."');window.location.href='db_auto.php';</script>";
	  exit;
   }if($scannum<12){
     echo "<script>alert('"._("自动备份保留数必须为大于等于12")."');window.location.href='db_auto.php';</script>";
	 exit;
   }if($scannum >48){
     echo "<script>alert('"._("自动备份保留数最大不超过48")."');window.location.href='db_auto.php';</script>";
	 exit;
   }
  $db->update_new("config","ID='".$rs["ID"]."'",array("scannum"=>$scannum));
  echo "<script>alert('"._("操作成功")."');window.location.href='db_auto.php'</script>";
}


?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统备份")?></font></td>
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
		<td width="89%" class="f-bulue1"><? echo _("数据库自动备份")?></td>
		<td width="11%" align="right">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
		<form action="?" method="post" name="myform">
		<tr>
		<td width="19%" align="right" class="bg"><? echo _("自动备份保留数:")?></td>
		<td width="81%" class="bg"> 
                    <input type="text" name="scannum" value="<?php if(empty($rs["scannum"])){echo '24';}else{echo $rs["scannum"];} ?>">	<?php echo _("超过备份保留数将删除最早的备份,范围 12~48     间隔时间为2小时备份一次")?></td>
		</tr>
		<tr>
		<td align="right" class="bg">&nbsp;</td>
		<td class="bg"><input type="submit"   value="<?php echo _("保存设置")?>"></td>
		</tr> 
		</form>
		</table>	
		<br></td>
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
        备份恢复-> <strong>自动备份</strong>
      </p>
      <ul>
          <li>自动备份保留数，超过备份保留数将删除最早的备份。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

