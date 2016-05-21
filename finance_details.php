#!/bin/php
<?php 
include("inc/conn.php");
include_once("evn.php");  
include("inc/loaduser.php");  
?>
<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("用户管理")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
<script src="js/jsdate.js" type="text/javascript"></script>
<!--这是点击帮助的脚本-2014.06.07-->
    <link href="js/jiaoben/css/chinaz.css" rel="stylesheet" type="text/css"/>
  
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
<?php   
MysqlBegin();//开始事务定义
$account         =$_REQUEST["account"];
$startDateTime   =$_REQUEST["startDateTime"];
$endDateTime     =$_REQUEST["endDateTime"];
$operator		     =$_REQUEST["operator"];
$type 			     =$_REQUEST["type"];
$projectID		   =$_REQUEST["projectID"];
$check           =$_REQUEST["check"]; 
$querystring="account=".$account."&startDateTime=".$startDateTime."&endDateTime=".$endDateTime."&operator=".$operator."&type=".$type."&projectID=".$projectID."&c_type=".$c_type."&check=".$check; 
$sql_err = true; 
if($_POST["check_all"]){//全部对账
  $sql_err = $db->update_new("userbill","ID",array("`check`"=>1));  
}
if($_POST["check_all_false"]){//取消全部对账
   $sql_err = $db->update_new("userbill","ID",array("`check`"=>0)); 
}
if($_POST["check_true"]){//确认对账
$ID = $_POST["ID"];
	if(is_array($ID)){
	  foreach($ID as $billID){
	    $sql_err = $db->update_new("userbill","ID='".$billID."'",array("`check`"=>1));
            $re=$db->select_one("b.userID,u.name","userbill as b,userinfo as u","b.ID = '$billID' and u.ID = b.userID"); //201.4.09.20添加用户对账日志
            $UID=$re["userID"];
            $Uname=$re["name"];
            //增加用户操作记录
	 addUserLogInfo($UID,14,"确认对账",$Uname);
           // $rs=$db->select_one("userID","userbill","ID = '$billID'");
           
	  } 
	} 
}
if($_POST["check_false"]){//取消对账 
$ID = $_POST["ID"];
	if(is_array($ID)){
	  foreach($ID as $billID){
	    $sql_err = $db->update_new("userbill","ID='".$billID."'",array("`check`"=>0)); 
             $re=$db->select_one("b.userID,u.name","userbill as b,userinfo as u","b.ID = '$billID' and u.ID = b.userID"); //201.4.09.20添加用户对账日志
            $UID=$re["userID"];
            $Uname=$re["name"];
            //增加用户操作记录
	 addUserLogInfo($UID,14,"取消对账",$Uname);
	  } 
	} 
}
if($sql_err){
 MysqlCommit(); 
 $c=_("操作成功" );	
 echo "<script>alert(".$c.");window.location.href='finance_details.php';</script>";  
 }else{
 MysqRoolback();
 $c=_("操作失败" );
 echo "<script>alert(".$c.");window.location.href='finance_details.php';</script>";  
}   
?> 
</head>
<body>
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
		    <td width="12%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="12%" align="right" class="title_bg2">&nbsp;</td>
		    <td width="38%" align="right" class="title_bg2">&nbsp;</td>
      </tr>
	  <tr>
	  	<td align="right"><? echo _("用户帐号:")?></td>
		  <td><input type="text" name="account" value="<?=$account?>"></td>
		  <td align="right"><? echo _("开始时间：")?></td>
	    <td><input type="text" name="startDateTime" value="<?=$startDateTime?>" onFocus="HS_setDate(this)"></td>
	  </tr>
	  <tr> 
		  <td align="right"><? echo _("所属项目:")?></td>
			<td><?php projectSelected() ?></td>
	    <td align="right"><? echo _("结束时间:")?></td>
	    <td><input type="text" name="endDateTime" value="<?=$endDateTime?>"  onFocus="HS_setDate(this)"></td>
	  <tr>  
	  <tr>
	    <td align="right"><? echo _("费用类型:")?></td>
	    <td>  
			<select name="type">
				<option value="change"<? if($type=='change') echo "selected='selected'";?> ><? echo _("选择类型")?></option> 
				<option value="a"<? if($type=="a") echo "selected='selected'";?>  ><? echo _("违约金")?></option>
				<option value="0"<? if($type=='0') echo "selected='selected'";?>  ><? echo _("开户预存")?></option>
				<option value="1"<? if($type=='1') echo "selected='selected'";?>  ><? echo _("前台续费")?></option>
				<option value="2"<? if($type=='2') echo "selected='selected'";?>  ><? echo _("卡片充值")?></option> 
				<option value="5"<? if($type=='5') echo "selected='selected'";?>  ><? echo _("销户退费")?></option>
				<option value="6"<? if($type=='6') echo "selected='selected'";?>  ><? echo _("用户冲帐")?></option>
				<option value="7"<? if($type=='7') echo "selected='selected'";?>  ><? echo _("用户移机")?></option>
				<option value="8"<? if($type=='8') echo "selected='selected'";?>  ><? echo _("安装费用")?></option>
				<option value="b"<? if($type=="b") echo "selected='selected'";?>  ><? echo _("暂停费用")?></option>
                                <option value="9"<? if($type=='9') echo "selected='selected'";?>  ><? echo _("支付宝充值")?></option>
				<option value="c"<? if($type=="c") echo "selected='selected'";?>  ><? echo _("暂停恢复退费")?></option> 
                                <option value="d"<? if($type=="d") echo "selected='selected'";?>  ><? echo _("用户过户")?></option>
                                <option value="e"<? if($type=="e") echo "selected='selected'";?>  ><? echo _("收取押金")?></option>
                                <option value="f"<? if($type=="f") echo "selected='selected'";?>  ><? echo _("退还押金")?></option>
				<?php //其他手工收费 
				/* $userBillType = $db->select_all("distinct(type)","userbill","type > 9 ");  
					 if($userBillType){  
					   foreach($userBillType as $billType){ 
						 $financeID = $billType["type"]-9;
						 $financeName = $db->select_one("name","finance","ID='".$financeID."'"); 
						if($billType["type"]== ($financeID) && $type ==$billType["type"]){
						   echo "<option value='".$billType["type"]."' selected>". $financeName["name"]."</option>"; 
						} else{
						   echo "<option value='".$billType["type"]."'>". $financeName["name"]."</option>"; 
						}
					   } //end foreach
					 }//end if userBillType  */
				?>
			</select>  
		 </td>
	  	<td align="right"><? echo _("收费人员:")?></td>
	    <td><?php managerSelect($operator) ?></td>
	  </tr>
	  <tr>
	  	<td align="right"><? echo _("对账类型:")?></td>
		   <td><?= userbillCheck($check)?></td>
		  <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td><input type="submit" value="<?php echo _("提交")?>"> &nbsp;&nbsp;&nbsp;&nbsp;  <a href="PHPExcel/excel_finance.php?<?=$querystring?>" style="color:#FF3300;" >EXCEL导出</a></td>
	    </tr>
	  </table> 
