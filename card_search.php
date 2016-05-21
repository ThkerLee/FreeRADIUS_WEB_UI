#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
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
            WindowTitle:          '<b>卡片管理</b>',
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
$cardNumber      =$_REQUEST["cardNumber"];
$startDateTime   =$_REQUEST["startDateTime"];
$startDateTime1  =$_REQUEST["startDateTime1"];
$endDateTime     =$_REQUEST["endDateTime"];
$endDateTime1    =$_REQUEST["endDateTime1"];
$operator		 =$_REQUEST["operator"];
$sold			 =$_REQUEST["sold"];
$solder          =$_REQUEST["solder"];
$recharge		 =$_REQUEST["recharge"];
$querystring     ="cardNumber=".$cardNumber."&startDateTime=".$startDateTime."&startDateTime1=".$startDateTime1."&endDateTime=".$endDateTime."&endDateTime1=".$endDateTime1."&operator=".$operator."&sold=".$sold."&recharge=".$recharge."&solder=".$solder ;
$usernums = $db->select_count("cardlog",''); 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("卡片管理")?></font></td>
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
        <td width="12%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
		<td width="88%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
	  	<td align="right"><? echo _("充值卡号")?>:</td>
		<td><input type="text" name="cardNumber" value="<?=$cardNumber?>"></td>
	  </tr>
	  
	  <tr>
	    <td align="right"><? echo _("生成时间")?>:</td>
	    <td>
		<input type="text" name="startDateTime" onFocus="HS_setDate(this)"value="<?=$startDateTime?>">
	    <? echo _("至")?>
		<input type="text" name="startDateTime1" onFocus="HS_setDate(this)" value="<?=$startDateTime1?>">		 </td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("失效时间")?>:</td>
	    <td>
		<input type="text" name="endDateTime" onFocus="HS_setDate(this)" value="<?=$endDateTime?>">
		<? echo _("至")?>
		<input type="text" name="endDateTime1" onFocus="HS_setDate(this)" value="<?=$endDateTime1?>">		</td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("是否销售")?>:</td>
	    <td>
			<select name="sold">
				<option value="type"><? echo _("选择类型")?></option>
				<option value="0"<? if($sold=='0') echo  "selected=\"selected\"";?>><? echo _("未销售")?></option>
				<option value="1"<? if($sold=='1') echo  "selected=\"selected\"";?>><? echo _("已销售")?></option>
			</select>		</td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("是否充值")?>: </td>
	    <td>
			<select name="recharge" >
				<option value="type"><? echo _("选择类型")?></option>
				<option value="0" <? if($recharge=='0') echo  "selected=\"selected\"";?>><? echo _("未充值")?></option>
				<option value="1"<? if($recharge=='1') echo  "selected=\"selected\"";?>><? echo _("已充值")?></option>
			</select>		</td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("制 卡 员:")?></td>
	    <td><?php managerSelect($operator)?></td>
	    </tr>
	  <tr>
	  <tr>
	    <td align="right"><? echo _("销售人员")?>：</td>
	    <td><?php managerSold($solder)?></td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>">
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <? if($usernums>0){  
		 ?>
			   <a href="PHPExcel/excel_card.php?<?=$querystring?>" style="color:#FF3300;" >EXCEL导出</a>
		 <?
		   }
		 ?> 
		 </td>
	    </tr>
	  </table>
	</form>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("卡片销售")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
	  <form action="card_del.php" name="myform1" method="post">
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="myTable">
        <thead>
              <tr>
                <th width="4%" align="center" class="bg f-12"><input type="checkbox" name="allID" title="<? echo _("全选")?>" onClick="change_allID();"></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("卡号")?></th>
                <th width="7%" align="center" class="bg f-12"><? echo _("金额（元）")?></th>
                <th width="6%" align="center" class="bg f-12"><? echo _("是否销售")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("是否充值")?></th>
                <th width="12%" align="center" class="bg f-12"><? echo _("制卡时间")?></th>
                <th width="11%" align="center" class="bg f-12"><? echo _("失效时间")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("制卡员")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("销售员")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("购买账号")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("充值人员")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("操作")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql=" 0=0 "; 
if($cardNumber){
	$sql .=" and cardNumber like '%".mysql_real_escape_string($cardNumber)."%'";
}
if($startDateTime){
	$sql .=" and cardAddTime>='".$startDateTime."'";
}
if($startDateTime1){
	$sql .=" and cardAddTime<'".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and ivalidTime>='".$endDateTime."'";
}
if($endDateTime1){
	$sql .=" and ivalidTime<'".$endDateTime1."'";
}
if($operator){
	$sql .=" and operator='".$operator."'";
}
if($solder){
     $sql .=" and solder='".$solder."'";
}
if($sold!="type" && isset($sold)){
	$sql .=" and sold='".$sold."'";
}
if($recharge!="type" && isset($recharge)){
	$sql .=" and recharge='".$recharge."'";
}
$sql .="order by ID desc";
$result=$db->select_all("*","card",$sql,20);
	if(is_array($result)){  
		foreach($result as $key=>$rs){
		$cardLogRs=$db->select_one("UserName","cardlog","cardNumber='".$rs["cardNumber"]."'");
		
		$sold=($rs["sold"]==1)?"<font color='#009900'>"._("售出")."</font>":"<font color='#666666'>"._("未售出")."</font>";
		$recharge=($rs["recharge"]==1)?"<font color='#009900'>"._("己充值")."</font>":"<font color='#666666'>"._("未充值")."</font>";
		$solder=($rs["solder"]=="NULL")?_("无"):$rs["solder"];
		$cardUserName=$cardLogRs["UserName"];
?>   
		  <tr>
		    <td align="center" class="bg"><input type="checkbox" name="ID[]" value="<?=$rs['ID'];?>"></td>
			<td align="center" class="bg"><?=$rs['cardNumber'];?></td>
			<td align="center" class="bg"><?=$rs["money"]?></td>
			<td align="center" class="bg"><?=$sold?></td>
			<td align="center" class="bg"><?=$recharge?></td>
			<td align="center" class="bg"><?=$rs["cardAddTime"]?></td>
			<td align="center" class="bg"><?=$rs['ivalidTime'];?></td>
			<td align="center" class="bg"><?=$rs['operator'];?></td> 
			<td align="center" class="bg"><?=$solder?></td>
			<td align="center" class="bg"><?=$rs['UserName'];?></td>
		    <td align="center" class="bg"><?=$cardUserName?></td>
		    <td align="center" class="bg"><a href="#" onClick="javascript:window.open('card_sold_print.php?ID=<?=$rs["ID"]?>','newname','height=400,width=700,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no,status=no,top=100,left=300')"><? echo _("打印")?></a></td>
		  </tr>
<?php  }} ?>
        </tbody>    
		<tbody>
		  <tr>
			<th colspan="12" align="left" class="bg f-12">
				<input type="submit" value="<? echo _("批量删除")?>">			</th>
		  </tr>
	</tbody>  
    </table>
	  </form>
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
        卡片管理-> <strong>卡片查询</strong>
      </p>
      <ul>
          <li>可以对售出的充值卡进行查询、打印、删除等操作。</li>
      </ul>

    </div>
<!---------------------------------------------->
<script language="javascript">
<!--
function change_allID(){
	ide=document.myform1.allID.checked;
	div=document.getElementById("myTable").getElementsByTagName("input");
	for(i=0;i<div.length;i++){
		div[i].checked=ide;
	}
}
-->
</script>
</body>
</html>

