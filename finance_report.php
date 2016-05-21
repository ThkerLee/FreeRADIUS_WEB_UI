#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("财务报表")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jsdate.js" type="text/javascript"></script> 
<script src="js/WdatePicker.js" type="text/javascript"></script> 
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
<?php   
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$operator		     =$_REQUEST["operator"];
$projectID		   =$_REQUEST["projectID"];
$type			       =$_REQUEST["type"];
$c_type          =$_REQUEST["c_type"];
$action          =empty($_REQUEST["action"])?"CIDDESC":$_REQUEST["action"];
$accept_name = $_REQUEST["accept_name"];//2014.09.25添加受理人员
$querystring="accept_name=".$accept_name."&operator=".$operator."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&projectID=".$projectID."&address=".$address."&type=".$type."&c_type=".$c_type."&action=".$action;
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle" align="left"><font color="#FFFFFF" size="2"><? echo _("营帐管理")?></font>    </td>
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
	<input type="radio" value="mo" name="credit" class="status"  id="mo" <?php if($_GET['action']=='moShow' || empty($_GET['action'])){ echo "checked";}?> onClick="document.location='?action=moShow'"> <?php echo _("默认财务信息")?> 
	<input type="radio" value="project" name="credit" class="status" id='project' <?php if($_GET['action']=='projectShow'){  echo "checked"; }?>  onClick="document.location='?action=projectShow'"><?php echo _("项目财务信息")?>

	
	<?php  /*
	        项目财务报表
	       */