</form>
	<br>
<form action="?action=check" name="iform" method="post"> 
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="title_bg2 bd">
      <tr>
          <td width="89%" class="f-bulue1"><? echo _("用户帐单列表")?> &nbsp;&nbsp;<input type="submit" name="check_true"  onClick="javascript:return(confirm('<?php echo _("确认对账?")?>'));" value="对账">&nbsp;&nbsp;<input type="submit"  onClick="javascript:return(confirm('<?php echo _("确认取消对账?")?>')); " name="check_false"value="取消对账">&nbsp;&nbsp;<input type="submit" name="check_all"  onClick="javascript:return(confirm('<?php echo _("确认全部对账?")?>'));" value="全部对账">&nbsp;&nbsp;<input type="submit" name="check_all_false"  onClick="javascript:return(confirm('<?php echo _("确认取消全部对账?")?>'));" value="取消全部对账"></td>
		    <td width="11%" align="right">&nbsp;</td>
      </tr>
	  </table>  
<?php 
		
$sql="b.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";  
if($account)$sql .=" and u.account like '%".mysql_real_escape_string($account)."%'";
if($startDateTime)$sql .=" and b.adddatetime>='".$startDateTime."'";
if($endDateTime)$sql .=" and b.adddatetime<'".$endDateTime."'"; 
if($operator) $sql .=" and b.operator='".$operator."'"; 
if($type!="change" && isset($type) && $type!='' ) $sql .=" and b.type='".$type."'"; 
if($projectID) $sql .=" and u.projectID='".$projectID."'";  
if($check){
  if($check  == "check_true") $sql .=" and b.check =1";
  if($check  == "check_false") $sql .=" and b.check =0";
  if($check  == "all") $sql .=" and b.check in (0,1)";  
} 
$resultSum=$db->select_one("sum(b.money) as sumMoney,b.userID","userbill as b,userinfo as u",$sql." and b.type !=4 and b.type !=8  and b.type !=3 and b.type !=6 and b.type !=5 and b.type !='f' and b.remark!='System:add user financial subjects'");  
if(! $resultSum["sumMoney"]){     
    $resultSum["sumMoney"]=0;   
}
if($type != "change" && $type != ""){
    $userbillsum=$db->select_one("sum(money)","userbill","type = '$type'");
   // echo '$type:'.$type;
    if($type == 5 || $type == 6 || $type == 'f'){
        $sum=$resultSum["sumMoney"]-$userbillsum['sum(money)'];
    }else{
        $sum=$resultSum["sumMoney"];
    }
}else{  //查询退还费用 
$MoneySql="(b.type = 5 or b.type = 6 or b.type = 'f')and b.userID=u.ID and u.projectID in (". $_SESSION["auth_project"].") and u.gradeID in (". $_SESSION["auth_gradeID"].")";
if($account)$MoneySql .=" and b.userID= '".$resultSum['userID']."'";
if($startDateTime)$MoneySql .=" and b.adddatetime>='".$startDateTime."'";
if($endDateTime)$MoneySql .=" and b.adddatetime<'".$endDateTime."'"; 
if($operator) $MoneySql .=" and b.operator='".$operator."'"; 
if($projectID) $MoneySql .=" and u.projectID='".$projectID."'";
if($type!="change" && isset($type) && $type!='' ) $MoneySql .=" and type='".$type."'"; 
     //$userbillsum=$db->select_one("sum(money)","userbill",$MoneySql);
       $userbillsum=$db->select_one("sum(b.money)","userbill as b,userinfo as u",$MoneySql);
       //echo $resultSum["sumMoney"];
      //echo  $userbillsum["sum(b.money)"];
    $sum=$resultSum["sumMoney"] - $userbillsum['sum(b.money)'];   
      //echo $sum;
   // }
     
  

}
#echo $userbillsum["sum(money)"];
    #$sum=$resultSum["sumMoney"]-$userbillsum['sum(money)'];
