#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php");  
include("inc/loaduser.php");
?>
<html>
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
<?php    
$UserName        =$_REQUEST["UserName"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$operator		     =$_REQUEST["operator"];
$productID		   =$_REQUEST["productID"];
$findOrderStatus =$_REQUEST["status"];
$querystring="UserName=".$UserName."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&startDateTime1=".$startDateTime1."&endDateTime1=".$endDateTime1."&operator=".$operator."&productID=".$productID."&findOrderStatus=".$findOrderStatus.""; 

$sql="o.userID=u.ID and o.ID=r.orderID and p.ID=o.productID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($UserName){
	$sql .=" and u.UserName ='".mysql_real_escape_string($UserName)."'";
}
if($startDateTime){
	$sql .=" and o.adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and o.adddatetime<'".$endDateTime."'";
}
if($operator){
	$sql .=" and o.operator='".$operator."'";
}
if($productID){
	$sql .=" and p.ID='".$productID."'";
}
$orderNums=$db->select_count("orderinfo as o,userinfo as u,userrun as r,product as p",$sql);
 
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("营帐管理")?></font></td>
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
	  	<td align="right"><? echo _("用户帐号")?>:</td>
		<td><input type="text" name="UserName" value="<?=$UserName?>"></td>
	  </tr>
	  
	  <tr>
	    <td align="right"><? echo _("使用产品")?>:</td>
	    <td><?=productSelected($productID);?></td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("开始时间")?>:</td>
	    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)">
	    -
	      <input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("结束时间")?>:</td>
	    <td><input type="text" name="startDateTime1" value="<?=$startDateTime1?>" onFocus="HS_setDate(this)">
	    -
	      <input type="text" name="endDateTime1" value="<?=$endDateTime1?>" onFocus="HS_setDate(this)"></td>
	  </tr>
	   <tr>
	    <td align="right"><? echo _("订单状态")?>:</td>
	    <td>
		  <select name="status">
			<option value="0" selected="selected"><? echo _("订单状态");?></option>
			<option value="1" <? if($findOrderStatus==1) echo "selected='selected'"?> ><? echo _("正在运行");?></option>
		    <option value="wait" <? if($findOrderStatus=="wait") echo "selected='selected'"?> ><? echo _("等待运行");?></option>
			<option value="2" <? if($findOrderStatus==2) echo "selected='selected'"?> ><? echo _("到期使用");?></option>
			<option value="5" <? if($findOrderStatus==5) echo "selected='selected'"?> ><? echo _("暂停");?></option>
			<option value="4" <? if($findOrderStatus==4) echo "selected='selected'"?> ><? echo _("完成");?></option> 
		 </select>
		</td>
	  </tr>
	  <tr>
	    <td align="right"><? echo _("操作人员")?>:</td>
	    <td><?php managerSelect($operator) ?></td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交")?>"></td>
	    </tr>
	  <tr>
	    <td align="center" colspan="2">
		<? if( $orderNums>0) { ?>  <a href="PHPExcel/excel_orderinfo.php?<?=$querystring?>" style="color:#FF3300;" ><? echo _("EXCEL导出") ; } ?></a>  
		 </td>
	    </tr>
	  </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? echo _("系统全部订单列表")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="7%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="7%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="17%" align="center" class="bg f-12"><? echo _("所选择产品")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("操作员")?></th>
                <th width="17%" align="center" class="bg f-12"><? echo _("开始时间")?></th>
                <th width="13%" align="center" class="bg f-12"><? echo _("结束时间")?></th>
                <th width="13%" align="center" class="bg f-12"><? echo _("操作时间")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("当前状态")?></th>
                <th width="6%" align="center" class="bg f-12"><? echo _("操作")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php 
$sql="o.userID=u.ID and o.ID=r.orderID and p.ID=o.productID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($UserName){
	$sql .=" and u.UserName = '".$UserName."'";
}
if($startDateTime){
	$sql .=" and o.adddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and o.adddatetime<'".$endDateTime."'";
}
if($operator){
	$sql .=" and o.operator='".$operator."'";
}
if($productID){
	$sql .=" and p.ID='".$productID."'";
}
if($findOrderStatus){
 if($findOrderStatus=="wait") 
    $findOrderStatus = 0;
    $sql .= " and o.status = '".$findOrderStatus."'";
} 
$result=$db->select_all("o.*,u.*,o.ID as orderID,r.*,o.adddatetime as o_adddatetime,o.status as order_status","orderinfo as o,userinfo as u,userrun as r,product as p",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
		$status = $rs["status"];
		if($status=='0'){
			$status_str="<a href='order_del.php?ID=".$rs["orderID"]."'>"._("撤消")."</a>";
		}else{
			$status_str="-----";
		}
		
		
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['orderID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=productShow($rs["productID"])?></td>
			<td align="center" class="bg"><?=$rs["operator"]?></td>
			<td align="center" class="bg"><?=$rs["begindatetime"]?></td>
			<td align="center" class="bg"><?=$rs["enddatetime"]?></td>
			<td align="center" class="bg"><?=$rs["o_adddatetime"]?></td>
			<td align="center" class="bg"><?=orderStatus($rs["order_status"])?></td>
		    <td align="center" class="bg"><?=$status_str;?></td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="9" align="center" class="bg">
				<?php $db->page($querystring); ?></td>
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
        营帐管理-> <strong>订单记录</strong>
      </p>
      <ul>
          <li>查询用户的订单状态和订单记录，包括已经完成订单、正在运行订单和等待运行订单。</li>
          <li>对等待运行订单，管理员可以进行撤消并将相应金额退还给用户。</li>
          <li>正在使用：表示此订单正在运行，所有的计费及产品功能都在执行中，此状态不可更改。</li>
          <li>等待运行： 当前订单已经录入， 等待运行中， 通常是时间还未到， 或是该用户的上一个订单未执行完成，此种状态可撤消，撤消后，续费记录也同时撤消。</li>
          <li>完成： 表示当前订单已经执行完成， 如没有后续订单， 则表示此用户已停机， 此状态不可更改。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

