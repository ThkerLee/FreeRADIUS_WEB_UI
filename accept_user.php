#!/bin/php
<?php include("inc/conn.php"); 
include_once("evn.php");  ?>
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
	$accept_name           = $_REQUEST["accept_name"];

$querystring="accept_name=".$accept_name;
 
 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">系统设置</font></td>
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
	<form action="?" name="myform" method="post">
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
		  <tr>
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
			<td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="9%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="56%" align="right" class="title_bg2"><a href="accept_user_add.php">增加</a></td>
		  </tr>
		  <tr>
		   
		    <td align="right">受理人员：</td>
		    <td>
                   <?php acceptSelect($accept_name); ?> 
		</td> 
                <td align="right">&nbsp;</td>
		    <td >&nbsp;</td>
	      </tr>
		  
		  <tr>
			<td align="right">&nbsp;</td>
			<td ><input name="submit" type="submit" value="<?php echo _("提交搜索")?>"></td>
			<td align="right">&nbsp;</td>
			<td style="color:#FF3300;">&nbsp;</td>
		  </tr>
	    </table>
	</form>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
			<thead>
				  <tr>
				        <th width="10%" align="center" class="bg f-12">编号</th>
					<th width="20%" align="center" class="bg f-12">姓名</th>
					<th width="20%" align="center" class="bg f-12">手机号</th>
					<th width="20%" align="center" class="bg f-12">家庭电话</th> 
					<th width="20%" align="center" class="bg f-12">地址</th>
					<th width="10%" align="center" class="bg f-12">操作</th>
				  </tr>
			</thead>
			<tbody>
<?php

if($accept_name){
	$sql1 .="  accept_name like '%".$accept_name."%'";
} 

 
?>			
			
			
			
			
		<?php 
		$result=$db->select_all("*","accept",$sql1,20);
		if($result){
			foreach($result as $key=>$rs){

		?>
			  <tr>
				
                                         <td width="10%" align="center" class="bg"><?=$rs['ID'];?></td>
					<td width="20%" align="center" class="bg"><?=$rs['accept_name'];?></td>
					<td width="20%" align="center" class="bg"><?=$rs['accept_phone'];?></td>
					<td width="20%" align="center" class="bg"><?=$rs['manager_mobile'];?></td>
					<td width="20%" align="center" class="bg "><?=$rs['accept_address'];?></td>
				<td  align="center" class="bg">    
						  <a  href="accept_user_edit.php?ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
				  <a  href="javascript:if(confirm('确认要删除吗?'))location='accept_user_del.php?ID=<?=$rs['ID'];?>'"><img src="images/del.png" width="12" height="12" border="0" /></a>     
                          </tr>
		<?php 
			}//end foreach
		}//end if
		?>
			</tbody>
  </table>
  <table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
		<tr>
		    <td align="center" class="bg">
				<?php $db->page($querystring); ?>			
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
        系统设置-> <strong>受理人员</strong>
      </p>
      <ul>
          <li>用户可以选择添加、修改、删除受理人员。</li>
          <li>添加管理员：点击右上角的《增加》按纽，可添加受理人员。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

