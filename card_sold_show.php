#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php"); 
date_default_timezone_set('Asia/Shanghai');
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("卡片管理")?></title>
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
</head>
<body>
<?php   
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$action          =$_REQUEST["action"]; 
$managerName     =$_REQUEST["managerName"];
$managerAccount  =$_REQUEST["managerAccount"];
  
$querystring= "startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&action=".$action."&managerName=".$managerName."managerAccount=".$managerAccount;
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle" align="left">
			<font color="#FFFFFF" size="2"><? echo _("已售卡片预览")?></font>
			    </td>
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
	<?php  /*
	        项目财务报表
	       */ 
		$startDateTime   =$_REQUEST["startDateTime"];
		$endDateTime     =$_REQUEST["endDateTime"]; 
	?>	
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd sosoProject" >
      <tr>
        <td width="14%" class="f-bulue1 title_bg2" align="left"><? echo _("条件搜索")?></td> 
		<td width="86%" align="left " class="title_bg2" colspan="3" >&nbsp;</td> 
      </tr>
	  
	<form action="?action=projectShow" name="myform" method="post"  >  
	  <tr>
	    <td align="right"><? echo _("管理员账号：")?></td>
	    <td><input type="text" name="managerAccount" value="<?=$managerAccount?>"  ></td> 
	    <td align="right"><? echo _("开始时间：")?></td>
	    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)"></td>
	    </tr>
	  <tr>
	    <td align="right"><? echo _("管理员姓名：")?></td>
	    <td><input type="text" name="managerName" value="<?=$managerName?>"  ></td>
	    <td align="right"><? echo _("结束时间")?></td>
	    <td><input type="text" name="endDateTime" value="<?=$endDateTime?>" onFocus="HS_setDate(this)"></td>
	    </tr> 
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<? echo _("提交"); ?>"></td> 
		  <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td> 
	  </tr>
	  </table>
	</form> 
	<?php
	  $sql =' sold =1 ';
	  if($startDateTime){
	     $sql .= " and soldTime >= '".$startDateTime."'" ;
	  }if($endDateTime){
	     $sql .= " and soldTime <= '".$endDateTime."'";
	  }
	   
	  $cardTotalMoney = $db->select_one("sum(money) as totalMoney","card",$sql);
	  $cardInfo = $db->select_all("*","card",$sql);
	 
	?>
	
	
	
	
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd credit_table" >
      <tr>
        <td width="70%" class="f-bulue1"><? echo _("已售卡片预览")?></td>
		<td width="30%" align="right">
		<?php /*?><? echo _("管理员账号")?>(<a href="?action=managerASC"><? echo _("升序")?></a> / <a href="?action=managerDESC"><? echo _("降序")?></a>) ||
		<? echo _("管理员姓名")?>(<a href="?action=managerNameASC"><? echo _("升序")?></a> / <a href="?action=managerNameDESC"><? echo _("降序")?></a>) 
		<?php */?>  
      </tr>
	  </table>
  	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1 credit_table" id="myTable"  >     
        <tbody> 
		  <tr>
		    <td width="20%" align="right" class="bg"><? echo _("总共金额:")?></td> 
			<td width="80%" align="left" class="bg" colspan="3" style="color:blue">&nbsp;<?=$cardTotalMoney["totalMoney"]?>
			<?
			   $c   =new ChineseNumber();
				echo _("   (大写金额:");
				echo $c->ParseNumber($cardTotalMoney["totalMoney"]);
				echo ")";
				?>
			</td> 
		  </tr> 
        </tbody>      
    </table>
 <?php   
   $sqlmanager = "";
 // if($action == "managerASC")
//	 $sqlmanager .=" order by  manager_account ASC ";
//  if($action == "managerDESC")
//	 $sqlmanager .=" order by  manager_account DESC ";
//  if($action == "managerNameASC")
//	 $sqlmanager .=" order by  manager_name ASC ";
//  if($action == "managerNameDESC") 
//   $sqlmanager .=" order by  manager_name DESC "; 
//  
  if($managerAccount && $managerName){
       $sqlmanager .=" manager_name='".mysql_real_escape_string($managerName)."' and manager_account = '".mysql_real_escape_string($managerAccount)."' ";
	    
  }else{
	   if($managerAccount){
			 $sqlmanager .=" manager_account = '".mysql_real_escape_string($managerAccount)."'";
	   }else if($managerName){
			 $sqlmanager .=" manager_name='".mysql_real_escape_string($managerName)."'";
	  }   
  }
	 
   $managers=$db->select_all('*','manager', $sqlmanager);
 foreach($managers  as $mangAccount){  
	    $ManagerToalMoney = $db->select_one("sum(money) as onesTotalMoney","card",$sql." and solder='".$mangAccount['manager_account']."'");
		 $countNumber = $db->select_one("count(*) as num","card",$sql." and solder='".$mangAccount['manager_account']."'");
    	 
       ?>  
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1 pjtable" >     
        <tbody>   
	    <tr>
		    <td width="20%" align="right" class="bg"><? echo _("管理员:")?></td> 
			<td width="30%" align="left" class="bg" colspan="2">&nbsp;<?=$mangAccount['manager_account']?></td>
	        <td width="10%" align="right" class="bg"><? echo _("管理员姓名:")?></td>
	        <td width="40%" align="left" class="bg">&nbsp;<?=$mangAccount["manager_name"]?></td>
		  </tr>
		  <tr>
		    <td align="right" class="bg"><? echo _("销售卡片总金额:")?></td>
			<td   align="left" class="bg" colspan="2" style="color:red">&nbsp;
				<?php 
			   if(is_null($ManagerToalMoney['onesTotalMoney'])) echo "0"; else echo $ManagerToalMoney['onesTotalMoney'];
				$c   =new ChineseNumber();
				echo _("   (大写金额:");
				echo $c->ParseNumber($ManagerToalMoney['onesTotalMoney']);
				echo ")";
				?> 
			</td>
			 <td align="right" class="bg"><? echo _("销售卡片总数:")?> </td>
			 <td align="left" class="bg">&nbsp;<?=$countNumber['num']?></td>
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
        卡片管理-> <strong>销售预览</strong>
      </p>
      <ul>
          <li>管理员可以对销售的卡片信息进行查看。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html> 