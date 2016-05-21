#!/bin/php
<?php
include("inc/conn.php");
require_once("evn.php");
// generate some random data
srand((double)microtime()*1000000000000);
include_once( 'php-ofc-library/open-flash-chart.php' );
$startDateTime=$_REQUEST["startDateTime"];
$endDateTime  =$_REQUEST["endDateTime"];
$where_sql="";
$title="";
if($startDateTime){
	$where_sql .="and adddatetime>='".$startDateTime."'";
	$title=$startDateTime._("到");
}
if($endDateTime){
	$where_sql .="and adddatetime<'".$endDateTime."'";
	$title .=$endDateTime;
}else{
	$title .=_("至今");
}
//get data infomation
$in_money_rs =$db->select_one("sum(money) as in_money","credit","type='0' ".$where_sql);
$in_money    =$in_money_rs["in_money"];
$in_money_rs1=$db->select_one("sum(money) as in_money1","credit","type='1' ".$where_sql);
$in_money1   =$in_money_rs1["in_money1"];

$out_money_rs =$db->select_one("sum(factmoney) as out_money","orderrefund","0=0 ".$where_sql);
$out_money    =$out_money_rs["out_money"];

$data = array();
$data[]=($in_money)?$in_money:"0"; //开户收入
$data[]=($in_money1)?$in_money1:"0";//续费收入
$data[]=($out_money)?$out_money:"0"; //退费



$g = new graph();

//
// PIE chart, 60% alpha
//
$g->pie(60,'#505050','{font-size: 12px; color: #404040;');
//
// pass in two arrays, one of data, the other data labels
//
$g->pie_values( $data, array(_("开户"),_("续费"),_("退费")) );
//
// Colours for each slice, in this case some of the colours
// will be re-used (3 colurs for 5 slices means the last two
// slices will have colours colour[0] and colour[1]):
//
$g->pie_slice_colours( array('#639F45','#356aa0','#C79810','#ff0000') );

$g->set_tool_tip( '#val#' );

$g->title( $title._('财务图表分析'), '{font-size:18px; color: #d01f3c}' );
echo $g->render();
?>