if($_GET['action']=='projectShow'){ 
		$startDateTime   =$_REQUEST["startDateTime"];
		$endDateTime     =$_REQUEST["endDateTime"];
		$operator		 =$_REQUEST["operator"];
	?>	
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd sosoProject" >
      <tr>
        <td width="14%" class="f-bulue1 title_bg2" align="left"><? echo _("条件搜索")?></td> 
		<td width="86%" align="left " class="title_bg2" >&nbsp;</td> 
      </tr>
	  
	<form action="?action=projectShow" name="myform" method="post"  > 
	  <tr>
	    <td align="right"><? echo _("收费人员:")?></td>
	    <td><?php managerSelect($_POST["operator"]) ?></td>
	   
	  </tr>
	  <tr> 
	    <td align="right"><? echo _("开始时间：")?></td>
	    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"></td>
	    </tr>
	  <tr> 
	    <td align="right"><? echo _("结束时间")?></td>
	    <td><input type="text" name="endDateTime" value="<?=$endDateTime?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"></td>
	    </tr> 
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<?php echo _("提交"); ?>"></td>
	    <td align="right"><?php /*?><!-- <a href="PHPExcel/excel_credit.php?<?=$querystring?>"><? // echo _("EXCEL导出"); ?></a>-->&nbsp;<?php */?></td>
	    <td>&nbsp;</td>
	  </tr>
	  </table>
	</form>
	<?php 
	 $sql1=" c.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")"; 
			if($startDateTime){
				$sql1 .=" and c.adddatetime>='".$startDateTime."'";
			}
			if($endDateTime){
				$sql1 .=" and c.adddatetime<'".$endDateTime."'";
			}
			if($operator){
				$sql1 .=" and c.operator='".$operator."'";
			}
	$moneyRs =$db->select_one("sum(c.money) as in_all_money","credit as c,userinfo as u",$sql1);
	$moneyRs1=$db->select_one("sum(c.factmoney) as out_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(1,2,3,4)");
	$moneyClosibng=$db->select_one("sum(c.factmoney) as closibng_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(1)");//销户
	$moneyReverse=$db->select_one("sum(c.factmoney) as reverse_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(2)");//冲账
	$moneyRestart=$db->select_one("sum(c.factmoney) as restart_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(3)");//停机恢复退费
        $pledgemoney=$db->select_one("sum(c.factmoney) as pledge_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(4)");//退还押金
	
 	?>
	
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd credit_table" >
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("财务报表统计")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1 credit_table" id="myTable"  >     
        <tbody> 
		  <tr>
		    <td width="20%" align="right" class="bg"><? echo _("总共金额:")?></td> 
			<td width="30%" align="left" class="bg" colspan="2">&nbsp;<?=$moneyRs["in_all_money"]?></td>
	        <td width="10%" align="right" class="bg"><? echo _("总退金额:")?></td>
	        <td width="40%" align="left" class="bg">&nbsp;<?=$moneyRs1["out_all_money"]?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("实收金额:")?></td>
			<td   align="left" class="bg" colspan="2">&nbsp;
				<?php 
				echo $face=$moneyRs["in_all_money"]-$moneyRs1["out_all_money"];
				$c   =new ChineseNumber();
				echo _("   (大写金额:");
				echo $c->ParseNumber($face);
				echo ")";
				?> 
			</td>
			 <td align="right" class="bg"><? echo _("用户冲账:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyReverse['reverse_all_money']?></td>
	      </tr> 
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("用户销户:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyClosibng['closibng_all_money']?></td>
	      </tr>
                <!-----------------------2014.07.23添加押金退费--------------------------->
             <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("退还押金:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$pledgemoney['pledge_all_money']?></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("停机恢复现金退费:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyRestart['restart_all_money']?></td>
	      </tr>
		  
        </tbody>      
    </table>
 <?php   
   //$projectIDs=explode(',', $_SESSION["auth_project"]);
  // print_r($projectIDs);
 //$projectIDs=$db->select_all('ID','project',"");//原来的
   $projectIDs=$db->select_all('ID','project',"ID  in (". $_SESSION["auth_project"].")");//2014.09.03修改没有权限的管理员不能看到项目
   //var_dump($projectIDs);
   if($projectIDs){ //2014.09.03添加判断$projectIDs
foreach($projectIDs  as $pjids){ 
    
   foreach($pjids as $pjid){
 

	    $sql1=" c.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")"; 
			if($startDateTime){
				$sql1 .=" and c.adddatetime>='".$startDateTime."'";
			}
			if($endDateTime){
				$sql1 .=" and c.adddatetime<'".$endDateTime."'";
			}
			if($operator){
				$sql1 .=" and c.operator='".$operator."'";
			}
    	$sql1 .=" and u.projectID='".$pjid."'";
	    $moneyRs =$db->select_one("sum(c.money) as in_all_money","credit as c,userinfo as u",$sql1);
	    $moneyRs1=$db->select_one("sum(c.factmoney) as out_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(1,2,3,4) ");
	    $moneyClosibng=$db->select_one("sum(c.factmoney) as closibng_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(1)");//销户
	    $moneyReverse=$db->select_one("sum(c.factmoney) as reverse_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(2)");//冲账
	    $moneyRestart=$db->select_one("sum(c.factmoney) as restart_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(3)");//停机恢复退费
            $pledgemoney=$db->select_one("sum(c.factmoney) as pledge_all_money","orderrefund as c,userinfo as u",$sql1." and c.type in(4)");//退还押金
       ?> 
	 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd pjtable"  >
      <tr>
        <td width="89%" class="f-bulue1"><?=projectShow($pjid); ?> </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>  
	   
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1 pjtable" >     
        <tbody>   
	    <tr>
		    <td width="20%" align="right" class="bg"><? echo _("总共金额:")?></td> 
			<td width="30%" align="left" class="bg" colspan="2">&nbsp;<?=$moneyRs["in_all_money"]?></td>
	        <td width="10%" align="right" class="bg"><? echo _("总退金额:")?></td>
	        <td width="40%" align="left" class="bg">&nbsp;<?=$moneyRs1["out_all_money"]?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("实收金额:")?></td>
			<td   align="left" class="bg" colspan="2">&nbsp;
				<?php 
				echo $face=$moneyRs["in_all_money"]-$moneyRs1["out_all_money"];
				$c   =new ChineseNumber();
				echo _("   (大写金额:");
				echo $c->ParseNumber($face);
				echo ")";
				?> 
			</td>
			 <td align="right" class="bg"><? echo _("用户冲账:")?> </td>
			 <td align="left" class="bg">&nbsp;<?=$moneyReverse['reverse_all_money']?></td>
	      </tr> 
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("用户销户:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyClosibng['closibng_all_money']?></td>
	      </tr>
                 <!-----------------------2014.07.23添加押金退费--------------------------->
             <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("退还押金:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$pledgemoney['pledge_all_money']?></td>
	      </tr>
		   <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("停机恢复现金退费:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyRestart['restart_all_money']?></td>
	      </tr>
		 </tbody>      
    </table>
         <?php 
    }   }
   }
}else{
	   ?> 
     	

	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd sosoMo" >
      <tr>
        <td width="14%" class="f-bulue1 title_bg2" align="left"><? echo _("条件搜索")?></td>
		   
		<td width="86%" align="left " class="title_bg2" colspan="3">&nbsp; 
		
		</td> 
      </tr> 
	<form action="?action=moShow" name="myform" method="post" >
	  <tr>
	    <td align="right"><? echo _("所属项目:")?></td>
	    <td><?php projectSelected($projectID) ?></td>
	    <td align="right"><? echo _("开始时间：")?></td>
	    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"></td>
	  </tr>
	  <tr>
	    <td align="right"><? echo _("收费人员:")?></td>
	    <td><?php managerSelect($_POST["operator"]) ?></td>
	    <td align="right"><? echo _("结束时间:")?></td>
	    <td><input type="text" name="endDateTime" value="<?=$endDateTime?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"></td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("费用类型:")?></td>
	    <td>
		<select name="c_type">
	      <option value="0" ><? echo _("选择类型")?></option>
	      <option value="1" <?php if($c_type==1) echo "selected"; ?>><? echo _("开户预存")?></option>
	      <option value="2" <?php if($c_type==2) echo "selected"; ?>><? echo _("用户续费")?></option>
		  <option value="3" <?php if($c_type==3) echo "selected"; ?>><? echo _("卡片充值")?></option>
		  <option value="4" <?php if($c_type==4) echo "selected"; ?>><? echo _("用户移机")?></option>
		
		  <option value="5" <?php if($c_type==5) echo "selected"; ?>><? echo _("用户冲账")?></option>
		  <option value="6" <?php if($c_type==6) echo "selected"; ?>><? echo _("用户退费")?></option>
		  <option value="7" <?php if($c_type==7) echo "selected"; ?>><? echo _("订单退费")?></option>
		  <option value="8" <?php if($c_type==8) echo "selected"; ?>><? echo _("停机恢复")?></option>
		  <option value="9" <?php if($c_type==9) echo "selected"; ?>><? echo _("支付宝充值")?></option>
                  <option value="10" <?php if($c_type==10) echo "selected"; ?>><? echo _("用户过户")?></option>
                  <option value="11" <?php if($c_type==11) echo "selected"; ?>><? echo _("收取押金")?></option>
                  <option value="12" <?php if($c_type==12) echo "selected"; ?>><? echo _("退还押金")?></option>
		</select>
		</td>
	    <td align="right">受理人员</td>
	    <td><?php acceptSelect($accept_name); ?></td> 
	    
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<?php echo _("提交")?>"></td>
	    <td align="right"><a href="PHPExcel/excel_credit.php?<?=$querystring?>"><? echo _("EXCEL导出")?></a></td>
	    <td>&nbsp;</td>
	  </tr>
	  </table>
	</form>
	<br>	
	<?php 
	$sql=" c.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
        if ($accept_name) {
        $sql.=" and u.accept_name like '%" . $accept_name . "%'";
    }
    if($startDateTime){
		$sql .=" and c.adddatetime>='".$startDateTime."'";
	}
	if($endDateTime){
		$sql .=" and c.adddatetime<'".$endDateTime."'";
	}
	if($operator){
		$sql .=" and c.operator='".$operator."'";
	}
	if($projectID){
		$sql .=" and u.projectID='".$projectID."'";
	}
	 if($c_type ){
	   if($c_type ==1 ){
		   $sql .=" and c.type=0 ";
		}
		if($c_type ==2 ){
		   $sql .=" and c.type= 1 ";
		}if($c_type ==3 ){
		   $sql .=" and c.type= 2 ";
		}if($c_type ==4 ){
		   $sql .=" and c.type= 3 ";
		}
		if($c_type ==5){
		   $sql .=" and c.type= 2 ";
		}
		if($c_type ==6 ){
		   $sql .=" and c.type= 1 ";
		}
		if($c_type ==7 ){
		   $sql .=" and c.type= 0 ";
		}
		if($c_type ==8 ){
		   $sql .=" and c.type= 3 ";
		}
                if($c_type ==9 ){
		   $sql .=" and c.type= 9 ";
		}
                 if($c_type ==10 ){
		   $sql .=" and c.type= 10 ";
		}
                  if($c_type ==11 ){
		   $sql .=" and c.type= 11 ";
		}
              if($c_type ==12 ){
		   $sql .=" and c.type= 4 ";
		}
	}

	$tableName=( $c_type ==1 || $c_type ==2 ||$c_type ==3 ||$c_type ==4  || $c_type ==9 || $c_type ==10 || $c_type ==11 || empty($c_type))?"credit":"orderrefund";
	$result  =$db->select_all("c.*,u.UserName","".$tableName." as c,userinfo as u",$sql."  order by c.ID desc",15);
	$moneyRs =$db->select_one("sum(c.money) as in_all_money","credit as c,userinfo as u",$sql."  order by c.ID desc");
	$moneyRs1=$db->select_one("sum(c.factmoney) as out_all_money","orderrefund as c,userinfo as u",$sql." and c.type in(1,2,3,4)  order by c.ID desc");
	$moneyClosibng=$db->select_one("sum(c.factmoney) as closibng_all_money","orderrefund as c,userinfo as u",$sql." and c.type in(1)  order by c.ID desc");//销户
	$moneyReverse=$db->select_one("sum(c.factmoney) as reverse_all_money","orderrefund as c,userinfo as u",$sql." and c.type in(2)  order by c.ID desc");//冲账
	$moneyRestart=$db->select_one("sum(c.factmoney) as restart_all_money","orderrefund as c,userinfo as u",$sql." and c.type in(3)  order by c.ID desc");//停机恢复退费
        $pledgemoney=$db->select_one("sum(c.factmoney) as pledge_all_money","orderrefund as c,userinfo as u",$sql." and c.type in(4)  order by c.ID desc");//退还押金
//清除财务 
MysqlBegin();//开始事务定义
 if($_REQUEST["action"]=="check"){  
   $check_credit =true;$check_orderrefund =true;$check_userbill =true; 
   $checkEnddateDateTime = $_POST["checkEnddateDateTime"];
   $check =$_POST["check"];  
 	 if($checkEnddateDateTime ==""){
    echo "<script>alert('截止时间不能为空');window.location.href='finance_report.php';</script>"; 
    exit;	
   }
   //查询该截止时间段的用户账单是否有未对账账单，如存在不给予删除  4 订单扣费 3 订单退费到余额
   $checkCount =$db->select_count("userbill","type !=4 and remark!='System:add user financial subjects' and  type !=3 and type !=8  and  adddatetime < '".$checkEnddateDateTime."' and userid !=0 and `check` !=1");
   if($checkCount >0){
    echo "<script>alert('截止时间内存在未对账的账单".$checkCount."个,请确认对账后在删除');window.location.href='finance_report.php';</script>"; 
    exit;	
   }
   $check_credit = $db->delete_new("credit","adddatetime < '".$checkEnddateDateTime."' ");	
	 if(!$check_credit) $check_credit = false;		
	 $check_orderrefund = $db->delete_new("orderrefund","adddatetime < '".$checkEnddateDateTime."' ");	
	 if(!$check_orderrefund) $check_orderrefund = false;		  
	 $check_userbill = $db->delete_new("userbill","adddatetime < '".$checkEnddateDateTime."' and 	`check`=1 or `type` in(3,4,8)");
	 if(!$check_userbill) $check_userbill = false;	 
 if($check_credit && $check_orderrefund && $check_userbill){
 MysqlCommit(); 
 $c=_("操作成功" );       
 }else{
 MysqRoolback();
 $c=_("操作失败" );   
 }  
 echo "<script>alert('". $c."');window.location.href='finance_report.php';</script>"; 
}  

 ?>  
<form action="?action=check" name="myform" method="post" > 
	<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bd creditinfo"  >
		  <tr>
			<td colspan="6" class="f-bulue1 title_bg2">
				<div width="20%" style="float:left"><? echo _("财务报表列表")?></div>
				<?php 
		     if(in_array("creditDell",$_SESSION["auth_permision"])){
		    ?>
		    <div align="right" style="float:right">截止时间：<input type="text" name="checkEnddateDateTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?=$checkEnddateDateTime?>"> 删除截止时间之前的数据&nbsp;<input type="submit" value="财务删除" onclick="javascript:return(confirm('<? echo _("确认删除?")?>'));"> </div> 
		    <?php
		    /*对账类型：<?= userbillCheck($check)?>&nbsp;*/
		    }
				?> 
			</td> 
			</tr>
			<tr>
			<td width="8%" align="center" class="bd_b"><? echo _("编号")?></td>
			<td width="17%" align="center" class="bd_b"><? echo _("用户名")?></td>
			<td width="23%" align="center" class="bd_b"><? echo _("充值类型")?></td>
			<td width="16%" align="center" class="bd_b"><? echo _("金额")?></td>
			<td width="16%" align="center" class="bd_b"><? echo _("时间")?></td>
			<td width="20%" align="center" class="bd_b"><? echo _("操作人员")?></td>
			</tr>
		  <?php 
			if(is_array($result)){
				foreach($result as $key=>$rs){
					unset($type);
				if($tableName=="credit"){
					if($rs["type"]=="1"){
						$type=_("用户续费");
					}elseif($rs["type"]=="0"){
						$type=_("开户预存");
					}else if($rs["type"]=='2'){
						$type=_("卡片充值");
					}else if($rs["type"]=='3'){
						$type=_("用户移机");
                                        }  else if($rs["type"]=='9'){
                                                $type=_("支付宝充值");
                                        }elseif ($rs["type"]=='10') {
                                                $type=_("用户过户");
                                        }elseif ($rs["type"]=='11') {
                                                    $type=_("收取押金");
                                         }elseif ($rs["type"]=='q') {
                                                    $type=_("其他收费");
                                         }
                                            
                                        
				}else{
				   if($rs["type"]=="3"){ 
					     $type=_("停机恢复"); 
					}if($rs["type"]=="2"){ 
					     $type=_("用户冲账"); 
					}elseif($rs["type"]=="1"){ 
						$type=_("用户退费");
					}elseif($rs["type"]=="0"){
						$type=_("订单退费");
					}elseif($rs["type"]=="4"){
						$type=_("退还押金");
					}			
				} 
		  ?>   
		  <tr>
			<td align="center" class="bd_b"><?=$rs["ID"] ?></td>
			<td align="center" class="bd_b"><?=getUserName($rs["userID"]);?></td>
			<td align="center" class="bd_b"><?=$type?></td>
			<td align="center" class="bd_b"><?=$rs["money"]?></td>
			<td align="center" class="bd_b"><?=$rs["adddatetime"]?></td>
			<td align="center" class="bd_b"><?=$rs["operator"]?></td>
			</tr>
		  <?php 
				}
			}
		  ?> 
		  <tr>
			<td colspan="6" align="center">
			<?php $db->page($querystring); ?>
			</td>
			</tr>
		  </table>
</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd credit_table"  >
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("财务报表统计")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1 credit_table" id="myTable"  >     
        <tbody> 
		  <tr>
		    <td width="20%" align="right" class="bg"><? echo _("总共金额:")?></td> 
			<td width="30%" align="left" class="bg" colspan="2">&nbsp;<?=$moneyRs["in_all_money"]?></td>
	        <td width="10%" align="right" class="bg"><? echo _("总退金额:")?></td>
	        <td width="40%" align="left" class="bg">&nbsp;<?=$moneyRs1["out_all_money"]?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("实收金额:")?></td>
			<td   align="left" class="bg" colspan="2">&nbsp;
				<?php 
				echo $face=$moneyRs["in_all_money"]-$moneyRs1["out_all_money"];
				$c   =new ChineseNumber();
				echo _("(大写金额:");
				echo $c->ParseNumber($face);
				echo ")";
				?> 
			</td>
			 <td align="right" class="bg"><? echo _("用户冲账:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyReverse['reverse_all_money']?></td>
	      </tr> 
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("用户销户:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyClosibng['closibng_all_money']?></td>
	      </tr>
              <!-----------------------2014.07.23添加押金退费--------------------------->
             <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("退还押金:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$pledgemoney['pledge_all_money']?></td>
	      </tr>
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg"  colspan="2">&nbsp;</td> 
			 <td align="right" class="bg"><? echo _("停机恢复现金退费:")?></td>
			 <td align="left" class="bg">&nbsp;<?=$moneyRestart['restart_all_money']?></td>
	      </tr>
        </tbody>      
    </table>
	
<?php

}
?>	
	<table width="100%" border="0" cellpadding="5" cellspacing="0"  class="bg1">
		<tr>
		    <td align="center" class="bg">&nbsp;</td>
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
        营帐管理-> <strong>营业报表</strong>
      </p>
      <ul>
          <li>按项目、时间、管理员、类型明细的筛选生成不同的财务报表。</li>
          <li>可以自定义天、周、月、年财务报表功能。</li>
          <li>根据需要生成不同帐单和报表，生成图形报表，方便直观分析。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html> 