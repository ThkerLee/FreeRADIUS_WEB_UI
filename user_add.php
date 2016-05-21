#!/bin/php
<?php include_once("inc/conn.php");  
require_once("ros_static_ip.php");
require_once("evn.php");  
include_once("inc/ajax_js.php");
date_default_timezone_set('Asia/Shanghai');
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
 <!--
<link href="style/bule/select.css" rel="stylesheet" type="text/css">
 -->
<script src="js/ajax.js" type="text/javascript"></script>
<script src="js/jsdate.js" type="text/javascript"></script>
<script src="js/datechooser.js" type="text/javascript"></script> 
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
            WindowTitle:          '<b>用户管理</b>',
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
$manageNum =$db->select_one("*","manager","manager_account = '".$_SESSION['manager']."'");  
$addusertotalnum =(int)$manageNum["addusertotalnum"];//允许开户人数
$addusernum      =(int)$manageNum["addusernum"];//已开户人数
$manager_totalmoney =(int)$manageNum["manager_totalmoney"];//允许收费金额
$totalInMoney = $db->select_one("sum(money) as in_all_money","credit","operator ='".$_SESSION['manager']."'"); //用户收入金额 包括，开户，续费，充值，卡片充值，用户移机===
$totalOutMoney = $db->select_one("sum(factmoney) as out_all_money","orderrefund","operator ='".$_SESSION['manager']."' and type in(1,2,3)"); // 1 销户 2冲账 3恢复暂停
$totalMoney = (int)$totalInMoney['in_all_money'] -(int)$totalOutMoney['out_all_money']; //实际收费金额
 			
if($_POST){ 
	//print_r($_POST);exit; 
	$result=userAddSave($_POST['act'],$_POST);  
	if(is_array($result)&& count($result)>1){ 
		array_pop($result);
	    $error=$result;
		$error= implode("\\n",$error);
		echo "<script>alert('".$error."');window.location.href='user_add.php?act=more';</script>";  
	}elseif(is_array($result) && count($result)==1){   
	    $error=$result; 
		$error= implode("\\n",$error);
		echo "<script>alert('".$error."');</script>";   
		if(($_POST['act']=="once"  || $_POST['actchild']=='child') && $error=="添加成功"){
                          $re=$db->select_one("*","message","type = 1");
                          $status=$re['status'];
                          if($status == "enable"){
                    echo "<script>if(window.confirm('"._("是否发送短信")."？')){window.open('short_messagess.php?mobile=".$_POST['mobile']."','newname','height=60,width=80,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=0,left=0');}</script>";
                          }
                    echo "<script>if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?UserName=".$_POST['account']."&action=all&installcharge=1&financeID=".$_POST["financeID"]."&financemoney=".$_POST["financemoney"]."','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=100,left=300');}window.location.href='user.php';</script>";	
		}else if ($_POST['act']=="netbar"   && $error=="添加成功"){
		 echo "<script>if(window.confirm('"._("是否打印票据")."？')){window.open('user_show_print.php?UserName=".$_POST['account']."&action=netbar_print','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,top=100,left=300');}window.location.href='user.php';</script>";
		}elseif($_POST['act']=="more") {
		   echo "<script>window.location.href='user.php';</script>"; 
		}
	}
}
  
