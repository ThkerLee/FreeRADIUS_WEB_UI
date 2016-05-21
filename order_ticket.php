#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?> 
<html>
<head><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("产品管理")?></title>
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
if($_GET["action"]=="editsave"){
	$sql=array(
		"type"=>$_POST["type"],
		"name"=>$_POST["name"],
		"tel" =>$_POST["tel"],
		"mark"=>$_POST["mark"],
		"tbwidth"=>$_POST["tbwidth"],
		"tbheight"=>$_POST["tbheight"],
		"lineheight"=>$_POST["lineheight"],
		"fontsize"=>$_POST["fontsize"],
		"tfontsize"=>$_POST["tfontsize"],
		"tbmarginbottom"=>$_POST["tbmarginbottom"]
		
	);
	$result= $db->select_count("ticket","");
	
	
	if($result){
	  $db->update_new("ticket","",$sql);
	}else{
	  $db->insert_new("ticket",$sql);
	}
	
	
	echo "<script language='javascript'>alert('"._("保存成功")."');</script>";
}

$rs=$db->select_one("*","ticket","0=0 limit 0,1");
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
          <td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("订单管理")?></font></td>
            <td width="3%" height="35">
               <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
           </td> <!------帮助--2014.06.07----->         
        </tr>
      </table></td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
        <tr>
          <td width="89%" class="f-bulue1"> <? echo _("票据设置")?></td>
          <td width="11%" align="right">&nbsp;</td>
        </tr>
      </table>
      <form action="?action=editsave" name="myform" method="post">
        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>    
              <tr>
                <td align="right" class="bg"><? echo _("标题类型:")?></td>
                <td align="left" class="bg">
					<input type="radio" name="type" value="auto" <?php if($rs["type"]=="auto") echo "checked"; ?>><? echo _("自动提取项目名")?> 
					<input type="radio" name="type" value="hand" <?php if($rs["type"]=="hand") echo "checked"; ?>><? echo _("手动输入标题")?>				</td>
              </tr>
              <tr id="print_title">
                <td align="right" class="bg"><? echo _("打印标题：")?></td>
                <td align="left" class="bg"><input type="text" name="name" value="<?=$rs["name"]?>"> 
                <? echo _("当选择提取项目名时，打印标题为(项目名+所输入的) ，选择手动输入时，订单打印标题为你所输入的文字")?> </td>
              </tr>
              <tr>
                <td align="right" class="bg"><? echo _("联系电话:")?></td>
                <td align="left" class="bg"><input type="text" name="tel" value="<?=$rs["tel"]?>"></td>
              </tr>
              <tr>
                <td align="right" class="bg"><? echo _("表单尺寸:")?></td>
                <td align="left" class="bg">
				<? echo _("宽");?>：<input name="tbwidth" type="text" value="<?=$rs["tbwidth"]?>" size="5">mm
                <? echo _("高");?>：<input name="tbheight" type="text" value="<?=$rs["tbheight"]?>" size="5">mm				</td>
              </tr>
              <tr>
                <td align="right" class="bg"><? echo _("字体大小:")?></td>
                <td align="left" class="bg"><input name="fontsize" type="text" value="<?=$rs["fontsize"]?>" size="5"> 
                px</td>
              </tr>
              <tr>
                <td align="right" class="bg"><? echo _("表格行高:")?></td>
                <td align="left" class="bg"><input name="lineheight" type="text" value="<?=$rs["lineheight"]?>" size="5"> 
                  <? echo _("建议为");?>20 </td>
              </tr>
			   <tr>
                <td align="right" class="bg"><? echo _("表单间距:")?></td>
                <td align="left" class="bg"><input name="tbmarginbottom" type="text" value="<?=$rs["tbmarginbottom"]?>" size="5">px</td>
              </tr>
              <tr>
                <td align="right" class="bg"><? echo _("标题大小:")?></td>
                <td align="left" class="bg"><input name="tfontsize" type="text" value="<?=$rs["tfontsize"]?>" size="5"> 
                px </td>
              </tr>
              <tr>
                <td width="22%" align="right" class="bg"><? echo _("备注:")?></td>
                <td width="78%" align="left" class="bg"><textarea name="mark" cols="60" rows="5"><?=$rs["mark"]?></textarea></td>
              </tr>
              <tr>
                <td align="right" class="bg">&nbsp;</td>
                <td align="left" class="bg"><input type="submit" value="<? echo _("保存")?>"></td>
              </tr>
              <tr>
                <td align="center" class="bg">&nbsp;</td>
                <td align="center" class="bg">&nbsp;</td>
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
        系统设置-> <strong>票据设置</strong>
      </p>
      <ul>
          <li>可以设置开户及续费充值通知打印单的票据格式。</li>
          <li>标题类型：分为自动提取项目名和手动输入标题两种，当选择自动提取项目名时，打印标题为(项目名+所输入的) ，如项目名为“蓝天小区”手动输入的标题为“开户通知单” ，则打印标题为： 蓝天小区开户通知单，选择手动输入时，订单打印标题为你所输入的文字如输入“XX 宽带开户受理业务单”则标题也显示同样内容。</li>
          <li>打印标题：输入需打印的标题的内容。</li>
          <li>其他内容，可以根据需要自行调整设置。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>
