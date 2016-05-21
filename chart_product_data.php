#!/bin/php
<?php
include("inc/conn.php");
require_once("evn.php");
// generate some random data
srand((double)microtime()*1000000000000);
include_once( 'php-ofc-library/open-flash-chart.php' );

$startDateTime=$_REQUEST["startDateTime"];
$endDateTime  =$_REQUEST["endDateTime"];
$title="";
if($startDateTime){
	$where_sql .="and o.adddatetime>='".$startDateTime."'";
	$title=$startDateTime. _("到");
}
if($endDateTime){
	$where_sql .="and o.adddatetime<'".$endDateTime."'";
	$title .=$endDateTime;
}else{
	$title .= _("至今");
}
$data = array();
$result=$db->select_all(" o.productID,count(o.productID) as allNumber,p.name","orderinfo as o,product as p","p.ID=o.productID  ".$where_sql." group by o.productID");
	if(is_array($result)){
		foreach($result as $key=>$rs){
			$saleNum  =$rs["allNumber"];
				$t_data[] =$rs["name"];
				$data[]   =($saleNum)?$saleNum:"0"; //开户收入
				$links[] ="javascript:alert('$val')";
		}
	}

$g = new graph();

//
// PIE chart, 60% alpha
//
$g->pie(60,'#505050','{font-size: 12px; color: #404040;',false,1);
//
// pass in two arrays, one of data, the other data labels
//
$g->pie_values( $data, $t_data, $links );
//
// Colours for each slice, in this case some of the colours
// will be re-used (3 colurs for 5 slices means the last two
// slices will have colours colour[0] and colour[1]):
//
$g->pie_slice_colours( array('#d01f3c','#356aa0','#C79810','#ff0000') );
$str = _("产  品:")." #x_label#<br>"._("销售量:")." #val#");
$g->set_tool_tip($str);

$g->title( $title. _("财务图表分析"), '{font-size:18px; color: #d01f3c}' );
echo $g->render();
?>