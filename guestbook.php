#!/bin/php
<?php 
include("inc/conn.php");
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("留言管理")?></title>
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
            WindowTitle:          '<b>系统信息</b>',
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
if($_GET["action"]=="del"){
	$db->delete_new("guestbook","ID='".$_GET["ID"]."'");
}

?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("留言管理")?></font></td>
                        <td width="3%" height="35"><div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div></td> <!------帮助--2014.06.07----->
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
        <td width="93%" class="f-bulue1"><? echo _("留言管理")?></td>
		<td width="7%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="myTable">
        <thead>
              <tr>
                <th width="6%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="9%" align="left" class="bg f-12"><? echo _("标题")?></th>
                <th width="30%" align="left" class="bg f-12"><? echo _("内容")?></th>
                <th width="33%" align="left" class="bg f-12"><? echo _("回复")?></th>
                <th width="17%" align="left" class="bg f-12"><? echo _("时间")?></th>
                <th width="5%" align="center" class="bg f-12"><? echo _("操作")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql="userID='".$_SESSION["clientID"]."' order by ID desc";
$result=$db->select_all("*","guestbook",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
		$UserName =getUserName($userID);			
?>   
		  <tr>
		    <td align="center" class="bg"><?=$key+1;?></td>
			<td align="left" class="bg"><?=$rs['title'];?></td>
			<td align="left" class="bg"><?=$rs["content"]?></td>
			<td align="left" class="bg"><?=$rs["reply"]?></td>
			<td align="left" class="bg"><?=$rs['adddatetime'];?></td>
			<td align="center" class="bg">
			<a  href="guestbook_reply.php?ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" title="<? echo _("回复")?>" alt="<? echo _("回复")?>" /></a>
			  <a  href="?action=del&ID=<?=$rs['ID'];?>" title="<? echo _("删除")?>"><img src="images/del.png" width="12" height="12" border="0" /></a>			</td>
		  </tr>
<?php  }} ?>
        </tbody>      
    </table>
	<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
		<tr>
		    <td align="center" class="bg">
				<?php $db->page(); ?>			
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
        系统信息-> <strong>留言管理</strong>
      </p>
      <ul>
          <li>管理员可以通过此页面查看客户留言信息并回复。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

