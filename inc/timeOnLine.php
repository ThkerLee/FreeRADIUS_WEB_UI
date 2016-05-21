
<?php
include_once("evn.php");
//
/**
 *===========================================
 * 函数名:    monthDays
 * 参数:     $begind $end
 * 功能描述:  根据用户订单结束开始时间 计算用户所用天数  主要针对包一月用户
 * 返回值:   	返回时间 return days
 * 作者:     Nancy
 * 修改记录:
 *===========================================
 */ 
function monthDays($begin,$end){
	
 if($end==time()){
	  $ctimes=$end-strtotime($begin); 
	  $days=ceil($ctimes/(60*60*24));
	}else{
	  $ctimes=strtotime($end)-strtotime($begin); 
	  $days=ceil($ctimes/(60*60*24));
	}
 return $days;
}

//用时 计算用户产品所用时间
#传参 start 用户运行订单开始时间

function timesTamp ($start){
  $now=time();//当期时间
  $beginTime=strtotime($start);//开始时间戳
  
// 用时一月 =运行开始时间<$now && (开始时间+1month)>=$now
 //用时两个月= (开始时间+1 month)<=$now &&  (开始时间+2 month)>=$now

//用户所用产品已用时间  最长产品时间为3年
for($i=1;$i<=36;$i++){
	$month[]=strtotime(date("Y-m-d H:i:s",strtotime("$start +$i month")));
}
$styleL="<font color='green'><b>";
$styleR="</b></font>";
for($i=0;$i<count($month);$i++){
  $m=$i+2;
	if($beginTime<$now && $month[0]>=$now){
		  return "1";break;
		}elseif($month[$i]<$now && $month[$i+1]>=$now  ){  //2014.04.11 删除 && $i!=0
			 return "$m";break;
		}elseif($m>35){
			return _("当前用户用时超过")."{$styleL} 3 {$styleR}"._("年")."s"." "._("程序无法计算");break;
		}
	}

}


//天
function day($length)
{
	$hour = 0;
	$minute = 0;
	$second = 0;
	$result = '';
	
	if ($length >= 60)
	{
		  
		$length = floor($length / 60); // 向下取整 即$length==分钟     第二个$length 秒
		if ($length >= 60)//走向小时
		{
			$length = floor($length / 60); //第一个 $length 小时     第二个  $length  分钟
			if ($length >= 24)//走向天
			{				
				$length = floor($length / 24);//第一个$length 天       第二个$length是小时			
				$result = $length;//返回的值为多少天
			}			
		}		
	}	
	return $result;
}

//天 时分秒
function online($length)
{
	$hour = 0;
	$minute = 0;
	$second = 0;
	$result = '';
	
	if ($length >= 60)
	{
		$second = $length % 60;
		if ($second > 0)
		{
			$result = $second . _("秒");
		}
		$length = floor($length / 60);
		if ($length >= 60)
		{
			$minute = $length % 60;
			if ($minute == 0)
			{
				if ($result != '')
				{
					$result = _("0分") . $result;
				}
			}
			else
			{
				$result = $minute . _("分") . $result;
			}
			$length = floor($length / 60);
			if ($length >= 24)
			{
				$hour = $length % 24;
				if ($hour == 0)
				{
					if ($result != '')
					{
						$result = _("0小时") . $result;
					}
				}
				else
				{
					$result = $minute . _("小时") . $result;
				}
				$length = floor($length / 24);
				$result = $length . _("天") . $result;
			}
			else
			{
				$result = $length . _("小时") . $result;
			}
		}
		else
		{
			$result = $length . _("分") . $result;
		}
	}
	else
	{	if($length!=""){
	
			$result = $length . _("秒");
		}else
		$result = $length;		
	}
	return $result;
} 

?>