if($_GET['act']=='child'){//子账号添加 
?>
  <form action="?" method="post" name="myform"  onSubmit="return checkUserForm();">
 <table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td> 
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"> 
		<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
		  <tr> 
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td> 
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td> 
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
        <td width="89%" class="f-bulue1"> <? echo _("用户添加")?> 
		<input type="radio" name="add" id="once"  value="once"<? if($_REQUEST['act']=='' || $_REQUEST['act']=='once') echo "checked";?> onClick="window.location.href='?act=once'" >  <? echo _("单个添加")?>
		<input type="radio" name="add" id="child"  value="once"<? if($_REQUEST['act']=='child' || $_REQUEST['actchild']=='child') echo "checked";?> onClick="window.location.href='?act=child'" >  <? echo _("子账号添加")?>
               <!-- <input type="radio" name="add" id="more"  value="more" <? if($_REQUEST['act']=='more') echo "checked";?> onClick="window.location.href='?act=more'" >  <? echo _("批量添加")?>
		
		 <input type="radio" name="add" id="netbar"  value="netbar" <? if($_REQUEST['act']=='netbar') echo "checked";?> onClick="window.location.href='?act=netbar'" >  <? echo _("时长计费")?>
		-->
		</td>  
		<td width="11%" align="right">&nbsp;</td> 
      </tr> 
	  </table>

  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist"> 
        <tbody>     
     <input type="hidden" name="addusertotalnum"  id="addusertotalnum" value="<?=$addusertotalnum?>" >
	   <input type="hidden" name="addusernum"       id="addusernum"  value="<?=$addusernum?>" > 
	   <input type="hidden" name="manager_totalmoney"id="manager_totalmoney"value="<?=$manager_totalmoney?>">
	   <input type="hidden" name="totalMoney"id="totalMoney" value="<?=$totalMoney?>"> 
        <tr> 
            <td align="right" class="bg"><b style="color: red;  ">基本信息</b></td> 
          <td align="left" class="bg">&nbsp;</td> 
        </tr> 
           <tr> 
		     <input type="hidden" value="child" name="actchild" id="actchild" > 
			<td width="13%" align="right" class="bg">* <? echo _("母 账 号")?>:</td> 
			<td width="87%" align="left" class="bg"><input type="text" id="Mname" name="Mname" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','check_Mname','Mname','MnameTXT');" class="input_out">  
			  <span id="MnameTXT"><? echo _("帐号不能为中文")?></span></td> 
		  </tr> 
 
<?php  
} 
if($_GET['act']=='once' || empty($_GET['act']) || $_GET['act']=='child') {//单个添加 
   if($_GET['act']=='once' || empty($_GET['act']) ){ 
   ?>
     <form action="?" method="post" name="myform" onSubmit="return checkUserForm();" > 
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td> 
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"> 
		<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
		  <tr> 
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td> 
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td>
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
        <td width="89%" class="f-bulue1"> <? echo _("用户添加")?> 
		    <input type="radio" name="add" id="once"  value="once"<? if($_REQUEST['act']=='' || $_REQUEST['act']=='once') echo "checked";?> onClick="window.location.href='?act=once'" >  <? echo _("单个添加")?>
		    <input type="radio" name="add" id="child"  value="once"<? if($_REQUEST['act']=='child' || $_REQUEST['actchild']=='child') echo "checked";?> onClick="window.location.href='?act=child'" >  <? echo _("子账号添加")?>
	     <!-- <input type="radio" name="add" id="more"  value="more" <? if($_REQUEST['act']=='more') echo "checked";?> onClick="window.location.href='?act=more'" >  <? echo _("批量添加")?>
		
		<input type="radio" name="add" id="netbar"  value="netbar" <? if($_REQUEST['act']=='netbar') echo "checked";?> onClick="window.location.href='?act=netbar'" >  <? echo _("时长计费")?>
		-->
		</td>  
		<td width="11%" align="right">&nbsp;</td> 
      </tr> 
	  </table> 
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist"> 
        <tbody>
  	  <input type="hidden" name="addusertotalnum"  id="addusertotalnum"value="<?=$addusertotalnum?>" >
	   <input type="hidden" name="addusernum"  id="addusernum"    value="<?=$addusernum?>" > 
	   <input type="hidden" name="manager_totalmoney" id="manager_totalmoney" value="<?=$manager_totalmoney?>">
	   <input type="hidden" name="totalMoney" id="totalMoney" value="<?=$totalMoney?>"> 
	 
   <?php
   }
 ?> <input type="hidden" value="once" name="act" id='act'> 
 <?php if($_GET['act']!='child'){ ?>
   <tr> 
       <td align="right" class="bg"><b style="color: red;  ">基本信息</b></td> 
          <td align="left" class="bg">&nbsp;</td> 
 </tr> 
<?php }?>	
                    
                    <tr> 
                            <td align="right" class="bg">* <? echo _("所属区域")?>:</td> 
                            <td align="left" class="bg"><?php selectArea();?></td> 
                    </tr> 
                 <tr> 
		    <td align="right" class="bg">* <? echo _("所属项目")?>:</td> 
		    <td align="left" class="bg" id="projectSelectDIV"> <select><option><? echo _("选择项目");?></option></select>
                                                                                                                        </td> 
		 </tr>  
 
                <tr id="TR_autoName">   
			<td width="13%" align="right" class="bg">* <? echo _("用户帐号")?>:</td> 
			<td width="87%" align="left" class="bg">
			<span id="userTXT">
			<input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','check_account','account','accountTXT');" class="input_out"> 
			</span>
			<input type="button" value="<?php echo _("自动分配账号");?>" onClick="ajaxInput('ajax/project.php','projectIDuser','projectID','userTXT');">
			  <span id="accountTXT"><? echo _("帐号不能为中文")?></span> 
		 </td> 
		  </tr>  
		  <tr> 
		    <td align="right" class="bg">* <? echo _("默认密码")?>:</td> 
		    <td align="left" class="bg">
			<input type="radio" value="1" name='showPwd'  onClick="showPassword(this);" > <? echo _("是")?>
			<input type="radio" value="0" name='showPwd' checked="checked"  onClick="showPassword(this);" > <? echo _("否")?>  
			
			</td> 
		  </tr> 
		  
		   <tr> 
		    <td align="right" class="bg">* <? echo _("用户密码")?>:</td> 
		    <td align="left" class="bg" id="passwordTD"><input type="password" id="password" name="password" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out">
             <? echo _("密码不能为中文")?> </td> 
		  </tr> 
		  <tr id="pwdTXT"> 
		    <td align="right" class="bg">* <? echo _("确认密码")?>:</td> 
		    <td align="left" class="bg" id="pwdTD"><input type="password" id="pwd" name="pwd" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"> </td>         </tr>  
		  <tr> 
		    <td align="right" class="bg">* <? echo _("真实姓名")?>: </td> 
		    <td align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"></td> 
		 </tr>
                 <tr> 
		    <td align="right" class="bg">* <? echo _("证件号码")?>:</td> 
                    <td align="left" class="bg"><input type="text" id="cardid" name="cardid" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';">&nbsp;&nbsp;证件类型:
                        <select name="type">  <!---2014.07.17添加证件类型---->
                            <option value ="1" selected='selected'>身份证</option>  
                            <option value ="2">军官证</option>  
                            <option value="3">护照</option>
                            <option value="4">其他</option>
                          </select>    
                         </td> 
                 </tr> 
                  <tr id="cappingTR"> 
		    <td align="right" class="bg">* <? echo _("手机号码")?>:</td> 
		    <td align="left" class="bg"><input type="text" name="mobile" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out">		       <? echo _("手机号码必须为11位数字")?></td> 
		    </tr> 
                 <tr> 
		    <td align="right" class="bg">* <? echo _("联系地址")?>:</td> 
		    <td align="left" class="bg"><input type="text" name="address" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"><? echo _("不能为空")?> </td> 
                 </tr>
                 
                 
                  <tr> 
                            <td align="right" class="bg"><b>详细信息</b></td> 
                            <td align="left" class="bg">
                                    <input type="radio" name="showtype" value="1"  onClick="showtype_change();">显示&nbsp;&nbsp;
                                    <input type="radio" name="showtype" value="0" checked="checked" onClick="showtype_change();" >隐藏</td>  
                            </td> 
                  </tr> 
                  
                  
                  <tr id="showID" style="display: none; "> 
		    <td align="right" class="bg"><? echo _("用户性别")?>: </td> 
		    <td align="left" class="bg">
			<input type="radio" name="sex" value="male" checked="checked" ><? echo _("男");?>&nbsp;&nbsp;
			<input type="radio" name="sex" value="female" ><? echo _("女");?>  </td> 
                </tr> 
		    <tr id="showID1" style="display: none; "> 
		    <td align="right" class="bg"><? echo _("用户生日")?>: </td> 
		    <td align="left" class="bg"><input type="text" id="birthday" name="birthday"    onClick="HS_setDate(this)"  onBlur="this.className='input_out';" class="input_out"></td> 
	      </tr>
		  <tr id="showID2" style="display: none; "> 
		    <td align="right" class="bg"><? echo _("工作电话")?>:</td> 
		    <td align="left" class="bg"><input type="text" id="workphone" name="workphone" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> </td> 
	      </tr> 
		 <!-- <tr id="unitpriceTR"> -->
                  <tr id="showID3" style="display: none; "> 
		    <td align="right" class="bg"><? echo _("家庭电话")?>:</td> 
		    <td align="left" class="bg"><input type="homephone" name="homephone" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"></td> 
	      </tr>  
		  <tr id="showID4" style="display: none; "> 
		    <td align="right" class="bg"><? echo _("电子邮件")?>:</td> 
		    <td align="left" class="bg"><input type="text" name="email" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out"></td> 
		    </tr> 
                    
                    <tr> 
                         <td align="right" class="bg"><b style="color: red;  ">账务信息</b></td> 
                         <td align="left" class="bg">&nbsp;</td> 
                    </tr> 
                  <tr> 
		    <td align="right" class="bg"><? echo _("初装费用")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="radio" name="installcharge_type" value="0" onClick="showinstalltxt();totalMoneys(); "  checked="checked" > <? echo _("无初装费")?> 
				<input type="radio" name="installcharge_type" value="1" onClick="showinstalltxt();totalMoneys(); "><? echo _("有初装费")?> 
				<span id="showinstalltxt_span" style="display:none;"> <input name='installcharge'type='hidden'id="installcharge" value="0"  onBlur="totalMoneys();"  ></span>	<br>
				<span id="peiroTimeTXT"></span>			</td> 
		  </tr>	
		   <tr> 
		    <td align="right" class="bg"><? echo _("缴费科目")?>:</td> 
		    <td align="left" class="bg">
			<?php 
		   $mResult=$db->select_all("*","finance","");
			echo "<select name='financeID' id='financeID' onchange=ajaxInput('ajax_check.php','financeID','financeID','financeTEXT'); onBlur=ajaxInput('ajax_check.php','remark','financeID','remarkTEXT');>";
			echo "<option value=''>". _("选择科目")."</option>";
			if($mResult){
				foreach($mResult as $mKey=>$mRs){
					echo "<option value='".$mRs["ID"]."'";
					if($manager==$mRs["ID"]) echo "selected";
					echo ">".$mRs["name"]."</option>";
				}
			}
			echo "</select>";
			?>  
			</td> 
		  </tr>	 
		   <tr> 
		    <td align="right" class="bg"><? echo _("缴费金额")?>:</td>  
		    <td align="left" class="bg"  id="financeTEXT"> <input type="text" id="financemoney" name="financemoney" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out" onClick="totalMoneys();"> <font color="red"><? echo _("金额亦可自定义")?> </font> </td> 
		  </tr>
                <!-------------2014.07.23添加押金---------------->
                  <tr > 
		    <td align="right" class="bg">押金金额:</td> 
		    <td align="left" class="bg"><input type="text" id="pledgemoney" name="pledgemoney" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';"> 为空则无押金</td> 
                 </tr> 
                  
                    
                 <tr> 
		    <td align="right" class="bg">* <? echo _("选择产品")?>:</td> 
		    <td align="left" class="bg"  id="productSelectDIV"><select ><option><? echo _("请选择产品");?></option></select>
		    <? //=productSelected(); ?>
		    <span id='productTXT'></span></td> 
		  </tr> 
                     </tr> 
		   <tr> 
		    <td align="right" class="bg">* <? echo _("续费周期")?>:</td> 
		    <td align="left" class="bg" >
		    	<span id="productPeriodTXT"> 
		       <select ><option><? echo _("请您选择周期");?> </option></select>
		      </span> 
		      <? echo _("如：购买包一月订单，续费3周期，即生成3张订单。开始时间为当前指定时间，下一订单的开始时间为上一订单的结束时间。");?> 
		     <span id="periodTXT"></span> &nbsp;&nbsp;<br>
		    </td> 
		   </tr> 	  
		  <tr> 
		    <td align="right" class="bg">* <? echo _("预存金额")?>:</td> 
		    <td align="left" class="bg"><input type="text" name="money" id="money" onFocus="this.className='input_on';totalMoneys();"  onClick="ajaxInputMore('ajax/project.php','pdoID','productID','timetype','timetypes','begintime','begindatetime','period','period','periodTXT');" onBlur="this.className='input_out'" class="input_out" value="0">  <? echo _("元")?>   <span id="periodTXT"></span>
		     </td> 
		    </tr> 
                    
		  <tr> 
		    <td align="right" class="bg">* <? echo _("收据单号")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="text" name="receipt" onFocus="this.className='input_on'" onBlur="this.className='input_out'"  value="<?=date("YmdHis",Time());?>"> 
				<? echo _("默认为当前时间，请自行修改")?> 
			</td> 
		    </tr> 
                   <tr> 
		    <td align="right" class="bg">* <? echo _("装机人员")?>:</td> 
		    <td align="left" class="bg"><?php managerZJRY() ?></td> 
		   </tr> 
                   <!-----------------------2014.09.25添加受理人员----------------------->
                    <tr> 
		    <td align="right" class="bg">受理人员:</td> 
		    <td align="left" class="bg"><?php acceptSelect(); ?> 受理人员请在系统设置里添加。</td> 
		   </tr> 
                   
                    <tr> 
                         <td align="right" class="bg"><b style="color: red;  ">高级信息</b></td> 
                         <td align="left" class="bg">&nbsp;</td> 
                    </tr> 
		   <tr> 
		    <td align="right" class="bg"><? echo _("计时类型")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="radio" name="timetype"  value="1" checked="checked" onClick="timetype_change();ajaxInputMore('ajax/project.php','pdoID','productID','timetype','timetype','begintime','begindatetime','period','period','periodTXT');"><? echo _("立即计时")?> 
				<input type="radio" name="timetype" value="0" onClick="timetype_change();ajaxInputMore('ajax/project.php','pdoID','productID','timetype','timetype','begintime','begindatetime','period','period','periodTXT');"><? echo _("上线计时")?>	
				 	</td> 
		  </tr> 
		  <tr id="begindatetimeTR"> 
		    <td align="right" class="bg"><? echo _("开始时间")?>:</td> 
		    <td align="left" class="bg"><input type="text" name="begindatetime" id='begindatetime' onBlur="this.className='input_out'；ajaxInputMore('ajax/project.php','pdoID','productID','timetype','timetype','begintime','begindatetime','period','period','periodTXT');"   onFocus="HS_setDate(this)"> 
		    <?php echo _("计时类型如果为“立即计时”，开始时间为空表示从当前时间开始计算")?></td> 
		  <tr> 
		    <td align="right" class="bg"><? echo _("地址分配")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="radio" name="iptype" value="0"onClick="iptype_change();"checked="checked"><? echo _("BRAS分配")?>&nbsp;&nbsp; 
				<input type="radio" name="iptype" value="1" onClick="iptype_change();ajaxGetIp('ajax/getip.php','projectID','projectID','ipaddress');" ><? echo _("系统分配")?>			    
			  <span id="radio_poolname" > <input type="radio" name="iptype" value="2"  onClick="iptype_change();ajaxInput('ajax/project.php','poolShow','productID','poolnameSelectDIV');"  ><? echo _("地址池分配")?></span>
				</td> 
		  </tr> 
		   <tr id="ipaddressTR"> 
		    <td align="right" class="bg"><? echo _("I P 地址")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="text" name="ipaddress" id="ipaddress" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="button" value="<?php echo _("分配IP")?> " onClick="ajaxGetIp('ajax/getip.php','projectID','projectID','ipaddress');">			</td>
			</tr>
			<tr id="poolnameTR"> 
		    <td align="right" class="bg"><? echo _("地址池")?>:</td> 
		    <td align="left" class="bg" id="poolnameSelectDIV" onFocus="this.className='input_on'" onBlur="this.className='input_out'"></td>
			</tr> 
		 <tr> 
		    <td align="right" class="bg"><? echo _("固定在线时长")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="radio" name="onlinestatus" value="enabled" onClick="status_change();"><? echo _("启用")?> 
				<input type="radio" name="onlinestatus" value="disable" onClick="status_change();" checked="checked"><? echo _("禁用")?></td> 
		 </tr> 
			<tr id="statusTR"  style="display:none"> 
		    <td align="right" class="bg"><? echo _("在线时长")?>:</td> 
		    <td align="left"  class="bg"><input type="text" name="onlinetime" onBlur="this.className='input_out'"> <? echo _("分")?></td> 
		    </tr> 
                    <?php
                    if(in_array("user_add_onlinenum",$_SESSION["auth_permision"])){ //2014.06.27 修改允许同时在线人数权限问题。
                    ?>
		   <tr> 
		    <td align="right" class="bg"><? echo _("允许同时在线人数")?>:</td> 
		    <td align="left" class="bg"><input type="text" name="onlinenum" value="1" onFocus="this.className='input_on'" onBlur="this.className='input_out'" >  
		     <? echo _("0或留空表示不限制")?></td> 
		    </tr>
                    <?php
                    }  else {
                   ?> 
                    <tr> 
		    <td align="right" class="bg"><? echo _("允许同时在线人数")?>:</td> 
		    <td align="left" class="bg"><input type="text" name="onlinenum" value="1" onFocus="this.className='input_on'" onBlur="this.className='input_out'" readonly="readonly" >  
		     <? echo _("0或留空表示不限制")?></td> 
		    </tr>
                   <?php
                   }
                    
                    ?>
		  <tr> 
		    <td align="right" class="bg"><? echo _("MAC 地址")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="text" name="MAC" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="checkbox" name="macbind" value="1" onClick="macbind_change()"> 
				<? echo _("是否绑定MAC地址")?></td> 
		    </tr> 
		  <tr> 
			<td align="right" class="bg"><? echo _("NAS 地址")?>:</td> 
			<td align="left" class="bg"> 
				<input type="text" name="NAS_IP" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="checkbox" name="nasbind" value="1" onClick="nasbind_change()"> 
				<? echo _("是否绑定NAS地址")?></td> 
			</tr>  
		<tr> 
			<td align="right" class="bg"><? echo _("VLAN 地址")?>:</td> 
			<td align="left" class="bg"> 
				<input type="text" name="VLAN" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="checkbox" name="vlanbind" value="1" onClick="VLANbind_change()"> 
				<? echo _("是否绑定VLAN地址")?></td> 
			</tr> 
		  
 <?php
 
  }else   if($_GET['act']=='more') {//批量添加
?>
<form action="?act='more'" method="post" name="myform"  onSubmit="return checkUserMoreForm();"  > 
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td> 
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"> 
		<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
		  <tr> 
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td> 
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td> 
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
        <td width="89%" class="f-bulue1"> <? echo _("用户添加")?> 
		<input type="radio" name="add" id="once"  value="once"<? if($_REQUEST['act']=='' || $_REQUEST['act']=='once') echo "checked";?> onClick="window.location.href='?act=once'" >  <? echo _("单个添加")?>
		<input type="radio" name="add" id="child"  value="once"<? if($_REQUEST['act']=='child' || $_REQUEST['actchild']=='child') echo "checked";?> onClick="window.location.href='?act=child'" >  <? echo _("子账号添加")?>
	    <!--<input type="radio" name="add" id="more"  value="more" <? if($_REQUEST['act']=='more') echo "checked";?> onClick="window.location.href='?act=more'" >  <? echo _("批量添加")?>
		 
		 <input type="radio" name="add" id="netbar"  value="netbar" <? if($_REQUEST['act']=='netbar') echo "checked";?> onClick="window.location.href='?act=netbar'" >  <? echo _("时长计费")?>
		 -->
		 </td>  
		<td width="11%" align="right">&nbsp;</td> 
      </tr> 
	  </table>

  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist"> 
        <tbody>      
		  <tr> <input type="hidden" value="more" name="act" >
			<td width="13%" align="right" class="bg"  height="30px">* <? echo _("用户帐号")?>:</td> 
			<td width="87%" align="left" class="bg" height="30px">
			  <? echo _("标识符")?>:<input type="text" name="prefix" id="prefix" size="4"  nFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','check_prefix','prefix','prefixTXT');" class="input_out">
			  <? echo _("开始ID")?>:<input type="text" id="startID" name="startID" size="4"onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','check_startID','startID','startTXT');" class="input_out">  
			  <? echo _("结束ID")?>:<input type="text" id="endID" name="endID"size="4" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','check_endID','endID','endTXT');" class="input_out"> 
			  <span id="prefixTXT"></span><span id="startTXT"></span> <span id="endTXT"></span>  <? echo _("生成用户名例:vip1,vip2(标识符与数字连接)")?></td> 
		  </tr>  
		  <!--
		  <tr> 
		    <td align="right" class="bg"  height="30px">* <? echo _("所属项目")?>:</td> 
		    <td align="left" class="bg"  height="30px"> 
			<?php 

				$sql=" ID in (". $_SESSION["auth_project"].")"; 
				$projectResult=$db->select_all("*","project",$sql); 
				echo "<select name='projectID' id='projectID'onchange=\"ajaxInput('ajax/project.php','projectID','projectID','productSelectDIV');\" onblur=\"ajaxInput('ajax/project.php','poolnameShow','projectID','poolnameSelectDIV');\">"; 
				echo "<option value=''>"._("选择项目")."</option>"; 
				if(is_array($projectResult)){ 
					foreach($projectResult as $key=>$projectRs){ 
						if($projectRs["ID"]==$projectID){ 
							echo "<option value=".$projectRs["ID"]." selected>".$projectRs["name"]."</option>"; 
						}else{ 
							echo "<option value=".$projectRs["ID"].">".$projectRs["name"]."</option>"; 
						} 
					} 
				} 
				echo "</select>";	  
			?> </td>  
			</tr> 
			-->
			 <tr> 
		     <td align="right" class="bg">* <? echo _("所属区域")?>:</td> 
	       <td align="left" class="bg"><? selectArea();?></td> 
		  </tr> 
      <tr> 
		    <td align="right" class="bg">* <? echo _("所属项目")?>:</td> 
		    <td align="left" class="bg" id="projectSelectDIV"> <select><option><? echo _("选择项目");?></option></select>
		    </td> 
		 </tr>  
		  <tr  > 
		    <td align="right" class="bg" height="30px">* <? echo _("装机人员")?>:</td> 
		    <td align="left" class="bg"  height="30px">		
			<input type="text" name="zjry" onFocus="this.className='input_on'" onBlur="this.className='input_out'" >
			</td> 
		  </tr> 
		  <tr > 
		    <td align="right" class="bg" height="30px">* <? echo _("收据单号")?>:</td> 
		    <td align="left" class="bg"  height="30px"> 
				<input type="text" name="receipt" onFocus="this.className='input_on'" onBlur="this.className='input_out'"  value="<?=date("YmdHis",time());?>"> 
				<? echo _("默认为当前时间，请自行修改")?> 
			</td> 
		    </tr> 
		  <tr > 
		    <td align="right" class="bg" height="30px">* <? echo _("选择产品")?>:</td> 
		    <td align="left" class="bg"  id="productSelectDIV"><select ><option>请选择产品</option></select><? //=productSelected();?><span id='productTXT'></span></td> 
		  </tr> 
		  <tr> 
		    <td align="right" class="bg"height="30px"><? echo _("初装费用")?>:</td> 
		    <td align="left" class="bg"  height="30px"> 
				<input type="radio" name="installcharge_type" value="0"  onClick="showinstalltxt();" checked="checked"> <? echo _("无初装费")?> 
				<input type="radio" name="installcharge_type" value="1" onClick="showinstalltxt();"><? echo _("有初装费")?> 
				<span id="showinstalltxt_span"  style="display:none;"> <input name='installcharge' id="installcharge"value="0" type='hidden'  ></span>				</td> 
		  </tr>		  
		  <tr > 
		    <td align="right" class="bg" height="30px">* <? echo _("预存金额")?>:</td> 
		    <td align="left" class="bg"  height="30px"><input type="text" name="money" id="money" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="0"> 
		      <? echo _("元")?></td> 
		    </tr> 
		  <tr> 
		    <td align="right" class="bg"height="30px"><? echo _("地址分配")?>:</td> 
		    <td align="left" class="bg"  height="30px"> 
				<input type="radio" name="iptype" value="0" onClick="iptype_change();" checked="checked"><? echo _("BRAS分配")?>&nbsp;&nbsp;  	</td> 
		  </tr> 
		   <tr> 
		    <td align="right" class="bg"><? echo _("固定在线时长")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="radio" name="onlinestatus" value="enabled" onClick="status_change();"><? echo _("启用")?> 
				<input type="radio" name="onlinestatus" value="disable" onClick="status_change();" checked="checked"><? echo _("禁用")?></td> 
		  </tr> 
		  <tr id="statusTR"> 
		    <td align="right" class="bg"><? echo _("在线时长")?>:</td> 
		    <td align="left"  class="bg"><input type="text" name="onlinetime" onBlur="this.className='input_out'"> <? echo _("分")?></td> 
		  </tr>  
		  <tr > 
		    <td align="right" class="bg" height="30px"><? echo _("计时类型")?>:</td> 
		    <td align="left" class="bg" height="30px"> 
				<input type="radio" name="timetype" value="1"  onClick="timetype_change();"><? echo _("立即计时")?> 
				<input type="radio" name="timetype" value="0" checked="checked" onClick="timetype_change();"><? echo _("上线计时")?>			</td> 
		    </tr> 
			<tr id="begindatetimeTR"  style="display:none" > 
		    <td align="right" class="bg" height="30px"><? echo _("开始时间")?>:</td> 
		    <td align="left" class="bg" height="30px"><input type="text" name="begindatetime" onBlur="this.className='input_out'"   onFocus="HS_setDate(this)"> 
		    <? echo _("计时类型如果为“立即计时”，开始时间为空表示从当前时间开始计算")?></td> 
		    </tr> 
		   <tr> 
		    <td align="right" class="bg"  height="30px"><? echo _("允许同时在线人数")?>:</td> 
		    <td align="left" class="bg" height="30px"><input type="text" name="onlinenum" value="1" onFocus="this.className='input_on'" onBlur="this.className='input_out'" >  
		     <? echo _("0或留空表示不限制")?></td> 
		    </tr>
                    <!---------------------------------------2014.02.19添加------------------------------------------------>
                     <tr> 
		    <td align="right" class="bg"><? echo _("MAC 地址")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="text" name="MAC" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="checkbox" name="macbind" value="1" onClick="macbind_change()"> 
				<? echo _("是否绑定MAC地址")?></td> 
		    </tr> 
		  <tr> 
			<td align="right" class="bg"><? echo _("NAS 地址")?>:</td> 
			<td align="left" class="bg"> 
				<input type="text" name="NAS_IP" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="checkbox" name="nasbind" value="1" onClick="nasbind_change()"> 
				<? echo _("是否绑定NAS地址")?></td> 
			</tr>  
		<tr> 
			<td align="right" class="bg"><? echo _("VLAN 地址")?>:</td> 
			<td align="left" class="bg"> 
				<input type="text" name="VLAN" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="checkbox" name="vlanbind" value="1" onClick="VLANbind_change()"> 
				<? echo _("是否绑定VLAN地址")?></td> 
			</tr> 
                        <!----------------------------------------------- 结束-------------------------------------------------------->
  <?
   }else if($_GET['act']=='netbar') {//时长计费
  ?>
  <form action="?act='netbar'" method="post" name="myform"  onSubmit="return checkUserNetbarForm();"  > 
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td> 
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg"> 
		<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
		  <tr> 
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td> 
			<td width="96%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td> 
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
        <td width="89%" class="f-bulue1"> <? echo _("用户添加")?> 
		<input type="radio" name="add" id="once"  value="once"<? if($_REQUEST['act']=='' || $_REQUEST['act']=='once') echo "checked";?> onClick="window.location.href='?act=once'" >  <? echo _("单个添加")?>
		<input type="radio" name="add" id="child"  value="once"<? if($_REQUEST['act']=='child' || $_REQUEST['actchild']=='child') echo "checked";?> onClick="window.location.href='?act=child'" >  <? echo _("子账号添加")?>
	     <!--<input type="radio" name="add" id="more"  value="more" <? if($_REQUEST['act']=='more') echo "checked";?> onClick="window.location.href='?act=more'" >  <? echo _("批量添加")?>
		
		 <input type="radio" name="add" id="netbar"  value="netbar" <? if($_REQUEST['act']=='netbar') echo "checked";?> onClick="window.location.href='?act=netbar'" >  <? echo _("时长计费")?>
		 -->
		 </td>  
		<td width="11%" align="right">&nbsp;</td> 
      </tr> 
	  </table> 
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist"> 
        <tbody>
        	<input type="hidden" value="netbar" name="act" id='act'>  
      <tr> 
		    <td align="right" class="bg">* <? echo _("所属项目")?>:</td> 
		    <td align="left" class="bg"> 
			<?php  
				$sql=" ID in (". $_SESSION["auth_project"].")"; 
				$projectResult=$db->select_all("*","project",$sql); 
				echo "<select name='projectID' id='projectID' onchange=\"ajaxInput('ajax/project.php','projectIDNetbarLimit','projectID','productSelectDIV');ajaxInput('ajax/project.php','poolnameShow','projectID','poolnameSelectDIV');\" >"; 
				echo "<option value=''>"._("选择项目")."</option>"; 
				if(is_array($projectResult)){ 
					foreach($projectResult as $key=>$projectRs){ 
						if($projectRs["ID"]==$projectID){ 
							echo "<option value=".$projectRs["ID"]." selected>".$projectRs["name"]."</option>"; 
						}else{ 
							echo "<option value=".$projectRs["ID"].">".$projectRs["name"]."</option>"; 
						} 
					} 
				} 
				echo "</select>";	
			?> </td> 
		    </tr>    
		  <tr> 
		    <td align="right" class="bg">* <? echo _("证件号码")?>:</td> 
		    <td align="left" class="bg"><input type="text" id="cardid" name="cardid" class="input_out" onFocus="this.className='input_on'" onBlur="this.className='input_out';">		      <? echo _("必须为15位或18位数字或字母")?> </td> 
	    </tr> 
		  <tr id="TR_autoName">   
			<td width="13%" align="right" class="bg">* <? echo _("用户帐号")?>:</td> 
			<td width="87%" align="left" class="bg">
			<span id="userTXT">
			<input type="text" id="account" name="account" onFocus="this.className='input_on'" onBlur="this.className='input_out';ajaxInput('ajax_check.php','check_account','account','accountTXT');" class="input_out"> 
			</span>
			<input type='hidden' name='usercheck' id='usercheck' value=''>
			<input type="button" value="<?php echo _("自动分配账号");?>" onClick="ajaxInput('ajax/project.php','projectIDuser','projectID','userTXT'); "> 
			<input type="button" value="<?php echo _("证件号为账号");?>" onClick="useCardid();ajaxInput('ajax_check.php','check_account','account','accountTXT');">
			  <span id="accountTXT"><? echo _("帐号不能为中文")?></span> 
		 </td> 
		  </tr>  
		  <tr> 
		    <td align="right" class="bg">* <? echo _("默认密码")?>:</td> 
		    <td align="left" class="bg">
			<input type="radio" value="1" name='showPwd'  onClick="showPasswordNetbar(this);" > <? echo _("是")?>
			<input type="radio" value="0" name='showPwd'  checked="checked"  onClick="showPasswordNetbar(this);" > <? echo _("否")?><input type="button" value="证件号后6位"   onClick="cardPwd();" >  
			
			</td> 
		  </tr> 
		  
		   <tr> 
		    <td align="right" class="bg">* <? echo _("用户密码")?>:</td> 
		    <td align="left" class="bg" id="passwordTD"><input type="text" id="password" name="password" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out">
             <? echo _("密码不能为中文")?> </td> 
		  </tr> 
		  <tr id="pwdTXT"> 
		    <td align="right" class="bg">* <? echo _("确认密码")?>:</td> 
		    <td align="left" class="bg" id="pwdTD"><input type="text" id="pwd" name="pwd" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"> </td>         </tr>  
		  <tr> 
		    <td align="right" class="bg"><? echo _("真实姓名")?>: </td> 
		    <td align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'" onBlur="this.className='input_out';" class="input_out"></td> 
		 </tr>
		  <tr> 
		    <td align="right" class="bg"><? echo _("用户性别")?>: </td> 
		    <td align="left" class="bg">
			<input type="radio" name="sex" value="male" checked="checked" ><? echo _("男");?>&nbsp;&nbsp;
			<input type="radio" name="sex" value="female" ><? echo _("女");?>  </td> 
	      </tr> 
		  <tr> 
		    <td align="right" class="bg">* <? echo _("收据单号")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="text" name="receipt" onFocus="this.className='input_on'" onBlur="this.className='input_out'"  value="<?=date("YmdHis",time());?>"> 
				<? echo _("默认为当前时间，请自行修改")?> 
			</td> 
		    </tr>  
	     <tr> 
		    <td align="right" class="bg">* <? echo _("计费类型")?>:</td> 
		    <td align="left" class="bg">  
			  <!-- <input type="radio" name="limittype" value="1" onchange="ajaxInput('ajax/project.php','projectIDNetbar','projectID','productSelectDIV');" onClick="showLimitMoney(this)" ><? echo _("结账下机");?>&nbsp;&nbsp;
			 -->
			 <input type="radio" name="limittype" value="2" checked="checked" onchange="ajaxInput('ajax/project.php','projectIDNetbarLimit','projectID','productSelectDIV');" onClick="showLimitMoney(this)"><? echo _("自动下机");?> 
			  </td>  
			</td> 
		    </tr> 
         <tr> 
		    <td align="right" class="bg" height="30px">* <? echo _("选择产品")?>:</td> 
		    <td align="left" class="bg"  id="productSelectDIV"><select ><option>请选择产品</option></select><? //=productSelected();?><span id='productTXT'></span></td> 
		  </tr> 
		 <tr id='moneyTR' > 
		    <td align="right" class="bg" height="30px">* <? echo _("预存金额")?>:</td> 
		    <td align="left" class="bg"  height="30px">
		    	<input type="text" name="money" id="money" onFocus="this.className='input_on'" onBlur="this.className='input_out'" class="input_out" value="0"> 
		      <? echo _("元")?></td> 
		 </tr>   
		 <tr> 
		    <td align="right" class="bg"><? echo _("地址分配")?>:</td> 
		    <td align="left" class="bg"> 
			  <input type="radio" name="iptype"   onClick="iptype_change();"checked="checked" ><? echo _("BRAS分配")?>&nbsp;&nbsp; 
				<input type="radio" name="iptype"   onClick="iptype_change();ajaxGetIp('ajax/getip.php','projectID','projectID','ipaddress');" ><? echo _("系统分配")?>
				<span id="radio_poolname" > <input type="radio" name="iptype"  onClick="iptype_change();"><?php echo _("地址池分配")?>&nbsp;&nbsp; 
			  </span> 
		   </td>
		</tr> 
		 <tr id="ipaddressTR"> 
		    <td align="right" class="bg"><? echo _("I P 地址")?>:</td> 
		    <td align="left" class="bg"> 
				<input type="text" name="ipaddress" id="ipaddress" onFocus="this.className='input_on'" onBlur="this.className='input_out'" > 
				<input type="button" value="<?php echo _("分配IP")?> " onClick="ajaxGetIp('ajax/getip.php','projectID','projectID','ipaddress');">			</td>
		</tr>  
		 <tr id="poolnameTR"> 
		    <td align="right" class="bg"><? echo _("地址池")?>:</td> 
		    <td align="left" class="bg" id="poolnameSelectDIV"> </td>
			</tr> 
  <?php
   }
 ?>          
          <tr > 
		    <td align="right" class="bg" height="30px"><? echo _("用户等级")?>:</td> 
		    <td align="left" class="bg" height="30px"><?php gradeSelected(); ?></td> 
		 </tr> 
		 <tr > 
		    <td align="right" class="bg" height="30px"><? echo _("用户备注")?>:</td> 
		    <!--<td align="left" class="bg" height="30px"><textarea name="remark" id="remark"cols="50"  rows="5"></textarea></td> --->
                    <td align="left" class="bg" height="30px"><input type="text" name="remark"id="remark" style="width:500px;"/></td>
		 </tr>
		  <tr> 
		    <td align="right" class="bg"  height="30px">&nbsp;</td> 
		    <td align="left" class="bg" height="30px"> 
				<input  type="submit"  value="<?php echo _("提交")?>" onClick="javascript:return window.confirm( '<?php echo _("确认提交")?>？ ');">			</td> 
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
  </form> 
 
