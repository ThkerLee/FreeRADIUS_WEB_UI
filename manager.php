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
 
	$group_name        = $_REQUEST["group_name"];
	$permision         = $_REQUEST["permision"];
	$account           = $_REQUEST["account"];
	$manager_groupID   = $_REQUEST["manager_groupID"];
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
 
$querystring="manager_account=".$account."&manager_groupID=".$manager_groupID;
 
 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("系统权限")?></font></td>
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
		    <td width="56%" align="right" class="title_bg2"><a href="manager_add.php">
                        <?php
                        $file = popen("license -T","r");
                        $data = fgets($file);//获取授权 $data=3为基础版不给增加管理员
                        pclose($file);
                       //$data=3;
                        if($data == 3){
                            echo"";
                        }  else {
                            echo _("增加");
                        }
                    ?>
                        
                        </a></td>
		  </tr>
		  <tr>
		   
		    <td align="right"><? echo _("所属组:")?></td>
		    <td>
				<?php 
				$group=$db->select_all("*","managergroup","group_name!=''");
				
				echo "<select name='manager_groupID'>";
				echo "<option value=''>"._("所属组")."</option>";
				if(is_array($group)){
					foreach($group as $gKey=>$gRs){
						 if($gRs["ID"]==$manager_groupID){
							echo "<option value=".$gRs["ID"]." selected>".$gRs["group_name"]."</option>";
						}else{
							echo "<option value=".$gRs["ID"].">".$gRs["group_name"]."</option>";
						}
						 
					}
				} 
				echo "</select>";
		?>		</td> 
		    <td align="right"><? echo _("系统账号:")?></td>
		    <td><input type="text" name="account" value="<?=$account?>"></td>
	      </tr>
		  
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input name="submit" type="submit" value="<? echo _("提交搜索")?>"></td>
			<td align="right">&nbsp;</td>
			<td style="color:#FF3300;"><? echo _("系统用户备份/恢复：数据备份选择备份单张表manager数据备份/恢复即可")?></td>
		  </tr>
	    </table>
	</form>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
			<thead>
				  <tr>
				    <th width="6%" align="center" class="bg f-12"><? echo _("系统帐号")?></th>
					<th width="10%" align="center" class="bg f-12"><? echo _("所属组")?></th>
					<th width="10%" align="center" class="bg f-12"><? echo _("真实姓名")?></th>
					<th width="10%" align="center" class="bg f-12"><? echo _("手机号码")?></th> 
					<th width="10%" align="center" class="bg f-12"><? echo _("家庭电话")?></th>
					<th width="12%" align="center" class="bg f-12"><? echo _("允许收费金额")?></th> 
					<th width="12%" align="center" class="bg f-12"><? echo _("实际收费金额")?></th> 
					<th width="12%" align="center" class="bg f-12"><? echo _("允许开户人数")?></th> 
					<th width="12%" align="center" class="bg f-12"><? echo _("已开户人数")?></th> 
					<th width="6%" align="center" class="bg f-12"><? echo _("操作")?></th>
				  </tr>
			</thead>
			<tbody>
<?php

if($account){
	$sql1 .=" and m.manager_account like '%".mysql_real_escape_string($account)."%'";
} 
  if($manager_groupID){
	$sql1 .=" and m.manager_groupID like '%".$manager_groupID."%'";
}
 
?>			
			
			
			
			
		<?php 
		$result=$db->select_all("m.*,g.group_name","manager as m,managergroup as g","m.manager_groupID=g.ID".$sql1,20);
		if($result){
			foreach($result as $key=>$rs){
			$totalInMoney = $db->select_one("sum(money) as in_all_money","credit","operator ='".$rs['manager_account']."'"); //用户收入金额 包括，开户，续费，充值，卡片充值，用户移机===
			$totalOutMoney = $db->select_one("sum(factmoney) as out_all_money","orderrefund","operator ='".$rs['manager_account']."' and type in(1,2,3)"); // 1 销户 2冲账 3恢复暂停
			$totalMoney = $totalInMoney['in_all_money'] - $totalOutMoney['out_all_money']; 
		?>
			  <tr>
				
				<td width="6%" align="center" class="bg"><?=$rs["manager_account"]?></td>
				<td width="10%" align="center" class="bg"><?=$rs["group_name"]?></td>
				<td width="10%" align="center" class="bg"><?=$rs["manager_name"]?></td>
				<td width="10%" align="center" class="bg"><?=$rs["manager_phone"]?></td>
				<td width="10%" align="center" class="bg"><?=$rs["manager_mobile"]?></td>
				<td width="12%" align="center" class="bg"><?=$rs["manager_totalmoney"]?></td>
				<td width="12%" align="center" class="bg"><?=$totalMoney ?></td>  
				<td width="12%" align="center" class="bg"><?=$rs["addusertotalnum"]?></td>
				<td width="12%" align="center" class="bg"><?=$rs["addusernum"]?></td> 
				<td colspan="6" align="center" class="bg">    
						  <a  href="manager_edit.php?ID=<?=$rs['ID'];?>"><img src="images/edit.png" width="12" height="12" border="0" /></a>
				  <a  href="javascript:if(confirm('确认要删除吗?'))location='manager_del.php?ID=<?=$rs['ID'];?>'"><img src="images/del.png" width="12" height="12" border="0" /></a>      </tr>
		<?php 
			}//end foreach
		}//end if
		?>
			<tr>
			  <td align="right" class="bg">&nbsp;</td>
				<td align="right" class="bg">&nbsp;</td>
				<td align="right" class="bg">&nbsp;</td>
				<td align="right" class="bg">&nbsp;</td>
				<td align="right" class="bg">&nbsp;</td>
				<td align="right" class="bg">&nbsp;</td>
				<td align="right" class="bg">&nbsp;</td> 
				<td align="right" class="bg">&nbsp;</td> 
				<td align="right" class="bg">&nbsp;</td>
				<td align="right" class="bg">&nbsp;</td> 
			  </tr>
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
        系统设置-> <strong>系统用户</strong>
      </p>
      <ul>
          <li>用户可以选择添加、修改、删除除系统管理员并可配置权限。</li>
          <li>添加管理员：点击右上角的《增加》按纽，可添加管理员。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

