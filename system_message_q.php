#!/bin/php
<?php 
include("inc/conn.php");

include_once("evn.php"); 
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>短信设置</title>
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
            WindowTitle:          '<b>短信管理</b>',
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
                        <td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">短信设置</font></td>
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
    $areaID =$_REQUEST["areaID"];
 $projectID =$_REQUEST["projectID"];
 $productID =$_REQUEST["productID"];
   $rs=$db->select_one("*","message","type = 1 ");
   $sql=" u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and  u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
 if($areaID)$sql .=" and u.areaID='".$areaID."'";  
 if($projectID)$sql .=" and u.projectID='".$projectID."'";
if($productID)$sql .=" and o.productID='".$productID."'";  

if($areaID || $projectID || $productID){
    $result=$db->select_all("u.mobile","userinfo as u,userattribute as a,orderinfo as o,product as p ",$sql,"");
    $num=  count($result);
  foreach($result as $r){
  $m.= $r['mobile'].","; 
} 
$mobile=substr($m,0, -1);
}

?>
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"> 
              <? echo _("群发短信")?>
		   </td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table> 
   <form action="" name="myform" method="post">
       <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
      <tr>
        <td width="9%" align="left"  class="bg">选择发送对象</td>
        <td align="left"  class="bg">
      所属区域:<?php selectArea($areaID);?>&nbsp;&nbsp;&nbsp;所属项目:<span id="projectSelectDIV"><select><option>选择项目</option></select></span>&nbsp;&nbsp;&nbsp;用户产品:<span id="productSelectDIV"><select ><option>请选择产品</option></select></span>
             <?php
           /*  $areaResult         =$db->select_all("ID,name","area",""); 
             if(is_array($areaResult)){ 
                            $i=0;//选中全部时用
					foreach($areaResult as $key=>$rs){ 
                                                        $i++;
							echo "<input type='checkbox' name='areaID' ";
							if(in_array($rs["ID"],$manager_areaArr)) echo " checked "; 
							echo "value='".$rs["ID"]."' id='ckall_".$i."' onclick=\"checkEvent('ck_".$i."','ckall_".$i."')\"> "._($rs["name"])." &nbsp;";
							$projectRs =$db->select_all("p.ID,p.name","areaandproject as ap,project as p","ap.areaID ='".$rs['ID']."' and p.ID=ap.projectID");
						 	if(count($projectRs)>0){
							  echo "&nbsp;"; 
							  echo "&nbsp;&nbsp;&nbsp;&nbsp;<div style='background:#8DB2E3; width='100%'>所属项目&nbsp;&nbsp;&nbsp;&nbsp; ";
						    foreach($projectRs as $pval){
						       echo "<input type='checkbox' name='projectID' ";
						       if(in_array($pval["ID"],$manager_projectArr)) echo " checked "; 
						       echo "value='".$pval["ID"]."' class='ck_".$i."'> ".$pval["name"]." &nbsp;";
							  }
						     echo "<br>".'</div>';
							 } 
						}
			}*/
             
             
             
             ?>             
            
            <input type="submit" value="提交">  
        </td>
      </tr>
	  </table>  
        </form>  
        
      
        <form action="short_message_QF.php" method="post" name="myform"  >
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="bg1" id="userinfolist">
    <tbody>

                  <tr>
		    <td width="9%" align="left" class="bg"><? echo _("端口")?></td>
		    <td  align="left" class="bg">
                        <input name="userid" type="text"  value="<?=$rs['userid']?>" size="50"> &nbsp;&nbsp;提示：端口、用户名、密码由蓝海提供才可用此功能</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("用户名")?></td>
		    <td align="left" class="bg">
				<input name="account" type="text"  value="<?=$rs['account']?>" size="50"> 	</td>
		 </tr> 
                 <tr>
		    <td align="left" class="bg"><? echo _("密码")?></td>
		    <td align="left" class="bg">
				<input name="password" type="text"  value="<?=$rs['password']?>" size="50"> 	</td>
		 </tr> 
		
		  <tr>
		    <td align="left" class="bg"><? echo _("电话号码")?></td>
		    <td align="left" class="bg">
                        <!--<input name="mobile" type="text"    id="client"  value="<?php echo $mobile; ?>" size="50">&nbsp;&nbsp;发送短信条数为：<?php if(empty($num)){echo "0";}  else {echo $num;}?>条-->
                        <textarea  name="mobile" id="client"   rows="2" style="width:365px;" ><?php echo $mobile; ?></textarea>&nbsp;&nbsp;发送短信条数为：<?php if(empty($num)){echo "0";}  else {echo $num;}?>条 
                    </td>
                    
		 </tr> 
		  <tr>
		    <td align="left" class="bg"><? echo _("内容")?></td>
		    <td align="left" class="bg">
				<textarea  name="content" id="content"   rows="2" style="width:365px;" ></textarea> 
			</td>
		  </tr> 
		  <tr>
		      <td align="left" class="bg">&nbsp;</td>
	        <td align="left" class="bg"><input type="submit" value="发送"></td>
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
        短信管理-> <strong>自定义短信</strong>
      </p>
      <ul>
          <li>可更具区域、项目、产品查询出用户的电话对用户群发短信。</li>
          <li>端口、用户名、密码由短信网关运营商提供才可用此功能。</li>
          <li>当前计费设备所使用的IP地址要保证必须能访问到短信网关运营商提供的管理平台界面。</li>
          <li>所有选项不能为空。</li>
      </ul>

    </div>
<!---------------------------------------------->

</body>
</html>