<script src="javascript/jsdate.js" type="text/javascript"></script>

<script src="javascript/myform.js" type="text/javascript"></script>

<script language="javascript">

<!--
/*
 function genaccandpwd()
{   var pwds = genpwd_byliupeng(6);
	document.getElementById("account").value=genacc_byliupeng();
	document.getElementById("password").value=pwds;
	document.getElementById("pwd").value=pwds;
	
}
function genacc_byliupeng()
{
	return Math.round(new Date().getTime()/1000).toString()+parseInt(10*Math.random());
}
function genpwd_byliupeng(n)
{
	var rnd="";
	for(var i=0;i<n;i++)
		rnd+=Math.floor(Math.random()*10);
	     return rnd;
}
*/
//window.onLoad=product_type_change();

window.onLoad=iptype_change();

window.onLoad=timetype_change(); 

window.onLoad=status_change();
 
function status_change(){
     var x = document.myform.onlinestatus[0].checked; 
     if(x==true){
	     document.getElementById("statusTR").style.display="";  
	 }else{
	     document.getElementById("statusTR").style.display="none";
	 } 
}
function iptype_change(){ 
	v= document.myform.iptype;    
	 if( v[0].checked){  
		  document.getElementById("ipaddressTR").style.display="none"; 
		  document.getElementById("poolnameTR").style.display="none";  
	 }else if( v[1].checked){  
		  document.getElementById("ipaddressTR").style.display=""; 
		  document.getElementById("poolnameTR").style.display="none"; 
	 }else if( v[2].checked){ 
		  document.getElementById("ipaddressTR").style.display="none"; 
		  document.getElementById("poolnameTR").style.display=""; 
	 } 
}
function timetype_change(){ 
	v=document.myform.timetype;  
	if(v[0].checked==true){ 
		document.getElementById("begindatetimeTR").style.display="";    
	}else if(v[1].checked=true){ 
		document.getElementById("begindatetimeTR").style.display="none";  
	}	

}
function showtype_change(){ 
	v=document.myform.showtype;  
	if(v[0].checked==true){ 
		document.getElementById("showID").style.display="";
                document.getElementById("showID1").style.display="";
                document.getElementById("showID2").style.display="";
                document.getElementById("showID3").style.display="";
                document.getElementById("showID4").style.display="";
	}else if(v[1].checked=true){ 
		document.getElementById("showID").style.display="none";
                document.getElementById("showID1").style.display="none";
                document.getElementById("showID2").style.display="none";
                document.getElementById("showID3").style.display="none";
                document.getElementById("showID4").style.display="none";
	}	

}
function macbind_change(){

	v=document.myform.macbind;

	if(v.checked==false){

		document.myform.MAC.value="";

	}

}

