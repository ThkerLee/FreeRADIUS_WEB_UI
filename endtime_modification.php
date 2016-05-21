#!/bin/php
<?php 
include("inc/conn.php");

include_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>批量增加到期时间</title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>
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
	<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
                        <td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">营帐管理</font></td>
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


<?php
if($_POST){
//$action         =$_REQUEST["action"];
$areaID          =$_REQUEST["areaID"];
$projectID       =explode(",",$_POST["areaprojectID"]);
$projectID       =$projectID[1]; 
$productID       =$_REQUEST["productID"];  
$endtime         =$_REQUEST["endtime"]; 
$nowTime  = date("Y-m-d H:i:s",time());

$sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and (r.enddatetime>'$nowTime' or r.enddatetime = '0000-00-00 00:00:00'  )and r.userID=u.ID and r.orderID=o.ID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].") ";
if($areaID){
   $sql .=" and u.areaID=".$areaID;
}

if($projectID){
	$sql .=" and u.projectID='".$projectID."'";
}
if($productID){
	$sql .=" and o.productID='".$productID."'";
}



$sql .=" and r.begindatetime !='0000-00-00 00:00:00' ";

if(($areaID ||  $projectID || $productID) && $endtime ){
$result=$db->select_all("DISTINCT r.userID","userinfo as u,userattribute as a,orderinfo as o,product as p,userrun as r",$sql,"");
if($result){
foreach ($result as $rs) {
    $userID=$rs["userID"];
    $time=$db->select_one("enddatetime,orderID","userrun","userID='".$userID."' and enddatetime != '0000-00-00 00:00:00' order by ID desc limit 0,1 ");//查询结束时间
              $orderID=$time["orderID"];
            $oldEndtime=$time["enddatetime"];
            $newEndTime= date("Y-m-d H:i:s",strtotime("$oldEndtime + $endtime  day"));//重新计算结束时间
     $db->update_new("userrun","orderID='".$orderID."'",array("enddatetime"=>$newEndTime));
     addUserLogInfo($userID,2,"批量增加到期时间从".$oldEndtime."改为".$newEndTime."",getName($userID));//操作日志
}



echo "<script> alert('"._("修改成功")."');</script>";
    }  else {
    echo "<script> alert('"._("该区域、 项目或产品下没有在网用户")."');</script>";    
    }
    
}  else {
echo "<script> alert('"._("区域、天数不能为空")."');</script>";    
}

}


?>
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
              <? echo _("批量增加到期时间")?>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <form action="" name="myform" method="post">
       <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
      <tr>
        <td width="9%" align="left"  class="bg">选择</td>
        <td align="left"  class="bg">
            所属区域:<?php selectArea($areaID);?>&nbsp;&nbsp;&nbsp;所属项目:<span id="projectSelectDIV"><select><option>选择项目</option></select></span>&nbsp;&nbsp;&nbsp;用户产品:<span id="productSelectDIV"><select ><option>请选择产品</option></select></span>&nbsp;&nbsp; 可进行的修改方式有以下三种 1、区域+天数 2、区域+项目+天数 3、区域+项目+产品+天数
  
          <!--  <input type="submit" value="提交">  --->
        </td>
      </tr>
	  </table>  
     <!--   </form>  -->
        
      
       <!-- <form action="?action=end&areaID=<?php $areaID ;?> " method="post" name="myform"  >--->
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>

        <tr style="display: none">
		    <td width="9%" align="left" class="bg">当前在网用户</td>
		    <td  align="left" class="bg">
                       &nbsp;&nbsp;<?php if(empty($num)){echo "0";}  else {echo $num;}?>人</td>
		 </tr> 
                 <tr >
		    <td width="9%" align="left" class="bg">增加天数</td>
		    <td align="left" class="bg">
				<input name="endtime" type="text"  value="" size="35"> 提示：天数必须填</td>
		 </tr> 
		  <tr>
		      <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="批量修改" onclick="return confirm('确定修改此区域、项目或产品下所有在网用户的到期时间?')"></td>
		  </tr> 
     </tbody> 
  </table>	
     </form>  
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
        营帐管理-> <strong>批量增加到期时间</strong>
      </p>
      <ul>
          <li>可增加某区域、项目、产品下在网用户的到期时间。</li>
          <li>修改方式有三种：1、区域+天数 2、区域+项目+天数 3、区域+项目+产品+天数 。</li>
          <li>需要增加的天数不能为空或为负数。</li>
      </ul>

    </div>
<!---------------------------------------------->

</body>
</html>

