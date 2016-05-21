#!/bin/php
<?php 
@include("inc/conn.php");
@require_once("evn.php"); ?>
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
if($_GET["action"]=="del"){
	$db->delete_new("speedrule","ID='".$_GET["ID"]."'");
	echo "<script>alert('" . _("删除成功") ." ');window.location.href='speedrule.php';</script>";
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
        <td width="89%" class="f-bulue1"> <? echo _("用户添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
          

        <tbody>
			<tr>
            <td width="6%" align="center" bgcolor="#FFFFFF" class="f-b"><? echo _("项目")?></td>
            <td width="15%" align="center" bgcolor="#FFFFFF" class="f-b"><? echo _("目标IP")?></td>
            <td width="11%" align="center" bgcolor="#FFFFFF" class="f-b"><? echo _("上传带宽")?></td>
            <td width="7%" align="center" bgcolor="#FFFFFF" class="f-b"><? echo _("下载带宽")?></td>
            <td width="6%" align="center" bgcolor="#FFFFFF" class="f-b"><? echo _("操作")?></td>
          </tr>
<?php
$result=$db->select_all("*","speedrule","");
if(!empty($result)){
	foreach($result as $key=>$row){								
		$srcip = $row["srcip"];	
		if($srcip==""){$srcip=_("任意");}
		$dstip = $row["dstip"];		
		if($dstip==""){$dstip=_("任意");}
		$srcport = $row["srcport"];	
		if($srcport==0){$srcport=_("任意");}
		$dstport = $row["dstport"];		
		if($dstport==0){$dstport=_("任意");}
		$upload = $row["upload"];	
		$download = $row["download"];		
		$GroupName = $row["GroupName"];	
?>
          <tr>
            <td align="center" bgcolor="#FFFFFF" class="border_b"><?=projectShow($row['projectID'])?></td>
            <td align="center" bgcolor="#FFFFFF" class="border_b border_l"><?=$dstip;?></td>
            <td align="center" bgcolor="#FFFFFF" class="border_b border_l"><?=$upload;?></td>
            <td align="center" bgcolor="#FFFFFF" class="border_b border_l"><?=$download;?></td>
            <td align="center" bgcolor="#FFFFFF" class="border_b border_l">
<a href="speedrule_edit.php?ID=<?=$row["ID"]?>"><img src="images/edit.png" alt="<? echo _("编辑")?>" width="16" height="16" border="0" title= "<? echo _("编辑")?>"></a>  
<a href="speedrule_del.php?action=del&ID=<?=$row["ID"]?>"><img src="images/del.png"  alt="<? echo _("删除")?>" border="0" title="<? echo _("删除")?>"</a></td>
          </tr>
<?php
	}//end foreach
} 
?>
        </tbody>
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
        产品管理-> <strong>规则管理</strong>
      </p>
      <ul>
          <li>对内网限速规则进行管理，可以进行的操作有修改和删除。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

