#!/bin/php
<?php 
//header("content-type:text/html; charset=utf-8");
header("Content-Type: application/vnd.ms-excel;");
header('Content-Disposition: attachment;filename="product.csv"'); 
require_once("../inc/conn.php");mysql_query("set names utf8");
echo "编号,产品名称,产品类型,价格,时间周期,费率,上传带宽,下载带宽,销售数量\n";

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
 $sql=" p.ID  IN (".$_SESSION["auth_product"].") ";
// $sql=" p.ID  IN (".$_SESSION["auth_product"].") ";
if($hide==1 && $show==1){
	$sql .=" and p.hide=1";
}else{
	$sql .=" and p.hide=0";
}
if($name){
	$sql .=" and name like '%".$name."%'";
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
 
 
 $result=$db->select_all("distinct p.*","product as p,productandproject as pj",$sql);

//$result=$db->select_all("distinct p.*","product as p",$sql,20);
	if(is_array($result)){
		foreach($result as $key=>$rs){
			$saleNum=$db->select_count("orderinfo as o,userrun as u","o.productID='".$rs["ID"]."'and o.ID=u.orderID and o.userID=u.userID   ");
                        
          echo $rs['ID'].",".$rs['name'].",".productTypeShow($rs['type']).",".$rs["price"].",".$rs['period'].",".$rs['unitprice'].",".$rs["upbandwidth"].",".$rs["downbandwidth"].",".$saleNum."\n";              
                        
        }
        
                }