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
$bar_1->key( _("正常"), 12 );

$bar_2 = new bar( 50, '#ffcc33' );
$bar_2->key( _("即将到期"), 12 );

$bar_3 = new bar( 50, '#9933CC' );
$bar_3->key( _("到期"), 12 );


//serach user number
$year=(!empty($_REQUEST["search_year"]))?$_REQUEST["search_year"]:date("Y",time());

$nowTime        = date("Y-m-d H:i:s",time());
$upcomingTime   = date('Y-m-d H:i:s',strtotime("$nowTime +15 day")); 

$num1_sql="select count(*) as user_num,DATE_FORMAT(adddatetime,'%c') as my_month from userinfo where adddatetime like '%$year%' and gradeID in (". $_SESSION["auth_gradeID"].") group by my_month";
$num0=$db->select_count("userinfo","gradeID in (". $_SESSION["auth_gradeID"].")");
$num1=$db->select_all("count(*) as user_num,DATE_FORMAT(adddatetime,'%c') as my_month","userinfo","adddatetime like '%$year%' and  gradeID in (". $_SESSION["auth_gradeID"].") group by my_month");
$num2=$db->select_all("count(u.ID) as user_num,DATE_FORMAT(u.adddatetime,'%c') as my_month","userinfo as u,userattribute as a,userrun as r","u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and r.enddatetime<'$upcomingTime' and r.enddatetime>='$nowTime' and u.gradeID in (". $_SESSION["auth_gradeID"].") and u.adddatetime like '%$year%' group by my_month");

$num3=$db->select_all("count(u.ID) as user_num,DATE_FORMAT(u.adddatetime,'%c') as my_month","userinfo as u,userattribute as a,userrun as r","u.ID=a.userID and u.ID=r.userID and a.orderID=r.orderID and r.enddatetime<'$nowTime' and r.enddatetime!='0000-00-00 00:00:00' and u.gradeID in (". $_SESSION["auth_gradeID"].") and u.adddatetime like '%$year%' group by my_month");




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
				$num_data1=$rs1["user_num"];
				break;
			}else{
				$num_data1=0;
			}	
		}
	} 
	if(is_array($num2)){
		foreach($num2 as $rs2){
			if($rs2["my_month"]==$j){
				$num_data2=$rs2["user_num"];
				break;
			}else{
				$num_data2=0;
			}	
		}
	} 
	if(is_array($num3)){
		foreach($num3 as $rs3){
			if($rs3["my_month"]==$j){
				$num_data3=$rs3["user_num"];
				break;
			}else{
				$num_data3=0;
			}	
		}
	} 
	$bar_1->data[] = $num_data1;
	$bar_2->data[] = $num_data2;
	$bar_3->data[] = $num_data3;
}

// create the chart:
$g = new graph();
$g->title(  $year."-"._("用户数据分析"), '{font-size: 26px;}' );

// add the 3 bar charts to it:
$g->data_sets[] = $bar_1;
$g->data_sets[] = $bar_2;
$g->data_sets[] = $bar_3;


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