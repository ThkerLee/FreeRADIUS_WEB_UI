#!/bin/php
<?php 
include("inc/conn.php");
require_once("evn.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("无标题文档")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<link href="inc/dialog.css" rel="stylesheet" type="text/css">
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
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td>
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bd">
  <tr>
    <td>
    <table width="100%" align="center" class="title_bg2 border_t border_l border_r">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("系统还原")?></td>
        <td width="11%" align="right"></td>
      </tr></table>
<?php
/******界面*/if(!$_POST['act']&&!$_SESSION['data_file']){/**********************/
$msgs[]=_("本功能在恢复备份数据的同时，将全部覆盖原有数据，请确定是否需要恢复，以免造成数据损失");
$msgs[]=_("数据恢复功能只能恢复由dShop导出的数据文件，其他软件导出格式可能无法识别");
$msgs[]=_("从本地恢复数据需要服务器支持文件上传并保证数据尺寸小于允许上传的上限，否则只能使用从服务器恢复");
$msgs[]=_("如果您使用了分卷备份，只需手工导入文件卷1，其他数据文件会由系统自动导入");
show_msg($msgs);
?>
<form action="" method="post" enctype="multipart/form-data" name="restore.php">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
<tr align="center" class="header"><td colspan="2" align="center"><? echo _("数据恢复<")?>/td></tr>
<tr><td width="33%"><input type="radio" name="restorefrom" value="server" checked>
<? echo _("从服务器文件恢复")?> </td><td width="67%"><select name="serverfile">
    <option value="">-<? echo _("请选择")?>-</option>
<?php
$handle=opendir('./backup');
while ($file = readdir($handle)) {
    if(eregi("^[0-9]{8,8}([0-9a-z_]+)(\.sql)$",$file)) echo "<option value='$file'>$file</option>";}
closedir($handle); 
?>
  </select> </td></tr>
<tr><td><input type="radio" name="restorefrom" value="localpc">       
<? echo _("从本地文件选择")?></td>
  <td><input type="hidden" name="MAX_FILE_SIZE" value="150000000"><input type="file" name="myfile"></td></tr>
<tr><td colspan="2" align="center"> <input type="submit" name="act" value="<?php echo _("上传并恢复数据库")?>">
<input type="submit" name="act" value="<?php echo _("上传备份数据库")?>">
</td>  </tr></table></form>


<?php
/**************************界面结束*/}/*************************************/
/****************************主程序*/if($_POST['act']==_("上传并恢复数据库")){/**************/
/***************服务器恢复*/if($_POST['restorefrom']=="server"){/**************/
if(!$_POST['serverfile'])
	{$msgs[]=_("您选择从服务器文件恢复备份，但没有指定备份文件");
	 show_msg($msgs); pageend();	}
if(!eregi("_v[0-9]+",$_POST['serverfile']))
	{$filename="./backup/".$_POST['serverfile'];
	if(import($filename)) $msgs[]=_("备份文件").$_POST['serverfile']._("成功导入数据库");
		else $msgs[]=_("备份文件").$_POST['serverfile']._("导入失败");
	show_msg($msgs); pageend();		
	}
else
	{
	$filename="./backup/".$_POST['serverfile'];
	if(import($filename)) $msgs[]=_("备份文件").$_POST['serverfile']._("成功导入数据库");
	else {$msgs[]=_("备份文件").$_POST['serverfile']._("导入失败");show_msg($msgs);pageend();}
	$voltmp=explode("_v",$_POST['serverfile']);
	$volname=$voltmp[0];
	$volnum=explode(".sq",$voltmp[1]);
	$volnum=intval($volnum[0])+1;
	$tmpfile=$volname."_v".$volnum.".sql";
	if(file_exists("./backup/".$tmpfile))
		{
		$msgs[]=_("程序将在3秒钟后自动开始导入此分卷备份的下一部份:文件").$tmpfile._(",请勿手动中止程序的运行，以免数据库结构受损");
		$_SESSION['data_file']=$tmpfile;
		show_msg($msgs);
		sleep(3);
		echo "<script language='javascript'>"; 
		echo "location='restore.php';"; 
		echo "</script>"; 
		}
	else
		{
		$msgs[]=_("此分卷备份全部导入成功");
		show_msg($msgs);
		}
	}
/**************服务器恢复结束*/}/********************************************/
/*****************本地恢复*/

if($_POST['restorefrom']=="localpc"){/**************/
	switch ($_FILES['myfile']['error'])
	{
	case 1:
	case 2:
	$msgs[]=_("您上传的文件大于服务器限定值，上传未成功");
	break;
	case 3:
	$msgs[]=_("未能从本地完整上传备份文件");
	break;
	case 4:
	$msgs[]=_("从本地上传备份文件失败");
	break;
    case 0:
	break;
	}
	if($msgs){show_msg($msgs);pageend();}
$fname=date("Ymd",time())."_".time().".sql";
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
    copy($_FILES['myfile']['tmp_name'], "./backup/".$fname);
}

if (file_exists("./backup/".$fname)) 
	{
	$msgs[]=_("本地备份文件上传成功");
	if(import("./backup/".$fname)) {$msgs[]=_("本地备份文件成功导入数据库"); unlink("./backup/".$fname);}
	else $msgs[]=_("本地备份文件导入数据库失败");
	}
else $msgs[]=_("从本地上传备份文件失败");
show_msg($msgs);
/****本地恢复结束*****/}/****************************************************/
/****************************主程序结束*/}/**********************************/
/*************************剩余分卷备份恢复**********************************/
if(!$_POST['act']&&$_SESSION['data_file'])
{
	$filename="./backup/".$_SESSION['data_file'];
	if(import($filename)) $msgs[]=_("备份文件").$_SESSION['data_file']._("成功导入数据库");
	else {$msgs[]=_("备份文件").$_SESSION['data_file']._("导入失败");show_msg($msgs);pageend();}
	$voltmp=explode("_v",$_SESSION['data_file']);
	$volname=$voltmp[0];
	$volnum=explode(".sq",$voltmp[1]);
	$volnum=intval($volnum[0])+1;
	$tmpfile=$volname."_v".$volnum.".sql";
	if(file_exists("./backup/".$tmpfile))
		{
		$msgs[]=_("程序将在3秒钟后自动开始导入此分卷备份的下一部份:文件").$tmpfile._(",请勿手动中止程序的运行,以免数据库结构受损");
		$_SESSION['data_file']=$tmpfile;
		show_msg($msgs);
		sleep(3);
		echo "<script language='javascript'>"; 
		echo "location='restore.php';"; 
		echo "</script>"; 
		}
	else
		{
		$msgs[]=_("此分卷备份全部导入成功");
		unset($_SESSION['data_file']);
		show_msg($msgs);
		}
}
if($_POST['act']==_("上传备份数据库")){
	switch ($_FILES['myfile']['error'])
	{
	case 1:
	case 2:
	$msgs[]=_("您上传的文件大于服务器限定值，上传未成功");
	break;
	case 3:
	$msgs[]=_("未能从本地完整上传备份文件");
	break;
	case 4:
	$msgs[]=_("从本地上传备份文件失败");
	break;
    case 0:
	break;
	}
	if($msgs){show_msg($msgs);pageend();}
	$fname=date("Ymd",time())."_".time().".sql";
	if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
		copy($_FILES['myfile']['tmp_name'], "./backup/".$fname);
	}
	
	if (file_exists("./backup/".$fname)) 
		{
		$msgs[]=_("本地备份文件上传成功");

		}
	else ($msgs[]=_("从本地上传备份文件失败"));
	show_msg($msgs);
}

