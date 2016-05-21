#!/bin/php
<?php
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>支付宝设置</title>
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
            WindowTitle:          '<b>网银管理</b>',
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
$rs=$db->select_one("*","paymen","ID=1");
if($_POST){ 
    $sql=array(
        "status"           => $_POST["status"],            //状态
        "partner"          => $_POST["partner"],           //合作身份者ID
        "skey"             => $_POST["skey"],              //安全检验码
        "WIDseller_email"  => $_POST["WIDseller_email"],   //支付宝账号
        "return_url"       => $_POST["return_url"],        //同步页面
        "WIDdefaultbank"   => $_POST["WIDdefaultbank"],    //默认网银
        "WIDsubject"       => $_POST["WIDsubject"],        //商品名称
        "WIDbody"          => $_POST["WIDbody"]            //商品描述
    );
    if(!empty($rs)){//查询的$rs不为空就修改
        if($_POST["partner"] == "" || $_POST["skey"]=="" || $_POST["WIDseller_email"]=="" || $_POST["return_url"]=="" ||$_POST["WIDdefaultbank"]=="" ||$_POST["WIDsubject"]==""){
            //合作身份者ID、安全检验码、支付宝账号、同步页面、默认网银、商品名称不能为空
            echo "<script language='javascript'>alert('"._("数据不完整")."');window.location.href='instantPaymen.php';</script>";
            exit();
        }  else {
             $db->update_new("paymen","ID=1",$sql);
        }
       
    }  else {//否则就新增
      if($_POST["partner"] == "" || $_POST["skey"]=="" || $_POST["WIDseller_email"]=="" || $_POST["return_url"]=="" ||$_POST["WIDdefaultbank"]=="" ||$_POST["WIDsubject"]==""){
            //合作身份者ID、安全检验码、支付宝账号、同步页面、默认网银、商品名称不能为空
            echo "<script language='javascript'>alert('"._("数据不完整")."');window.location.href='instantPaymen.php';</script>";
            exit();
        }  else {
        $db->insert_new("paymen",$sql);
        }
    }
	 echo "<script language='javascript'>alert('"._("配置成功")."');window.location.href='instantPaymen.php';</script>"; 
   
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
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">支付宝设置</font></td>
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
<form action="?" method="post" name="myform" >
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1">支付宝设置</td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>
	 <td align="left" class="bg" style="text-align:right;">  状态  </td>
		   <td align="left" class="bg">
			 <input name="status" type="radio" value="enable"<?php if($rs['status']=="enable")echo"checked";?> />  启用  
			 <input name="status" type="radio" value="disable" <?php if($rs['status'] =="" || $rs['status']=="disable")echo"checked";?>/> 禁用  </td>
		  </tr> 
		  <tr>
                      <td align="left" class="bg" style="text-align:right;">合作身份者ID</td>
		    <td align="left" class="bg">
				<input name="partner" type="text"  value="<?=$rs['partner']?>" size="50">   提示：合作身份者id，以2088开头的16位纯数字</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg" style="text-align:right;">安全检验码</td>
		    <td align="left" class="bg">
				<input name="skey" type="text"    id="client"  value="<?=$rs['skey']?>" size="50">  提示：安全检验码，以数字和字母组成的32位字符	</td>
		 </tr> 
		  <tr>
		    <td align="left" class="bg" style="text-align:right;">支付宝账号</td>
		    <td align="left" class="bg">
				<input name="WIDseller_email" type="text"    id="client"  value="<?=$rs['WIDseller_email']?>" size="50"> 提示：签约支付宝账号或卖家支付宝账号</td>
		  </tr>
		  <tr>
		    <td align="left" class="bg" style="text-align:right;">同步页面</td>
		    <td align="left" class="bg">
				<input name="return_url" type="text"    id="client"  value="<?=$rs['return_url']?>" size="50">  提示：用户登录自助页面的地址或域名,需http://格式的完整路径,例如http://natshell.oicp.net:8888  
			</td>
		  </tr> 
                  <tr>
		    <td align="left" class="bg" style="text-align:right;">默认网银</td>
		    <td align="left" class="bg">
				<input name="WIDdefaultbank" type="text"    id="client"  value="<?=$rs['WIDdefaultbank']?>" size="50">  提示：请填银行简码，中国银行-BOCB2C  工商银行-ICBCB2C  招商银行-CMB  建设银行-CCB  农业银行-ABC  
			</td>
		  </tr> 
                  <tr>
		    <td align="left" class="bg" style="text-align:right;">商品名称</td>
		    <td align="left" class="bg">
				<input name="WIDsubject" type="text"    id="client"  value="<?=$rs['WIDsubject']?>" size="50">  提示：网费充值   
			</td>
		  </tr> 
                  <tr>
		    <td align="left" class="bg" style="text-align:right;">商品描述</td>
		    <td align="left" class="bg">
				<input name="WIDbody" type="text"    id="client"  value="<?=$rs['WIDbody']?>" size="50"> 提示：可以为空   
			</td>
		  </tr> 
		  <tr>
		      <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="保存"></td>
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
        网银管理-> <strong>支付宝设置</strong>
      </p>
      <ul>
          <li>通过支付宝的支付渠道，付款者可以直接汇款给另一个拥有支付宝账号的收款者。</li>
          <li>合作身份者ID、安全检验码、签约支付宝账号需要到支付宝官网申请。</li>
          <li>同步页面为充值成功后更新用户余额的页面，此页面必须外网能访问。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

