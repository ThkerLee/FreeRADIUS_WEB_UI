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
</head>
<?php 
if($_POST){
	$db->update_new("duenotice","",$_POST);
	//Array ( [status] => yes [period] => 1 [days] => 512 [fwd_ipaddr] => 192.168.100.126 [fwd_port] => 8000 [product] => natshell ) 
	if ( file_exists("/var/run/duenotice.pid") ) {
	  exec("kill `cat /var/run/duenotice.pid`");	
	  unlink("/var/run/duenotice.pid");
	}
	if (0 == strcmp($_POST['status'], "yes") ) {
		///var/run/duenotice.pid
		pclose(popen("/usr/bin/duenotice", "r"));
	}
	//print_r($_POST);
	echo "<script language='javascript'>alert('保存成功');</script>";
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
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2">系统设置</font></td>
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
		<?php 
		$rs=$db->select_one("*","duenotice","0=0 order by ID desc limit 0,1");
		?>
	  <form action="?" method="post" name="myfrom">
  	  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
  	    <tbody>
		  <tr>
		    <td width="18%" height="30" align="left" class="bg">状态</td>
	        <td width="82%" align="left" class="bg">
				<input type="radio" name="status" value="yes" <?php if($rs["status"]=="yes") echo "checked"; ?>>启用
				<input type="radio" name="status" value="no" <?php if($rs["status"]=="no") echo "checked"; ?>>禁用			</td>
		  </tr>
		  <tr>
		    <td align="left" class="bg">通行间隔时间</td>
		    <td align="left" class="bg">
				<input type="text" name="period" value="<?=$rs["period"]?>"> 
				小时			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><p >提前通告的天数</p></td>
		    <td align="left" class="bg">
				<input type="text" name="days" value="<?=$rs["days"]?>"> 
				天			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg">需转发的IP地址</td>
		    <td align="left" class="bg">
				<input type="text" name="fwd_ipaddr" value="<?=$rs["fwd_ipaddr"]?>">
			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><p >端口</p></td>
		    <td align="left" class="bg">
				<input type="text" name="fwd_port" value="<?=$rs["fwd_port"]?>">			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg">产品类型</td>
		    <td align="left" class="bg">
				<select name="product">
					<option value="natshell">NatShell</option>
				</select>			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
		    <td align="left" class="bg">&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="保存"></td>
		  </tr>
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg">&nbsp;</td>
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
</body>
</html>

