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

//Set properties
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
$objActSheet->setTitle('卡片信息'); 
 
      
//*************************************      
//设置单元格内容      
//      
//由PHPExcel根据传入内容自动判断单元格内容类型      
//设置第一行为的字段名称
$fieldName=array(         
	"A1"=>"卡号",
	"B1"=>"金额(元)",
	"C1"=>"是否销售",
	"D1"=>"是否充值",
	"E1"=>"制卡时间",
	"F1"=>"失效时间",
	"G1"=>"制卡员",
	"H1"=>"销售员",
	"I1"=>"充值人员" 
);
if(is_array($fieldName)){
	foreach($fieldName as $key=>$value){
		$objActSheet->setCellValue($key,$value);		
	}
}
//********************************************设置保存的条件
@$cardNumber      =$_POST["cardNumber"];
@$startDateTime   =$_POST["startDateTime"];
@$startDateTime1  =$_POST["startDateTime1"];
@$endDateTime     =$_POST["endDateTime"];
@$endDateTime1    =$_POST["endDateTime1"];
@$operator		  =$_POST["operator"];
@$sold			  =$_POST["sold"];
@$recharge		  =$_POST["recharge"]; 
$sql=" 0=0 "; 
$querystring ="cardNumber='".$cardNumber."'&startDateTime='".$startDateTime."'&startDateTime1='".$startDateTime1."'&endDateTime='".$endDateTime."'&endDateTime1='".$endDateTime1."'&operator='".$operator."'&sold='".$sold."'&recharge='".$recharge."'" ;
if($cardNumber){
	$sql .=" and cardNumber like '%".$cardNumber."%'";
}
if($startDateTime){
	$sql .=" and cardAddTime>='".$startDateTime."'";
}
if($startDateTime1){
	$sql .=" and cardAddTime<'".$startDateTime."'";
}
if($endDateTime){
	$sql .=" and ivalidTime>='".$endDateTime."'";
}
if($endDateTime1){
	$sql .=" and ivalidTime<'".$endDateTime1."'";
}
if($operator){
	$sql .=" and operator='".$operator."'";
}
if($sold!="type" && isset($sold)){
	$sql .=" and sold='".$sold."'";
}
if($recharge!="type" && isset($recharge)){
	$sql .=" and recharge='".$recharge."'";
}
$sql .="order by ID desc";
$result=$db->select_all("*","card",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
		$cardLogRs=$db->select_one("UserName","cardlog","cardNumber='".$rs["cardNumber"]."'");
		
		$sold=($rs["sold"]==1)?"<font color='#009900'>售出</font>":"<font color='#666666'>未售出</font>";
		$recharge=($rs["recharge"]==1)?"<font color='#009900'>己充值</font>":"<font color='#666666'>未充值</font>";
		$solder=($rs["solder"]=="NULL")?"无":$rs["solder"];
		$cardUserName=$cardLogRs["UserName"];
		
		
		$objActSheet->setCellValue("A".$row,$rs["cardNumber"]);
		$objActSheet->setCellValue("B".$row,$rs["money"]);	
		$objActSheet->setCellValue("C".$row,$sold);
		$objActSheet->setCellValue("D".$row,$recharge);
		$objActSheet->setCellValue("E".$row,$rs["cardAddTime"]);
		$objActSheet->setCellValue("F".$row,$rs["ivalidTime"]);
		$objActSheet->setCellValue("G".$row,$rs["operator"]);
		$objActSheet->setCellValue("H".$row,$solder);
		$objActSheet->setCellValue("I".$row,$cardUserName);
		$row=$row+1;
		} 	
     } 

//Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="cards.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
