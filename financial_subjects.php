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
<title><? echo _("财务科目管理")?> </title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
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
 //echo chr(0x80);

$ID = $_REQUEST['ID'];
if($_GET["action"]=="find"){//soso 
$name=$_REQUEST["name"];  
$remark=$_REQUEST["remark"]; 
$money =$_REQUEST["money"];
$querystring="name=".$name."remark=".$remark;  
}else if($_GET['action']=='editSave'){//editSave
 $count=$db->select_one("max(ID) as maxID",'finance',''); 
 $count=$count["maxID"]+10;
 if($_POST){
   if($_POST['ID']==''){//添加
     $name   = $_POST['name'];
	   $money  =$_POST["money"];
	   $remake = $_POST['remark']; 
	   $adddatetime=date("Y-m-d H:i:s",time());
	   $operator=$_SESSION['manager'];
	   $rsAll=$db->select_all("name","finance","name='".$name."'");
	 if($rsAll){
	     echo "<script>alert('". _("该科目已存在，请不要重复添加!") ."');window.location.href='financial_subjects.php?action=add';</script>";
		   exit;
	 }else{ 
		 $finance= array(
			  "name"=>$name,
			  "money"=>$money,
			  "remark"=>$remake,
			  "type"=>$count,
			  "operator"=>$operator,
			  "adddatetime"=>$adddatetime
		 );
		 $db->insert_new("finance",$finance);
		 echo "<script>alert('". _("添加成功!") ."');window.location.href='financial_subjects.php';</script>";
		 exit;
	 }
   }else{//修改
     $ID     = $_POST['ID'];
     $name   = $_POST['name'];
	   $money  = $_POST["money"];
	   $remake = $_POST['remark']; 
	   $rsName=$db->select_all("name","finance","name='".$name."' and ID!='".$ID."'");
	 if($rsName){
		 echo "<script>alert('". _("修改的科目名已被占用，请重新输入!")."');window.location.href='financial_subjects.php?action=edit&ID=".$ID."';</script>";
		 exit;
	 }else{ 
		 $finance= array(
			  "name"=>$name,
			  "money"=>$money,
			  "remark"=>$remake
		 ); 
		 $db->update_new("finance","ID='".$ID."'",$finance);
		 echo "<script>alert('". _("修改成功!") ."');window.location.href='financial_subjects.php';</script>";
		 exit;
	 }
   }// END  else
 }//end $_POST 
 
}else if($_GET['action']=='dell'){//edit 
 if(!isset($_REQUEST['dell_true'])){
  echo"<script>if(window.confirm('". _("删除该科目,即同时删除该科目以前用户所交费用记录。是否确认删除？")."'))window.location.href='financial_subjects.php?ID=".$ID."&action=dell&dell_true=1';else window.location.href='financial_subjects.php';</script>";
  exit;
 }else{
  if(isset($_REQUEST['dell_true']) && $_REQUEST['dell_true']=='1'){
   $type= financeType($ID);
   $db->delete_new("finance","ID='".$ID."'");
   $db->delete_new("userbill","type='".$type."'");//删除用户账单记录表所对应的该收费项目的用户收费信息
   //userbill财务关联表  
   echo "<script>alert('". _("删除成功!")."');window.location.href='financial_subjects.php';</script>";
   exit;
  }//end if $_REQUEST['dell_true']=='1'
 } //end if isset($_REQUEST['dell_true'])
}
$Rsnums=$db->select_count("finance",'0=0');  

