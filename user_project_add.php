#!/bin/php
<?php 
require_once("inc/conn.php");
require_once("evn.php");
?>

<html>



<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>无标题文档</title>

<link href="style/bule/bule.css" rel="stylesheet" type="text/css">

<script src="js/ajax.js" type="text/javascript"></script>
<!-- 产品添加  -->
<script src='./jquery-1.4.js'> </script>
<style>
#project
{color:lime;
display:none;
background:#fff;
position:absolute;
top:35;
left:50;
text-align:right;
}

#project td a:hover
{
color:blue;
}
</style>
</head>

<body>

<?php 
/*
if($_POST){

	$result=projectAddSave($_POST);

	echo "<script>alert('".$result."');window.location.href='project.php';</script>";

}*/

?>
<!--<div class=''>&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:add_project()'>项目添加</a>	</div>-->
<table width="500px" height="345" border="0" cellpadding="0" cellspacing="0" id="project">

  <tr>

    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>

    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">

		  <tr>

			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>

			<td width="60%" height="35" valign="middle"><font color="#FFFFFF" size="2">项目管理</font></td>

		  </tr>

   		</table>

	</td>

    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>

  </tr>

  

  <tr>

    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>

    <td height="250" valign="top">

	<table width="100px" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">

      <tr>

        <td width="100%" class="f-bulue1"><font style="text-align:left">项目添加</font></td>

		<td width="11%" align="right">&nbsp;</td>

      </tr>

	  </table>

<form action="?" method="post" name="myform"  ><!--  onSubmit="return checkProjectForm();"-->

  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">

        <tbody>     

		  <tr>

			<td width="100px" align="right" class="bg">项目名称：</td>

			<td width="100px" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','projectName','name','nameTXT');" class="input_out"><span id="nameTXT"></span>			</td>

		  </tr>

		  <tr>

		    <td align="right" class="bg">开始 IP： </td>

		    <td align="left" class="bg"><input type="text" id="beginip" name="beginip" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','beginip','beginip','beginipTXT');" ><span id="beginipTXT"></span></td>

	      </tr>

		  <tr>

		    <td align="right" class="bg">结束 IP：</td>

		    <td align="left" class="bg"><input type="text" id="endip" name="endip" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','endip','endip','endipTXT');"><span id="endipTXT"></span></td>

	      </tr>

		  <tr>

		    <td align="right" class="bg">选择设备：</td>

		    <td align="left" class="bg"><?php deviceSelected(); ?></td>

	      </tr>

		  <tr>

		    <td align="right" class="bg">初装费用：</td>

		    <td align="left" class="bg">

				<input name="installcharge" type="text" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','endip','endip','endipTXT');" value="0" size="5">

			</td>

		    </tr>

		  <tr>

		    <td align="right" class="bg">项目描述：</td>

		    <td align="left" class="bg"><input type="text" name="description" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"></td>

	      </tr>

		  <tr>

		    <td align="right" class="bg">MTU值：</td>

		    <td align="left" class="bg"><input name="mtu" type="text" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out'" value="1480"></td>

	      </tr>

		  <tr>

		    <td align="right" class="bg">&nbsp;</td>

		    <td align="left" class="bg">

				<input type="submit" value="提交">			</td>

	      </tr>
 <tr><td colspan=2><font color='red' style="padding-left:430px" ><a href='javascript:add_project()'>关闭</a></font></td></tr>
        </tbody>      

    </table>	

</form>

	</td>

 <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>

  </tr>

  <tr>

    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>

    <td width="500" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>

    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>

  </tr>

 

</table>

</body>

</html>



<script>
var flag=true;
	function add_project()
	{
		if(flag)
		{
		$('#project').fadeIn(10);// fadeIn淡入    jQuery弹出效果淡入淡出（时间） 即多长时间完成淡入淡出  单位毫秒
		$('#project').css('display','block');flag=false;		
		}
		else
		 {
		 flag=true;
		 $('#project').fadeOut(10);//淡出
		 $('#project').css('display','none');//none隐藏
		 //hidden 占位隐藏 
		//visibility  不占位隐藏
		 }
	}
	

</script>