#!/bin/php
<?php  
include("inc/conn.php");
require_once("evn.php"); 
require_once("inc/timeOnLine.php");  
date_default_timezone_set('Asia/Shanghai');  
 
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("�ޱ����ĵ�")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css"> 
</head>
<script type="text/javascript"> 
</script> 
<?php   
 if($_GET){
	$UserName= $_GET['UserName'];
	$action =$_GET["action"]; 
	$rs  =$db->select_one("*","userinfo","UserName='".$UserName."'");
	$rs2 =$db->select_one("*","ticket","0=0 limit 0,1");  
	if($action=='netbar_checkout'){//���� 
	 //ִ�����߶���
	 include('inc/scan_down_line.php');
                   //--------在t.php记录下线记录2014.03.17----------
                  $file = fopen('t.php','a');
                  $name="netbar_checkout.php*";
                  $time=date("Y-m-d H:i:s",time())."||";
                  fwrite($file,$name.$time);
                  fclose($file);
          //-----------------------------------------------
	 $orderID = $_GET["orderID"];
	   //�޸Ĳ���
	   if($rs['checkout']!=1){
       $db->query("update userrun set status=4,enddatetime='".date("Y-m-d H:i:s",time())."' where orderID='".$orderID."'"); 
     }
	 $db->query("update orderinfo set status=4 where ID='".$orderID."'");  
	 $db->query("update userattribute set status=4,stop=1,orderID='".$orderID."' "); 
	   //���˱�ʶ
	 $db->query("update userinfo set checkout=1,money = 0 where  UserName='".$UserName."' "); 
	 $attr = $db->select_one("sum(r.stats) as totalNum","userattribute as a,runinfo as r","a.UserName='".$UserName."' and a.orderID = r.orderID "); 
	 $run  = $db->select_one("begindatetime ,enddatetime,userID","userrun","orderID='".$orderID."'");
	 $userID = $run["userID"]; 
	 $addTime = $rs['adddatetime'];//����ʱ��
	 $beginTime =$run["begindatetime"]; //������ʼʱ��
	 $endTime    =$run["enddatetime"];  //��������ʱ�� 
	 if($rs && $rs['totalNum']>0)  $onlineTime = day($rs['totalNum']);
	 else $onlineTime =0; 
	 $money =(int)$rs['money'];//������ΪӦ�����Ľ��  ����Ϊ�����Ľ��
     if($money<0){
	    $type =_('��������'); 
		 //��ֵ
		 //����û��ʵ���¼��
	     addUserBillInfo($userID,"1",$money,_("ʱ���ƷѲ�������"));  
		 //��Ӳ����¼
		 addCreditInfo($userID,"1",$money);
	 }else if($money>0){
	    $type =_('�˻�����');
		//����
		//����û��ʵ���¼��
	    addUserBillInfo($userID,"6",$money,_("ʱ�����˻�����"));
	    //��Ӳ����¼
	    addOrderRefund($userID,2,$money,$money,$orderID,$_SESSION["manager"],_("ʱ�����˻�����")); //��¼�û�������¼
	    addUserLogInfo($userID,"9",_("�û�����:").$money,getName($userID),$money);//$_SERVER['REQUEST_URI']
   
	 }else if($money==0){
	    $type =_('�����Ҳ�');
	 } 
	  $title    =($rs2["type"]=="auto")?"".projectShow($rs["projectID"])."".$rs2["name"]."":"".$rs2["name"]."";
	  $mark     =$rs2["mark"];
	  $tel  		=$rs2["tel"]; 
 }
}
?>

<style type="text/css">
<!--
.STYLE4 {
	font-size: <?=$rs2["fontsize"]?>px;
	line-height:<?=$rs2["lineheight"]?>px;
}
.STYLE6 {
font-size: <?=$rs2["tfontsize"]?>px;
line-height:50px;
}
table{
margin-bottom:<?=$rs2["tbmarginbottom"]?>px;
}
-->
</style> 
<body  style="overflow-x:hidden;overflow-y:auto">

<? 
  if($action=='netbar_checkout'){//����Ʊ��
?>   
 <table width="<?=$rs2["tbwidth"]?>mm" height="<?=$rs2["tbheight"]?>mm" border="0" align="center" cellpadding="6" cellspacing="0" bordercolor="#8DB2E3" class="bd">
          <tr id='GroupName_tr'>
            <td colspan="4" align="center" valign='top' class='bd_b STYLE6'><?=$title?>&nbsp;</td> 
          </tr>
          <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("��Ŀ")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
              <?=projectShow($rs["projectID"])?>			  </td>
            <td align="right" class='bd_b bd_l STYLE4'><? echo _("�Ʊ�����")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'>
              <?=date("Y",time());?><? echo _("��")?><?=date("m",time());?><? echo _("��")?><?=date("j",time());?><? echo _("��")?> &nbsp;</td>
          </tr>    
		  <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("�� �� ��")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$rs["UserName"]?>&nbsp;</td>
            <td width="16%" align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("�ͷ���Ա")?>:</td>
            <td width="30%" align="left" class='bd_b bd_l STYLE4'><?=$_SESSION['manager'];?>&nbsp;</td>
          </tr> 
		 <tr id='GroupName_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("��ʼʱ��:")?></td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$beginTime?>&nbsp;</td>
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("����ʱ��")?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'><?=$endTime ?></td>
	   </tr>
	   <tr id='GroupName_tr'> 
			<td align="right" valign='top' class='bd_b bd_l STYLE4'><? echo _("����ʱ��")?>:</td>
            <td align="left"  class='bd_b bd_l STYLE4'><?=$onlineTime;?></td>
			<td align="right" valign='top' class='bd_b bd_l STYLE4' style="color:red"><?=$type?>:</td>
            <td align="left" class='bd_b bd_l STYLE4'style="color:red">
			<?  $c=new ChineseNumber();
			    echo _("��").$rs["money"]._("Ԫ")."&nbsp;&nbsp;("._("��д���").":";
				echo $c->ParseNumber($rs["money"]).")";
			?>&nbsp;
			 </td>
	    </tr>	 
        <tr id='Name_tr'>
            <td align="right" valign='top' class='bd_b STYLE4'><? echo _("��ע")?>:</td>
            <td colspan="3" align="left" class='bd_b bd_l STYLE4'><?=$mark?>&nbsp;</td>
          </tr> 
          
          <tr id='Framed_IP_Address_tr'>
            <td align="right" valign='top' class="bg STYLE4"><? echo _("�ͷ��绰")?>:</td>
            <td align="left" valign='top' class="bg bd_l STYLE4"><?=$tel?>&nbsp;</td>
            <td align="right" valign='top' class="bg bd_l STYLE4"><? echo _("�û�")?><a href="javascript:print();" class="STYLE7 STYLE4"><? echo _("ȷ��")?></a>:</td>
            <td align="right" valign='top' class="bg bd_l STYLE4">&nbsp;</td>
          </tr>

 <? } ?>
          
</table>
</body>
</html>
