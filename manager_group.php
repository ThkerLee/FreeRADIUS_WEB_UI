#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
 ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("系统管理组")?></title>
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
 $db->delete_new("managergroup","group_name=''");
if($_POST){
	$group_name  =$_POST["group_name"];
	$permision   =$_POST["permision"];
	if(is_array($permision)){
		foreach($permision as $value){
			$str .=$value."#";
		}
	}	
	$sql=array(
		"group_name"=>$group_name,
		"group_permision"=>$str
	);
	$db->insert_new("managergroup",$sql);
	echo "<script>alert('success');window.location.href='manager_group.php';</script>";
	
}

//查询项目集合
?>
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
   		</table>	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="88%" class="f-bulue1"><? echo _("系统角色")?></td>
		<td width="12%" align="center"><a href="manager_group_add.php"><? echo _("增加")?></a></td>
      </tr>
	  </table>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
		<thead>
		<tr>
		<th width="88%" align="center" class="bg f-12"><? echo _("组名")?></th>
		<th width="12%" align="center" class="bg f-12"><? echo _("操作")?></th>
		</tr>
		</thead>
		<tbody>
		<?php 
		$result=$db->select_all("*","managergroup","",20);
		if($result){
		foreach($result as $key=>$rs){
		?>
		<tr>
		<td width="88%" align="center" class="bg"><?=$rs["group_name"]?></td>
		<td colspan="2" align="center" class="bg">    
		<a  href="manager_group_edit.php?ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
		<a  href="manager_group_del.php?ID=<?=$rs['ID'];?>"><img src="images/del.png" width="12" height="12" border="0" /></a>	  
		</tr>
		<?php 
		}//end foreach
		}//end if
		?>
		<tr>
		<td align="right" class="bg">&nbsp;</td>
		<td width="12%" align="left" class="bg">&nbsp;</td>
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
        系统设置-> <strong>用户角色</strong>
      </p>
      <ul>
          <li>系统中可以预先定义好管理员的用户角色，做成不同的用户组权限。</li>
          <li>在建立管理员时，不用再详细分配权限。</li>
          <li>点击右上角“增加”，可添加管理组。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