/**********************剩余分卷备份恢复结束*******************************/
function import($fname)
{global $d;
$sqls=file($fname);
foreach($sqls as $sql)
	{
	str_replace("\r","",$sql);
	str_replace("\n","",$sql);
	if(!$d->query(trim($sql))) return false;
	}
return true;
}
function show_msg($msgs)
{
$title=_("提示:");
echo "<table width='95%' border='0'  cellpadding='5' cellspacing='0' align='center'>";
echo "<tr><td>".$title."</td></tr>";
echo "<tr><td><br><ul>";
while (list($k,$v)=each($msgs))
	{
	echo "<li>".$v."</li><br><br>";
	}
echo "</ul></td></tr></table>";
}

function pageend()
{
exit();
}
?>


    <br />

    </td>
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
        备份恢复-> <strong>数据恢复</strong>
      </p>
      <ul>
          <li>可以将备份的数据库文件恢复到本系统中。</li>
          <li>备份的数据可以从本地上传和选择从服务器中恢复。</li>
          <li>从服务器文件恢复：即从备份到服务器本机的数据库文件进行数据恢复，通过下拉菜单进行文件选择。</li>
          <li>从本地文件恢复： 从可连接服务器的任意电脑上，进行数据库文件的恢复，选择“浏览”按纽，从本地计算机上选取文件。</li>
          <li>上传并恢复数据库：上传备份文件，并将之恢复到本系统中。</li>
          <li>上传备份数据库：仅上传备份文件到系统目录，暂不恢复。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>
