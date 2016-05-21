#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css"> 
<script src="js/jsdate.js" type="text/javascript"></script>
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
            WindowTitle:          '<b>营帐管理</b>',
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
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("充值统计")?> </font></td>
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
	<input type="radio" value="once" name="credit" class="status"  id="mo" <?php if($_GET['action']=='onceShow' || empty($_GET['action'])){ echo "checked";}?> onClick="document.location='?action=onceShow'"> <? echo _("充值记录查询")?> 
	<input type="radio" value="sum" name="credit" class="status" id='project' <?php if($_GET['action']=='sumShow'){  echo "checked"; }?>  onClick="document.location='?action=sumShow'"><? echo _("充值统计查询")?>
	
<?php
   if($_REQUEST['action']=="sumShow"){
      $action        = trim($_REQUEST["action"]);
	  $account       = trim($_REQUEST["account"]);
	  $name          = trim($_REQUEST["name"]);
	  $moneyMin      = trim($_REQUEST['moneyMin']);
	  $moneyMax      = trim($_REQUEST["moneyMax"]); 
	  $querystring ="action=".$action."&name=".$name."&moneyMin=".$moneyMin."&moneyMax=".$moneyMax;  //所有的总共收费金额 体现 非单笔 
	  
   
   ?>
 <form action="?action=sumShow" name="myform" method="post">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
      <tr>
        <td width="12%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="88%" align="right" class="title_bg2"  colspan="3">&nbsp;</td>
      </tr> 
	  <tr>
	  	<td align="right"><? echo _("用户帐号:")?></td>
		<td><input type="text" name="account" value="<?=$account?>"></td> 
	  </tr> 
	  <tr> 
		<td align="right"><? echo _("用户姓名:")?></td>
		<td><input type="text" name="name" value="<?=$name?>"></td> 
	  </tr>
	  <tr> 
		<td align="right"><? echo _("最低金额:")?></td>
		<td><input type="text" name="moneyMin" value="<?=$moneyMin?>"></td>
	  </tr>
	   <tr> 
		<td align="right"><? echo _("最高金额:")?></td>
	    <td><input type="text" name="moneyMax" value="<?=$moneyMax?>" ></td> 
	  </tr>   
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<?php echo _("提交")?>"> </td>
	    </tr>
	  </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("充值统计查询")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="12%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="16%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="19%" align="center" class="bg f-12"><? echo _("用户姓名")?></th> 
                <th width="19%" align="center" class="bg f-12"><? echo _("充值金额")?></th> 
              </tr>
        </thead>	     
        <tbody>  
<?php  
 $sql=" u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";    
		 $creditSql  =" 0=0  group by userID";
		if($operator) 
		    $creditSql .=" and operator = '".$operator."'"; 
		if($moneyMax && $moneyMin){
		    $creditSql .=" having totalMoney <= '".mysql_real_escape_string($moneyMax)."' and  totalMoney >= '".mysql_real_escape_string($moneyMin)."'";   
		}else{
		  if($moneyMax)  
		     $creditSql .=" having totalMoney <= '".mysql_real_escape_string($moneyMax)."'";  
		  if($moneyMin) 
		     $creditSql .=" having totalMoney >= '".mysql_real_escape_string($moneyMin)."'"; 
		}   
		
	       $creditSumMoney = $db->select_all(" sum(money) as totalMoney,userID","credit",$creditSql,20);   
		    
		   if(is_array($creditSumMoney)){ 
		      foreach($creditSumMoney as $caredVal){  
			    $sql=" u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") and ID='".$caredVal['userID']."'";   
				if($account) 
				   $sql .=" and  account = '".mysql_real_escape_string($account)."'";
				if($name)
				   $sql .=" and  name ='".mysql_real_escape_string($name)."'";
			    $result=$db->select_one("u.* ","userinfo as u",$sql);
				if( $result){  
				 ?>
				    <tr>
						<td align="center" class="bg"><?=$result['ID']?>&nbsp;</td><!-- 用户ID-->
						<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$result["UserName"]?>','loadusershow')"><?=$result['UserName'];?></a></td> 
						<td align="center" class="bg"><?=$result['name']?>&nbsp;</td>
						<td align="center" class="bg"><?=$caredVal['totalMoney']?>&nbsp;</td> 
					 </tr>  
				 <?php  
			}
   } //end foreach 
  }//end if is_array ?>

		  <tr>
		    <td colspan="6" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
          </tr>
        </tbody>      
    </table>
   <?php   
   }//end action = sumShow  充值统计查询
   elseif($_REQUEST["action"]=="onceShow" || empty($_REQUEST['action'])){ 
	  $action        = trim($_REQUEST["action"]);
	  $account       = trim($_REQUEST["account"]);
	  $name          = trim($_REQUEST["name"]); 
	  $moneyMin      = trim($_REQUEST['moneyMin']);
	  $moneyMax      = trim($_REQUEST["moneyMax"]); 
	  $startDateTime = trim($_REQUEST["startDateTime"]);
	  $endDateTime   = trim($_REQUEST["endDateTime"]);
	  $operator      = $_REQUEST['operator']; 
	  $querystring ="action=".$action."&account=".$account."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&moneyMin=".$moneyMin."&moneyMax=".$moneyMax."&operator=".$operator; 
	  $sql="c.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
	if($account){
		$sql .=" and u.account='".mysql_real_escape_string($account)."'";
	}
	if($name)
		$sql .=" and u.name='".mysql_real_escape_string($name)."'";
	if($moneyMin)
		$sql .=" and c.money >='".mysql_real_escape_string($moneyMin)."'";
	if($moneyMax)
		$sql .=" and c.money <='".mysql_real_escape_string($moneyMax)."'";
	if($startDateTime){
		$sql .=" and c.adddatetime>='".$startDateTime."'";
	}
	if($endDateTime){
		$sql .=" and c.adddatetime<'".$endDateTime."'";
	}
	if($operator){
		$sql .=" and c.operator='".$operator."'";
	}
	$result=$db->select_one("count(*) as sum","credit as c,userinfo as u",$sql);  
   ?>
  <form action="?action=onceShow" name="myform" method="post">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd">
      <tr>
        <td width="12%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="88%" align="right" class="title_bg2"  colspan="3">&nbsp;</td>
      </tr> 
	  <tr>
	  	<td align="right"><? echo _("用户帐号:")?></td>
		<td><input type="text" name="account" value="<?=$account?>"></td> 
		<td align="right"><? echo _("用户姓名:")?></td>
		<td><input type="text" name="name" value="<?=$name?>"></td> 
	  </tr>  
	  <tr> 
		<td align="right"><? echo _("最低金额:")?></td>
		<td><input type="text" name="moneyMin" value="<?=$moneyMin?>"></td> 
		<td align="right"><? echo _("最高金额:")?></td>
	    <td><input type="text" name="moneyMax" value="<?=$moneyMax?>" ></td> 
	  </tr> 
	   <tr>
	    <td align="right"><? echo _("开始时间:")?></td>
		<td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)"></td> 
		<td align="right"><? echo _("结束时间:")?></td>
	    <td><input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>  
	  </tr>   
	  <tr>
	    <td align="right"><? echo _("收费人员:")?></td>
	    <td><?php managerSelect($_POST["operator"]) ?></td> 
	  </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<?php echo _("提交")?>">
		<?php 
		  if($result['sum']>0){
		  ?>
		    &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
			&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
			<a href="PHPExcel/excel_rechangelog.php?<?=$querystring?>" style="color:#FF3300;" ><? echo _("EXCEL导出")?></a> 
		  <?php 
		  } 
		?>
		 
		
		</td>
	    </tr>
	  </table>
	</form>
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("充值记录")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="12%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("用户姓名")?></th>
                <th width="15%" align="center" class="bg f-12"><? echo _("缴费类型")?></th>
                <th width="19%" align="center" class="bg f-12"><? echo _("充值金额")?></th>
                <th width="12%" align="center" class="bg f-12"><? echo _("收费人员")?></th>
                <th width="22%" align="center" class="bg f-12"><? echo _("收费时间")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
 
