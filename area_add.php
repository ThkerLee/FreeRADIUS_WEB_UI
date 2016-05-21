#!/bin/php
<?php 
require_once("inc/conn.php"); 
include_once("./ros_static_ip.php");
require_once("evn.php");
include_once("inc/ajax_js.php");
 ?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("添加区域")?></title>
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
            WindowTitle:          '<b>区域管理</b>',
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
$_REQUEST;
if($_POST){  
	  $sql=array(
		 "name"=>$_POST["name"],
		 "operator"=>$_SESSION['manager'],
		 "description"=>$_POST["description"], 
		 "adddatetime"=>date("Y-m-d H:i:s",time()) 
	);
   $file = popen("license -T","r");
  $data = fgets($file);//获取授权1为ISP版、2为加强版、3为基础版
   pclose($file);
  // $data=3;
   if($data ==3 ){
     $num = $db->select_count("area","ID");
     if($num>=1){
      echo "<script>alert('". _("该版本只允许1个区域") ." ');window.location.href='area_add.php';</script>"; 
      exit();
      }  else {
      $r=$db->insert_new("area",$sql);   
     }
     
      }  else {
    $r=$db->insert_new("area",$sql);   
                         }
                	
  
  //---------------------------------------------2014.03.07修改权限问题--------------------------------
	$rs = $db->select_one("ID","area","0=0 order by ID desc limit 0,1");
	$man_areaID=$rs['ID'];
        $_SESSION["auth_area"] .=",".$man_areaID;
	$db->update_new("manager","manager_account='".$_SESSION["manager"]."'",array("manager_area"=>$_SESSION["auth_area"]));
        if($_SESSION["manager"] != "admin"){
          $rs = $db->select_one("ID","area","0=0 order by ID desc limit 0,1");
          $ra = $db->select_one("*","manager","manager_account='admin'");
          if($ra["manager_area"]==""){
            $manager_area=$ra["manager_area"].$rs["ID"];  
          }  else {
            $manager_area=$ra["manager_area"].",".$rs["ID"];  
          }
        $db->update_new("manager","manager_account='admin'",array("manager_area"=>$manager_area));  
          
        }
        
        
                        if($data ==3){
                         echo "<script>alert('". _("添加成功") ." ');window.location.href='area_add.php';</script>";   
                        }  else {
                      echo "<script>alert('". _("添加成功") ." ');window.location.href='area.php';</script>";       
                        }

			 
}
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("区域添加")?></font></td>
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
        <td width="89%" class="f-bulue1"><? echo _("区域添加")?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
   <form action="?" method="post" name="myform" >
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <tbody>     
		  <tr>
			<td width="13%" align="right" class="bg"><? echo _("名称：")?></td>
			<td width="87%" align="left" class="bg"><input type="text" id="name" name="name" onFocus="this.className='input_on'"  value="<?=$_REQUEST['name']?>"  onBlur="this.className='input_out';ajaxInput('ajax_check.php','projectName','name','nameTXT');" class="input_out"><span id="nameTXT"></span>			</td>
		  </tr>  
		  <tr > 
		    <td align="right" class="bg" height="30px"><? echo _("备注")?>:</td> 
		    <td align="left" class="bg" height="30px"><textarea name="description" id="description"cols="50"  rows="2"></textarea></td> 
		  </tr> 
		  <tr>
		    <td align="right" class="bg">&nbsp;</td>
		    <td align="left" class="bg">
				<input type="submit"  value="<? echo _("提交")?>"  >			</td>
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
        区域管理-> <strong>添加区域</strong>
      </p>
      <ul>
          <li>把多个项目放在一个区域进行管理。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

