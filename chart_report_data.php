#!/bin/php
<?php 
include_once('inc/conn.php');
include_once( 'php-ofc-library/open-flash-chart.php' );
require_once("evn.php");
// generate some random data
srand((double)microtime()*1000000);

//
// We are diplaying 3 bar charts, so create 3
// bar chart objects:
//

$bar_1 = new bar( 50, '#639F45' );
$bar_1->key("收入金额", 12 );

$bar_2 = new bar( 50, '#ffcc33' );
$bar_2->key("退费金额", 12 );



//serach user number
$year=(!empty($_REQUEST["search_year"]))?$_REQUEST["search_year"]:date("Y",time());

$nowTime        = date("Y-m-d H:i:s",time());
$upcomingTime   = date('Y-m-d H:i:s',strtotime("$nowTime +15 day")); 

$num1_sql="select count(*) as user_num,DATE_FORMAT(adddatetime,'%c') as my_month from userinfo where adddatetime like '%$year%' group by my_month";
$moneyRs =$db->select_one("sum(money) as in_all_money","credit","");
$num0=$moneyRs["in_all_money"];
$num1=$db->select_all("sum(money) as in_all_money,DATE_FORMAT(adddatetime,'%c') as my_month","credit","adddatetime like '%$year%' group by my_month");
$num2=$db->select_all("sum(factmoney) as out_all_money,DATE_FORMAT(adddatetime,'%c') as my_month","orderrefund","adddatetime like '%$year%' group by my_month");





//
// NOTE: how we are filling 3 arrays full of data,
//       one for each bar on the graph
//


for( $i=0; $i<12; $i++ )
{
    $j=$i+1;
	if(is_array($num1)){
		foreach($num1 as $rs1){
			if($rs1["my_month"]==$j){
				$num_data1=$rs1["in_all_money"];
				break;
			}else{
				$num_data1=0;
			}	
		}
	} 
	if(is_array($num2)){
		foreach($num2 as $rs2){
			if($rs2["my_month"]==$j){
				$num_data2=$rs2["out_all_money"];
				break;
			}else{
				$num_data2=0;
			}	
		}
	} 

	$bar_1->data[] = $num_data1;
	$bar_2->data[] = $num_data2;
}

// create the chart:
$g = new graph();
$g->title( $year._("-财务报表数据分析"), '{font-size: 26px;}' );

// add the 3 bar charts to it:
$g->data_sets[] = $bar_1;
$g->data_sets[] = $bar_2;


//
$g->set_x_labels(  array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec' ) );
// set the X axis to show every 2nd label:
$g->set_x_label_style( 10, '#9933CC', 0, 1 );
// and tick every second value:
$g->set_x_axis_steps( 2 );
//
//设置Y轴的标准
$y_max   =$num0+100;
$y_steps =10;
$g->set_y_max( $y_max );
$g->y_label_steps( $y_steps );
$g->set_y_legend( 'natshell.com', 12, '0x736AFF' );
echo $g->render();

?>