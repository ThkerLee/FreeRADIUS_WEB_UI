#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php");  
include("inc/loaduser.php");
date_default_timezone_set('Asia/Shanghai'); 
?>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><? echo _("运营管理")?></title>

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

$operator		 =$_POST["operator"];

$productID		 =$_REQUEST["productID"];

$querystring="UserName=".$UserName."&name=".$name."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&startDateTime1=".$startDateTime1."&endDateTime1=".$endDateTime1."&operator=".$operator."&productID=".$productID."";

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

	  	<td align="right"><? echo _("用户帐号")?>：</td>

		<td><input type="text" name="UserName" value="<?=$UserName?>"></td>

	  </tr>

	  

	  <tr>

	    <td align="right"><? echo _("使用产品")?>：</td>

	    <td><?=productSelected($productID);?></td>

	    </tr>

	  <tr>

	    <td align="right"><? echo _("开始时间")?>：</td>

	    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)">

	    -

	      <input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>

	    </tr>

	  <tr>

	    <td align="right"><? echo _("结束时间")?>：</td>

	    <td><input type="text" name="startDateTime1" value="<?=$startDateTime1?>" onFocus="HS_setDate(this)">

	    -

	      <input type="text" name="endDateTime1" value="<?=$endDateTime1?>" onFocus="HS_setDate(this)"></td>

	  </tr>

	  <tr>

	    <td align="right"><? echo _("操作人员")?>：</td>

	    <td><?php managerSelect($_POST["operator"]) ?></td>

	    </tr>

	  <tr>

	    <td align="right">&nbsp;</td>

	    <td>&nbsp;</td>

	    </tr>

	  <tr>

	    <td align="right">&nbsp;</td>

	    <td><input type="submit" value="<? echo _("提交")?>"></td>

	    </tr>

	  </table>

	</form>

	  <br>

	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">

      <tr>

        <td width="89%" class="f-bulue1"><? echo _("流量监控")?></td>

		<td width="11%" align="right">&nbsp;</td>

      </tr>

	  </table>

  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">

         <thead>

              <tr>

			    <th width="4%" align="center" class="bg f-12"><? echo _("编号")?></th>

                <th width="6%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>

                <th width="6%" align="center" class="bg f-12"><? echo _("用户姓名")?></th>

                <th width="8%" align="center" class="bg f-12"><? echo _("所属项目")?></th>

                <th width="8%" align="center" class="bg f-12"><? echo _("使用产品")?></th>               

                <th width="8%" align="center" class="bg f-12"><? echo _("开始时间")?></th>

                <th width="8%" align="center" class="bg f-12"><? echo _("到期时间")?></th>

                <th width="7%" align="center" class="bg f-12"><? echo _("流量值")?></th>

				<th width="6%" align="center" class="bg f-12"><? echo _("产品费率")?></th>

                <th width="6%" align="center" class="bg f-12"><? echo _("已用流量")?></th>

				<th width="6%" align="center" class="bg f-12"><? echo _("剩余流量")?></th>

				<th width="6%" align="center" class="bg f-12"><? echo _("封顶金额")?></th>

				<th width="6%" align="center" class="bg f-12"><? echo _("已用金额")?></th>

				<th width="6%" align="center" class="bg f-12"><? echo _("剩余金额")?></th> 

			  </tr>

        </thead>     

        <tbody>  

<?php

     

$sql="o.userID=u.ID and o.ID=r.orderID and p.ID=o.productID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";