/*if($resultSum["sumMoney"] != 0 || $type == 5 || $type == 6)
    $sum=$resultSum["sumMoney"]-$userbillsum['sum(money)'];
else
    $sum = 0;*/
?>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="bg1"  id="myTable">	
  <thead>
  	 <tr style="color:red">
  	          	<th width="6%" align="center"colspan="2"  class="bg f-12"><? echo _("对账类型")?></th>
                <th width="9%" align="center" class="bg f-12">
                	<? 
                	 if (empty($check) || $check=="all") echo "所有"; 
                	 else if ($check  == "check_true")  echo "已对账";
                	 else if( $check  == "check_false") echo "未对账";
                 ?>
                 </th>
                <th width="6%" align="center" class="bg f-12">费用类型</th> 
                <th width="4%" align="center" class="bg f-12">
                	<? if ($type=="change"|| empty($type)) echo "所有";else echo userBillStatus($type);  ?>
                </th>
                <th width="15%" align="center" class="bg f-12">
                	<?
                   if (empty($check) || $check=="all") echo "所有对账金额"; 
                	 else if ($check  == "check_true")  echo "已对账金额";
                	 else if( $check  == "check_false") echo "未对账金额";
                	?> </th>
                <th width="9%" align="left" colspan="4" class="bg f-12">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sum;?>元 </th> 
                 
     </tr>
              <tr>
                <th width="2%" align="center" class="bg f-12"><input type="checkbox"  name="allID"  id='allID'  value="allID"  onClick="change_allID();"></th> 
                <th width="4%" align="center" class="bg f-12"><? echo _("编号")?></th>
                <th width="15%" align="center" class="bg f-12"><? echo _("用户帐号")?></th>
                <th width="9%" align="center" class="bg f-12"><? echo _("用户姓名")?></th>
                <th width="8%" align="center" class="bg f-12"><? echo _("类型")?></th>
                <th width="13%" align="center" class="bg f-12"><? echo _("金额")?></th>
                <th width="4%" align="center" class="bg f-12"><? echo _("对账")?></th>
                <th width="7%" align="center" class="bg f-12"><? echo _("收费人员")?></th>
                <th width="10%" align="center" class="bg f-12"><? echo _("收费时间")?></th>
                <th width="16%" align="center" class="bg f-12"><? echo _("备注")?></th>
              </tr>
        </thead>	     
        <tbody>    
<?php
$sql .=" and b.remark!='System:add user financial subjects'  and b.type !=4  and b.type !=3  and b.type !=8  order by billID desc";  
$result=$db->select_all("b.*,b.money as billMoney,b.remark as bRemark,b.ID as billID,b.check,u.*,b.adddatetime as badddatetime","userbill as b,userinfo as u",$sql,20); 
 
 	if(is_array($result)){
		foreach($result as $key=>$rs){  
?>   
		  <tr>
		  <td align="center" class="bg"><input type="checkbox" name="ID[]" id="ID" value="<?=$rs["billID"]?>"></td>
		  <td align="center" class="bg"><?=$rs['billID'];?></td>
			<td align="center" class="bg"><a href="#" OnClick="dowm(event,'downloadLink');loaduser('ajax/loaduser.php','UserName','<?=$rs["UserName"]?>','loadusershow')"><?=$rs['UserName'];?></a></td>
			<td align="center" class="bg"><?=$rs["name"]?></td>
			<td align="center" class="bg"><?=userBillStatus($rs['type'])?></td>
			<td align="center" class="bg"><?php if($rs['type'] == 6 || $rs['type'] == 5 || $rs['type'] == "f") echo "-"; ?><?=$rs['billMoney'];?></td>
			<td align="center" class="bg"><?=orderCheckStatus($rs['check'])?></td>
			<td align="center" class="bg"><?=$rs["operator"];?></td>
			<td align="center" class="bg"><?=$rs["badddatetime"]?></td>
		  <td align="center" class="bg"><?=$rs["bRemark"]?></td>
		  </tr>
<?php  }} ?>
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
        营帐管理-> <strong>用户对账</strong>
      </p>
      <ul>
          <li>可对费用类型、对账类型等条件进行对账处理及导出。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
<script>
function change_allID(){
	ide=document.iform.allID.checked;
	div=document.getElementById("myTable").getElementsByTagName("input"); 
	for(i=0;i<div.length;i++){ 
		div[i].checked=ide;
	}  
} 	
</script>
</html>