function nasbind_change(){

	v=document.myform.nasbind;

	if(v.checked==false){

		document.myform.NAS_IP.value="";

	}

}
function VLANbind_change(){

	v=document.myform.vlanbind;

	if(v.checked==false){

		document.myform.VLAN.value="";

	}

}

function product_type_change(){

	v=document.myform.type.value;

	if(v=="year"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("年")?></font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";

		document.getElementById("unitpriceTR").style.display="none";

		document.getElementById("cappingTR").style.display="none";

	}else if(v=="month"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("月")?></font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("天")?></font>";

		document.getElementById("unitpriceTR").style.display="none";

		document.getElementById("cappingTR").style.display="none";

	}else if(v=="hour"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("时")?></font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元")?></font>";

		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/时")?></font>";

		document.getElementById("unitpriceTR").style.display="";

		document.getElementById("cappingTR").style.display="";

	}else if(v=="flow"){

		document.getElementById("periodTXT").innerHTML="<font color='#0000ff'><? echo _("M")?></font>";

		document.getElementById("creditlineTXT").innerHTML="<font color='#0000ff'><? echo _("元")?></font>";

		document.getElementById("unitpriceTXT").innerHTML="<font color='#0000ff'><? echo _("元/M")?></font>";

		document.getElementById("unitpriceTR").style.display="";

		document.getElementById("cappingTR").style.display="";

	}

}

