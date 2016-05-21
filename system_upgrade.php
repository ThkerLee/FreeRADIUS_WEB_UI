#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统升级")?></title>
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
<body>
<?php 
if($_GET["action"]=='up'){ 
	
	$file=$_FILES["filename"];
	if (is_uploaded_file($file['tmp_name'])) {
		copy($file['tmp_name'], "./natshell_upgrade.zip");
	}   
	if(file_exists("natshell_upgrade.zip")){
		exec("tar xvf natshell_upgrade.zip", $result, $return_var);
    if ( $return_var != 0 ) {
      pclose(popen("unzip -o natshell_upgrade.zip", "r"));	
    }	
		exec("rm natshell_upgrade.zip");
		exec("chmod +x /usr/local/usr-gui/*.php");
		exec("chmod +x /usr/local/usr-gui/inc/*.php");
		exec("chmod +x /usr/local/usr-gui/ajax/*.php");
		exec("chmod +x /usr/local/usr-gui/PHPExcel/*.php");
		if(file_exists("version.php"))  exec("version.php");
                if(file_exists("sql.conf")) {
            exec("cp -rf sql.conf /mnt/mysql/usr/local/etc/raddb/");
            exec("rm -rf sql.conf");
        }
        echo "<script>alert('"._("升级成功")."');window.history.go(-1);</script>";
	}
	
	//判断版本号;
	if(file_exists("version.php")){
		@include("check_data.php");
		@include("version.php");
		if(file_exists("check_data.php")) exec("rm check_data.php");
		exec("rm version.php");
	}
	
}
?>

<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统设置")?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("系统升级")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
	  <form action="system_upgrade.php?action=up" method="post" name="myfrom" enctype="multipart/form-data">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
  	    <tbody>  
		  <tr>
		    <td align="left" class="bg"><BR><? echo _("提示:")?>
                    <UL>
                      <LI><? echo _("本功能在本系统基础上升级新的版本")?></LI>
                      <LI><? echo _("您需要选择你所升级的版本")?></LI>
                      <LI><? echo _("版本需要以次升级")?></LI>
                    </UL>		    
			<br></td>
	      </tr>
		  <tr>
		    <td height="30" align="left" class="bg"><? echo _("当前版本:")?><?=$config_version ?></td>
	      </tr>
		  <tr>
		    <td height="30" align="left" class="bg"><? echo _("选择您升级的文件")?></td>
	      </tr>
		  <tr>
		    <td align="left" class="bg">
				<input type="file" name="filename">			</td>
	      </tr>
		  <tr>
		    <td align="left" class="bg">
				<input type="submit" value="<?php echo _("上传")?>">
			</td>
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>系统升级</strong>
      </p>
      <ul>
          <li> 可以对系统的固件进行升级，点击“浏览”选择一个有效的升级文件，并点击“上传”，即可进行固件更新。</li>
          <li>如果是内网更新，速度一般为几秒钟之内完成，升级完成，系统返回提示。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

