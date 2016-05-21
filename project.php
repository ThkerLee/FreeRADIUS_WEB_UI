#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("无标题文档")?></title>
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
            WindowTitle:          '<b>项目管理</b>',
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
$name=$_REQUEST["name"];
$manRs=$db->select_one("*","manager","manager_account='".$_SESSION['manager']."'"); 
$_SESSION["auth_area"] =empty($record["manager_area"])?"0":$record["manager_area"];
$_SESSION["auth_area"] =empty($manRs['manager_area'])?"0":$manRs['manager_area'];
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("项目管理")?></font></td>
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
	  <form action="?action=search" name="myform" method="post">
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
		  <tr>
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
			<td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="14%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="51%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"><? echo _("项目名称：")?></td>
		    <td><input type="text" name="name" value="<?=$name?>"></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input type="submit" value="<?php echo _("提交搜索")?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="PHPExcel/excel_project.php?<?="name=".$name?>" style="color:#FF3300;" >EXCEL导出</a></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
	    </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="50%" class="f-bulue1"><? echo _("项目管理")?></td>
		<td width="50%" align="right"><a href="project_add.php" class="f-b"><? echo _("添加项目")?></a></td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="7%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("项目名")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("所属区域")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("起始IP")?></th>
                <th width="18%" align="center" class="bg f-12"><? echo _("结束IP")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("Nas设备")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("MTU值")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("用户数")?></th>
                <th width="11%" align="center" class="bg f-12"><? echo _("安装费用")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("操作")?></th>
              </tr>
        </thead>	     
      <tbody>  
<?php 
$sql=" 0=0 ";
if($name){
	$sql .=" and name like '%".mysql_real_escape_string($name)."%'";
}
$sql .=" and p.ID in (".$_SESSION["auth_project"] .") and ap.areaID in(".$_SESSION["auth_area"].")";
$result=$db->select_all("distinct(p.ID),p.*","project as p, areaandproject as ap",$sql,20);
if(is_array($result)){
		foreach($result as $key=>$rs){
		$num=$db->select_count("userinfo","projectID='".$rs["ID"]."' and gradeID in (". $_SESSION["auth_gradeID"].")");
		$area=$db->select_one("a.name","area as a,areaandproject as ap","a.ID=ap.areaID and ap.projectID=".$rs["ID"]);
?>   
		  <tr>
		  <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><?=$rs['name'];?></td>
			<td align="center" class="bg"><?=$area['name'];?></td>
			<td align="center" class="bg"><?=$rs['beginip'];?></td>
			<td align="center" class="bg"><?=$rs['endip'];?></td>
			<td align="center" class="bg"><?=deviceShow($rs["device"]);?>&nbsp;</td>
			<td align="center" class="bg"><?=$rs["mtu"]?></td>
			<td align="center" class="bg"><?=$num?></td>
			<td align="center" class="bg"><?=$rs["installcharge"]?></td>
			<td align="center" class="bg">
			  <a  href="project_edit.php?ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" title="<? echo _("修改")?>" alt="<? echo _("修改")?>" /></a>
			  <a  href="project_del.php?ID=<?=$rs['ID'];?>"><img src="images/del.png" width="12" height="12" border="0"  title="<? echo _("删除")?>" alt="<? echo _("删除")?>"  /></a>			</td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="10" align="center" class="bg">
				<?php $db->page("name=".$name); ?>			</td>
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
        项目管理-> <strong>项目管理</strong>
      </p>
      <ul>
          <li>对所管理的项目进行查看、修改、删除等操作。</li>
          <li>在项目中如有用户存在则不能删除该项目。</li>
          <li>可以已EXECL方式导出。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