$result=$db->select_all("c.*,u.*,c.ID as creditID,c.money as creditMoney,c.adddatetime as rechargetime","credit as c,userinfo as u",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
		if($rs["type"]==0){
			$type=_("开户预存");
		}else if($rs["type"]==1){
			$type=_("前台续费");
		}else if($rs["type"]==2){
			$type=_("卡片充值");
		}
		
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['creditID'];?>&nbsp;</td>
			<td align="center" class="bg"><a href="#" OnClick="download(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a>&nbsp;</td>
			  <td align="center" class="bg"><?=$rs['name'];?>&nbsp;</td>
			<td align="center" class="bg"><?=$type?>&nbsp;</td>
			<td align="center" class="bg"><?=$rs["creditMoney"]?>&nbsp;</td>
			<td align="center" class="bg"><?=$rs["operator"]?>&nbsp;</td>
			<td align="center" class="bg"><?=$rs["rechargetime"]?>&nbsp;</td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="7" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
          </tr>
        </tbody>      
    </table>	
   <?php 
   }//end action =onceShow  充值记录查询
 
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
    <!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        营帐管理-> <strong>充值记录</strong>
      </p>
      <ul>
          <li>查看用户充值的记录和充值统计。</li>
          <li>可做为查询审计功能使用。</li>
      </ul>

    </div>
<!---------------------------------------------->
<?php 
include("inc/loaduser.php");
?>
</body>
</html>