if($UserName){

	$sql .=" and u.UserName like '%".mysql_real_escape_string($UserName)."%'";

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
	$sql .=" and  p.`type`='flow' ";// and p.`type`in ('flow','hour')

$result=$db->select_all(" o.*,u.ID as UID ,u.UserName,u.account,u.projectID,o.ID as orderID,r.*,o.adddatetime as o_adddatetime,o.status as order_status,p.period ,p.price,p.capping,p.name as product_name,p.type as Ttype","orderinfo as o,userinfo as u,userrun as r,product as p",$sql,20);

	if(is_array($result)){

		foreach($result as $key=>$rs){

		//$status = $rs["status"];

//		if($status=='0'){

//			$status_str="<a href='order_del.php?ID=".$rs["orderID"]."'>撤消</a>";

//		}else{

//			$status_str="-----";

//		} <br>



   

		$tTotalF=$db->select_one("sum(stats) as useValue,`limit`","runinfo","userID='".$rs["UID"]."' and orderID='".$rs["orderID"]."'");

		$tTotalP=$db->select_one("sum(price) as totalValue","runinfo","userID='".$rs["UID"]."' and orderID='".$rs["orderID"]."'");

		$tBalance=$db->select_one("balance","userrun","userID='".$rs["UID"]."' and orderID='".$rs["orderID"]."'"); //用户余额

		if(!$tTotalF['useValue']){ //已用流量

		   $flowTotal=0; 
		   $Emoney=0; 
		   $SYmoney=$rs['price'];

		} 
		
		
		if(!$tTotalP['totalValue']){

			$tTotalP['totalValue']=0;

		}

		if($rs['period']=='0'){

		    $unitPrive=0;

		}else{

		  $unitPrive=$rs['price']/$rs['period']; 

		}

		

		$unitPrive=number_format($unitPrive, 2, '.', '');    

		$rs['period']=$rs['period']*1024 ;  //kb

		$flowTotal=$tTotalF['useValue']/1024;///1024;//使用总流量值 

		$flowTotalsy=$rs['period'] - $flowTotal;     //剩余的流量 

		if($flowTotalsy<0){

			$flowTotalsy=0;

		}  

       if($rs['Ttype']=="flow" ||$rs['Ttype']=="hour"  ){
			if(is_null($tBalance['balance']) ){//为计算  ，即已用金额为0，剩余金额为产品金额
	
			   $Emoney=0; 
			   $SYmoney=$rs['price'];
	
			}elseif($tBalance['balance']=='0'){
			
			   $Emoney=$rs['price']; 
			   $SYmoney=0;
			
			} 
			 /*elseif(number_format($tBalance['balance'], 2, '.', '')==number_format($rs['price'], 2, '.', '')){
			
			   $Emoney=$rs['price']; 
			   $SYmoney=0;
			
			}*/else{
	
				$Emoney=number_format($rs['price']-$tBalance['balance'], 2, '.', '');//消费
	
				$SYmoney=number_format($tBalance['balance'], 2, '.', '');   //剩余
	
			}
       }else{ 
	      $Emoney=$rs['price'];
	      $SYmoney=0;
		 
	   }
	   
	   
		 $rs['price']=(float)$rs['price'];  

		 if($rs['begindatetime']!='0000-00-00 00:00:00'){

		   $beginTime=date("Y-m-d",strtotime($rs['begindatetime']));

		 }else{

		   $beginTime='0000-00-00';

		 }if($rs['enddatetime']!='0000-00-00 00:00:00'){

		   $endTime =date("Y-m-d",strtotime($rs['enddatetime']));

		 }else{

		   $endTime ='0000-00-00';

		 }

		if(!$tTotalF['useValue']){ //已用流量
 
		   $Emoney=0; 
		   $SYmoney=$rs['price'];

		} 

		 

			 

		 

?>   



	<tr>

		    <td align="center" class="bg"><?=$rs['orderID'];?></td>

			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>

			<td align="center" class="bg"><?=$rs["UserName"]?></td>

			<td align="center" class="bg"><?=projectShow($rs["projectID"])?></td>

			<td align="center" class="bg"><?=$rs['product_name'];?></td>

			<td align="center" class="bg"><?=$beginTime?> </td>

			<td align="center" class="bg"><?=$endTime?></td>

			<td align="center" class="bg"><?php if($rs['Ttype']=='flow') echo flowUnit($rs['period'],'kb'); else echo _("非流量产品");?> </td>

			<td align="center" class="bg"><?php if($rs['Ttype']=='flow') echo $unitPrive._("元/M"); else echo "/"; ?> </td> 

			<td align="center" class="bg"><?php if($rs['Ttype']=='flow') echo flowUnit($flowTotal,'kb'); else echo "/";?></td>

			<td align="center" class="bg"><?php if($rs['Ttype']=='flow') echo flowUnit($flowTotalsy,'kb');else echo "/"; ?></td>

			<td align="center" class="bg"><?=$rs['capping']?><? echo _("元")?> </td>

			<td align="center" class="bg"><?=$Emoney?><? echo _("元")?> </td>

			<td align="center" class="bg"><?=$SYmoney //=//$bTotalP['balanceValue'];//$priceTotalsy ?><? echo _("元")?> </td>

		  </tr> 

 

<?php  }} ?>



		  <tr>

		    <td colspan="14" align="center" class="bg">

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
        营帐管理-> <strong>流量监控</strong>
      </p>
      <ul>
          <li>对包流量用户进行上网流量监控。</li>
          <li>两分钟生成一次流量使用记录及相应的帐务记录，方便管理员查询。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>

</html>