function showinstalltxt(){
    var va = document.getElementById("installchargeold").value; 
    var charge_type=document.myform.installcharge_type[0].checked;  
	
	 if(charge_type==false){  //
		document.getElementById("showinstalltxt_span").innerHTML= "<br> <input name='installcharge' id='installcharge'type='text' onBlur='totalMoneys();'   value="+va+">"; 
		document.getElementById("showinstalltxt_span").style.display="";
 
	}else{
	    document.getElementById("showinstalltxt_span").style.display="none"; 
		document.getElementById("showinstalltxt_span").innerHTML= "<br> <input name='installcharge' id='installcharge' type='text'  value='0'>"; 
	
	}  
}
//根据产品单价 * 周期 + 初装费 = totalMoney 
function totalMoneys(){ 
   var charge  = document.getElementById("installcharge").value; 
   var period =  document.getElementById("period").value; 
   var finID = document.getElementById("financeID").value;//缴费类型
   var finmoney = document.getElementById("financemoney").value;//缴费金额
   var productPrice = document.getElementById("productPrice").value; 
   if(finID =="") financemoney_val = 0; else financemoney_val =parseInt(finmoney);  
   if(charge == "")  charge_val = 0; else charge_val = parseInt(charge);
   if(period == "")  period_val = 0; else period_val = parseInt(period);
   if(productPrice == "")  productPrice_val = 0; else productPrice_val = parseInt(productPrice); 
   var totalMoney=productPrice_val * period_val + charge_val + financemoney_val;
   document.getElementById("money").value=totalMoney;  
 }
 
 //根据项目获取用户的默认密码
 function showPassword(v){
  val =v.value; 
  pwd = document.getElementById("userpwd").value;
  if(val==1){//默认密码 
   document.getElementById("passwordTD").innerHTML= "<input type='text' id='password' name='password' value="+pwd+"  ><span id=\"accountTXT\"><? echo _("密码不能为中文")?></span> ";
   document.getElementById("pwdTD").innerHTML= "<input type=\"txt\" id=\"pwd\" name=\"pwd\" value="+pwd+" >"; 
  }else{
   document.getElementById("passwordTD").innerHTML= "<input type='password' id='password' name='password'    ><span id=\"accountTXT\"><? echo _("密码不能为中文")?></span> ";
   document.getElementById("pwdTD").innerHTML= "<input type=\"password\" id=\"pwd\" name=\"pwd\"  >"; 
  } 
 }
 
 //根据项目获取用户的默认密码
 function showPasswordNetbar(v){
  val =v.value; 
  pwd = document.getElementById("userpwd").value;
  if(val==1){//默认密码 
   document.getElementById("password").value= pwd;
   document.getElementById("pwd").value= pwd; 
  }else{
   document.getElementById("password").value= '';
   document.getElementById("pwd").value= '' 
  } 
 }
 //证件号为用户账号。。针对时长计费即网吧酒店计费
 function useCardid(){ 
  cid = document.getElementById("cardid").value;
  if(cid=='')  alert("<? echo _("证件号不能为空");?>"); 
  else  document.getElementById("account").value=cid; 
  //ajaxInput('ajax_check.php','check_account','account','accountTXT');
 }
 //证件号后6位为密码
 function cardPwd(){
  cid = document.getElementById('cardid').value;
  len = cid.length;
  star = len-6; 
  if(len>=6) val = cid.substring(star, len);
  else val = cid; 
  document.getElementById("password").value=val;
  document.getElementById("pwd").value=val;  
 }
 //根据所选择的计费类型设置金额值
 function showLimitMoney(v){ 
  if(v.value==1){//结账下机 
   document.getElementById("moneyTR").style.display="none"; 
   document.getElementById("money").value="0";
  }else{//预存自动下机 
   document.getElementById("moneyTR").style.display=""; 
  }
 }
-->

</script>
<!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        用户管理-> <strong>添加用户</strong>
      </p>
      <ul>
          <li>对用户进行开户处理，选择套餐，填写用户资料开户。</li>
          <li>可以在初装费用参数下设置初装费，开户时选择是否要初装费。</li>
          <li>*号选项为必填选项。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>

</html>



