#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<style type="text/css">
<!--
.STYLE1 {
	color: #0000FF;
	font-weight: bold;
}
.STYLE2 {
	color: #FF6600;
	font-weight: bold;
}
-->
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("无标题文档"); ?></title>
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
<script src="js/ajax.js" type="text/javascript"></script>
<body>
<?php 
$ID		  = $_REQUEST["ID"];
$name     = $_REQUEST["name"];
$action   = $_REQUEST["action"];
$nasip    = $_REQUEST["nasip"];
$projectID= $_REQUEST["projectID"];
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];

if($action=="addsave"){
	$sql=array(
		"projectID"=>$projectID,
		"nasip"    =>$nasip,
		"username" =>$username,
		"password"=>$password
	);
	$db->insert_new("project_ros",$sql);
}else if($action=="editsave") {
	$sql=array(
		"projectID"=>$projectID,
		"nasip"    =>$nasip,
		"username" =>$username,
		"password"=>$password
	);
	$db->update_new("project_ros","ID='$ID'",$sql);
}else if($action=="del"){
	$db->delete_new("project_ros","ID='$ID'");
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("项目管理"); ?></font></td>
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
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="76%" class="f-bulue1"><? echo _("NAS同步") ;?></td>
		<td width="13%" align="center" class="f-bulue1">&nbsp;
		<span class="STYLE1" > <a href="javascript:ajaxInput('ros_write.php','rosid','rosid','rosid')"><? echo _("手动同步"); ?></a></span>
	        <input type="hidden" id="rosid">
		</td>
		<td width="11%" align="center"><a href="?action=add" class="f-b"><? echo _("添加") ; ?></a>		 </td>
      </tr>
	  </table>
	  <?php if($action=="add"){
	  ?>
	  <form action="?action=addsave" name="myform" method="post">
	  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
		  <tr>
			<th width="12%" align="right" class="bg f-12"><? echo _("所属项目"); ?></th>
			<th width="88%" align="left" class="bg f-12">
				<?php 
					$projectResult=$db->select_all("*","project","");
					echo "<select name='projectID'>";
					if(is_array($projectResult)){
						foreach($projectResult as $key=>$projectRs){
							if($projectRs["ID"]==$ID){
								echo "<option value=".$projectRs["ID"]." selected>".$projectRs["name"]."</option>";
							}else{
								echo "<option value=".$projectRs["ID"].">".$projectRs["name"]."</option>";
							}
						}
					}
					echo "</select>";			
				?>	
			</th>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("NAS IP");?> </td>
		    <td align="left" class="bg">
				<input type="text" name="nasip">
			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("用户"); ?></td>
		    <td align="left" class="bg">
				<input type="text" name="username">
			</td>
	      </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("密码"); ?></td>
		    <td align="left" class="bg">
			<input type="text" name="password">
			</td>
	      </tr>
		  <tr>
		    <td align="center" class="bg">&nbsp;</td>
			<td align="left" class="bg"><input type="submit" value="<? echo _("提交"); ?>"></td>     
    	</table>
	  </form>
	  <?php }else if($action=="edit"){ 
	  	$rs=$db->select_one("pr.*,obj.name as project_name","project_ros as pr,project as obj","pr.projectID=obj.ID and pr.ID='$ID'");
	  ?>
	  <form action="?action=editsave" name="myform" method="post">
	 	<input type="hidden" name="ID" value="<?=$ID?>">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
				  <tr>
					<th width="12%" align="right" class="bg f-12"><? echo _("所属项目"); ?></th>
					<th width="88%" align="left" class="bg f-12">
						<?php 
							$projectResult=$db->select_all("*","project","");
							echo "<select name='projectID'>";
							if(is_array($projectResult)){
								foreach($projectResult as $key=>$projectRs){
									if($projectRs["ID"]==$rs["projectID"]){
										echo "<option value=".$projectRs["ID"]." selected>".$projectRs["name"]."</option>";
									}else{
										echo "<option value=".$projectRs["ID"].">".$projectRs["name"]."</option>";
									}
								}
							}
							echo "</select>";			
						?>	
					</th>
				  </tr>
				  <tr>
					<td align="right" class="bg"><? echo _("NAS IP"); ?> </td>
					<td align="left" class="bg">
						<input type="text" name="nasip" value="<?=$rs["nasip"]?>">
					</td>
				  </tr>
				  <tr>
					<td align="right" class="bg"><? echo _("用户"); ?></td>
					<td align="left" class="bg">
						<input type="text" name="username" value="<?=$rs["username"]?>">
					</td>
				  </tr>
				  <tr>
					<td align="right" class="bg"><? echo _("密码"); ?></td>
					<td align="left" class="bg">
					<input type="text" name="password" value="<?=$rs["password"]?>">
					</td>
				  </tr>
				  <tr>
					<td align="center" class="bg">&nbsp;</td>
					<td align="left" class="bg"><input type="submit" value="<? echo _("提交"); ?>"></td>     
		</table>
	  </form>
	  
	  <? }else{?>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="7%" align="center" class="bg f-12"><? echo _("编号"); ?></th>
                <th width="20%" align="center" class="bg f-12"><? echo _("项目名"); ?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("NAS IP"); ?></th>
                <th width="18%" align="center" class="bg f-12"><? echo _("用户"); ?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("密码"); ?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("操作"); ?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql=" pr.projectID=obj.ID";
if($name){
	$sql .=" and name like '%".$name."%'";
}
$result=$db->select_all("pr.*,obj.name as project_name","project_ros as pr,project as obj",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
		$num=$db->select_count("","projectID='".$rs["ID"]."' and gradeID in (". $_SESSION["auth_gradeID"].")");
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><?=$rs['project_name'];?></td>
			<td align="center" class="bg"><?=$rs['nasip'];?></td>
			<td align="center" class="bg"><?=$rs['username'];?></td>
			<td align="center" class="bg"><?=$rs['password']?>&nbsp;</td>
			<td align="center" class="bg">
			  <a  href="?action=edit&ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" title="<? echo _("修改")?>" alt="<? echo _("修改")?>" /></a>
			  <a  href="?action=del&ID=<?=$rs['ID'];?>"><img src="images/del.png" width="12" height="12" border="0"  title="<? echo _("删除")?>" alt="<? echo _("删除")?>"  /></a>			</td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="6" align="center" class="bg">
				<?php $db->page("name=".$name); ?>			</td>
          </tr>
        </tbody>      
    </table>	
	
	  <?php 
	  }
	  ?>
	
	</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
  
</table>
     <div id="Window1" style="display:none;">
      <p>
        项目管理-> <strong>NAS同步</strong>
      </p>
      <ul>
          <li>把计费中的相应项目用户帐号数据信息同步到NAS 设备上。</li>
          <li>在本设置中可以添加你要同步的项目，点击右上角添加。</li>
          <li>所属项目：选择你要同步的项目名称。</li>
          <li>NAS IP ：输入你需要同步到的网关设备地址。</li>
          <li>用 户：填入 nas 设备登录管理界面的用户名。</li>
          <li>密 码：填入 nas 设备登录管理界面的密码。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

