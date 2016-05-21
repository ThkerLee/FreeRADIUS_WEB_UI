#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("产品管理")?></title>
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
            WindowTitle:          '<b>产品管理</b>',
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
$name=$_REQUEST["name"];
$type=$_REQUEST["type"];
$hide=$_REQUEST["hide"];
$show=$_REQUEST["show"];
$action= empty($_REQUEST["action"])?"IDDESC":$_REQUEST["action"];
//存在问题，当建立的产品修改时，如果修改为当前产品什么项目也不选择的话那么。。无项目关联的产品将不会查询显示出来
$product        =$db->select_all('productID',"productandproject","projectID in (".$_SESSION["auth_project"].") order by productID ASC"); 
// $product        =$db->select_all('ID',"product","ID != 0  order by ID ASC");
  
if(is_array($product)){ 
	foreach($product as $prs){
		     $pID .=$prs['productID'].","; 
		  // $pID .=$prs['ID'].","; 
	}
	 $pID  = substr($pID,0,-1);
	 $_SESSION["auth_product"]=empty($pID)?"0":$pID ;  
} 
if($_GET["action"]=="hide"){
	$ID  =$_GET["ID"];
	$hide=($hide==1)?"0":"1";
	$db->update_new("product","ID='".$ID."'",array("hide"=>$hide));
}if($_GET["action"]=="show"){
	$ID  =$_GET["ID"];
	$show=0;
	$db->update_new("product","hide = 1",array("hide"=>$show));
}
$querystring="name=".$name."&type=".$type."&hide=".$hide."&show=".$show."&action=".$action;
?>
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="center"><font color="#FFFFFF" size="2"><? echo _("产品管理")?></font>  </td>
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
			<td width="14%" class="f-bulue1 title_bg2"><? echo _("条件搜索")?></td>
			<td width="21%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="14%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="51%" align="right" class="title_bg2">&nbsp;</td>
		  </tr>
		  <tr>
		    <td align="right"> <? echo _("产品名称：")?></td>
		    <td><input type="text" name="name" value="<?=$name?>"></td>
		    <td align="right"><? echo _("产品类型：")?></td>
		    <td><select name="type">
					<option value=""><? echo _("选择类型")?></option>
					<option value="year" <?php if($type=="year") echo "selected"; ?>><? echo _("包年")?></option>
					<option value="month" <?php if($type=="month") echo "selected"; ?>><? echo _("包月")?></option>
					<option value="hour" <?php if($type=="hour") echo "selected"; ?>><? echo _("包时")?></option>
					<option value="flow" <?Php if($type=="flow") echo "selected"; ?>><? echo _("计流量")?></option>
				</select>			</td>
	      </tr>
		  <tr>
			<td align="right">&nbsp;</td>
			<td><input type="submit" value="<?php echo _("提交搜索")?>">
		    <a href="?hide=1&show=1"><?php echo _("隐藏产品")?></a> &nbsp;&nbsp;
                    <a href="PHPExcel/excel_product.php?<?=$querystring?>" style="color:#FF3300;" >EXCEL导出</a></td>
			<td align="right">&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  </table>
	</form>
	  <br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
        <td width="15%" class="f-bulue1"><? echo _("产品管理")?></td>
		<td width="85%" align="center">
		    <? echo _("产品ID")?>(<a href="?action=IDASC"><? echo _("升序")?></a> / <a href="?action=IDDESC"><? echo _("降序")?></a>) ||	
		    <? echo _("产品名称")?>(<a href="?action=nameASC"><? echo _("升序")?></a> / <a href="?action=nameDESC"><? echo _("降序")?></a>) ||
			<? echo _("产品类型")?>(<a href="?action=typeASC"><? echo _("升序")?></a> / <a href="?action=typeDESC"><? echo _("降序")?></a>) ||	
			<? echo _("周期价格")?>(<a href="?action=priceASC"><? echo _("升序")?></a> / <a href="?action=priceDESC"><? echo _("降序")?></a>) ||	
		    <? echo _("时间周期")?>(<a href="?action=periodASC"><? echo _("升序")?></a> / <a href="?action=periodDESC"><? echo _("降序")?></a>) || <a href="product_add.php" class="f-b"><? echo _("添加产品")?></a> || <a href="?action=show&hide=0&show=0" class="f-b"><? echo _("显示所有产品")?></a></td>
      </tr>
	  </table>
  	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1" id="userinfolist">
        <thead>
              <tr>
                <th width="5%" align="center" class="bg f-12">  <? echo _("编号") ;if($_GET['action']=="IDASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="IDDESC"){echo"<font color='red'><b>↓</b></font>";}?> </th>
                <th width="22%" align="center" class="bg f-12"> <? echo _("产品名称");if($_GET['action']=="nameASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="nameDESC"){echo"<font color='red'><b>↓</b></font>";}?></th>
                <th width="10%" align="center" class="bg f-12"> <? echo _("产品类型");if($_GET['action']=="typeASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="typeDESC"){echo"<font color='red'><b>↓</b></font>";} ?></th>
                <th width="9%" align="center" class="bg f-12">	<?  echo _("价格");if($_GET['action']=="priceASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="priceDESC"){echo"<font color='red'><b>↓</b></font>";} ?></th>
                <th width="8%" align="center" class="bg f-12"> <? echo _("时间周期");if($_GET['action']=="periodASC"){echo"<font color='red'><b>↑</b></font>";}if($_GET['action']=="periodDESC"){echo"<font color='red'><b>↓</b></font>";}?> </th>
                <th width="8%" align="center" class="bg f-12"><? echo _("费率")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("上传带宽")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("下载带宽")?></th>
                <th width="6%" align="center" class="bg f-12"><? echo _("销售数量")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("到期时间")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("操作")?></th>
              </tr>
        </thead>	     
        <tbody>  
<?php  
 $sql=" p.ID  IN (".$_SESSION["auth_product"].") ";
// $sql=" p.ID  IN (".$_SESSION["auth_product"].") ";
if($hide==1 && $show==1){
	$sql .=" and p.hide=1";
}else{
	$sql .=" and p.hide=0";
}
if($name){
	$sql .=" and name like '%".mysql_real_escape_string($name)."%'";
}
if($type){
	$sql .=" and type='".$type."'";
}
 
  if($action =="IDASC") $sql .=" order by ID ASC";
  elseif($action =="IDDESC")   $sql .=" order by ID DESC";
  elseif($action =="nameASC")  $sql .=" order by name  ASC";
  elseif($action =="nameDESC") $sql .=" order by name DESC";
  elseif($action =="typeASC")  $sql .=" order by type  ASC";
  elseif($action =="typeDESC") $sql .=" order by type DESC";
  elseif($action =="priceASC")   $sql .=" order by price  ASC";
  elseif($action =="priceDESC")  $sql .=" order by price DESC";
  elseif($action =="periodASC")   $sql .=" order by period  ASC"; 
  elseif($action =="perioddDESC") $sql .=" order by period DESC"; 
 
 
 $result=$db->select_all("distinct p.*","product as p,productandproject as pj",$sql,20);

//$result=$db->select_all("distinct p.*","product as p",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
			$saleNum=$db->select_count("orderinfo as o,userrun as u","o.productID='".$rs["ID"]."'and o.ID=u.orderID and o.userID=u.userID   ");	
?>   
		  <tr>
		    <td align="center" class="bg"><?=$rs['ID'];?></td>
			<td align="center" class="bg"><?=$rs['name'];?></td>
			<td align="center" class="bg"><?=productTypeShow($rs['type'])?></td>
			<td align="center" class="bg"><?=$rs["price"];?></td>
			<td align="center" class="bg"><?=$rs['period'];?></td>
			<td align="center" class="bg"><?=$rs['unitprice'];?></td>
			<td align="center" class="bg"><?=$rs["upbandwidth"]?></td>
			<td align="center" class="bg"><?=$rs["downbandwidth"]?></td>
			<td align="center" class="bg"><?=$saleNum?></td>
                        <td align="center" class="bg"><?=$rs["enddatetime"]?></td>
			<td align="center" class="bg"> 
			  <a  href="product_edit.php?ID=<?=$rs['ID'];?>" title="<? echo _("修改")?>"><img src="images/edit.png" width="12" height="12" border="0" /> </a>
			  <a  href="product_del.php?ID=<?=$rs['ID'];?>"onClick="javascript:return(confirm('<? echo _("确认删除")?>'));" title="<? echo _("删除")?>"><img src="images/del.png" width="12" height="12" border="0" /></a>	 
			  <a  href="?action=hide&ID=<?=$rs['ID'];?>&hide=<?=$rs["hide"]?>"><? echo _("隐藏")?></a>			
		    </td>
		  </tr>
<?php  }} ?>

		  <tr>
		    <td colspan="11" align="center" class="bg">
				<?php $db->page($querystring); ?>			</td>
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
        产品管理-> <strong>产品管理</strong>
      </p>
      <ul>
          <li>可以对已经建立好的产品进行删除、修改、查看等操作。</li>
          <li>点击产品对应的“修改”按纽 即可对该产品进行修改，可修改的栏目包括上传、下载带宽，以及所属项目，其余项目为不可更改项目。</li>
          <li>点击产品对应的“删除”按纽，即可删除该类产品，但如果产品已经进行销售，则该产品无法删除。</li>
          <li>点击产品对应的“隐藏”按纽，即可能该产品进行隐藏，此隐藏主要是为了在添加用户时，使不再使用的产品不出现，以免产品过多，影响操作。</li>
          <li>可以已EXECL方式导出。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>

