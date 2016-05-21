#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("报修登记")?></title>
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
            WindowTitle:          '<b>工单管理</b>',
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
<?php 
$userID   =$_POST["userID"];
$UserName =$_REQUEST["UserName"];
$reason   =$_POST["reason"];
$type 	  =$_POST["type"];
if($_POST){
	$sql=array(
		"userID"=>$userID,
		"UserName"=>$UserName,
		"reason"=>$reason,
		"type"=>$type,
		"operator"=>$_SESSION["manager"],
		"status"=>1,//报修中
		"startdatetime"=>date("Y-m-d H:i:s",time())
	);
	$db->insert_new("repair",$sql);
	echo "<script>alert(\"". _("成功登记，等待管理员处理，请不要重得添加")."\");</script>";
	echo "<script>if(window.confirm('"._("是否打印票据")."?')){window.open('repair_show_print.php?UserName=".$UserName."&ID=".$userID."&action=now','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no,top=100,left=300');}window.location.href='repair.php';</script>";
}
?>
</head>
<body>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("报修管理")?></font></td>
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
        <td width="93%" class="f-bulue1"><? echo _("报修登记")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <form action="?action=addSave" name="myform" method="post"  onSubmit="return checkRepairAddForm();">
		  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1">
			  <tr>
				<td width="18%" align="right" class="bg"><? echo _("用 户 名:")?></td>
				<td width="82%" class="bg">
					<input type="text" id="UserName" name="UserName" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','repair_check','UserName','UserNameTXT');" class="input_out" value="<?=$UserName?>">
                <span id="UserNameTXT"></span>				</td>
			  </tr>
			  <tr>
			    <td align="right" class="bg"><? echo _("工单种类:")?></td>
			    <td class="bg">
				<select name="type">
					<option value="1"><? echo _("报装")?></option>
					<option value="2"><? echo _("报修")?></option>
					<option value="3"><? echo _("其他")?></option>
				</select>
				</td>
		    </tr>
			  <tr>
				<td align="right" valign="top" class="bg"><? echo _("备注说明")?>:</td>
				<td class="bg">
					<textarea name="reason" rows=8 cols=50></textarea>				</td>
			  </tr>
			  <tr>
				<td align="right" class="bg">&nbsp;</td>
				<td class="bg">
					<input type="submit" value="<? echo _("提交")?>"  onClick="javascript:return window.confirm( '<? echo _("确认提交")?>？ ');">				</td>
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
        工单管理-> <strong>工单录入</strong>
      </p>
      <ul>
          <li>对用户的报装、报修录入工单，并可由管理员指定处理人员和处理时效。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

