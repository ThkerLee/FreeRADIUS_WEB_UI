#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
            WindowTitle:          '<b>运营管理</b>',
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
$UserName      =$_REQUEST["UserName"]; 
$action        =$_REQUEST['action']; 
if($_GET["action"]=="clean"){
$db->query("truncate table radpostout");
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("运营管理")?></font></td>
                        <td width="3%" height="35"><div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div></td> <!------帮助--2014.06.07----->
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	  <form action="?action=search" name="myform" method="post">
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
		  <tr>
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
			  <td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="14%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="51%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"><? echo _("用户帐号：")?></td>
		    <td><input type="text" name="UserName" value="<?=$UserName?>"></td>
		    <td>&nbsp</td>
		    <td>&nbsp</td>
		  </tr>  
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input type="submit" value="<?php echo _("提交搜索")?>"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
	    </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("错误记录")?></td>
		<td width="11%" align="center"><a href="?action=clean"><font color="#CC0066">清空列表</font></a></td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="7%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="20%" align="center" class="bg f-12"><? echo _("用户帐号")?></th> 
                <th width="20%" align="center" class="bg f-12"><? echo _("用户姓名")?></th> 
                <th width="20%" align="center" class="bg f-12"><? echo _("所属项目")?></th> 
                <th width="20%" align="center" class="bg f-12"><? echo _("失败原因")?></th> 
                <th width="13%" align="center" class="bg f-12"><? echo _("拨号时间")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$querystring="UserName=".$UserName."";
$sql=" r.username=u.UserName and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($UserName) $sql .=" and r.username like '%".mysql_real_escape_string($UserName)."%'"; 
$sql .=" order by r.time desc"; 
$result=$db->select_all("r.*,u.name,u.projectID","radpostout as r,userinfo as u",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){  
?>   
		  <tr>
		  <td align="center" class="bg"><?=$rs['id'];?></td>
			<td align="center" class="bg"><?=$rs['username'];?></td>
			<td align="center" class="bg"><?=$rs['name'];?></td> 
			<td align="center" class="bg"><?=projectShow($rs["projectID"]);?></td> 
			<td align="center" class="bg"><?=$rs["content"]?></td>  
			<td align="center" class="bg"><?=$rs["time"]?></td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="9" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
	      </tr>
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
        运营管理-> <strong>错误记录</strong>
      </p>
      <ul>
          <li>此页面是查看用户拨号认证失败原因。</li>
      </ul>

    </div>
<!---------------------------------------------->
    
</body>
</html>

