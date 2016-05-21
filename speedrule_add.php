#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("限速规则")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<!--<script src="js/jquery.js" type="text/javascript"></script>--和下面的jquery冲突-->
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
            WindowTitle:          '<b>产品管理</b>',
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
	$projectID=$_POST["projectID"];
	$srcip    =$_POST["srcip"];
	$dstip    =$_POST["dstip"];
	$srcport  =$_POST["srcport"];
	$dstport  =$_POST["dstport"];
	$upload   =$_POST["upload"];
	$download =$_POST["download"];
	$subnet   =$_POST["subnet"];
	if (empty($projectID)) {
		$input_errors[] =   _("您必须选择一个项目");
	}
	if (empty($dstip) && !is_ip($dstip)) {
		$input_errors[] =  _("须输入一个合法的IP地址。");
	}
	if (empty($upload) || !is_number($upload)) {
		$input_errors[] =  _("须指定一个合法的数字");
	}
	if (empty($download) || !is_number($download)) {
		$input_errors[] = _("须指定一个合法的数字");
	}	
	if($input_errors){
		foreach($input_errors as $va){
			$strtxt .=$va."\\n";
		}
		echo "<script language='javascript'>alert('".$strtxt."');window.history.go(-1);</script>";
		exit;
	}
	$dstip=$dstip."/".$subnet;
	if(!$input_errors){
		$sql="insert into speedrule(projectID,dstip,upload,download) values('$projectID','$dstip','$upload','$download')";
		$db->query($sql);
		echo "<script>alert('". _("添加成功") . " ');window.location.href='speedrule.php';</script>";
	}
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
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("限速规则")?></font></td>
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
        <td width="89%" class="f-bulue1"> <? echo _("限速规则添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
		<form  method="post" name="myform" id="myform" action="?action=addsave">
		  <table width="100%" border="0" cellpadding="5" cellspacing="1" class="bg1">
			<tr>
			  <td width="20%" align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("项目:")?></td>
			  <td width="80%" bgcolor="#FFFFFF" class="border_b border_l"><?=projectSelected($ID="") ?> </td>
			</tr>
		
			<tr>
			  <td align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("目标IP:")?></td>
			  <td bgcolor="#FFFFFF" class="border_b border_l">
				  <input name='dstip' type='text' id='dstip' onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out">
				  /
				  <select name="subnet">
					<?php
						for($si=1;$si<=32;$si++){
					?>
						<option value="<?=$si?>"><?=$si?></option>
					<?php 			
						}
					?>
			    </select>			    255.255.255.0&nbsp;<? echo _("则选择24")?></td>
			</tr>
			
			<tr>
			  <td align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("上传带宽:")?></td>
			  <td bgcolor="#FFFFFF" class="border_b border_l">
			  <input type="text" name='upload' id="upload" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"/>
kbit			  </td>
			</tr>
			<tr>
			  <td align="right" bgcolor="#FFFFFF" class="border_b"><? echo _("下载带宽:")?></td>
			  <td bgcolor="#FFFFFF" class="border_b border_l">
			  <input  name='download' type="text" id="download" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"/>
kbit			   </td>
			</tr>
		
			<tr>
			  <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
			  <td align="left" bgcolor="#FFFFFF"><input name="submit" type="submit" value="<? echo _("提交")?>" /></td>
			</tr>
			<tr>
			  <td colspan="2" align="left" bgcolor="#FFFFFF" class="line-20"><p ><? echo _("添加说明： ") ;echo "</p>			    <p >";  echo _("此处设定的限速规则,可以针对PPPOE用户访问指定的IP地址段分配指定的限速规则. 如:需要对内网电影服务器的访问限速为10M,而电影服务器的IP地址为:192.168.100.100,则按如下方式填写:目标IP:192.168.100.0,子网掩码选择24位,上传和下载的带宽均填写:10240即可.")?></p></td>
		    </tr>
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
        产品管理-> <strong>内网限速</strong>
      </p>
      <ul>
          <li>可以对指定的内网或外网地址段进行特殊的限速规则设置。</li>
          <li>用于对内网的电影、游戏等服务器生成单独的限速规则。</li>
          <li>生成此限速规则，不会使用户访问外网的速率发生变更。</li>
          <li>项目：所需要指定内网限速规则的项目。</li>
          <li>目标 IP ：需要单独设定限速规则的网段，可以是内网或外网网段。</li>
          <li>上传带宽：指定单独设定的网段的访问上传速率，单位为 kbit。</li>
          <li>下载带宽：指定单独设定的网段的访问下载速率，单位为 kbit。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

