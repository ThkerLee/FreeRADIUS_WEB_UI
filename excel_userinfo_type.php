#!/bin/php
<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2010 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.3c, 2010-06-01
 */

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once 'PHPExcel.php';
require_once("../inc/scan_conn.php"); 

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
$objPHPExcel->setActiveSheetIndex(0);      
$objActSheet = $objPHPExcel->getActiveSheet();     
//设置当前活动sheet的名称      
$objActSheet->setTitle('用户信息'); 
 
      
//*************************************      
//设置单元格内容      
//      
//由PHPExcel根据传入内容自动判断单元格内容类型      
//设置第一行为的字段名称
$fieldName=array(
	"A1"=>"帐号",
	"B1"=>"密码",
	"C1"=>"姓名",
	"D1"=>"证件号码",
	"E1"=>"工作电话",
	"F1"=>"家庭电话",
	"G1"=>"手机号码",
	"H1"=>"电子邮件",
	"I1"=>"联系地址",
	"J1"=>"帐户余额",
	"K1"=>"在线人数",
	"L1"=>"IP 地址",
	"M1"=>"是否绑定MAC",
	"N1"=>"MAC 地址",
	"O1"=>"是否绑定NASIP",
	"P1"=>"NASIP地址",
	"Q1"=>"开始时间",
	"R1"=>"结束时间",
	"S1"=>"产品" ,
	"T1"=>"项目编号",
	"U1"=>"是否绑定VLAN",
	"V1"=>"VLAN地址",
	"W1"=>"母账号",
	"X1"=>"产品编号"
);
if(is_array($fieldName)){
	foreach($fieldName as $key=>$value){
		$objActSheet->setCellValue($key,$value);		
	}
}
//********************************************设置保存的条件
@$UserName        =$_REQUEST["UserName"];
@$startDateTime   =$_REQUEST["startDateTime"];
@$endDateTime     =$_REQUEST["endDateTime"];
@$projectID		  =$_REQUEST["projectID"];
@$name			  =$_REQUEST["name"];
@$address		  =$_REQUEST["address"];
@$exceltype		  =$_REQUEST["exceltype"];
@$nowTime         =date("Y-m-d H:i:s",time());
@$enddays         =$_REQUEST["enddays"];
@$upcomingTime    = date('Y-m-d H:i:s',strtotime("$nowTime +$enddays day"));
if($exceltype=='1'){
@$sql="u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and r.enddatetime<'$upcomingTime' and r.enddatetime>='$nowTime' ";
}else{
@$sql="u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and r.enddatetime<'$nowTime' and r.enddatetime!='0000-00-00 00:00:00'";	
} 

if($UserName){
	$sql .=" and u.UserName like '%".$UserName."%'";
}
if($name){
	$sql .=" and u.name like '%".$name."%'";
}
if($address){
	$sql .=" and u.address like '".$address."'";
}
if($startDateTime){
	$sql .=" and r.enddatetime>='".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and r.enddatetime<'".$endDateTime."'";
}
if($projectID){
	$sql .=" and u.projectID='".$projectID."'";
}
@$result=$db->select_all("u.*,a.*,r.enddatetime,r.begindatetime","userinfo as u,userattribute as a,userrun as r",$sql);

if(is_array($result)){
	$row=2;
	foreach($result as $rs){
		$rs3=$db->select_one("p.name,p.ID","orderinfo as o,product as p","o.ID='".$rs["orderID"]."' and p.ID=o.productID");
		$waitOrderRs =$db->select_one("enddatetime","userrun","userID='".$rs["ID"]."' and status=0");
		$EndDate=$rs["enddatetime"];
		if($waitOrderRs){
			$EndDate=$waitOrderRs["enddatetime"];
		}		
		//查询出用户的IP地址
		$rs1=$db->select_one("Value","radreply","UserName='".$rs["UserName"]."' and Attribute='Framed-IP-Address'");
		
		$objActSheet->setCellValue("A".$row,$rs["UserName"]);
		$objActSheet->setCellValue("B".$row,$rs["password"]);	
		$objActSheet->setCellValue("C".$row,$rs["name"]);
		$objActSheet->setCellValue("D".$row,$rs["cardid"]);
		$objActSheet->setCellValue("E".$row,$rs["workphone"]);
		$objActSheet->setCellValue("F".$row,$rs["homephone"]);
		$objActSheet->setCellValue("G".$row,$rs["mobile"]);
		$objActSheet->setCellValue("H".$row,$rs["email"]);
		$objActSheet->setCellValue("I".$row,$rs["address"]);
		$objActSheet->setCellValue("J".$row,$rs["money"]);
		$objActSheet->setCellValue("K".$row,$rs["onlinenum"]);
		$objActSheet->setCellValue("L".$row,$rs1["Value"]);
		$objActSheet->setCellValue("M".$row,$rs["macbind"]);
		$objActSheet->setCellValue("N".$row,$rs["MAC"]);
		$objActSheet->setCellValue("O".$row,$rs["nasbind"]);
		$objActSheet->setCellValue("P".$row,$rs["NAS_IP"]);
		$objActSheet->setCellValue("Q".$row,$rs["begindatetime"]);
		$objActSheet->setCellValue("R".$row,$EndDate);	
		$objActSheet->setCellValue("S".$row,$rs3["name"]);
		$objActSheet->setCellValue("T".$row,$rs["vlanbind"]);
		$objActSheet->setCellValue("U".$row,$rs["VLAN"]);
		$objActSheet->setCellValue("V".$row,$rs["Mname"]); 
		$objActSheet->setCellValue("W".$row,$rs3["ID"]);
		$row=$row+1;
	}
}

//Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
