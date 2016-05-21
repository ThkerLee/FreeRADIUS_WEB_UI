<?
function onlinetime($length,$str=" - ")
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
			$result = $second." " . _("秒") ;
		}
		$length = floor($length / 60);
		if ($length >= 60)
		{
			$minute = $length % 60;
			if ($minute == 0)
			{
				if ($result != '')
				{
					$result =" ". _("0分"). $str. $result;
				}
			}
			else
			{
				$result = $minute." " . _("分"). $str. $result;
			}
			$length = floor($length / 60);
			if ($length >= 24)
			{
				$hour = $length % 24;
				if ($hour == 0)
				{
					if ($result != '')
					{
						$result =" ". _("0小时"). $str. $result;
					}
				}
				else
				{
					$result = $hour." ". _("小时"). $str. $result;
				}
				$length = floor($length / 24);
				$result = $length." " . _("天"). $str. $result;
			}
			else
			{
				$result = $length." " . _("小时"). $str. $result;
			}
		}
		else
		{
			$result = $length." " . _("分"). $str. $result;
		}
	}
	else
	{
		$result = $length." " . _("秒");
	}
	return $result;
} ?>