$Rs=$db->select_one("*","finance","ID='".$ID."'");
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2">
			<?php if($_GET['action']=='find' || !isset($_GET['action'])) echo _("财务科目管理");
			      else if($_GET['action']=='add') echo _("财务科目添加");
			      else if($_GET['action']=='edit') echo _("财务科目修改");
			 ?></font></td>
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
   if(!isset($_GET['action']) ||$_GET['action']=='find' ){
 ?>       <form action="?action=find" name="myform" method="post"> 
		<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="bd"> 
      <tr>
		  	<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索") ?></td>
			  <td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="14%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="51%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"> <? echo _("科目名称:")?> </td>
		    <td><input type="text" name="name" value="<?=$name?>"></td>
		    <td align="right">&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input type="submit" value= <? echo _("\"提交搜索\"")?> >  </td>
			<td align="right">&nbsp;</td>
			<td>&nbsp;<? if($Rsnums>0) { ?>  <a href="PHPExcel/excel_subjects.php?<?=$querystring?>" style="color:#FF3300;" ><? echo _("EXCEL科目导出"); }?></a>  </td>
		  </tr>
		  </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="92%" class="f-bulue1"><? echo _("科目管理")?> </td>
		<td width="8%" align="center"><a href="?action=add" class="f-b"><? echo _("添加科目")?> </a></td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="5%" align="center" class="bg f-12"><? echo _("编号")?> </th>
                <th width="16%" align="center" class="bg f-12"><? echo _("科目名称")?> </th>
				<th width="10%" align="center" class="bg f-12"><? echo _("金额")?> </th>
                <th width="10%" align="center" class="bg f-12"><? echo _("添加人员")?> </th>
                <th width="16%" align="center" class="bg f-12"><? echo _("添加时间")?> </th>
                <th width="25%" align="center" class="bg f-12"><? echo _("备注")?> </th>
                <th width="9%" align="center" class="bg f-12"><? echo _("操作")?> </th>
              </tr>
        </thead>	     
        <tbody>  
<?php  
$sql .="ID!=''";
if($name){
	$sql .=" and name like '%".mysql_real_escape_string($name)."%'";
} 
$sql .=" order by ID DESC";
$result=$db->select_all("*","finance",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){  
?>   
		  <tr>
		  <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><?=$rs['name'];?></td>
			<td align="center" class="bg"><?=$rs['money'];?></td>
			<td align="center" class="bg"><?=$rs['operator'];?></td>
			<td align="center" class="bg"><?=$rs['adddatetime'];?></td>
			<td align="center" class="bg"><?=$rs['remark']?></td>
			<td align="center" class="bg">
			  <a  href="?ID=<?=$rs['ID'];?>&action=edit"><? echo _("修改")?> </a>
			  <a  href="?ID=<?=$rs['ID'];?>&action=dell"><? echo _("删除")?> </a>	 
		  </td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="10" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
	      </tr>
        </tbody>      
    </table>	
<?php
}// end find 
 else if($_GET['action']=='edit' || $_GET['action']=='add' ){//edit 
 ?> 
 	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="89%" class="f-bulue1"><? if($_GET['action']=='edit' ) echo _("财务科目修改"); else echo _("财务科目添加");?></td>
		<td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
 <tbody>   
 <form  method="post" action="?action=editSave"  >
  <input  type="hidden" id="ID" name="ID" value="<?=$Rs['ID']?>"  >
  <tr>  
	<td align="right" class="bg"><? echo _("科目名称:")?> </td>
	<td align="left" class="bg"><input type="text"  id="name" name="name" class="input_out"  value="<?=$Rs['name']?>"onFocus="this.className='input_on'" onBlur="this.className='input_out';" ></td>
  </tr> 
  <tr>  
	<td align="right" class="bg"><? echo _("金 额:")?> </td>
	<td align="left" class="bg"><input type="text"  id="money" name="money" class="input_out"  value="<?=$Rs['money']?>"onFocus="this.className='input_on'" onBlur="this.className='input_out';"  ></td>
  </tr> 
  <tr>
	<td align="right" class="bg"><? echo _("科目备注")?> </td>
	<td align="left" class="bg"><textarea name="remark" cols="50" rows="5"  onFocus="this.className='textarea_on'" onBlur="this.className='textarea_out';" class="textarea_out"><?=$Rs['remark']?></textarea>
	</td>
  </tr>
   <tr>
	<td align="right" class="bg">&nbsp;</td>
	<td align="left" class="bg"><input type="submit" value=<? echo _("\"提交\"")?>> </td>
  </tr> 
  </form>
  </tbody>
  </table>
 <?php
 }
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
        营帐管理-> <strong>财务科目</strong>
      </p>
      <ul>
          <li>对用户收费项目进行自定义设置，方便管理员生成不同的财务收费科目。</li>
          <li>可收取其他的一些费用，如初装费、移机费等费用</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

