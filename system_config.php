#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html;  charset=utf-8" />
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
<?php 
if($_POST){
	$sql=array(
		"site"=>$_POST["site"],
		"copyright"=>$_POST["copyright"],
		"speedStatus"=>$_POST["speedStatus"],
		"macStatus"=>$_POST["macStatus"],
		"picTopLeft"=>$_POST["picTopLeft"],
		"picTopRight"=>$_POST["picTopRight"],
		"picBottomLeft"=>$_POST["picBottomLeft"],
		"picBottomRight"=>$_POST["picBottomRight"],
		"picLogin"=>$_POST["picLogin"],
		"WEB"=>$_POST["WEB"],
		"Name"=>$_POST["Name"],
		"copyrightLog"=>$_POST["copyrightLog"],//登录页面版权信息
		"CRStatement"=>$_POST["CRStatement"],//版权声明
		"Contact"=>$_POST['Contact']//联系方式
	);
	$db->update_new("config","",$sql); 
	 copy("./images/".$_POST["picLogin"], "../usr-as-gui/images/login_bg.jpg"); 
	 copy("./images/".$_POST["picTopLeft"], "../usr-as-gui/images/li_r1_c1.jpg") ;
	 copy("./images/".$_POST["picTopRight"], "../usr-as-gui/images/li_r1_c12.jpg") ;
	 copy("./images/".$_POST["picBottomLeft"], "../usr-as-gui/images/li_r18_c1.jpg") ; 
	 copy("./images/".$_POST["picBottomRight"], "../usr-as-gui/images/li_r18_c13.jpg") ;   
	echo "<script language='javascript'>alert('"._("保存成功")."');window.location.href='system_config.php';</script>";
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
        <td width="89%" class="f-bulue1"><? echo _("系统配置")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
		<?php 
		$rs=$db->select_one("*","config","0=0 order by ID desc limit 0,1");
		?>
	  <form action="?" method="post" name="myfrom">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
  	    <tbody>
		  <tr>
		    <td height="30" align="left" class="bg"><? echo _("系统标题")?></td>
		    <td align="left" class="bg"><input type="text" name="site" value="<?=$rs["site"]?>"></td>
		    </tr>
		  <tr>
		    <td width="18%" height="30" align="left" class="bg"><? echo _("版本信息")?></td>
		    <td width="82%" align="left" class="bg">
				<textarea name="copyright" rows="5" cols=50><?=$rs["copyright"]?></textarea>			</td>
		  </tr>
		  <tr>
		    <td height="30" colspan="2" align="left" class="bg"><? echo _("设置界面的图标，请不要随意更改")?> </td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("上面左边图标")?></td>
		    <td align="left" class="bg">
			<div id="picTopLeftView"></div>
			<input type="hidden" name="picTopLeft" id="picTopLeftID" value="<?=$rs["picTopLeft"]?>">
		  <!--<iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=logo&upFileID=picTopLeftID&viewID=picTopLeftView" scrolling="no" frameborder="0"></iframe>-->
		  <iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=logo&upFileID=picTopLeftID&viewID=picTopLeftView" scrolling="no" frameborder="0"></iframe>	
		  </td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("上面右边图标")?></td>
		    <td align="left" class="bg">
			<div id="picTopRightView"></div>
			<input type="hidden" name="picTopRight" id="picTopRight" value="<?=$rs["picTopRight"]?>">
		  <iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=logo&upFileID=picTopRight&viewID=picTopRightView" scrolling="no" frameborder="0"></iframe>			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("下面左边图标")?></td>
		    <td align="left" class="bg">
			<div id="picBottomLeftView"></div>
			<input type="hidden" name="picBottomLeft" id="picBottomLeft" value="<?=$rs["picBottomLeft"]?>">
		  <iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=logo&upFileID=picBottomLeft&viewID=picBottomLeftView" scrolling="no" frameborder="0"></iframe>			</td>
		    </tr>
		  <tr>
		    <td align="left" class="bg"><? echo _("下面右边图标")?></td>
		    <td align="left" class="bg">
			<div id="picBottomRightView"></div>
			<input type="hidden" name="picBottomRight" id="picBottomRight" value="<?=$rs["picBottomRight"]?>">
		    <iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=logo&upFileID=picBottomRight&viewID=picBottomRightView" scrolling="no" frameborder="0"></iframe>			</td>
		    </tr>
			<!-- 登录界面图标修改-->
			  <tr>
		    <td height="30" colspan="2" align="left" class="bg"><? echo _("设置登录界面的图标，请不要随意更改")?> </td>
		    </tr>
				  <tr>
		    <td align="left" class="bg"><? echo _("背景图标")?></td>
		    <td align="left" class="bg">
			<div id="picLoginView"></div>
			<input type="hidden" name="picLogin" id="picLogin" value="<?=$rs["picLogin"]?>">
		  <iframe width="300" height="24" src="inc/picUpLoad.php?upFileFoler=images&upFileID=picLogin&viewID=picLoginView" scrolling="no" frameborder="0"></iframe>			</td>
		    </tr>
		 
			  <tr>
		    <td align="left" class="bg"><? echo _("网址")?></td>
		    <td align="left" class="bg">
			<div id="WEB"></div>
			<textarea  name="WEB" id="WEB"   rows="5" cols=50 ><?=$rs["WEB"]?> </textarea>
		  	</td>
		    </tr>
			 <tr>
		    <td align="left" class="bg"><? echo _("名称")?></td>
		    <td align="left" class="bg">
			<div id="WEB"></div>
			<input type="text" name="Name" id="Name" value="<?=$rs["Name"]?>" > 
		  	</td>
		    </tr>
			  <tr>
		    <td align="left" class="bg"><? echo _("版权")?></td>
		    <td align="left" class="bg">
			<div id="copyrightLog"></div>
			<textarea  name="copyrightLog" id="copyrightLog"   rows="5" cols=50 ><?=$rs["copyrightLog"]?></textarea>
		  	</td>
		    </tr>
			
			<!-- 管理系统版本-->
			  <tr>
		    <td height="30" colspan="2" align="left" class="bg"><? echo _("管理系统版本，请不要随意更改")?> </td>
		    </tr>
		   <tr>
		    <td align="left" class="bg"><? echo _("版权声明")?></td>
		    <td align="left" class="bg">
			<div id="CRStatement"></div>
			<textarea  name="CRStatement" id="CRStatement"   rows="15" cols=50 ><?=$rs["CRStatement"]?></textarea>
		  	</td>
		    </tr>
			  <tr>
		    <td align="left" class="bg"><? echo _("联系方式")?></td>
		    <td align="left" class="bg">
			<div id="Contact"></div>
			<textarea  name="Contact" id="Contact"   rows="5" cols=50 ><?=$rs["Contact"]?></textarea>
		  	</td>
		    </tr>
			
			
			
		  <tr>
		    <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="<? echo _("保存")?>"></td>
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        系统设置-> <strong>界面配置</strong>
      </p>
      <ul>
          <li>可以对本计费系统的界面LOGO 进行配置，以便更符合运营需求。</li>
          <li>系统标题：浏览器页面左上角显示的标题名称。</li>
          <li>版本信息：浏览器下方显示的版本信息。</li>
          <li>上、下、左、右的图标，分别为页面四个角的图标，点击浏览、上传后，再点击保存，即刻生效。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

