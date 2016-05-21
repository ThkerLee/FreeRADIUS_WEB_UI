<?php  
date_default_timezone_set('Asia/Shanghai'); 
//header('Content-Type:text/html;charset=utf-8');
/**
 ************************************
 * 文件名:   class_public.php
 * 文件描述: 是针对一些功能相关的配置
 * 创建人:   
 * 创建日期:  2010-05-01
 * 版本号:   1.0
 * 修改记录: RealNatInf 03/25 2010
 ************************************
 */ 
 global $systemconfig; 
 if(file_exists("/etc/serial")){
 	$serialStr=file_get_contents("/etc/serial");
	$serialArr=explode("\n",$serialStr);
	$systemconfig["max_project"]=(int)$serialArr[0];
	$systemconfig["max_user"]   =(int)$serialArr[1]; 
 }else if(file_exists("/mnt/mysql/mysql/usr-gui/serial")){
  $serialStr=file_get_contents("/mnt/mysql/mysql/usr-gui/serial");
	$serialArr=explode("\n",$serialStr);
	$systemconfig["max_project"]=(int)$serialArr[0];
	$systemconfig["max_user"]   =(int)$serialArr[1];  
 }else if(file_exists("/etc/PINF")){
  $serialStr=file_get_contents("/etc/PINF");
	$serialArr=explode("\n",$serialStr);
	$systemconfig["max_project"]=(int)$serialArr[5];
	$systemconfig["max_user"]   =(int)$serialArr[6];   
 }else if(file_exists("/etc/inc/auth_licence/license")){ 
  $str =file_get_contents('/etc/inc/auth_licence/license');
  $info=explode("\n",$str); 
	$systemconfig["max_project"]=(int)$info[5];
	$systemconfig["max_user"]   =(int)$info[6];   
 }else if(is_numeric(@ exec("license -P"))){ 
  @ $systemconfig["max_project"] = (int)exec("license -P"); 
  @ $systemconfig["max_user"]   = (int)exec("license -M");  
 }else{
  @ $num = (int)exec("license -m"); 
    $systemconfig["max_project"] = ceil($num/50);//$num/100;
	  $systemconfig["max_user"]   =$num*2;//$num*3;  
 }
 /*
 64位，免费授权代码
if($systemconfig["max_project"]==0)$systemconfig["max_project"]=1;//当项目授权为0时，默认授权为1
if($systemconfig["max_user"]==0)$systemconfig["max_user"]=100;//当用户为0时，默认授权为100
*/
//echo "当前版本支持最大项目数为：".$systemconfig["max_project"]."&nbsp;&nbsp;最大用户数为：".$systemconfig["max_user"];


/**
 *===========================================
 * 函数名:   deviceSelected()
 * 参数:     $va
 * 功能描述:  查询设备数据，并且把数据以select选择框输出
 * 返回值:   成功->selectd 的配置信息, 失败 false
 * 作者:     leo
 * 修改记录:
 *===========================================
 */ 
function deviceSelected($va=""){
	global $db;
	//$deviceResult=$db->select_all("*","product","");
        $file = popen("license -t","r");
        $data = fgets($file);
        pclose($file);
        if($data == "RADIUS"){//如果是一体机 只显示蓝海设备
            $deviceArray=array( 
                    "natshell" =>_("蓝海卓越"),
                    "natshellWEB"=>_("蓝海卓越WEB认证"),		
				  );
	echo "<select  name='device' onChange='getDevice();' id='device'>";
if(is_array($deviceArray)){
		foreach($deviceArray as $deviceKey=>$deviceVa){
			if($deviceKey==$va){
				echo "<option value=".$deviceKey." selected>".$deviceVa."</option>";
			}else{
				echo "<option value=".$deviceKey.">".$deviceVa."</option>";
			}
		}
	}
	echo "</select>";
        }  else {
	$deviceArray=array( 
						"H3C"=>_("H3C"),
						"wansky"=>_("网思"),
						"ZTE" =>_("中兴"),
						"Cisco" =>_("思科"), 
						"ericsson" =>_("爱立信"),
						//"hi-spider"=>_("海蜘蛛"),
						"hi-spider2013"=>_("海蜘蛛2013"),
						"natshell" =>_("蓝海卓越"),
						"natshellWEB"=>_("蓝海卓越WEB认证"),
						"RP-PPPOE"=>_("海蜘蛛"),
						"ma5200f"  =>_("华为ME60"),
						"mikrotik" =>_("Mikrotik"),
						"sla-profile"=>_("贝尔-阿尔卡特"),
						"wanskyWEB"=>_("网思WEB认证"),
						"8021X"  =>_("802.1X 交换机"),
						"ik_radius" =>"爱快流控",
                                                "bhw_radius"=>"碧海威流控",
                                                "Panabit" =>"Panabit"
				  );
	echo "<select  name='device' onChange='getDevice();' id='device'>";
	if(is_array($deviceArray)){
		foreach($deviceArray as $deviceKey=>$deviceVa){
			if($deviceKey==$va){
				echo "<option value=".$deviceKey." selected>".$deviceVa."</option>";
			}else{
				echo "<option value=".$deviceKey.">".$deviceVa."</option>";
			}
		}
	}
	echo "</select>";
        }  
}

/**
 *===========================================
 * 函数名:   device
 * 参数:    $va
 * 功能描述:  通过传入设备的ID编号得到设备的名字
 * 返回值:   成功->返回设备名, 失败 false
 * 作者:     leo
 * 修改记录:
 *===========================================
 */ 
function deviceShow($va=""){
	global $db;
	$deviceArray=array(
						"H3C"=>_("H3C"),
						"wansky"=>_("网思"),
                                                  "ZTE" =>_("中兴"),
						"Cisco" =>_("思科"), 
						"ericsson" =>_("爱立信"),
				    //"hi-spider"=>_("海蜘蛛"),
						"hi-spider2013"=>_("海蜘蛛2013"),
						"natshell" =>_("蓝海卓越"),
						"natshellWEB"=>_("蓝海卓越WEB认证"),
						"RP-PPPOE"=>_("海蜘蛛"),
						"ma5200f"  =>_("华为ME60"),
						"mikrotik" =>_("Mikrotik"),
						"sla-profile"=>_("贝尔-阿尔卡特"),
						"wanskyWEB"=>_("网思WEB认证"),
						"8021X"  =>_("802.1X 交换机"),
                                                 "ik_radius" =>"爱快流控",
                                                 "bhw_radius"=>"碧海威流控",
                                                 "Panabit" =>"Panabit"
						
				  );
	if(is_array($deviceArray)){
		foreach($deviceArray as $deviceKey=>$deviceVa){
			if($deviceKey==$va){
				$deviceName=$deviceVa;
			}
		}
	}
	return $deviceName;
}

/**
 *===========================================
 * 函数名:    gradeSelected
 * 参数:      $gradeID
 * 功能描述:   查询设备数据，并且把数据以select选择框输出
 * 返回值:    返回selected html
 * 作者:     leo
 * 修改记录:
 *===========================================
 */ 
function gradeSelected($gradeID=""){
	global $db;
	$gradeResult=$db->select_all("*","grade","");
	echo "<select  name='gradeID'>";
	if(is_array($gradeResult)){
		foreach($gradeResult as $gradeKey=>$gradeRs){
			if($gradeRs["ID"]==$gradeID){
				echo "<option value=".$gradeRs["ID"]." selected>"._($gradeRs["name"])."</option>";
			}else{
				echo "<option value=".$gradeRs["ID"].">"._($gradeRs["name"])."</option>";
			}
		}
	}
	echo "</select>";
}
/**
 * 根据传入字类型type  和 周期 period值，返回项目记录
 *
 * @param type  period ID  userName
 * @return project selectoption 
 */

 
function productSelect($type="",$period="",$ID,$userName ,$projectID="",$areaID=""){
	global $db; 
	//查询项目集合 
  if($type=="month" || $type=="year" ){ 
      //$productResult   =$db->select_all("p.name,p.ID","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0  and p.ID!=$ID and  pj.projectID='".$projectID."' and p.type='".$type."' and pj.areaID=".$areaID); //2014.05.22 修改为下面的查询 去掉and p.type='".$type."'
     $productResult   =$db->select_all("p.name,p.ID","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0  and p.ID!=$ID and  pj.projectID='".$projectID."' and pj.areaID=".$areaID); 
	}if($type=="days" || $type=="hour" || $type=="flow"){
	   $productResult   =$db->select_all("p.name,p.ID","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0 and period=$period and p.ID!=$ID and  pj.projectID='".$projectID."' and p.type='".$type."'"); 
	   $productResult   =$db->select_all("p.name,p.ID","product as p,productandproject as pj","p.ID=pj.productID and p.hide=0 and p.ID!=".$ID." and  pj.projectID='".$projectID."' and p.type='".$type."'"); 
  } 
	echo "<select id='checkProductID'  name='checkProductID' onchange=ajaxInput('ajax_check.php','checkProductID','checkProductID','productTXT')>"; 
	if(is_array($productResult)){
		echo "<option  value='' >"._("请您选择产品")."</option>";
		foreach($productResult as $key=>$productRs){
			if($productRs["ID"]==$ID){
				echo "<option value=".$productRs["ID"].";".$ID.";".$userName."  selected>".$productRs["name"]."</option>";
							
			}else{
				echo "<option value=".$productRs["ID"].";".$ID.";".$userName." >".$productRs["name"]."</option>";
			}
		}
	}else{
	   echo "<option  value='' >"._("无符合更换的产品")."</option>";
	} 
	echo "</select><span id='productTXT'></span>";
 
}
 function productPeriod(){
 echo "<select id='period' name='period' onChange=\"totalMoneys();\">";
   echo "<option  value='' >"._("请您选择周期")."</option>";
   echo "<option value='1' selected >  1</option>"; 
		for($i=2;$i<=12;$i++){  
			 echo "<option value=".$i." >".$i."</option>"; 
		}
  echo "</select>";
 } 
/**
 *===========================================
 * 函数名:    projectAddSave
 * 参数:     $record
 * 功能描述:  传入添加 项目数据的
 * 返回值:    返回相应的字符串
 * 作者:     leo
 * 修改记录:
 *===========================================
 */ 
function projectAddSave($record){
	global $db,$systemconfig;
	$num=$db->select_count("project","");
	if($systemconfig["max_project"]!=0){//当不于0时表示设置了项目限制
		if($num>=$systemconfig["max_project"]){
			$error_msg=_("当前版本项目数为").$systemconfig["max_project"];
		}else{
			$db->insert_new("project",$record);
			$rs = $db->select_one("ID","project","0=0 order by ID desc limit 0,1");
			$man_projectID=$rs['ID'];//$db->insert_id();
		  //$man_areaID=$rs['areaID'];//$db->insert_id();
			//$manRs=$db->select_one("*","manager","manager_account='".$_SESSION["manager"]."'");
			//$man_projectID=($manRs["manager_project"])?$manRs["manager_project"].",".$man_projectID:$man_projectID;
		  //$man_areaID=($manRs["manager_area"])?$manRs["manager_area"].",".$man_areaID:$man_areaID;
		 // $db->update_new("manager","manager_account='".$_SESSION["manager"]."'",array("manager_area"=>$man_areaID));
			$error_msg=_("添加成功");
	  	$_SESSION["auth_project"] .=",".$man_projectID;
		}
	}else{
		$db->insert_new("project",$record);
		$rs = $db->select_one("ID","project","0=0 order by ID desc limit 0,1");
		$man_projectID=$rs['ID'];//$db->insert_id();
		//$manRs=$db->select_one("*","manager","manager_account='".$_SESSION["manager"]."'");
		//$man_projectID=($manRs["manager_area"])?$manRs["manager_area"].",".$man_areaID:$man_areaID;
		//$db->update_new("manager","manager_account='".$_SESSION["manager"]."'",array("manager_area"=>$man_areaID));
		$error_msg=_("添加成功");
	  $_SESSION["auth_project"] .=",".$man_projectID;
	}
	return $error_msg;
}



/**
 *===========================================
 * 函数名:   projectSelected
 * 参数:     $ID
 * 功能描述:  在项目表中查询出数据，以SELECT的形式输出，$ID表示默认的选中项
 * 返回值:   成功->selectd 的配置信息, 失败 false
 * 作者:     leo
 * 修改记录:
 *===========================================
 */ 
function projectSelected($ID=""){
	global $db;
	//查询项目集合
	$sql=" ID in (". $_SESSION["auth_project"].")";
	$projectResult=$db->select_all("*","project",$sql); 
	echo "<select name='projectID' id='projectID'>";
	echo "<option value=''>"._("选择项目")."</option>";
	if(is_array($projectResult)){
		foreach($projectResult as $key=>$projectRs){
			if($projectRs["ID"]==$ID){
				echo "<option value=".$projectRs["ID"]." selected>".$projectRs["name"]."</option>";
			}else{
				echo "<option value=".$projectRs["ID"].">".$projectRs["name"]."</option>";
			}
		}
	}
	echo "</select>";
}
 
function poolnameSelect($productID,$projectID,$poolname){
 global $db;  
  $rs = $db->select_one("poolname,device","project","ID=".$projectID); 
  $re =$db->select_one("pool","product","ID=".$productID);
  echo "<input type='radio' name='poolname'  id='poolname'value=''  >不选地址池";
 /* if($r["poolname"]!=""){
    $poolnameArr= explode("#",$rs["poolname"]);
    foreach($poolnameArr as $val){ 
    	if($poolname == $val)
        echo "<input type='radio' name='poolname'  id='poolname'value='".$val."'checked >".$val;
      else
        echo "<input type='radio' name='poolname'  id='poolname'value='".$val."' >".$val; 
    }
  }*/
  if($re["pool"]!=""){
   // $poolnameArr= explode("#",$rs["poolname"]);
    //foreach($poolnameArr as $val){ 
    	if($poolname == $re["pool"]){
        echo "<input type='radio' name='poolname'  id='poolname'value='".$re["pool"]."'checked >".$re["pool"];
        }else{
        echo "<input type='radio' name='poolname'  id='poolname'value='".$re["pool"]."' >".$re["pool"];       
        }
    }
   echo "<input type='hidden' id='device' name='device' value='".$rs["device"]."'>";
   
 	
}
/**
 *===========================================
 * 函数名:   projectShow
 * 参数:    $va
 * 功能描述:  通过传入项目的ID编号得到相关名字
 * 返回值:   成功->返回项目名, 失败 false
 * 作者:     leo
 * 修改记录:
 *===========================================
 */ 
function projectShow($ID=""){
	global $db;
	//查询项目集合
	$projectRs=$db->select_one("name","project","ID='".$ID."'");
	return $projectRs["name"];
}


/**
 * 根据传入字段值，以中文返回产品类型名称
 *
 * @param resource product $value
 * @return product chinese name
 */
function productTypeShow($va){
	switch($va)
	{
		case "year":
			return _("包年");
			break;		
		case "month":
			return _("包月");
 			break;	
		case "days":
			return _("包天");
 			break;		
		case "hour":
			return _("包时");
			break;		
		case "flow":
			return _("计流量");
			break;			
	//	case "netbar_hour":
	//		return _("时长计费");
	//		break;	
	}
}

/**
 * 根据传入字id值，返回项目记录
 *
 * @param id 
 * @return project selectoption 
 */
function productSelected($ID){
	global $db;
	//查询项目集合
	$productResult=$db->select_all("*","product","hide=0 and ID in (".$_SESSION["auth_product"].")");
	echo "<select id='productID' name='productID' onchange=ajaxInput('ajax_check.php','productID','productID','productTXT')>";
	if(is_array($productResult)){
		echo "<option value=''>"._("请您选择产品")."</option>";
		//if(!empty($ID)){
	   	 foreach($productResult as $key=>$productRs){
			if($productRs["ID"]==$ID){
				echo "<option value=".$productRs["ID"]." selected>".$productRs["name"]."</option>";
			}else{
				echo "<option value=".$productRs["ID"].">".$productRs["name"]."</option>";
			}
	     }
		//}
	}
	 echo "</select><span id='productTXT'></span>";
}
/**
 * 根据传入字用户账号值，返回tmpusers表 所需信息
 *
 * @param account
 * @return project selectoption 
 */
function select_tmpusers($account){
	global $db;
	//查询项目集合
	$userInfo=$db->select_all("DISTINCT u.account as username,u.password,r.enddatetime,p.upbandwidth,p.downbandwidth","userinfo as u,userattribute as a,orderinfo as o,product as p,userrun as r ,radreply as ra","u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and r.enddatetime>'".$NowTime."' and r.userID=u.ID and r.orderID=o.ID "); 
      
	  	// $userName=$db->select_one("*" ,"radreply","Attribute='Framed-IP-Address' and UserName='".$account."' and userID='".id."'"); 
		$userName=$db->select_one("*" ,"radreply","Attribute='Framed-IP-Address' and UserName='".$account."'"); 
        if($userName['Attribute']=="Framed-IP-Address"){//判断是否分配IP
		  	$userInfo[$k]['ip']=$userName['Value'];
		  }else{
		    $userInfo[$k]['ip']=''; 
		  }   
 return  $userInfo; 
}


/**
 * 根据传入字id值，返回产品名称
 *
 * @param id 
 * @return project selectoption 
 */
function productShow($ID=""){
	global $db;
	//查询项目集合
	$productRs=$db->select_one("name","product","ID='".$ID."'");
	return $productRs["name"];
}

/**
 * 返回单个产品的费率
 *
 * @param productID
 * @return int number 
 */
function productRate($productID){
	global $db;
	$productRs=$db->select_one("*","product","ID='".$productID."'");
	if($productRs["type"]=="year"){ 
		$unitprice =$productRs["price"]/($productRs["period"]*365*24*3600);//计算产品的费率，精确到秒 
	}else if($productRs["type"]=="month"){ 
		$unitprice =$productRs["price"]/($productRs["period"]*30*24*3600);//计算产品的费率，精确到秒 
	}elseif($productRs['type']=="hour"){
		$unitprice   =$productRs["price"]/($productRs["period"]*3600);//计算产品的费率，精确到秒		
	}elseif($productRs['type']=="flow"){ //这是计算流量的
		$unitprice   =$productRs["price"]/($productRs["period"]*1024);//计算产品的费率，精确到 秒/KB							
	}elseif($productRs['type']=="days"){//这是计算包天的
	    $unitprice =$productRs["price"]/($productRs["period"]*24*3600);//计算产品的费率，精确到秒
	}
	return $unitprice;
}
/**
 *===========================================
 * 函数名:    selectUserTmp 
 * 参数:      $account 
 * 功能描述:  能过传入的数据，查找出用户的密码  Ip  修改产品时
 * 返回值:    添加成功后返回用户编号，不成功就返回flase
 * 作者:     tm
 * 修改记录:
 *===========================================
 */ 
 function selectUserTmp($account){
 global $db;
    $NowTime=date("Y-m-d H:i:s",time()); 
	$username=$account;   
$userInfo=$db->select_all("DISTINCT u.account as username,u.password,r.enddatetime,p.upbandwidth,p.downbandwidth","userinfo as u,userattribute as a,orderinfo as o,product as p,userrun as r ,radreply as ra","u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and r.enddatetime>'".$NowTime."' and r.userID=u.ID and r.orderID=o.ID order by u.ID desc  "); 
  
if(is_array($userInfo)){
     foreach($userInfo as $k=>$u){ 	 
		 if($u['username']==$username){ 
		 
			 $userName=$db->select_one("*" ,"radreply","Attribute='Framed-IP-Address' and UserName='".$username."'");
			  
				  if($userName['Attribute']=="Framed-IP-Address" && $username==$userName['UserName']){//判断是否分配IP
					$userInfo[$k]['ip']=$userName['Value'];  
				  }else{
					$userInfo[$k]['ip']=''; 
					
				  }   
		}else{
		 unset($userInfo[$k]);
		}
 	}//end foreach   
}//end is_array  
 
  return  $userInfo;
 
 }
 

function AddTmpusers($account,$password,$productID=''){//
global $db;
    $NowTime=date("Y-m-d H:i:s",time()); 
	$username=$account;  
	$ID=getUserID($account) ;
//$userInfo=$db->select_all("DISTINCT u.account as username,u.password,r.enddatetime,p.upbandwidth,p.downbandwidth","userinfo as u,userattribute as a,orderinfo as o,product as p,userrun as r ,radreply as ra","u.ID=a.userID and o.productID=p.ID and o.ID=a.orderID and r.enddatetime>'".$NowTime."' and r.userID=u.ID and r.orderID=o.ID order by u.ID desc limit 0,1 "); 
 	 $ipRs=$db->select_one("*","radreply","userID='".$ID."' and Attribute='Framed-IP-Address'");
 	 $userName=$db->select_one("*" ,"radreply","Attribute='Framed-IP-Address' and UserName='".$username."'"); 
	 $Prs=$db->select_one("*","product","ID='".$productID."'");
	  if($ipRs){//判断是否分配IP
		$IP=$ipRs['Value'];
	  }else{
		$IP=''; 
	  }  
	 $sql=array(
			"userName"		 =>$username, 
			"password"		 =>$password,
			"ip"             =>$IP,
			"upbandwidth"    =>$Prs['upbandwidth'],
			"downbandwidth"  =>$Prs['downbandwidth'],
			"duedate"	     =>$user['enddatetime'],
			"operationtime"  =>$NowTime,
			"action"		 =>"add"
			);
			
	if(!$db->insert_new("tmpusers",$sql)){
			mysql_query("ROOLBACK");
		} 

}
/**
 *===========================================
 * 函数名:    EditTmpusers
 * 参数:     
 * 功能描述:  能过传入的数据，修改值
 * 返回值:    添加成功后返回用户编号，不成功就返回flase
 * 作者:     tm
 * 修改记录:
 *===========================================
 */ 
 
 function EditTmpusers($userName,$value){ 
 global $db;
 $NowTime=date("Y-m-d H:i:s",time()); 
 //查看该用户是否存在
 $value['operationtime']=$NowTime;
 $value['action']="modify";
 $sRs=$db->select_one('userName','tmpusers',"userName='".$userName."'");
 if(is_array($value)){//修改用户信息 ，即用户密码
 
	 if($sRs){//存在 则修改
		//$value 用户密码 用户ip 用户上行带宽 用户下行带宽  
		  if(!$db->update_new("tmpusers","userName='".$userName."'",$value)){
			  mysql_query("ROOLBACK");
		  } 
	 }else{//不存在 则添加
		 $value['userName']=$userName;
		 if(!$db->insert_new("tmpusers",$value)){
		    mysql_query("ROOLBACK");
		 } 
	 } //end else 
  } //end if   
	 return true;
 }
/**
 *===========================================
 * 函数名:    MnameUpdateSave
 * 参数:      $MID,$CID
 * 功能描述:   得到子账号的ID写入母账号Mname的值
 * 返回值:    添加成功后true，不成功就返回flase
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */ 
 
 function MnameUpdateSave($MID,$CID){
  global $db;
  $Mrs=$db->select_one("Mname","userinfo","ID='".$MID."'");
  if(!$Mrs) return false;
  
  if($Mrs['Mname']==""){//不存在子账号
   $cID=$CID."#";
   $sql_rs_err = $db->update_new("userinfo","ID='".$MID."'",array("Mname"=>$cID)); 
   if(!$sql_rs_err) return false;
   
  }else{//已经有子账号存在了
   $cID=$Mrs['Mname'].$CID."#";
   $sql_rs_err = $db->update_new("userinfo","ID='".$MID."'",array("Mname"=>$cID));
   if(!$sql_rs_err) return false;
  } 
  return true;
  
 }
 /**
 *===========================================
 * 函数名:    showMMname()//
 * 参数:      $MID
 * 功能描述 : 根据用户母账号ID显示Mname 即子ID
 * 返回值:   成功后Mname，不成功就返回flase
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */ 
 
 function showMMname($MID){
  global $db;
  $Mrs=$db->select_one("Mname","userinfo","ID='".$MID."'");
  if($Mrs['Mname']!="" && strpos($Mrs['Mname'],"#")){//不存在子账号
    return $Mrs['Mname'];
  }else{//已经有子账号存在了
    return false;
  }  
 }
 

/**
 *===========================================
 * 函数名:    userAddSave
 * 参数:     $record
 * 功能描述: 能过传入的数据，添加用户，并且判断是否合法~~如是添加成功返回用户的编号
 * 返回值:    添加成功后返回用户编号，不成功就返回flase
 * 作者:     leo
 * 修改记录:
 *===========================================
 */ 
function userAddSave($way,$record){
	global $db,$systemconfig;
    MysqlBegin();//开始事务定义
	$sql_a = true;  $sql_b = true;	$sql_c = true;	$sql_d = true;	$sql_e = true;	$sql_f = true;	$sql_g = true;	$sql_h = true;	$sql_i = true;	$sql_j = true;  $sql_k = true;	$sql_l = true;	$sql_m = true;	$sql_n = true;	$sql_o = true;	$sql_p = true; $sql_q = true; $sql_r = true; $sql_s=true; $sql_t =true; $sql_u =true; $sql_v = true;
	$sql_aa = true;$sql_bb = true;$sql_cc = true; $sql_dd = true; $sql_ee =true;
	//$ssql_aa 允许收取费用是否超额 $sql_bb 续费周期 $sql_cc 添加用户获取订单和状态值是否正常
	$num=$db->select_count("userinfo","");
	if(is_numeric($num) && $num>=0)  $sql_a = true; else $sql_a = false; 
	if($systemconfig["max_user"]!=0){
		if($num>=$systemconfig["max_user"]){
			$error_msg[]=_("当前版本用户数为").$systemconfig["max_user"];
		}
	} 
	$manager         =$_SESSION["manager"];
  $manageNum =$db->select_one("*","manager","manager_account = '".$manager."'");  
	$addusertotalnum =$manageNum["addusertotalnum"];//允许开户人数
  $addusernum      =$manageNum["addusernum"];//已开户人数    
	if($addusernum>=$addusertotalnum) $sql_u = false; else $sql_u = true ;   
	$projectID     =$record["projectID"];
	$money         =$record["money"];
        $productID     =$record["productID"];
	$timetype  	   =$record["timetype"];//1立即计时 0上线计时
	$iptype        =$record["iptype"];
	$gradeID	     =$record["gradeID"];
	$onlinenum     =$record["onlinenum"];
	$installcharge_type =$record["installcharge_type"];//0无初装费 
	$zjry	         =$record["operator"]; 
	$begindatetime =$record["begindatetime"];
	$adddatetime   =date("Y-m-d H:i:s",time());
	$closedatetime ='0000-00-00 00:00:00';//设置用户销户时间
	$receipt	     =$record["receipt"];
	$timestatus    =$record["onlinestatus"];//是否启用在线时长自动下线
	$onlinetime    =$record["onlinetime"];//在线时长
	$status        =1;//设置订单正常运行1表示正在使用的
	$remark        =$record['remark'];
	$financeID     =$record['financeID'];
	$financemoney  =$record['financemoney'];
	$areaID        =$record['areaID'];
	   //2014.07.22 添加押金
        if(empty($record['pledgemoney'])){
          $pledgemoney=0;  
        }  else {
           $pledgemoney   =$record['pledgemoney']; 
        }
	if($installcharge_type==0){
	   $record["installcharge"]=0;
	} 
        //2014.09.25添加受理人员
        $accept_name   =  $record['accept_name'];
	if($way=='once'){//单个添加
	$Mname         =(empty($record["Mname"]))?"":$record["Mname"];
	$account       =trim($record["account"]);
	$password      =$record["password"];
	$name          =$record["name"];  
	$sex           =$record["sex"];
	$birthday      =$record["birthday"];
	$cardid        =$record["cardid"];
	$workphone     =$record["workphone"];
	$homephone     =$record["homephone"];
	$mobile        =$record["mobile"];
	$email         =$record["email"];
	$address       =$record["address"];
	$ipaddress	   =$record["ipaddress"];
	$macbind	     =(empty($record["macbind"]))?0:$record["macbind"];
	$nasbind  	   =(empty($record["nasbind"]))?0:$record["nasbind"];
	$vlanbind  	   =(empty($record["vlanbind"]))?0:$record["vlanbind"];
	$MAC 		       =$record["MAC"];
	$NAS_IP		     =$record["NAS_IP"]; 
	$installcharge =$record["installcharge"]; 
	$period        =$record["period"];//续费周期$sql_bb
	$poolname      =$record["poolname"];//地址池名 
	if(!is_numeric($period)) $sql_bb = false;
	
	//$num=$db->select_count("userinfo","UserName='$account'");
		//if($num>0){
	//		$error_msg=_("系统中存在相同帐号名称");
	//	} 
		$sql=array(
			"UserName"		   =>$account,
			"account"    	   =>$account,
			"password"		   =>$password,
			"name"			     =>$name,
			"projectID"	     =>$projectID,
			"cardid"	       =>$cardid,
			"workphone"      =>$workphone,
			"homephone"      =>$homephone,
			"mobile"         =>$mobile,
			"email"          =>$email,
			"address"        =>$address,
			"money"          =>$money,
			"adddatetime"    =>$adddatetime,
			"closedatetime"  =>$closedatetime,
			"NAS_IP"         =>$NAS_IP,
			"MAC"            =>$MAC,
			"VLAN"           =>$VLAN,
			"gradeID"        =>$gradeID,
			"zjry"           =>$zjry,
			"receipt"        =>$receipt,
			"remark"         =>$remark, 
			"Mname"          =>$Mname,
			"sex"            =>$sex,
			"birthday"       =>$birthday,
			"status"         =>$timestatus,
			"onlinetime"     =>$onlinetime,
			"areaID"         =>$areaID,
                        "pledgemoney"    =>$pledgemoney,
                        "accept_name"    =>$accept_name
		); 
		  
		  if(!$error_msg){  
		    $sql_b = $db->insert_new("userinfo",$sql); 
		   
			$user= $db->select_one("ID","userinfo","UserName='".$account."'");  
			$userID=$user['ID'];//$db->insert_id(); 
                        //2014.07.22 添加-------押金----------------------------------------------------------------------
                        if($pledgemoney != 0){ 
                                    //更新用户帐单
                                 addUserBillInfo($userID,"e",$pledgemoney);//记录用户帐单
                                    //记录财务记录
                                    addCreditInfo($userID,"11",$pledgemoney); 
                        }
                        //--------------------------------------------------------------------
			if(is_numeric($userID) && $userID>0)   $sql_s = true ;  else  $sql_s = false;
			if($Mname!=''){//存在母账号
			  //判断母账号是否停机
			  $MID=getUserID($Mname); 
			  
			  //母账号的Mname字段值修改。。。添加该母账号的子账号ID
			  $sql_c = MnameUpdateSave($MID,$userID);   
			  
			  $Mstatus=$db->select_one("status,stop","userattribute","UserName='".$Mname."'") ;
			  if(!$Mstatus) $sql_d = false ;else $sql_d = true;
			  //$sql_d = $Mstatus; 
			  
			  if($Mstatus['status']==4) //停机
			    $status=4;//设置订单正常运行4表示 停机使用
			    
			}  
			//添加用户验证密码信息 
			$sql_e = addUserPassword($account,$password); 
			$sql_f = addUserOnline($account,$onlinenum); 
			 
			//更新用户帐单
			$sql_g = addUserBillInfo($userID,"0",$money); //开户预存
			if($installcharge_type==1){
			    $sql_h =  $db->query("update userinfo set money=money-".$installcharge." where ID='".$userID."'"); 
				$sql_i = addUserBillInfo($userID,"8",$installcharge);   //安装费用
			}
			
			
			for($i=1;$i<=$period;$i++){
			  //上线计时用户
			  if($timetype==0){
			        $orderID=addOrder($userID,$productID,0,$operator,$receipt); 
					if(is_numeric($orderID) && $orderID>0) $sql_j = true;  else $sql_j = false; 
					//用户运行时间
					$status = addUserRuntime($userID,$productID,$orderID,0,$timetype,$begindatetime);
					if(is_numeric($status) && $status >=0) $sql_k = true ;else $sql_k = false;
					//$sql_k =$status;
					if($i==1){
						$orderID1 = $orderID;  //第一个订单ID
						$status1   =1;   //第一个订单运行状态
						$db->update_new("userrun","orderID = '".$orderID1."'",array("status"=>1));
					}
			  
			  }else{//立即计时或指定时间计时用户 
			    $orderID=addOrder($userID,$productID,$status,$operator,$receipt); 
					if(is_numeric($orderID) && $orderID>0) $sql_j = true;  else $sql_j = false; 
					//查询上一定单的结束时间
					if($i>1) $begindatetime = userAddBegindatetime($userID); 
					//用户运行时间
					$status = addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime);
					if(is_numeric($status) && $status >=0) $sql_k = true ;else $sql_k = false; 
			    } 
			}	
			
			//获取当前用户正常阶段使用的订单ID or 订单状态
			if($timetype==1){//仅针对非上线计时用户
			    $normalStatus = userNormalStatus($userID);
				if($normalStatus) {
				   $orderID  =  $normalStatus["orderID"]; 
				   $status   =  $normalStatus["status"]; 
				}
			}else{
				   $orderID = $orderID1;
				   $status  = $status1; 
			}
			if(!is_numeric($orderID) || !is_numeric($status)) $sql_cc = false ; else $sql_cc = true; 
			 
		   
			//插入用户属性，以作为用户拨号的凭据,1=正常用户
			$sql_l = addUserAttribute($userID,$orderID,$status,$macbind,$onlinenum,$nasbind,$vlanbind);  
			  
			if($status==4){ 
				$uplist=array("stop"=>1); 
				$sql_m = $db->update_new("userattribute","UserName='".$account."' and userID='".$userID."'",$uplist); 
			}
			 
			
			//if($timetype=="1"){//用户技术参数,当订单立即起效果写入或更改用户技术参数的~
			$sql_n = addUserParaminfo($userID,$productID,'','',"userAdd"); 
			//}  
			//////////////////////////////////////////////////////////////////////////////
			$sql_o = addUserIpaddress($userID,$iptype,$ipaddress,$poolname);  
			//记录用户操作记录 
			$sql_p = addUserLogInfo($userID,"0",_("新增用户"),$name,$money);  
			
			//增加订单记录
			$sql_q = addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI']);  
			
			//记录财务记录
			$sql_r = addCreditInfo($userID,"0",$money,$projectID);  
			
			//添加在线时长自动下线功能 
			$sql_t = adduserOnlinetime($timestatus,$userID,$account,$onlinetime);
			//修改管理员开户用户数
			$sql_v = managerEditAddusernum();
			//分配收费金额是否在合法范围内
			$sql_aa = managerTotalmoneyShow();
			 
			//缴费类型
			if($financeID!=''){
			  $financeType = financeType($financeID);
			  $sql_ee =  $db->query("update userinfo set money=money-".$financemoney." where ID='".$userID."'"); 
			  $sql_dd = addUserBillInfo($userID,$financeType,$financemoney,_("System:add user financial subjects"),$operator='');
			} 
			 if ($sql_a &&  $sql_b && $sql_c && $sql_d && $sql_e && $sql_f && $sql_g && $sql_h && $sql_i && $sql_j &&  $sql_k && $sql_l && $sql_m && $sql_n && $sql_o && $sql_p && $sql_q && $sql_r && $sql_s &&  $sql_t && $sql_u && $sql_v && $sql_aa && $sql_bb  && $sql_cc && $sql_dd && $sql_ee){
                MysqlCommit(); 
                $error_msg[]=_("添加成功");	
             } else{
                MysqRoolback();
                if($sql_aa== false){
                	  $error_msg[]=_("添加失败，或收费金额已经达到上限");
                }else if($sql_bb== false){
				      $error_msg[]=_("添加失败请选择续费周期");
				}else{
                      $error_msg[]=_("添加失败，或开户用户已经达到上限");	
					  //.$sql_a .'a&&'.  $sql_b .'b&&'. $sql_c .'c&&'. $sql_d .'d&&'. $sql_e .'e&&'. $sql_f .'f&&'. $sql_g .'g&&'. $sql_h .'h&&'. $sql_i .'i&&'. $sql_j .'j&&'.  $sql_k .'k&&'. $sql_l .'l&&'. $sql_m .'m&&'. $sql_n .'n&&'. $sql_o .'o&&'. $sql_p .'p&&'. $sql_q .'q&&'. $sql_r .'r&&'. $sql_s .'s&&'.  $sql_t .'t&&'. $sql_u .'u&&'. $sql_v .'v&&'. $sql_aa .'aa&&'. $sql_bb.'bb'.$sql_cc."cc".""
               	}
                
            }  
	    } 
		 
	}elseif($way=='more'){//批量添加
		$startID=$record['startID'];
		$endID  =$record['endID'];
		$name          =''; 
		$cardid        ='';
		$workphone     ='';
		$homephone     ='';
		$mobile 	   ='';
		$email         ='';
		$address       ='';
		$ipaddress	   =''; 
		//$macbind	   =0;
		//$nasbind  	   =0;
                $macbind	    =(empty($record["macbind"]))?0:$record["macbind"];
                $nasbind  	   =(empty($record["nasbind"]))?0:$record["nasbind"];
                $vlanbind  	   =(empty($record["vlanbind"]))?0:$record["vlanbind"];
		$MAC 		   =$record["MAC"];
		$NAS_IP		   =''; 
		$installcharge =$record["installcharge"];
                

	 for($i=$startID;$i<=$endID;$i++){
	    $account       =$record["prefix"].$i; 
		$num=$db->select_count("userinfo","UserName='$account'"); 
		//if(!$num){ $sql_a = false; break;}else $sql_a = true;
		if($num>0){
			$error_msg[]=_("系统中存在相同帐号名称 $account ");
			//break;
		}
	 }
	 $j = $startID - 1; //因为++原因，获取的值要与$endID同步的话就必须-1
	 for($i=$startID;$i<=$endID;$i++){
	   $account       =$record["prefix"].$i; 
	   $password      =pwd_rand();
	   //ROSIP认证  
	   $sql=array(
				"UserName"		 =>$account,
				"account"    	 =>$account,
				"password"		 =>$password,
				"name"			   =>$name,
				"projectID"	   =>$projectID,
				"cardid"	     =>$cardid,
				"workphone"    =>$workphone,
				"homephone"    =>$homephone,
				"mobile"       =>$mobile,
				"email"        =>$email,
				"address"      =>$address,
				"money"        =>$money,
				"adddatetime"  =>$adddatetime,
				"closedatetime"=>$closedatetime,
				"NAS_IP"       =>$NAS_IP,
				"MAC"          =>$MAC,
				"VLAN"         =>$VLAN,
				"gradeID"		   =>$gradeID,
				"zjry"         =>$zjry,
				"receipt"      =>$receipt,
				"remark"       =>$remark, 
		    "status"  =>$timestatus,
			  "onlinetime"   =>$onlinetime,
			  "areaID"       =>$areaID
			);
	  if(!$error_msg){ 
			if(!$db->insert_new("userinfo",$sql))  $sql_b_= false;   else $sql_b = true; 
			//$userID=$db->insert_id();
		    $user= $db->select_one("ID","userinfo","UserName='".$account."'");  
			$userID=$user['ID'];//$db->insert_id(); 
			if(is_numeric($userID) && $userID>0)   $sql_c = true ;  else  $sql_c = false;
			//添加用户验证密码信息
			$sql_d = addUserPassword($account,$password);
			$sql_e = addUserOnline($account,$onlinenum);
			
			//更新用户帐单
			$sql_f = addUserBillInfo($userID,"0",$money);//记录用户帐单
	
			if($installcharge_type==1){
				$sql_g = $db->query("update userinfo set money=money-".$installcharge." where ID='".$userID."'");
				$sql_h = addUserBillInfo($userID,"8",$installcharge);//记录用户帐单
			}
			
			//查入订单,成功则返回订单编号
			
			
			$orderID=addOrder($userID,$productID,$status,$operator,$receipt); 
			if(is_numeric($orderID) && $orderID>0) $sql_i = true;  else $sql_i = false; 
		    //用户运行时间
			$status = addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime);
			if(is_numeric($status) && $status >=0) $sql_j = true ;else $sql_j = false;
			 
			//插入用户属性，以作为用户拨号的凭据,1=正常用户
			$sql_i = addUserAttribute($userID,$orderID,$status,$macbind,$onlinenum,$nasbind,$vlanbind);
		
			//if($timetype=="1"){//用户技术参数,当订单立即起效果写入或更改用户技术参数的~
			$sql_l = addUserParaminfo($userID,$productID,'','',"userAdd");
			//}
			
			$sql_m = addUserIpaddress($userID,$iptype,$ipaddress);
			
			//记录用户操作记录
			$sql_n = addUserLogInfo($userID,"0",_("新增用户"),$name,$money);
			
			//增加订单记录
			$sql_o = addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI']);
			
			//记录财务记录
			$sql_p = addCreditInfo($userID,"0",$money,$projectID);
			//添加在线时长自动下线功能
			$sql_q = adduserOnlinetime($timestatus,$userID,$account,$onlinetime); 
			//修改管理员开户用户数
			$sql_v = managerEditAddusernum();
			//分配收费金额是否在合法范围内
			$sql_aa = managerTotalmoneyShow();
			
			if ($sql_a &&  $sql_b && $sql_c && $sql_d && $sql_e && $sql_f && $sql_g && $sql_h && $sql_i && $sql_j &&  $sql_k && $sql_l && $sql_m && $sql_n && $sql_o && $sql_p && $sql_p && sql_u && $sql_v && $sql_aa){
			     $j++;
				    continue;
            }else{
		          break;
           }  
	   } // end !$error_msg 
	 } // end for 
	      if ($j == $endID){//获取  当$j == $endID 即表明从开始ID到结束ID的遍历添加成功，else 有遍历为全部添加成功  roolback 回滚   
                MysqlCommit(); 
                $error_msg[]=_("添加成功");	
           } else{
                MysqRoolback();
                $error_msg[]=_("添加失败，或开户用户已经达到上限，或收费金额已经达到上限");	
           }   
	} elseif($way=='netbar'){//时长计费 
	
     $account =trim($record["account"]);
	   $password      =$record["password"];
	   $name          =$record["name"];  
	   $sex           =$record["sex"]; 
	   $cardid        =$record["cardid"];
		 $onlinenum     =1;
	   $ipaddress	    =$record["ipaddress"];
		 $timetype =0;
		 $installcharge_type=0;
		 $zjry =$_SESSION['manager'];
		 $begindatetime ='0000-00-00 00:00:00'; 
		 $timestatus = 'disable';
		 $onlinetime=0;
		 $financeID='';
		 $financemoney=0;  
		 $sql=array(
			"UserName"		 =>$account,
			"account"    	 =>$account,
			"password"		 =>$password,
			"name"			   =>$name,
			"projectID"	   =>$projectID,
			"cardid"	     =>$cardid,
			"workphone"      =>'',
			"homephone"      =>'',
			"mobile"         =>'',
			"email"          =>'',
			"address"        =>'',
			"money"          =>$money,
			"adddatetime"    =>$adddatetime,
			"closedatetime"  =>$closedatetime,
			"NAS_IP"         =>'',
			"MAC"            =>'',
			"VLAN"           =>'',
			"gradeID"		     =>$gradeID,
			"zjry"           =>$zjry,
			"receipt"        =>$receipt,
			"remark"         =>$remark, 
			"Mname"          =>'',
			"sex"            =>$sex,
			"birthday"       =>'',
			"status"         =>$timestatus,
			"onlinetime"     =>$onlinetime
		); 
		if(!$error_msg){  
		    $sql_b = $db->insert_new("userinfo",$sql); 
		   
			$user= $db->select_one("ID","userinfo","UserName='".$account."'");  
			$userID=$user['ID'];//$db->insert_id(); 
			if(is_numeric($userID) && $userID>0)   $sql_s = true ;  else  $sql_s = false; 
			//添加用户验证密码信息 
			$sql_e = addUserPassword($account,$password); 
			$sql_f = addUserOnline($account,$onlinenum); 
			 
			//更新用户帐单
			$sql_g = addUserBillInfo($userID,"0",$money); //开户预存
			if($installcharge_type==1){
			    $sql_h =  $db->query("update userinfo set money=money-".$installcharge." where ID='".$userID."'"); 
				$sql_i = addUserBillInfo($userID,"8",$installcharge);   //安装费用
			}   
			  //上线计时用户
			  if($timetype==0){
			        $orderID=addOrder($userID,$productID,0,$operator,$receipt); 
					if(is_numeric($orderID) && $orderID>0) $sql_j = true;  else $sql_j = false; 
					//用户运行时间
					$status = addUserRuntime($userID,$productID,$orderID,0,$timetype,$begindatetime);
					if(is_numeric($status) && $status >=0) $sql_k = true ;else $sql_k = false; 
			  
			  }else{//立即计时或指定时间计时用户 
			        $orderID=addOrder($userID,$productID,$status,$operator,$receipt); 
					if(is_numeric($orderID) && $orderID>0) $sql_j = true;  else $sql_j = false;  
					 
					//用户运行时间
					$status = addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime);
					if(is_numeric($status) && $status >=0) $sql_k = true ;else $sql_k = false; 
			    }  
			//插入用户属性，以作为用户拨号的凭据,1=正常用户
			$sql_l = addUserAttribute($userID,$orderID,$status,$macbind,$onlinenum,$nasbind,$vlanbind);  
			  
			if($status==4){ 
				$uplist=array("stop"=>1); 
				$sql_m = $db->update_new("userattribute","UserName='".$account."' and userID='".$userID."'",$uplist); 
			} 
			
			//if($timetype=="1"){//用户技术参数,当订单立即起效果写入或更改用户技术参数的~
			$sql_n = addUserParaminfo($userID,$productID,'','',"userAdd");  
			//////////////////////////////////////////////////////////////////////////////
			$sql_o = addUserIpaddress($userID,$iptype,$ipaddress);  
			//记录用户操作记录 
			$sql_p = addUserLogInfo($userID,"0",_("新增用户"),$name,$money);  
			
			//增加订单记录
			$sql_q = addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI']);  
			
			//记录财务记录
			$sql_r = addCreditInfo($userID,"0",$money,$projectID);  
			
			//更正用户的到期时间 
		   	$Penddatetime= $db->select_one("enddatetime","product","ID='".$productID."'");
		   	if(strtotime($Penddatetime["enddatetime"])>time())  
			  $sql_s = $db->update_new("userrun","orderID='".$orderID."' and userID='".$userID."'",array("enddatetime"=>$Penddatetime["enddatetime"]));  
			  elseif(strtotime($Penddatetime["enddatetime"])<time())
			  $sql_s =false;
			  else
			   $sql_s = $db->update_new("userrun","orderID='".$orderID."' and userID='".$userID."'",array("enddatetime"=>"0000-00-00 00:00:00"));
			
			//添加在线时长自动下线功能 
			//$sql_t = adduserOnlinetime($timestatus,$userID,$account,$onlinetime);
			//修改管理员开户用户数
			$sql_v = managerEditAddusernum();
			//分配收费金额是否在合法范围内
			$sql_aa = managerTotalmoneyShow(); 
			 
			 if ($sql_a &&  $sql_b && $sql_c && $sql_d && $sql_e && $sql_f && $sql_g && $sql_h && $sql_i && $sql_j &&  $sql_k && $sql_l && $sql_m && $sql_n && $sql_o && $sql_p && $sql_q && $sql_r && $sql_s &&  $sql_t && $sql_u && $sql_v && $sql_aa && $sql_bb  && $sql_cc && $sql_dd && $sql_ee){
           MysqlCommit(); 
           $error_msg[]=_("添加成功");	
      } else{
           MysqRoolback();
           if($sql_aa== false){
               $error_msg[]=_("添加失败，或收费金额已经达到上限" );
           }else if($sql_bb== false){
				      $error_msg[]=_("添加失败请选择续费周期");
				   }else if($sql_s== false && $way=='netbar'){
				   	  $error_msg[]=_("时长计费类型产品结束时间不允许小于当前时间");
				   }else{  $error_msg[]=_("添加失败，或开户用户已经达到上限" );	
					  //.$sql_a .'a&&'.  $sql_b .'b&&'. $sql_c .'c&&'. $sql_d .'d&&'. $sql_e .'e&&'. $sql_f .'f&&'. $sql_g .'g&&'. $sql_h .'h&&'. $sql_i .'i&&'. $sql_j .'j&&'.  $sql_k .'k&&'. $sql_l .'l&&'. $sql_m .'m&&'. $sql_n .'n&&'. $sql_o .'o&&'. $sql_p .'p&&'. $sql_q .'q&&'. $sql_r .'r&&'. $sql_s .'s&&'.  $sql_t .'t&&'. $sql_u .'u&&'. $sql_v .'v&&'. $sql_aa .'aa&&'. $sql_bb.'bb'.$sql_cc."cc".$orderID."status".$status.""
            }
       }  
	    }  
		}
	  MysqlEnd();
	 return  $error_msg;
	
}

/*******************************************
 * 根据传入帐号，得到用户的ID编号
 *
 * @param $account
 * @return userID
 */
function getUserID($account){
	global $db;
	$rs=$db->select_one("ID","userinfo","account='".$account."'");
	if($rs)	return $rs["ID"];
	else return false;
	 
} 
/*******************************************
 * 根据输入用户编号，得到用户帐号
 *
 * @param $account
 * @return userID
 */
function getUserName($ID){
	global $db;
	$rs=$db->select_one("account","userinfo","ID='".$ID."'");
	if($rs) return $rs["account"];
	else return false;
	 
}
/*******************************************
 * 根据输入用户编号，得到用户姓名
 *作者：nancy
 * @param userID
 * @return $name
 */
function getName($ID){
	global $db;
	$rs=$db->select_one("name","userinfo","ID='".$ID."'");
	if($rs){
		return $rs["name"];
	}
}
/*****************************************
 * 添加用户验证参数
 *
 * @param resource product $value
 * @return true or false
 */

function addUserPassword($UserName,$password){
	global $db;
	$userID=getUserID($UserName); 
	$query_str3="delete from radcheck where UserName='".$UserName."'";  
	$projectRs=$db->select_one("p.device,p.mtu,p.accounts,p.status","project as p,userinfo as u","p.ID=u.projectID and u.ID='".$userID."'");//
	if(!$projectRs)  return false;
	$sql_rs_err = $db->query($query_str3);
	if(!$sql_rs_err) return false;
	
	if($projectRs["device"]=="mikrotik" && $projectRs["status"]=='enable') {
		//添加rosIP认证信息
		$pwdSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','Password','==','');";
		$sql_rs_err = $db->query($pwdSQL);
		if(!$sql_rs_err) return false;
		
		$authSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','Auth-Type',':=','Local');";
		$sql_rs_err = $db->query($authSQL);	
		if(!$sql_rs_err) return false;
	   
    }else{ 
	//添加密码属性
	$UserName   = $UserName;
	$Attribute 	= "User-Password";
	$op			= ":=";
	$Value		=  $password;
	$strSQL = "INSERT INTO radcheck(UserName,Attribute,op,Value) values('$UserName','$Attribute','$op','$Value');";
	$sql_rs_err = $db->query($strSQL);
	if(!$sql_rs_err) return false;
	}
	 return true;
}


function addUserOnline($UserName,$num){
	global $db;
	$query_str3="delete from radcheck where Attribute='Simultaneous-Use' And UserName='".$UserName."'";
	$sql_rs_err = $db->query($query_str3);
    if(!$sql_rs_err) return false;
//	if(!empty($num)){
//		//添加密码属性
//		$UserName   = $UserName;
//		$Attribute 	= "Simultaneous-Use";
//		$op			= ":=";
//		$Value		=  $num;
//		$strSQL = "INSERT INTO radcheck(UserName,Attribute,op,Value) values('$UserName','$Attribute','$op','$Value');";
//		$db->query($strSQL);
//	}
	return true;

}

/*****************************************
 * 添加用户技术参数信息  正常产品添加
 *
 * @param resource product $value
 * @return true or false
 */
 function addUserParaminfo($userID,$productID,$E_upbandwidth='',$E_downbandwidth='',$userAdd=''){
	global $db;
	$configRs =$db->select_one("*","config","0=0 order by ID desc limit 0,1");
	$userRs   =$db->select_one("*","userinfo","ID='".$userID."'");
	$productRs=$db->select_one("*","product","ID='".$productID."'");// 查询出产品的信息
	$projectRs=$db->select_one("p.device,p.mtu,p.accounts,p.status","project as p,userinfo as u","p.ID=u.projectID and u.ID='".$userID."'");//
	$attrRs   =$db->select_one("*","userattribute","userID='".$userID."'");
	$orderID  =$attrRs["orderID"];  
	if(!$configRs || !$userRs || !$productRs ||!$projectRs || !$attrRs  ) return false; 
	
	$acount = $projectRs['accounts'];
	$MAC    = $userRs ['MAC'];
	$projectStatus=$projectRs['status'];  
	if($E_upbandwidth!="" && $E_downbandwidth!=""){//限速上下带宽
		$productRs['upbandwidth']=$productRs['limitupbandwidth'];
		$productRs['downbandwidth']=$productRs['limitdownbandwidth'];
	}
	if($productRs['type']=='flow' && $productRs['limittype'] ==1  && $userAdd==''){//是否为流量用户 限速用户  $userAdd!="" 添加用户
	  // 是否超额  
	  $runinfo =$db->select_one("*",'runinfo','userID="'.$userID.'" and orderID="'.$orderID.'"  order by  `limit`  desc limit 0,1 '  );  
	  if(!$runinfo) return false;
	  //替用户下线
	  $UserName= getUserName($userID); 
	  if($runinfo){//超额
	   if($runinfo['limit']=='1'){
	      $productRs['upbandwidth']=$productRs['limitupbandwidth'];
		    $productRs['downbandwidth']=$productRs['limitdownbandwidth'];
		 }
	  }  
	} 
		//用户设备信息
	$device      =$projectRs["device"];//设备信息
	$mtu		     =$projectRs["mtu"];
	$speedStatus =$configRs["speedStatus"];
	$projectID   =$userRs["projectID"];
	$UserName    =$userRs["account"];
	$PWD         =$userRs["password"];
	//产品带宽信息
	$upload_bandwidth   = $productRs['upbandwidth'];
	$download_bandwidth = $productRs['downbandwidth']; 
	$upload             = $productRs["upbandwidth"] * 1024;
	$download           = $productRs["downbandwidth"] * 1024;
	$Up_Burst           = $productRs["upbandwidth"] * 1024/8 * 1.5;
	$Down_Burst         = $productRs["downbandwidth"]  * 1024/8 * 1.5;	
 
	 
	//添加用户mtu值  
  //删除已有的带宽参数  
	$sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and  Attribute !='Framed-IP-Address' and Attribute !='Framed-Pool'  ");	
	if(!$sql_rs_err) return false;  
	//根据不同的设备添加用户的技术参数
	if($device=='natshell' || $device=='wansky' ){
		if($speedStatus=="1"){//当启用内部限速规则
			$speed=$db->select_all("*","speedrule","projectID='".$projectID."'");
			if(!$speed) return false;
			$speedCount=count($speed);
			if(is_array($speed)){
				foreach($speed as $speedKey=>$speedRs){
					$dstip[$speedKey]    =$speedRs["dstip"];
				}
			}
			//$del_sql="delete from radreply where userID='".$userID."' and  Attribute !='Framed-IP-Address' ";
			//$sql_rs_err = $db->query($del_sql); 
			if(!$sql_rs_err) return false;  
			
			if($speedCount){
				$speedCount=$speedCount*2;$j=0;
				for($i=0;$i<$speedCount;$i+=2){
					$str =  "1#".($i+1)."=nomatch dst net ".$dstip[$j];
					$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-filter','+=','".$str."');"; 
					$sql_rs_err = $db->query($strShaperSQL);
					if(!$sql_rs_err) return false;
					$str =  "2#".($i+1)."=nomatch src net ".$dstip[$j];
					$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-filter','+=','".$str."');"; 
					$sql_rs_err = $db->query($strShaperSQL);
					if(!$sql_rs_err) return false;
				}//end for
			}//end if
			$j=$j*2;
			$str =  "in#1=flt1  rate-limit ".$upload;
			$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','".$str."');"; 
			$sql_rs_err = $db->query($strShaperSQL);
			if(!$sql_rs_err) return false;
			$str =  "out#2=flt2  rate-limit ".$download;
			$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','".$str."');"; 
			$sql_rs_err = $db->query($strShaperSQL);	
			if(!$sql_rs_err) return false;
		}else{
			//$sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and  Attribute !='Framed-IP-Address' ");	
			//if(!$sql_rs_err) return false;						
																																										
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','in#1=all shape $upload  $Up_Burst pass');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','out#2=all shape $download $Down_Burst pass');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
			 //radcheck
			$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	 
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
			$sql_rs_err = $db->query($strSQL); 
			if(!$sql_rs_err) return false;
		}//end speedStatus 
		if(!$sql_rs_err) return false; 
	}elseif($device=='natshellWEB' || $device=='wanskyWEB'){//蓝海卓越WEB认证
	
			$product_download_bandwidth  =floor($download_bandwidth*1.2);
			$product_upload_bandwidth    =floor($upload_bandwidth*1.2);
			
	    //$sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and  Attribute !='Framed-IP-Address' ");	
			//if(!$sql_rs_err) return false;						
																																										
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','ChilliSpot-Bandwidth-Max-Up','+=','$product_upload_bandwidth');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','ChilliSpot-Bandwidth-Max-Down','+=','$product_download_bandwidth');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
			 //radcheck
			$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	 
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
			$sql_rs_err = $db->query($strSQL); 
			if(!$sql_rs_err) return false;
	}else if($device=="mikrotik"){//另外一种ROS 
	   // $userName=getUserName($userID);
		//$sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and (Attribute='mpd-limit' or Attribute='mpd-filter' or Attribute='Mikrotik-Rate-Limit' or Attribute='Mikrotik-Rate-E-Limit')");	 
		//if(!$sql_rs_err) return false;
					
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Mikrotik-Rate-Limit','+=','$upload_bandwidth"."k/".$download_bandwidth."k');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
		
		if($projectStatus=='enable'){//启用
		   //radcheck
			$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
			if(!$sql_rs_err) return false;			
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','Password','==','');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','Auth-Type',':=','Local');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
		
		}else{//禁用
		    //radcheck
			$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");				
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
		}
		
	}else if($device=="H3C"){
            /* 修改时间2014.08.04
             * 这个是原来的
 	    $product_upload_bandwidth    = $upload_bandwidth * 1024/8;
            $product_download_bandwidth  = $download_bandwidth * 1024/8; 
		
	    $peak_product_upload_bandwidth = $product_upload_bandwidth+5000 ;
	    $peak_product_download_bandwidth = $product_download_bandwidth+5000 ;
 
		while ( strlen_utf8($product_upload_bandwidth) < 8 ) {
		  $product_upload_bandwidth = "0".$product_upload_bandwidth;	
		}
		while ( strlen_utf8($peak_product_download_bandwidth) < 8) {
		  $peak_product_download_bandwidth = "0".$peak_product_download_bandwidth;	
		}
		while ( strlen_utf8($peak_product_upload_bandwidth) < 8) {
		  $peak_product_upload_bandwidth = "0".$peak_product_upload_bandwidth;	
		}
		while ( strlen_utf8($product_download_bandwidth) < 8) {
		  $product_download_bandwidth = "0".$product_download_bandwidth;	
		}
		
 	
		$str = "{$peak_product_upload_bandwidth}{$product_upload_bandwidth}{$peak_product_download_bandwidth}{$product_download_bandwidth}";
		$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Class','+=','".$str."');"; 
		$sql_rs_err = $db->query($strShaperSQL); 
		if(!$sql_rs_err) return false; */
            //2014.08.04 重新生成限速规则
            $product_upload_bandwidth    = $upload_bandwidth * 1000;//上传规则
            $product_download_bandwidth  = $download_bandwidth * 1000; //下载规则
            $upload_bandwidth_sql="insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','H3C-Input-Average-Rate','+=','".$product_upload_bandwidth."');";
            $download_bandwidth_sql="insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','H3C-Output-Average-Rate','+=','".$product_download_bandwidth."');";
            $sql_rs_err = $db->query($upload_bandwidth_sql);
            $sql_rs_err = $db->query($download_bandwidth_sql);
            if(!$sql_rs_err) return false;
 	    //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
		if(!$sql_rs_err) return false;			
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
	
		
	
	}else if($device=="ma5200f"){ 
 	   $product_upload_bandwidth    = $upload_bandwidth * 1024;
		 $product_download_bandwidth  = $download_bandwidth * 1024;
 
		//删除已有的带宽参数 
		//$strSQL = "delete  from radreply where UserName='".$UserName."' and Attribute !='Framed-IP-Address' ";
		//$sql_rs_err = $db->query($strSQL);
		//if(!$sql_rs_err) return false;
		
	    $peak_product_upload_bandwidth = $product_upload_bandwidth+5000 ;
	    $peak_product_download_bandwidth = $product_download_bandwidth+5000  ;
 
		while ( strlen_utf8($product_upload_bandwidth) < 8 ) {
		  $product_upload_bandwidth = "0".$product_upload_bandwidth;	
		}
		while ( strlen_utf8($peak_product_download_bandwidth) < 8) {
		  $peak_product_download_bandwidth = "0".$peak_product_download_bandwidth;	
		}
		while ( strlen_utf8($peak_product_upload_bandwidth) < 8) {
		  $peak_product_upload_bandwidth = "0".$peak_product_upload_bandwidth;	
		}
		while ( strlen_utf8($product_download_bandwidth) < 8) {
		  $product_download_bandwidth = "0".$product_download_bandwidth;	
		}
 	
		$str = "{$peak_product_upload_bandwidth}{$product_upload_bandwidth}{$peak_product_download_bandwidth}{$product_download_bandwidth}";
		$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Class','+=','".$str."');"; 
		$sql_rs_err = $db->query($strShaperSQL); 
		if(!$sql_rs_err) return false; 
 	    //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
		if(!$sql_rs_err) return false;			
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
	}elseif($device=="hi-spider"){
		//$strSQL = "delete  from radreply where UserName='".$UserName."' and Attribute !='Framed-IP-Address' ";
		//$sql_rs_err = $db->query($strSQL);
		//if(!$sql_rs_err) return false;
		
		$product_upload_bandwidth    = floor($upload_bandwidth/8);
		$product_download_bandwidth  = floor($download_bandwidth/8);  
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Hispider-Upload-Rate-Limit','+=','$product_upload_bandwidth');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
		
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Hispider-Download-Rate-Limit','+=','$product_download_bandwidth');";
		$sql_rs_err = $db->query($strSQL);	
		if(!$sql_rs_err) return false;
         //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");				
		if(!$sql_rs_err) return false;
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
	
	}elseif($device=="hi-spider2013"){
		//$strSQL = "delete  from radreply where UserName='".$UserName."' and Attribute !='Framed-IP-Address' ";
	  //	$sql_rs_err = $db->query($strSQL);
		//if(!$sql_rs_err) return false;
		
		$product_upload_bandwidth    = floor($upload_bandwidth/8);
		$product_download_bandwidth  = floor($download_bandwidth/8);
    $product_upload_bandwidth    =$product_upload_bandwidth."-".$product_upload_bandwidth;
    $product_download_bandwidth  =$product_download_bandwidth."-".$product_download_bandwidth;  
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Hispider-Upload-Rate-Limit','+=','$product_upload_bandwidth');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
		
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Hispider-Download-Rate-Limit','+=','$product_download_bandwidth');";
		$sql_rs_err = $db->query($strSQL);	
		if(!$sql_rs_err) return false;
         //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");				
		if(!$sql_rs_err) return false;
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
	
	}
	elseif($device=="RP-PPPOE"){
		//删除已有的带宽参数 
		//$strSQL = "delete  from radreply where UserName='".$UserName."' and Attribute !='Framed-IP-Address' ";
		//$sql_rs_err = $db->query($strSQL);
		//if(!$sql_rs_err) return false;
		
		$product_upload_bandwidth    = floor($upload_bandwidth/8);
		$product_download_bandwidth  = floor($download_bandwidth/8);
			
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Upstream-Speed-Limit','+=','$product_upload_bandwidth');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
		
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Downstream-Speed-Limit','+=','$product_download_bandwidth');";
		$sql_rs_err = $db->query($strSQL);	
		if(!$sql_rs_err) return false;
         //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");				
		if(!$sql_rs_err) return false;
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
	}elseif($device=="ericsson"){//爱立信 
	    //删除已有的带宽参数 
		//$strSQL = "delete  from radreply where UserName='".$UserName."' and Attribute !='Framed-IP-Address' ";
		//$db->query($strSQL);
		
	    if($upload_bandwidth>0 && $upload_bandwidth<1024){
			$product_upload_bandwidth    =1;  
		}else{ 
			$product_upload_bandwidth    =round($upload_bandwidth/1024);//四舍五入
		}
		
		if($download_bandwidth>0 && $download_bandwidth<1024){
			$product_download_bandwidth  =1;  
		}else{ 
			$product_download_bandwidth  =round($download_bandwidth/1024);//四舍五入 
		}		
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Qos-Policy-Policing','+=','$product_upload_bandwidth"."M-Up"."');";
		$sql_rs_err = $db->query($strSQL); 
		if(!$sql_rs_err) return false;
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Qos-Policy-Metering','+=','$product_download_bandwidth"."M-Down"."');";
		$sql_rs_err = $db->query($strSQL); 
		if(!$sql_rs_err) return false;
		 //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
		if(!$sql_rs_err) return false;			
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
	}elseif($device=="sla-profile"){ //贝尔-阿尔卡特 
	   //规则只有下载带宽
		if($download_bandwidth>0 && $download_bandwidth<1024){
			$product_download_bandwidth  =1;  
		}else{ 
			$product_download_bandwidth  =round($download_bandwidth/1024);//四舍五入 
		}
		$attributeVal ="sla-prof-".$product_download_bandwidth."M";
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Alc-SLA-Prof-Str','+=','$attributeVal');";
		$sql_rs_err = $db->query($strSQL); 
		if(!$sql_rs_err) return false; 
		 //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
		if(!$sql_rs_err) return false;			
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false; 
  }elseif($device=="ZTE"){ //中兴  
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','ZTE-Rate-Ctrl-Scr-Up','+=','$upload_bandwidth');";
		$sql_rs_err = $db->query($strSQL); 
		if(!$sql_rs_err) return false;   
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','ZTE-Rate-Ctrl-Scr-Down','+=','$download_bandwidth');";
		$sql_rs_err = $db->query($strSQL); 
		if(!$sql_rs_err) return false; 
		 //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
		if(!$sql_rs_err) return false;			
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false; 
  }elseif($device=="Cisco"){ //思科设备
  	$val ="ip:qos-policy-in=add-class(sub,(class-default),police(".$download_bandwidth."))";
  	$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Cisco-AVPair','+=','$val');";
		
		
		$sql_rs_err = $db->query($strSQL); 
		if(!$sql_rs_err) return false;
		 //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
		if(!$sql_rs_err) return false;			
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false; 
  }elseif ($device=="ik_radius") { //2014.08.04添加爱快设备限速规则
            $product_upload_bandwidth    = $upload_bandwidth ;//上传规则
            $product_download_bandwidth  = $download_bandwidth ; //下载规则
            $upload_bandwidth_sql="insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Upstream-Speed-Limit','+=','".$product_upload_bandwidth."');";
            $download_bandwidth_sql="insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Downstream-Speed-Limit','+=','".$product_download_bandwidth."');";
            $sql_rs_err = $db->query($upload_bandwidth_sql);
            $sql_rs_err = $db->query($download_bandwidth_sql);
            if(!$sql_rs_err) return false;
      
            //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
		if(!$sql_rs_err) return false;			
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;  
    }elseif ($device=="bhw_radius") {  //2014.08.04添加碧海威设备限速规则
                 //删除已有的带宽参数 
		//$strSQL = "delete  from radreply where UserName='".$UserName."' and Attribute !='Framed-IP-Address' ";
		//$sql_rs_err = $db->query($strSQL);
		//if(!$sql_rs_err) return false;
		
		$product_upload_bandwidth    = floor($upload_bandwidth/8);
		$product_download_bandwidth  = floor($download_bandwidth/8);
			
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Upstream-Speed-Limit','+=','$product_upload_bandwidth');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
		
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Downstream-Speed-Limit','+=','$product_download_bandwidth');";
		$sql_rs_err = $db->query($strSQL);	
		if(!$sql_rs_err) return false;
                 //radcheck
		$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");				
		if(!$sql_rs_err) return false;
		$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;   
    }elseif ($device=="Panabit") {  //2014.08.04添加Panabit设备限速规则和Microtik 一样
            $strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Mikrotik-Rate-Limit','+=','$upload_bandwidth"."k/".$download_bandwidth."k');";
		$sql_rs_err = $db->query($strSQL);
		if(!$sql_rs_err) return false;
		
		if($projectStatus=='enable'){//启用
		   //radcheck
			$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");	
			if(!$sql_rs_err) return false;			
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','Password','==','');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','Auth-Type',':=','Local');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
		
		}else{//禁用
		    //radcheck
			$sql_rs_err = $db->delete_new("radcheck","UserName='".$UserName."' and (Attribute='Password' or Attribute='Auth-Type' or Attribute='User-Password' )");				
			if(!$sql_rs_err) return false;
			$strSQL = "insert into radcheck(UserName,Attribute,op,Value) values('$UserName','User-Password',':=','$PWD');";
			$sql_rs_err = $db->query($strSQL);
			if(!$sql_rs_err) return false;
		}

    }
  $strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Acct-Interim-Interval','+=','$acount');";
	$sql_rs_err = $db->query($strSQL);
	if(!$sql_rs_err) return false; 
	$mtu_sql="insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Framed-MTU','+=','$mtu')"; 
	$sql_rs_err = $db->query($mtu_sql);
	if(!$sql_rs_err) return false;	
	return true;
}

 
/***************************************
 * 添加用户相关属性信息，这里这作为用户登录的凭据
 *
 * @param $userID,$iptype,$ipaddress
 * @return true or false
 */
function addUserAttribute($userID,$orderID,$status,$macbind,$onlinenum="",$nasbind="",$vlanbind=""){
	global $db;
	$UserName=getUserName($userID); 
	if($status==4 || $status==0) $stop = 1;
	else $stop = 0; 
	$sql=array(
			"userID"=>$userID,
			"UserName"=>$UserName,
			"orderID"=>$orderID,
			"status"=>$status,
			"macbind"=>$macbind,
			"stop"=>$stop,
			"onlinenum"=>$onlinenum,
			"nasbind"=>$nasbind,
			"vlanbind"=>$vlanbind
		);
	$sql_rs_err = $db->insert_new("userattribute",$sql); 
	if(!$sql_rs_err) return false; 
 	$sql_rs_err = $db->update_new("orderinfo","ID='".$orderID."'",array("status"=>$status)); 
	if(!$sql_rs_err) return false;
	return true;
}

/***************************************
 * 修改用户相关属性信息，这里这作为用户登录的凭据
 *
 * @param $userID,$record
 * @ record 是要更改的字段
 * @return true or false
 */
function updateUserAttribute($userID,$record){
	global $db;
	$sql = ' and  begindatetime !="0000-00-00 00:00:00" ';
	if($record['orderID']){
	 $sql =" and orderID ='".$record['orderID']."' ";
	}
	$enddatetime = $db->select_one("enddatetime,status,begindatetime","userrun","userID='".$userID."'".$sql );// and orderID='".$record['orderID']."'"
	//if(!$enddatetime)  return false; //当修改为上线计时用户但用户还未上线做修改   该值不存在
	$sql_rs_err = $db->update_new("userattribute","userID='".$userID."'",$record);
	if(!$sql_rs_err)   return false;
	 if(strtotime($enddatetime['enddatetime']) <time() && strtotime($enddatetime['begindatetime'])>"0000-00-00 00:00:00"){
	  $sql_rs_err = $db->update_new("userattribute","userID='".$userID."'",array("status"=>4));
	  if(!$sql_rs_err) return false;
	}
	return true;
}

/***************************************
 * 添加用户IP地址信息
 *
 * @param $userID,$iptype,$ipaddress
 * @return true or false
 */
function addUserIpaddress($userID,$iptype,$ipaddress,$poolname=""){
	global $db;
	$UserName   = getUserName($userID);
	$attribute 	= "Framed-IP-Address";
	$op			    = ":=";
	$value		  = $ipaddress;	
	$sql_rs_err = $db->delete_new("radreply","userID=".$userID." and (Attribute='$attribute' or Attribute='Framed-Pool')");
	if(!sql_rs_err) return false;
	if($iptype==1){//当类型等于1时间表示要系统分配IP地址
		$strSql     = "INSERT INTO radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','$attribute','$op','$value');";
		if($db->query($strSql))  return true;
		else  return false; 
	}elseif($iptype==2 && $poolname!=""){
		$sql=array(
		"userID"=>$userID,
		"UserName"=>$UserName,
		"Attribute"=>"Framed-Pool",
		"op"=>$op,
		"Value"=>$poolname 
	);
    $sql_rs_err = $db->insert_new("radreply",$sql); 
    if(!$sql_rs_err) return false; 
	}
	return true;
} 

/**
 * 添加用户运行时间
 *
 * @param $userID,$productID,$orderID,$stautsu,$timetype
 *  $status 当前订单的状态
 *  $timetype 订单的时间类弄，1表示立即计时，0表示用户上线才开始算起
 * @return true or false
 */
function addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime="",$stopdatetime=""){
	global $db;
	$todaytime     =(!empty($begindatetime))?$begindatetime:date("Y-m-d H:i:s",time());	
	$stopdatetime  =(!empty($stopdatetime))?$stopdatetime:"0000-00-00 00:00:00";//暂停时间 
	$stats         ="0";//统计 
	if($timetype=="0"){
		$beigndatetime     ="0000-00-00 00:00:00";
		$enddatetime       ="0000-00-00 00:00:00";
		$orderenddatetime  ="0000-00-00 00:00:00";	
	}elseif($timetype=="1"){
		$productRs=$db->select_one("*","product","ID='".$productID."'");// 查询出产品的信息
		if(!$productRs)  return false;
		if($stopdatetime!="0000-00-00 00:00:00"){ 
			if($productRs['type']=="year"){//只能精确到天,如果精确到时分秒的话那么导入用户的开始时间就是23点59:59了
			    $begindatetime = mysqlYmdDate(Mysqlgtedate($stopdatetime,-$productRs['period'],"year")); 
			}elseif($productRs['type']=="month"){ 
			    $begindatetime = mysqlYmdDate(mysqlGteDate($stopdatetime,-$productRs['period'],"month"));  
			}elseif($productRs['type']=="days"){
			    $begindatetime = mysqlYmdDate(mysqlGteDate($stopdatetime,-$productRs['period'],"day")); 
			}elseif($productRs['type']=="hour"){ 
			    $begindatetime = mysqlYmdDate(mysqlGteDate($stopdatetime,-1,"month")); 
			}elseif($productRs['type']=="flow"){
			//产品 流量计费周期  和计费类型 天/月 添加修改时间 2012-02-09
			    if($productRs['timetype']=="days"){
				    $begindatetime = mysqlYmdDate(mysqlGteDate($stopdatetime,-$productRs['periodtime'],"day"));  //流量包天用户
				}else if($productRs['timetype']=="months"){ 
				    $begindatetime = mysqlYmdDate(mysqlGteDate($stopdatetime,-$productRs['periodtime'],"month")); //流量包月用户
				}  
			} 
			$enddatetime       = $stopdatetime; 
			$orderenddatetime  = $stopdatetime;	  
		} else {
			if($productRs['type']=="year"){
				$datestr =  mysqlGteDate($todaytime,$productRs['period'],"year");//mysql 添加时间方法必须连接数据库
				//$todaytime." +".$productRs['period']." year";   //php时间戳 弊端 时间上线 2038 
			}elseif($productRs['type']=="month"){
			    $datestr =  mysqlGteDate($todaytime,$productRs['period'],"month"); 
			}elseif($productRs['type']=="days"){
			    $datestr =  mysqlGteDate($todaytime,$productRs['period'],"day"); 
			}elseif($productRs['type']=="hour"){  
			    $datestr =  mysqlGteDate($todaytime,1,"month"); 
			}elseif($productRs['type']=="flow"){
			//产品 流量计费周期  和计费类型 天/月 添加修改时间 2012-02-09
			    if($productRs['timetype']=="days"){ 
			        $datestr =  mysqlGteDate($todaytime,$productRs['periodtime'],"day");  //流量包天用户
				}else if($productRs['timetype']=="months"){
			        $datestr =  mysqlGteDate($todaytime,$productRs['periodtime'],"month"); //流量包月用户
				}  
			} 
			$begindatetime     = $todaytime;//开进运行时间 
			$enddatetime       = $datestr;//date('Y-m-d H:i:s',strtotime($datestr));//运行结束时间
			$orderenddatetime  = $datestr;//date('Y-m-d H:i:s',strtotime($datestr));//订单应该结束时间  

			if($enddatetime>="2038-01-01" || $orderenddatetime>="2038-01-01")return false;
			if(date("Y-m-d H:i:s",time()) >  $datestr  && $beigndatetime!="0000-00-00 00:00:00" && $enddatetime!="0000-00-00 00:00:00"){//到期用户   排除上线计时用户
				$status=4;
				$sql_rs_err = $db->update_new("userattribute","userID='".$userID."' and orderID='".$orderID."'",array('status'=>4,"stop"=>1));
				if(!$sql_rs_err) return false;
				$sql_rs_err = $db->update_new("orderinfo","userID='".$userID."' and  ID='".$orderID."'",array('status'=>4));
				if(!$sql_rs_err) return false;
			 }else if( strtotime($begindatetime)  >  time()  &&  strtotime($enddatetime) >  time()  ){//等待运行订单
			    $status=0;  
				  $sql_rs_err = $db->update_new("orderinfo","userID='".$userID."' and  ID='".$orderID."'",array('status'=>0));
				  if(!$sql_rs_err) return false; 
			 }else if(strtotime($begindatetime) <= time()   &&  strtotime($enddatetime) >  time()){//正在使用订单
			    $status=1;  
				  $sql_rs_err = $db->update_new("orderinfo","userID='".$userID."' and  ID='".$orderID."'",array('status'=>1));
				if(!$sql_rs_err) return false;
			    $sql_rs_err = $db->update_new("userattribute","userID='".$userID."' and orderID='".$orderID."'",array('status'=>1,"stop"=>0));
				if(!$sql_rs_err) return false; 
			 }  
		}
	}  
	
	$sql=array(
		"userID"=>$userID,
		"orderID"=>$orderID,
		"begindatetime"=>$begindatetime,
		"enddatetime"=>$enddatetime,
		"orderenddatetime"=>$orderenddatetime,
		"stats"=>$stats,
		"status"=>$status	
	);    
    $sql_rs_err = $db->insert_new("userrun",$sql);
	if(!$sql_rs_err) return false;
	else  return  $status;   
}

/**
 * 执行用户等待运行时间
 *
 * @param $userID,$productID,$orderID,$stautsu,$timetype
 * @return true or false
 */

function updateUserRuntime($ID,$productID,$status,$begindatetime=""){
	global $db;
	$todaytime     =(!empty($begindatetime))?$begindatetime:date("Y-m-d H:i:s",time());	
	$stopdatetime  ="0000-00-00 00:00:00";//暂停时间
	$stats         ="0";//统计
	$productRs=$db->select_one("*","product","ID='".$productID."'");// 查询出产品的信息
	//计时，计流量是以按月为一个周期，最小单位为一个月	
	if($productRs['type']=="year"){
		$datestr = $todaytime." +".$productRs['period']." year";
	}elseif($productRs['type']=="month"){
		$datestr = $todaytime." +".$productRs['period']." month";
	}elseif($productRs['type']=="hour"){
		$datestr = $todaytime." +1 month";
	}elseif($productRs['type']=="days"){
	    $datestr = $todaytime." +".$productRs['period']." day";
	}elseif($productRs['type']=="flow"){
	//产品 流量计费周期  和计费类型 天/月 添加修改时间 2012-02-09
		if($productRs['timetype']=="days"){
			$datestr = $todaytime." +".$productRs['periodtime']." day"; //流量包天用户
		}else if($productRs['timetype']=="months"){
			$datestr = $todaytime." +".$productRs['periodtime']." month";//流量包月用户
		} 
		//$datestr = $todaytime." +1 month";
	}
	$begindatetime     = $todaytime;//开进运行时间 
	$enddatetime       = date('Y-m-d H:i:s',strtotime($datestr));//运行结束时间
	$orderenddatetime  = date('Y-m-d H:i:s',strtotime($datestr));//订单应该结束时间
	$sql=array(
		"begindatetime"=>$begindatetime,
		"enddatetime"=>$enddatetime,
		"orderenddatetime"=>$orderenddatetime,
		"stopdatetime"=>$stopdatetime,
		"status"=>$status	
	);
	if($db->update_new("userrun","ID='".$ID."'",$sql)){
		return true;
	}else{
		return false;
	} 
} 
/**
 * 添加用户操作记录
 *
 * @param $userID,$type,$content
 * @return true or false
 */
function addUserLogInfo($userID,$type,$content,$name,$money='',$operator=""){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$rs=$db->select_one('*','userinfo','ID="'.$userID.'"');
	if(!$rs)  return false;
	$sql=array(
		"userID"=>$userID,
		"account"=>$rs['account'],
		"name"=>$name,
		"projectID"=>$rs['projectID'], 
		"money"=>$money,
		"type"=>$type,
		"content"=>$content,
		"operator"=>$operator,
		"adddatetime"=>date("Y-m-d H:i:s",time()),
	);
	$sql_rs_err = $db->insert_new("userlog",$sql);
	if(!$sql_rs_err) return false;
	else  return true; 
}


/**
 * 添加用户帐单记录
 *
 * @param $userID,$type,$money
 * @return true or false
 */
function addUserBillInfo($userID,$type,$money,$remark="",$operator=''){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$sql=array(
		"userID"=>$userID,
		"type"=>$type,
		"money"=>$money,
		"operator"=>$operator,
		"remark"=>$remark,
		"adddatetime"=>date("Y-m-d H:i:s",time()),
	);
	if($db->insert_new("userbill",$sql)){
		return true;
	}else{
		return false;
	}
}



/**
 * 用户申请停机、申请恢复操作
 *
 * @param $userID,$type
 * @param $type 2 apply for showdown account , 3 aplly form open account
 * @return true or false
 */
function  userApplyInfo($userID,$stopdatetime,$restoredatetime){
	global $db;	
	$sql=array(
		"stopdatetime"=>$stopdatetime,
		"restoredatetime"=>$restoredatetime//这是把正在运行（类型为1）的订单更改为停机状态（类型5）
	);
	$oRs=$db->select_one("orderID","userattribute","userID='".$userID."'"); 
	$result=$db->update_new("userrun","userID='".$userID."' and orderID='".$oRs["orderID"]."'",$sql);//暂停运行表信息
	if($result) return true;
	else return false; 
}

/**
 *用户销户处理
 *
 * @param $userID,$orderID,$money,这里是指用户实际退的金额
 * @param 
 * @return true or false
 */

function userClosing($userID,$orderID,$money,$operator=""){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$uRs     =$db->select_one("money","userinfo","ID='".$userID."'");
	if($uRs["money"]>0){
		if($money<=$uRs["money"]){//实际退费，要比余额小的
			$db->query("update userinfo set money=money-".$money.",closedatetime='".date("Y-m-d H:i:s",time())."' where ID='".$userID."'");
			addOrderRefund($userID,1,$uRs["money"],$money,$orderID,$operator); //同生成退费订单
		}			
	}else{
			$db->query("update userinfo set closedatetime='".date("Y-m-d H:i:s",time())."' where ID='".$userID."'");
			addOrderRefund($userID,1,0,0,$orderID,$operator); //同生成退费订单
	}
	
}





/**
 *计算用户使用产品还剩余多少金额
 * @param $userID,$orderID
 * @return product Money 
 */
function computeUserProductSurplus($orderID){
	global $db;
	$orderRs  =$db->select_one("*","orderinfo","ID='".$orderID."'");
	$productRs=$db->select_one("*","product","ID='".$orderRs["productID"]."'");
	$runRs    =$db->select_one("*","userrun","orderID='".$orderID."'"); 
	//计算当前用户的使用时间，根据不同类型计算出剩余金额
	if($runRs["status"]==0 || ($runRs["status"]==1 && $runRs["enddatetime"]=='0000-00-00 00:00:00')){//表示还没有开始上线的用户  or 上线计时用户订单正在使用但还未上线用户
		$second=0;
	} else{
		$second =abs(strtotime(date("Y-m-d H:i:s"))- strtotime($runRs["begindatetime"]));//计算用户使用的时间 
	}
	$unitprice   =productRate($orderRs["productID"]);//根据产品的编号计算本产品的费率
	if($productRs["type"]=="hour"){
		$mRs=$db->select_one("sum(stats) as toaldata","runinfo","orderID='$orderID'");
		$second=$mRs["toaldata"];
		$useMoney=ceil($unitprice*$second);
	}else if($productRs["type"]=="flow"){//流量设置
		$mRs=$db->select_one("sum(stats) as toaldata","runinfo","orderID='$orderID'");
		$second=$mRs["toaldata"]; 	
		$useMoney=ceil($second*$unitprice);//使用的金额,ceil 返回不小于value的下一个整数  floor返回不大于value的下一个整数
	}else{
		$useMoney=ceil($second*$unitprice);//使用的金额,ceil 返回不小于value的下一个整数  floor返回不大于value的下一个整数
	}
	$surplusMoney=$productRs["price"]-$useMoney; //计算出剩余金额
	
	return $surplusMoney;
}


/**
 * 添加一条新的订单
 *
 * @param $userID,$orderID,$operator,$status
 * @return insert into succes return orderID or false
 */
 
function addOrder($userID,$productID,$status,$operator="",$receipt=null){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	//查入订单
	$orderSql=array(
		"userID"=>$userID,
		"productID"=>$productID,
		"operator"=>$operator,
		"adddatetime"=>date("Y-m-d H:i:s",time()),
		"receipt"    =>$receipt,
		"status"=>$status
	);	
	if($db->insert_new("orderinfo",$orderSql)){
		//$orderID=$db->insert_id(); 
		$order =$db->select_one("ID","orderinfo","userID='".$userID."' order by ID DESC limit 0,1");
		//$orderID=$db->insert_id();
		$orderID=$order['ID'];
		if(!is_numeric($orderID) || $orderID <=0) return false;
		
		//同时扣除用户帐号中的金额
		$productRs   =$db->select_one("price","product","ID='".$productID."'");
		$productPrice=(int)$productRs["price"];
		$sql_rs_err = $db->query("update userinfo set money=money-".$productPrice." where ID='".$userID."'");
		addUserBillInfo($userID,"4",$productRs["price"],'',$operator);	
		if(!$productRs || !$sql_rs_err) return false;	
		return $orderID;
	}else{
		return false;
	}	
} 


/**
 * 添加订单记录
 *
 * @param $userID,$type,$orderID,$operator,$content
 * @return true or false
 */
function addOrderLogInfo($userID,$orderID,$type,$content,$operator=''){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$sql=array(
		"orderID"=>$orderID,
		"userID"=>$userID,
		"type"=>$type,
		"operator"=>$operator,
		"adddatetime"=>date("Y-m-d H:i:s",time()),
		"content"=>$content
	);
	if($db->insert_new("orderlog",$sql)){
		return true;
	}else{
		return false;
	}

}
/**
 * 作废一张订单 用户销户
 *
 * @param resource $orderID
 * @return true or false
 */
function closingOrder($userID,$orderID){
	global $db;
	$time = date("Y-m-d H:i:s",time());
	addOrderLogInfo($userID,$orderID,"1",$_SERVER['REQUEST_URI'],"SYSTEM_fn_canelOrder");//加写订单日志
	$surplusMoney=computeUserProductSurplus($orderID);//得到此订单应该返回的金额
	addRechargeInfo($userID,$surplusMoney);//把些订单的剩余的费用返回给用户
	addUserBillInfo($userID,"3",$surplusMoney);	//写用户帐单记录，3为退费
	//作废订单并非删除    
	 $db->update_new("userrun"," orderID='".$orderID."'",array("enddatetime"=>$time,"status"=>4 ));  
	 $db->update_new("orderinfo","ID='".$orderID."' ",array("status"=>4));
	 $db->update_new("userinfo"," ID='".$userID."'",array("closedatetime"=>$time ));
	 addOrderRefund($userID,0,$surplusMoney,$surplusMoney,$orderID,"SYSTEM_fn_cancelOrder"); //同生成退费订单
	 //生成操作记录
	 addUserLogInfo($userID,$type,$content,$name,$surplusMoney,$operator=""); 
}


/**
 * 作废一张订单  订单撤销  并删除订单
 *
 * @param resource $orderID
 * @return true or false
 */
function cancelOrder($userID,$orderID,$type="",$content="",$name=""){
	global $db;
	addOrderLogInfo($userID,$orderID,"1",$_SERVER['REQUEST_URI'],"SYSTEM_fn_canelOrder");//加写订单日志
	$surplusMoney=computeUserProductSurplus($orderID);//得到此订单应该返回的金额
	addRechargeInfo($userID,$surplusMoney);//把些订单的剩余的费用返回给用户
	addUserBillInfo($userID,"3",$surplusMoney);	//写用户帐单记录，3为退费
	//作废订单并非删除
	 $db->query("update userrun set enddatetime='".date("Y-m-d H:i:s",time())."',status=4 where orderID='".$orderID."'");
	 $db->query("update orderinfo set status=4 where ID='".$orderID."'"); 
	 addOrderRefund($userID,0,$surplusMoney,$surplusMoney,$orderID,"SYSTEM_fn_cancelOrder"); //同生成退费订单
	 //生成操作记录
	 addUserLogInfo($userID,$type,$content,$name,$surplusMoney,$operator="");
	 
	//删除订单
	 $db->delete_new("userrun","orderID = '".$orderID."' and userID = '".$userID."'");
	 $db->delete_new("orderinfo","ID = '".$orderID."' and userID = '".$userID."'");
	 //判断当前订单是否存在  即撤销的是否为下一个等待运行订单且该订单已被默认为拨号验证订单
	$orderNow = $db->select_count("userattribute","userID='".$userID."' and orderID = '".$orderID."' ");
	if($orderNow>0){//撤销的等待运行订单为用户拨号验证的订单
	  //查询该用户是否有正在运行 等待运行定单编号
	  $orderRun = $db->select_one("orderID","userrun","userID = '".$userID."' and status in (0,1) order by status desc limit 0,1");
	  if($orderRun){//存在正在运行订单 或等待运行订单  条件 1，0
	     $db->update_new("userattribute","userID='".$userID."'",array("orderID"=>$orderRun["orderID"],"stop"=>0,"status"=>1));   
	  }else{//用户到期  查询最近一到期订单编号
	      $orderStopAfter = $db->select_one("orderID","userrun","userID = '".$userID."' and status =4 order by orderID desc limit 0,1");
	      if( $orderStopAfter)   $db->update_new("userattribute","userID='".$userID."'",array("orderID"=>$orderStopAfter["orderID"],"stop"=>1,"status"=>4));   
	  } 
	}
	
}




/**
 * 用户充值，添加用户金额
 *  
 * @param resource $userID,$type,$money,$opeartor
 * if $type is ont empty ,this is subtract user's money .or type=1 this is del user's money
 * @return true or false
 */
function addRechargeInfo($userID,$money,$type=""){
	global $db;

	if(empty($type)){
		$sql="update userinfo set money=money+".(int)$money."  where ID='".$userID."'";
	}else{
		$sql="update userinfo set money=money-".(int)$money."  where ID='".$userID."'";
	}
	if($db->query($sql)){
	 	return true;
	}else{
		return false;
	}
	
}


/**
 * 财务统计
 *  
 * @param resource $userID,$type,$money,$opeartor
 * @return true or false
 */
function addCreditInfo($userID,$type,$money,$projectID="",$operator=""){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$sql=array(
		"userID"=>$userID,
		"type"=>$type,
		"money"=>$money,
		"operator"=>$operator,
		"projectID"=>$projectID,
		"adddatetime"=>date("Y-m-d H:i:s",time())
	);
	if($db->insert_new("credit",$sql)){
		return true;
	}else{
		return false;
	}		
}


/**
 * 生成一张退费订单
 *
 * @param $userID,$money,$type ,$orderID
 * @return true or false
 */
function addOrderRefund($userID,$type,$money,$factmoney,$orderID,$operator,$remark=""){
	global $db;
	$sql=array(
		"orderID"=>$orderID,
		"userID"=>$userID,
		"money"=>$money,
		"factmoney"=>$factmoney,//实际退费
		"type"=>$type,
		"operator"=>$operator,
		"remark"=>$remark,
		"adddatetime"=>date("Y-m-d H:i:s",time())
	);
	if($db->insert_new("orderrefund",$sql)){
		return true;
	}else{
		return false;
	}
}


/**
 * 生成卡片
 *
 * @param $prefix,$startNum,$endNum,$money,ivalidTime
 * @return true or false
 */
function addCard($prefix,$startNum,$endNum,$money,$ivalidTime,$remark){
	global $db;
	$dateTime=date("Y-m-d H:i:s",time()); 
	$k=0;
	for($i=$startNum;$i<=$endNum;$i++){ 
	    $Number=$startNum+$k;  
	    $length1 = strlen($startNum); 
		$length2 = strlen($Number);//+$i 后的字符的长度
	    $len = $length1 - $length2;
	    $zero ='0';
	    for($j=0;$j<$len;$j++) $zero .="0";  
	    $zeros = substr($zero,0,-1);
		$cardNumber =$prefix.$zeros.$Number; 
		$str=$prefix.$i.$dateTime;	
		$actviation=md5($str);	
		$actviation=rand(10000000,100000000);	
		$sql=array(
			"cardNumber"=>$cardNumber,
			"prefix"=>$prefix,
			"startNum"=>$startNum,
			"endNum"=>$endNum,
			"money"=>$money,//金额
			"ivalidTime"=>$ivalidTime,
			"sold"=>0,
			"recharge"=>0,
			"operator"=>$_SESSION["manager"],
			"solder"=>"NULL",
			"actviation"=>$actviation,//激活码
			"cardAddTime"=>$dateTime,
			"remark"=>$remark
		);
		$db->insert_new("card",$sql);
		$k++;
	}
	//insert new cardlog infomation 
	addCardLog(0,"Creat Card:{$prefix}-{$startNum}-{$endNum}");
}
/**
 * 生成卡片记录表
 *
 * @param $prefix,$startNum,$endNum,$money,ivalidTime
 * @return true or false
 */
function addCardLog($type,$content,$operator=""){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$sql=array(
		"type"=>$type,
		"content"=>$content,
		"operator"=>$operator,
		"addTime"=>date("Y-m-d H:i:s",time())
	);
	$sql_rs_err = $db->insert_new("cardlog",$sql);
	if(!$sql_rs_err) return false;
	else return true;
}
/**
 * 获取项目ros认证状态
 *
 * @param $projectID
 * @return status or false
 */
function showProjectStatus($projectID){
 global $db;
 $pj=$db->select_one('status','project','ID="'.$projectID.'"');
 if($pj)
 return $pj['status'];
 else
 return false;
} 
 
/**
 * 充值卡片记录表 type= 4
 *
 * @param $prefix,$startNum,$endNum,$money,ivalidTime
 * @return true or false
 */
function rechargeCardLog($type=4,$content,$operator=""){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$sql=array(
		"type"=>$type,
		"content"=>$content,
		"operator"=>$operator,
		"addTime"=>date("Y-m-d H:i:s",time())
	);
	$sql_rs_err = $db->insert_new("cardlog",$sql);
	if(!$sql_rs_err) return false;
	else return true;
}

/**
 * 根据传入的订单运行编号，1，2，3，4，返回当前在线状态
 *
 * @param resource product $value
 * @return true or false
 */
function orderStatus($num){
	switch($num){
		case "0":
			$str="<font color='#519A01'>"._("等待运行")."</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>"._("正在使用")."</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>"._("到期使用")."</font>";
			break;
	//	case "3":
	//		$str="<font color='#ff0000'>"._("欠费停用")."</font>";
	//  	break;
		case "4":
			$str="<font color='#ccccc'>"._("完成")."</fon>";
			break;
		case "5":
			$str="<font color='#F4C103'>"._("暂停")."</font>";
			break;
		default :
			$str=_("未知");
			break;	
	}
	return $str;
}





/**
 * 根据传入的订单运行编号，1，2，3，4，返回当前在线状态
 *
 * @param resource product $value
 * @return true or false
 */
function userBillStatus($num){
  global $db; 
  $numarray=array("a","b","c","d","e","f",0,1,2,3,4,5,6,7,8,9);//userbil表1-9用户账单 收费情况
  if(in_array($num,$numarray)){
  	switch($num){
		case "0":
			$str="<font color='#519A01'>"._("开户预存")."</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>"._("前台续费")."</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>"._("卡片充值")."</font>";
			break;
		case "3":
			$str="<font color='#ff0000'>"._("订单退还")."</font>";
			break;
		case "4":
			$str="<font color='#ccccc'>"._("订单扣费")."</fon>";
			break;
		case "5":
			$str="<font color='#F4C103'>"._("销户退费")."</font>";
			break;
		case "6":
			$str="<font color='#F4C103'>"._("用户冲帐")."</font>";
			break;
		case "7":
			$str="<font color='#F4C103'>"._("用户移机")."</font>";
			break;
		case "8":
			$str="<font color='#F4C103'>"._("安装费用")."</font>";
			break;
                case "9":
			$str="<font color='#F4C103'>"._("支付宝充值")."</font>";
			break;
		case "a":
			$str="<font color='red'>"._("违约金")."</font>";
			break;
		case "b":
			$str="<font color='#FF9900'>"._("暂停费用")."</font>";
			break;
                case "c":
			$str="<font color='#00ff00'>"._("暂停恢复退费")."</font>";
			break;
                case "d":
			$str="<font color='#F4C103'>"._("用户过户")."</font>";  //2014.07.17 修改
			break;
                case "e":
			$str="<font color='blue'>"._("收取押金")."</font>";  //2014.07.23 修改
			break;
                case "f":
			$str="<font color='red'>"._("退还押金")."</font>";  //2014.07.23 修改
			break;
	}
	return $str;
  }else{
   $MTC=$db->select_one("name","finance","type='".$num."'"); 
	  if($MTC){
		  foreach($MTC as $type){ 
		     $str="<font color='blue'>".$type."</font>";
			 }//end foreach 
	  }else{//end if($rs)
	       $str="<font color='blue'>"._("未知")."</font>";
	  }
   }//end in_array 
   return $str;
} 
function orderCheckStatus($num){
	switch($num){
		case "0": 
			$str="<font color='#ff0000'>"._("未对账")."</font>";
			break;
		case "1": 
			$str="<font color='#00ff00'>"._("已对账")."</font>";
			break; 
		default :
			$str="<font color='#ff0000'>"._("未对账")."</font>";
			break;	
	}
	return $str;
}

/**
 * 根据传入的订单运行编号，1，2，3，4，返回当前在线状态
 *
 * @param resource product $value
 * @return true or false
 */
function userLogStatus($num){
	switch($num){
		case "0":
			$str="<font color='#519A01'>"._("新增")."</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>"._("续费")."</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>"._("修改")."</font>";
			break;
		case "3":
			$str="<font color='#ff0000'>"._("暂停")."</fon>";
			break;
		case "4":
			$str="<font color='#77FFF00'>"._("取消暂停")."</fon>";
			break;
		case "5":
			$str="<font color='#ff0000'>"._("销户")."</font>";
			break;
		case "6":
			$str="<font color='#ff0000'>"._("设置停机")."</font>";
			break;
		case "7":
			$str="<font color='#FF3333'>"._("更改产品")."</font>";
			break;
		case "8":
			$str="<font color='#FF55II'>"._("删除用户")."</font>";
			break;	
		case "9":
			$str="<font color='#ff0000'>"._("用户冲账")."</font>";
			break;	
		case "10":
			$str="<font color='#00aa00'>"._("续费并更改产品")."</font>";
			break;	
		case "11":
			$str="<font color='red'>"._("订单撤销")."</font>";
			break;
                case "12":
			$str="<font color='#77FFF00'>"._("取消停机")."</fon>";//2014.3.10添加状态12
			break;
                 case "13":
			$str="<font color='red'>"._("退还押金")."</fon>";//2014.7.23添加状态13
			break;
                 case "14":
			$str="<font color='red'>"._("用户对账")."</fon>";//2014.7.23添加状态13
			break;
		default :
			$str=_("未知");
			break;	
	}
	return $str;
}

/**
 * 根据传入的订单运行编号根据状态显示状态  获取所对应的数字类型， 
 *
 * @param  
 * @return  
 */
function showUserLogType($type){

global $db;
	//$deviceResult=$db->select_all("*","product","");
	$typeArray=array(   "0"=>_("选择类型"),  //实际类型需-1
						"1"  =>_("新增"),
						"2"  =>_("续费"),
						"3"  =>_("修改"),
						"6"  =>_("销户"),
						"4"  =>_("暂停"),
						"5"  =>_("取消暂停"),
						"7"  =>_("设置停机"),
						"8"  =>_("更改产品"),
						"9"  =>_("删除用户"),
						"10" =>_("用户冲账"),
						"12" =>_("订单撤销"),
                                                 "14" =>_("退还押金"),
						"11" =>_("续费并更改产品"),
				  );
	echo "<select  name='type'  id='type'>"; 
	if(is_array($typeArray)){
		foreach($typeArray as $typeKey=>$typeVa){
		   if($typeKey==$type){ 
				echo "<option value=".$typeKey." selected>".$typeVa."</option>";
			} else{
				echo "<option value=".$typeKey.">".$typeVa."</option>";
			}
		}
	}
	echo "</select>"; 
}


/**
 * 根据传入的订单运行编号，1，2，3，4，返回当前在线状态
 *
 * @param resource product $value
 * @return true or false
 */
function orderRefundStatus($num){
	switch($num){
		case "0":
			$str="<font color='#519A01'>"._("订单退费")."</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>"._("销户退费")."</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>"._("冲帐退费")."</font>";
			break;
		default :
			$str=_("未知");
			break;	
	}
	return $str;
}


/**
 * 用于让用户下线的执行功能函数
 * 根据传入的参数的，把用户踢下线的，
 * @param $SessionID,$NAS_IP,$http_port,$Vender,$UserName,$share_passwd
 * 
 */

function close_port($SessionID,$NAS_IP,$http_port,$Vender,$UserName,$share_passwd){
	global $db;
	if($Vender == "natshell"){	
		$passwd =md5(strrev(md5($share_passwd)));
		$url    ='http://'.$NAS_IP.":".$http_port.'/remote_cmd.php?port='.$SessionID.'&passwd='.$passwd;
		$fp     = @fopen($url,"r");
//		echo $url;
//		exit;
	}
	if($Vender == "mikrotik"){
		$acctRs=$db->select_one("*","radacct","AcctStopTime = '0000-00-00 00:00:00' And UserName = '".$UserName."'");
		$AcctSessionId   = $acctRs['AcctSessionId'];
		$FramedIPAddress = $acctRs['FramedIPAddress'];

		//$nasRs  = $db->select_one("*","nas","ip = '".$NAS_IP."'");
		//$secret = $nasRs['secret'];
		$secret =$share_passwd;
		$packet = time();
		$fp     = fopen("/tmp/".$packet,"w");
		$str    = "Acct-Session-Id=".$AcctSessionId."\n";
		$str   .= "User-Name=".$UserName."\n";
		$str   .= "Framed-IP-Address=".$FramedIPAddress."\n";
		//echo $str;
		fputs($fp, $str,1024);
		exec("/usr/local/bin/radclient -f /tmp/".$packet." ".$NAS_IP.":".$http_port." disconnect ".$secret) ;
/*
echo "Acct-Session-Id=8100000c" > packet.txt
echo "User-Name=gtr" >> packet.txt
echo "Framed-IP-Address = 10.12.255.248" >> packet.txt

cat packet.txt | radclient -x 192.168.50.180:1701 disconnect 123
*/
	}
}


/*
 * 为Linux-Radius添加的下线的代码
 * 这个 $SessionID 其实是Framed-IP-Address哈
 *  echo "User-Name = test,  Framed-IP-Address = 172.18.0.1"
 *  | /usr/local/bin/radclient -x 192.168.98.10:3799 disconnect natshell
 */
function down_user_linux_radius($UserName, $FramedIPAddress, $NAS_IP, $port, $share_passwd, $link, $Vender) {
    if($Vender == "ik_radius"){ 
   // $cmd="ik_radius_client {$NAS_IP}:{$port} disconnect {$share_passwd} \"User-Name = {$UserName}\" 2>/dev/null & ";
      $cmd = "ik_radius_client {$NAS_IP}:{$port} {$share_passwd} {$UserName}";
    pclose(popen($cmd, "r"));      
        
    }
  if ( 0 == strcmp("natshell", trim($Vender)) ) {
    //针对以前的网关
    $cmd = "echo \"User-Name = {$UserName},  Framed-IP-Address = {$FramedIPAddress}\"| /usr/local/bin/radclient {$NAS_IP}:{$port} disconnect {$share_passwd} 2>/dev/null &";    
    pclose(popen($cmd, "r")); 
    //针对现在的Linux网关（添加了COA功能之后，添加了COA功能之后就去掉了以前自己写的radiuscoaserver）
    //echo "Acct-Session-Id='51CD58F709EC00',User-Name='test',Framed-IP-Address='172.18.0.11'" | radclient -d /etc/raddb/ 192.168.100.176:3799 disconnect natshell	 
    $cmd = "echo \"Acct-Session-Id= {$link}, User-Name = {$UserName},  Framed-IP-Address = {$FramedIPAddress}\"| /bin/radclient -d /etc/raddb {$NAS_IP}:{$port} disconnect {$share_passwd} 2>/dev/null &";    
    pclose(popen($cmd, "r"));   
    //wansky natshell项目 $cmd = "echo \"User-Name = {$UserName},  Framed-IP-Address = {$FramedIPAddress}\"| /usr/local/bin/radclient {$NAS_IP}:{$port} disconnect {$share_passwd} &";
      
     
    //针对现在的Linux网关（没有添加COA功能的网关）  
    $cmd = "radiuscoaclient {$NAS_IP} \"User-Name={$UserName} Framed-IP-Address={$FramedIPAddress} Radius-IP-Address={$NAS_IP} Share-Key={$share_passwd}\" 2 >/dev/null >/dev/null &";  
    pclose(popen($cmd, "r"));  
	  /*
	  $fd = fopen("/tmp/aaa", "w");
 	  fwrite($fd, $cmd);
 	  fclose($fd);
    */ 
  } else {
    //$acctRs=$db->select_one("*","radacct","AcctStopTime = '0000-00-00 00:00:00' And UserName = '".$UserName."'");
    //$AcctSessionId   = $acctRs['AcctSessionId'];
    $cmd = "echo \"Acct-Session-Id = {$link}\", \"User-Name = {$UserName},  Framed-IP-Address = {$FramedIPAddress}\"| /usr/local/bin/radclient {$NAS_IP}:{$port} disconnect {$share_passwd} 2>/dev/null &"; 
    //echo $cmd;
    pclose(popen($cmd, "r"));  
    $cmd = "echo \"User-Name = {$UserName},  Framed-IP-Address = {$FramedIPAddress}\"| /bin/radclient -d /etc/raddb {$NAS_IP}:{$port} disconnect {$share_passwd} 2>/dev/null &";    
    pclose(popen($cmd, "r"));
    //$fd = fopen("/tmp/abc", "w");
    //fwrite($fd, $cmd);
    //fclose($fd);          
    $cmd = "radiuscoaclient {$NAS_IP} \"User-Name={$UserName} Framed-IP-Address={$FramedIPAddress} Radius-IP-Address={$NAS_IP} Share-Key={$share_passwd}\"";  
    pclose(popen($cmd, "r"));  
    //阿尔卡特 
    $cmd="echo \"Acct-Session-Id = '{$link}' \" | radclient -d /etc/raddb {$NAS_IP}:{$port} disconnect {$share_passwd} &";
    pclose(popen($cmd, "r")); 
  }   
} 
/**
 * 根据传入的参数用户ID，判断用户是否已经离线，时间大概是五分钟~~
 * 
 * @param $userID 用户编号
 * retrun 如果时间大于300秒~修改用户记录中的信息 
 *
 */
function user_is_offline($userID){
	global $db;
	$UserName      =getUserName($userID);
	$row           =$db->select_one("*","radacct","UserName='".$UserName."' and AcctStopTime='0000-00-00 00:00:00'");
	$UserName      =$row["UserName"];
	$AcctStartTime =$row["AcctStartTime"];
	
	//echo "username: ".$row["UserName"];
	//echo " | 上线时间: ".strtotime($row["AcctStartTime"]);
	//echo " | 当前时间: ". Time();
	//echo " | 在线秒数: ". $row["AcctSessionTime"];
	$updateTime =  $row["AcctSessionTime"]-(Time() - strtotime($row["AcctStartTime"]));
	//echo " | 上线时间距离现在的长度: ". $updateTime;
	if ($updateTime < -300){
		$AcctStopTime  = date("Y-m-d H:i:s",(strtotime($row["AcctStartTime"])+$row["AcctSessionTime"]));
		//echo " | 应该的结束时间: ".$AcctStopTime;
		$db->query("update radacct SET AcctStopTime = '$AcctStopTime' where AcctStartTime = '$AcctStartTime' AND UserName = '$UserName' AND AcctStopTime = '0000-00-00 00:00:00'");
	}

}



/**
 * 已下拉选择的方式输出
 * 
 * @param 
 * retrun 
 *
 */

function managerSelect($manager=""){
	global $db;
	$mResult=$db->select_all("*","manager","");
	
	echo "<select name='operator' >";
	echo "<option value=''>"._("选择人员")."</option>";
	if($mResult){
            if($_SESSION["manager"] == "admin"){
               foreach($mResult as $mKey=>$mRs){
			echo "<option value='".$mRs["manager_account"]."'";
			if($manager==$mRs["manager_account"]) echo "selected";
			echo ">".$mRs["manager_account"]."</option>";
		} 
            }else {
            echo "<option value='".$_SESSION["manager"]."'";
            echo ">".$_SESSION["manager"]."</option>";
        }
		
	}
	echo "</select> ";
	
}

/**
 * 已下拉选择的方式输出
 * 
 * @param 
 * retrun 
 *
 */

function acceptSelect($accept_name=""){
	global $db;
	$mResult=$db->select_all("*","accept","");
	
	echo "<select name='accept_name' >";
	echo "<option value=''>"._("选择人员")."</option>";
	if($mResult){
               foreach($mResult as $mKey=>$mRs){
			echo "<option value='".$mRs["accept_name"]."'";
			if($accept_name==$mRs["accept_name"]) echo "selected";
			echo ">".$mRs["accept_name"]."</option>";
		} 

		
	}
	echo "</select> ";
	
}

//装机人员

function managerZJRY (){
global $db;
          $mResult=$db->select_all("*","manager","");
	//$mResult=$db->select_all("*","manager","manager_account = '".$_SESSION["manager"]."'");  //2014.08.26修改添加用户时装机人只能看到自己
	echo "<div style=\"position:relative;\">
	<span style=\"width:153px;heigh:20; overflow:hidden;\"> ";
?> 
<select   style="width:153px;" name='operator' class='select_css' id='operator' onchange="document.getElementById('reoperator').value=this.value;">
<?php
	echo "<option value='可输入装机人员'>"._("可输入装机人员")."</option>";
	if($mResult){
		foreach($mResult as $mKey=>$mRs){
                    echo "<option value='".$mRs["manager_account"]."' ";
                    if($mRs["manager_account"] == $_SESSION["manager"]){
                        echo "selected";
                    }
                    echo ">".$mRs["manager_account"]."</option>";
		}
	}
	echo "</select>  
	</span> 
	<input type='text' name='reoperator' id='reoperator' value='".$_SESSION["manager"]."' style='width:135px;position:absolute;left:0px;height:19px;' />

	</div>";

}


//销售人员  也是提取系统管理员
function managerSold($manager=""){
	global $db;
	$mResult=$db->select_all("*","manager","");
	echo "<select name='solder'>";
	echo "<option value=''>"._("选择人员")."</option>";
	if($mResult){
		foreach($mResult as $mKey=>$mRs){
			echo "<option value='".$mRs["manager_account"]."'";
			if($manager==$mRs["manager_account"]) echo "selected";
			echo ">".$mRs["manager_account"]."</option>";
		}
	}
	echo "</select>";
}
/**
 * ippool 已下拉选择的方式输出
 * 
 * @param 
 * retrun 
 *
 */
 function ippoolSelect($ippoolID=""){
	global $db; 
	$pResult=$db->select_all("*","ippool",'`type`=1');
	echo "<select name='ippoolID' >";
	echo "<option value=''>"._("选择地址池")."</option>";
	if($pResult){
		foreach($pResult as $pKey=>$pRs){
			echo "<option value='".$pRs["ID"]."'";
			if($ippoolID==$pRs["ID"]) echo "selected";
			echo ">".$pRs["name"]."</option>";
		}
	}
	echo "</select>";
} 
//到期地址池分配
function duepoolSelect($ippoolID=""){
	global $db; 
	$pResult=$db->select_all("*","ippool",'`type`=2');
	echo "<select name='duepoolID' >";
	echo "<option value=''>"._("选择地址池")."</option>";
	if($pResult){
		foreach($pResult as $pKey=>$pRs){
			echo "<option value='".$pRs["ID"]."'";
			if($ippoolID==$pRs["ID"]) echo "selected";
			echo ">".$pRs["name"]."</option>";
		}
	}
	echo "</select>";
} 
/**
 * 收费项目 已下拉选择的方式输出
 * 
 * @param 
 * retrun 
 *
 */
 function financeSelect($financeID=""){
	global $db;
	$mResult=$db->select_all("*","finance","");
	echo "<select name='financeID' onchange=ajaxInput('ajax_check.php','financeID','financeID','financeTEXT'>;)";
	echo "<option value=''>"._("选择科目")."</option>";
	if($mResult){
		foreach($mResult as $mKey=>$mRs){
			echo "<option value='".$mRs["ID"]."'";
			if($manager==$mRs["ID"]) echo "selected";
			echo ">".$mRs["name"]."</option>";
		}
	}
	echo "</select>";
}
 


/****** 根人工收费ID 获取收费名*/
function getFinanceName($financeID=''){
 global $db;
	$mResult=$db->select_one("name","finance","ID='".$financeID."'"); 
	return empty($mResult["name"])?_("未知"):$mResult["name"];
	
}
function getOperateUserName($name){
global $db;
  $rs= $db->select_one('manager_name',"manager","manager_account = '".$name."'");
  if($rs){
    return empty($rs['manager_name'])?$name:$rs['manager_name']; 
  }
}
 
/**
 * 收费项目 type
 *方法名financeType()
 * @param 
 * retrun 
 *
 */
 function financeType($financeID){
	global $db;
	$Rs=$db->select_one("type","finance","ID='".$financeID."'");
	if($Rs)
	 return $Rs['type']; 
	else
	 return false; 
}
 
 function MysqlBegin(){  
     mysql_query("BEGIN");//开始事务定义 
}
function paymentShow(){//付款方式
  echo"<select name=\"methods\"  id=\"methods\">
			 <option value=''  selected=\"selected\">". _("支付方式")."</option>
			 <option value='1'>". _("现金支付")."</option>
			 <option value='0'>". _("余额扣除")."</option>
 </select>"; 
}
function refundWayShow(){//退款方式
  echo"<select name=\"refund\"  id=\"refund\">
			 <option value=''  selected=\"selected\">". _("退款方式")."</option>
			 <option value='1'>". _("现金退费")."</option>
			 <option value='0'>". _("划分余额")."</option>
 </select>"; 
}
function rechangType(){//用户充值  卡片 现金充值 
echo"<select name=\"rechange\"  id=\"rechange\" onchange='showRechang()'>
			 <option value='0'>". _("充值方式")."</option>
			 <option value='0'  selected=\"selected\">". _("现金充值")."</option>
			 <option value='1'>". _("卡片充值")."</option>
    </select>"; 
}
 //导入用户获取的用户订单开始时间
function mysqlYmdDate($beginTime){ 
    $time    = mysql_query("select date_format('".$beginTime."','%Y-%m-%d') as getTime"); 
    $getTime = mysql_fetch_array($time,MYSQL_ASSOC);  
    if($getTime) return $getTime["getTime"];
    else return false; 
}
/**
 *===========================================
 * 函数名:   mysqlGteDate()
 * 参数:    开始时间 周期 类型 别名
 * 功能描述: 避免linux时间戳上线2038 去获取用户的计算用户的产品结束时间   
 * 返回值:    
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */ 

function mysqlGteDate($beginTime,$period,$type){ 
    $type    = trim(strtolower($type));
	if(!is_numeric($period)) return false;
    if($type !="year" && $type !="month" && $type !="day" && $type!="hour") return $type; 
	 //通用 if($type!="year" || $type!="month" || $type!="week" || $type!="day" || $type!="hour" || $type!="minute" ||  $type!="seconds") return false; 
	 //$time    = mysql_query("select date_add('".$beginTime."',interval  ".$period." ".$type." ) as  getTime");
    $time    = mysql_query("select DATE_ADD(date_format(DATE_ADD(date_format(DATE_ADD(date_format(date_add('".$beginTime."',interval  ".$period." ".$type." ),'%Y-%m-%d'), INTERVAL 23 HOUR ),'%Y-%m-%d %T'), INTERVAL 59 MINUTE ),'%Y-%m-%d %T'), INTERVAL 59 SECOND ) as  getTime"); 
     $getTime = mysql_fetch_array($time,MYSQL_ASSOC);  
    if($getTime) return $getTime["getTime"];
    else return false; 
}
/*
  根据时间显示 年月日，用户结束时间
*/
function mysqlShowDate($date){
	 $time    = mysql_query("select date_format('".$date."','%Y-%m-%d %H:%i:%s' ) as  getTime"); 
   $getTime = mysql_fetch_array($time,MYSQL_ASSOC);  
   if($getTime) return $getTime["getTime"];
   else return false;  
}
 /*
  根据时间显示 年月日 时分秒，比较时间
*/
function mysqlShowTime($time){
	 $time    = mysql_query("select date_format('".$time."','%Y-%m-%d %H:%i:%s' ) as  getTime"); 
   $getTime = mysql_fetch_array($time,MYSQL_ASSOC);  
   if($getTime) return $getTime["getTime"];
   else return false;  
} 
/*
  根据 结束时间和开始时间获取时间差 不受时间2038的限制 >=0 表示还存在时间差，如果〈0没有时分秒的差 天
*/ 
function mysqlDatediff($endtime,$bengintime){ 
	// $time    = mysql_query("select datediff('".$endtime."','".$bengintime."') as  getTime"); 
   $time    = mysql_query("select TIMESTAMPDIFF(SECOND,'".$bengintime."','".$endtime."') as  getTime"); //当前时间与结束时间比相差的时间秒值， >0 说明当前时间比结束时间小
   $getTime = mysql_fetch_array($time,MYSQL_ASSOC);  
   if($getTime) return $getTime["getTime"]/24/3600;
   else return false;  
}
/*
   根据时间参数，把时间精确到23：59：59
*/ 
function mysqlDated23($date){
	 $time    = mysql_query("select DATE_ADD(date_format(DATE_ADD(date_format(DATE_ADD(date_format('".$date."','%Y-%m-%d'), INTERVAL 23 HOUR ),'%Y-%m-%d %T'), INTERVAL 59 MINUTE ),'%Y-%m-%d %T'), INTERVAL 59 SECOND ) as  getTime"); 
   $getTime = mysql_fetch_array($time,MYSQL_ASSOC);  
   if($getTime) return $getTime["getTime"];
   else return false;  
}

/**
 *===========================================
 * 函数名:   adduserOnlinetime()
 * 参数:     状态  用户ID 用户名
 * 功能描述:    
 * 返回值:    
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */
function adduserOnlinetime($timestatus,$userID,$UserName,$onlinetime){
    global $db;
	$onlinetime = $onlinetime * 60 ;//秒
	if($timestatus=="enabled"){//启用
	    $sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and UserName='".$UserName."' and Attribute='Session-Timeout'");
		if(!$sql_rs_err) return false;
		$sql=array(
		   "userID"=>$userID,
		   "UserName"=>$UserName,
		   "Attribute"=>"Session-Timeout",
		   "op"=>"+=", 
		   "Value"=> $onlinetime            
		);
		 $sql_rs_err = $db->insert_new("radreply",$sql);
		 if(!$sql_rs_err) return false;
		 return true; 
	}else{//禁用
	     $sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and UserName='".$UserName."'  and Attribute='Session-Timeout'");
		 if(!$sql_rs_err) return false;
		 return true; 
	}


}
/**
 *===========================================
 * 函数名:   userAddBegindatetime()
 * 参数:      用户ID 
 * 功能描述:   根据用户的ID获取用户开始时间 
 * 返回值:     $begindatetime;   
 * 作者:      nancy
 * 修改记录:
 *===========================================
 */ 
//针对开户添加用户，根据上一订单获取下一订单的开始时间
function userAddBegindatetime($userID){ 
     global $db;
     $userStartTime = $db->select_one("enddatetime","userrun","userID = '".$userID."' and status in (0,1) order by enddatetime desc limit 0,1");
	 if($userStartTime){
	     $begindatetime = $userStartTime['enddatetime']; 
	     return $begindatetime;
	 }else{
	     return date("Y-m-d H:i:s",time());
	 }
}
//根据用户ID获取用户当前应该使用或接下来使用的订单状态
//针对添加用户时，当用户设置的开始时间<当前时间，并且第一个订单的结束时间小于当前时间
function userNormalStatus($userID){//针对立即计时用户，非上线计时用户  ，用户添加或续费
   global $db;
   $nowTime = date("Y-m-d H:i:s",time());//当前时间
   //(时间范围)正在使用的订单
   $userNormal = $db->select_one("status,orderID","userrun","userID='".$userID."'  and begindatetime <= '".$nowTime."' and enddatetime >  '".$nowTime."' ");
   if($userNormal){//存在 正在使用时间范围订单
      $normalStaus = array("orderID"=>$userNormal["orderID"],"status"=>$userNormal['status']);
      return $normalStaus;
   }else{//不存在正在使用时间范围订单 
      unset($userNormal);
      $userNormal = $db->select_one("status,orderID","userrun","userID='".$userID."' and begindatetime > '".$nowTime."' and enddatetime >  '".$nowTime."' order by begindatetime asc  limit 0,1 ");
      if($userNormal){//存在等待运行订单 获取距今最近的订单信息
	      $normalStaus = array("orderID"=>$userNormal["orderID"],"status"=>$userNormal['status']);
	      return $normalStaus;
	  }else{//不存在等待运行订单。。且无正在使用时间范围内的订单。。。。获取上一结束订单的订单信息
	       unset($userNormal);
           $userNormal = $db->select_one("status,orderID","userrun","userID='".$userID."' and begindatetime < '".$nowTime."' and enddatetime <=  '".$nowTime."' order by begindatetime desc  limit 0,1 ");
	       if($userNormal){
		      $normalStaus = array("orderID"=>$userNormal["orderID"],"status"=>$userNormal['status']);
		      return $normalStaus;
		   } else{
		      return false;
		   }
		  
	  }
   } 
}
 
//针对续费获取获取用户的最后一个订单的结束时间
function orderAddLastEnddatetime($userID){ 
     global $db;
	 //判断用户是否有等待运行订单
	 $waitOrder = $db->select_one("endatetime","userrun","userID = '".$userID."' and status = 0 and enddatetime > '".date("Y-m-d H:i:s",time())."' order by enddatetime desc ");
	 if($waitOrder)	{
	   return $waitOrder["enddatetime"];
	 
	  }else{
	  $nowOrder = $db->select_one("endatetime","userrun","userID = '".$userID."' and status = 1 and enddatetime > '".date("Y-m-d H:i:s",time())."'  ");
	  if($waitOrder)	  return $nowOrder["enddatetime"];
	  else  return date("Y-m-d H:i:s",time()); 
	 } 
}
//获取当前用户的正常订单个数
function orderNormal($userID){ 
     global $db;  
	 $num=$db->select_count("userrun","userID='".$userID."' and status in (1,0)");
	 if($num)  return $num; 
	 else return 0;
}
/**
 *===========================================
 * 函数名:   managerEditAddusernum()
 * 参数:    管理员账号  当前已开户用户数  现在开户用户数
 * 功能描述:    
 * 返回值:    
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */

function managerEditAddusernum($nowAddNum=1){
  global $db;
  $manager         =$_SESSION["manager"];
  $manageNum =$db->select_one("*","manager","manager_account = '".$manager."'");  
  $addusertotalnum =$manageNum["addusertotalnum"];//允许开户人数
  $addusernum      =$manageNum["addusernum"];//已开户人数  
  $totalNum        = $addusernum + $nowAddNum;
  if($addusernum >=$addusertotalnum ) return false;
  if($totalNum>$addusertotalnum){
    return false;
  }else{//当前添加数+已开户数<=允许开户数
    $sql=array(
	      "addusernum"=>$totalNum
	);
    $rs = $db->update_new("manager","manager_account='".$manager."'",$sql);  
	if($rs) return true ;else return false;
  } 
}
/**
 *===========================================
 * 函数名:   managerTotalmoneyShow ()  
 * 参数:      
 * 功能描述:    
 * 返回值: 合法true 不合法false
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */
 function managerTotalmoneyShow(){
 	global $db;
 	$manager         =$_SESSION["manager"];
  $managerTotalMoney =$db->select_one("*","manager","manager_account = '".$manager."'");  
  $totalInMoney = $db->select_one("sum(money) as in_all_money","credit","operator ='".$_SESSION['manager']."'"); //用户收入金额 包括，开户，续费，充值，卡片充值，用户移机===
  $totalOutMoney = $db->select_one("sum(factmoney) as out_all_money","orderrefund","operator ='".$_SESSION['manager']."' and type in(1,2,3)"); // 1 销户 2冲账 3恢复暂停
  
  $totalMoney = (int)$totalInMoney['in_all_money'] -(int)$totalOutMoney['out_all_money']; //实际收费金额 
  $manager_totalmoney =(int)$managerTotalMoney["manager_totalmoney"];//允许收取费用  manager表获取值 
   if($totalMoney >=$manager_totalmoney) return false; //实际收取费用超过 分配权限收取费用
  else return true;
}
  
//时长结账下机
function userCheckout($orderID){
	 global $db;
	  //修改参数
    $sql_err1 =$db->query("update userrun set status=4,enddatetime='".date("Y-m-d H:i:s",time())."' where orderID='".$orderID."'"); 
	  $sql_err2 =$db->query("update orderinfo set status=4 where ID='".$orderID."'");  
	  $sql_err3 =$db->query("update userattribute set status=4,stop=1,orderID='".$orderID."' "); 
	   //结账标识
	  $sql_err4 =$db->query("update userinfo set checkout=1,money = 0 where  UserName='".$UserName."' "); 	
    if($sql_err1 && $sql_err2 && $sql_err3 && $sql_err4) return true;
    else return false;
}
function getBindUserIP($projectID,$userID,$UserName){
    global $db;
	$query_str = "SELECT  * FROM project where ID='".$projectID."'";
	$sql = $db->query($query_str);
	if(!$sql) return false;
	if($sql){
		while($row =  mysql_fetch_array($sql)){	
			$start_ip=$row["beginip"];
			$end_ip =$row["endip"];
		}
		mysql_free_result($sql);
	   $bips=explode(".",$start_ip);
	   $eips=explode(".",$end_ip);
	   $icount=ipcountq($bips,$eips)+1; 
	   for($i=0;$i<$icount;$i++){
			$currentlyIP = ipH2Dq(dechex($bips[0]*16777216+$bips[1]*65536+$bips[2]*256+$bips[3]+$i));
			$query_str2 = "SELECT  Value FROM radreply where Value='$currentlyIP' And Attribute='Framed-IP-Address';";
			$r = $db->query($query_str2);
			if(!$r) return false;
			$ip = mysql_fetch_array($r);
			if(!$ip){
				$i=9999999;
			mysql_free_result($r);
			}
		}
	$IP = trim($currentlyIP) ;
	$sel = $db->select_count("radreply","userID='".$userID."' and UserName='".$UserName."' and Attribute='Framed-IP-Address'");
	if(!is_numeric($sel)) return false;
	if($sel>0){
		$sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and UserName='".$UserName."' and Attribute='Framed-IP-Address'");
		if(!$sql_rs_err) return false;
		$sql=array(
			"userID"=>$userID,
			"UserName"=>$UserName,
			"Attribute"=>"Framed-IP-Address",
			"op"=>":=",
			"Value"=>$IP 
		
		);
		  $sql_rs_err = $db->insert_new("radreply",$sql);
		  if(!$sql_rs_err) return false;
		}
	} 
	return true;
}
function getDuepoolIP($projectID,$userID,$UserName){
    global $db; 
	//$query_str = "SELECT  duepoolID FROM project where ID='".$projectID."' and duestatus='enable' and duepoolID !=0";
	$sql = $db->select_one("duepoolID","project","ID='".$projectID."' and duestatus='enable' and duepoolID !=0");
	if(!$sql) return false;
	if($sql){ 
			$poolIP  = $db->select_one("beginip,endip","ippool","ID='".$sql["duepoolID"]."'"); 
			$start_ip=$poolIP["beginip"];
			$end_ip  =$poolIP["endip"]; 
	    $bips=explode(".",$start_ip);
	    $eips=explode(".",$end_ip);
	    $icount=ipcountq($bips,$eips)+1; 
	   for($i=0;$i<$icount;$i++){
			$currentlyIP = ipH2Dq(dechex($bips[0]*16777216+$bips[1]*65536+$bips[2]*256+$bips[3]+$i)); 
		  $ip =$db->select_one("Value","radreply","Value='$currentlyIP' And Attribute='Framed-IP-Address';");
	    if(!$ip)  break;   
		}
	 $IP = trim($currentlyIP) ; 
	 $sql_rs_err = $db->delete_new("radreply","userID='".$userID."' and UserName='".$UserName."' and Attribute='Framed-IP-Address' or Attribute='Framed-Pool'");
	 if(!$sql_rs_err) return false;  
		$sql=array(
			"userID"=>$userID,
			"UserName"=>$UserName,
			"Attribute"=>"Framed-IP-Address",
			"op"=>":=",
			"Value"=>$IP  
		); 
		  $sql_rs_err = $db->insert_new("radreply",$sql);
		  if(!$sql_rs_err) return false; 
	} 
	return true;
}
function ipD2Hq($strIP){
        return dechex($strIP[0]*16777216+$strIP[1]*65536+$strIP[2]*256+$strIP[3]);
}
function ipcountq($bip,$eip){
        return
($eip[0]*16777216+$eip[1]*65536+$eip[2]*256+$eip[3])-($bip[0]*16777216+$bip[1]*65536+$bip[2]*256+$bip[3]);
}
function ipH2Dq($hip){
        if(strlen($hip)==7){
                $hip="0".$hip;
        }
        if(strlen($hip)==8){
                $ipsection=array();
                $j=0;
                for($i=0;$i<4;$i++){
                        $ipsection[$i]=hexdec(substr($hip,$j,2));
                        $j=$j+2;
                }
                        return  implode(".",$ipsection);
        }//end if
        else{
                $input_errors[] =  "非法的十六进制IP地址!";
        }//end if else
}//end function
function userbillCheck($check=""){  
	echo "<select name='check' >"; 
	echo "<option value='all' ";
	 if($check=="" || $check=="all") echo "selected";
  echo ">"._("所有")."</option>"; 
	echo "<option value='check_true'";
	 if($check=="check_true" ) echo "selected";
  echo ">"._("已对账")."</option>";  
	echo "<option value='check_false'";
	 if($check=="check_false") echo "selected";
  echo ">"._("未对账")."</option>";	 
	echo "</select> "; 
}
function selectArea($areaID="",$userID=""){
	global $db;
	//if $productID is empty add user  else update user
  $sql=" a.ID=ap.areaID and a.ID in (". $_SESSION["auth_area"]." ) and ap.projectID in(".$_SESSION["auth_project"].") order by convert(a.name using gbk) asc"; 
	$areaResult=$db->select_all("distinct(a.ID),a.name","area as a ,areaandproject as ap" ,$sql); 
	echo "<select name='areaID' id='areaID' onchange=\"ajaxInput('ajax/project.php','areaID','areaID','projectSelectDIV'); \">"; 
	echo "<option value='0'>"._("选择区域")."</option>"; 
		if(is_array($areaResult)){ 
			 foreach($areaResult as $key=>$areaRs){ 
				 if($areaRs["ID"]==$areaID){  
			      if(!empty($userID)) $areaRs["ID"] =$areaRs["ID"].",".$userID;
						echo "<option value=".$areaRs["ID"]." selected>".$areaRs["name"]."</option>"; 
				 }else{ 
			      if(!empty($userID)) $areaRs["ID"] =$areaRs["ID"].",".$userID;
					  echo "<option value=".$areaRs["ID"].">".$areaRs["name"]."</option>"; 
				 } 
			 } 
		} 
	echo "</select>";   
}
/**
 *===========================================
 * 函数名:   MysqlCommit()
 * 参数:     
 * 功能描述: 执行事务   
 * 返回值:    
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */ 
function MysqlCommit(){  
	 mysql_query("COMMIT");//执行事务 
} 
 /**
 *===========================================
 * 函数名:   MysqRoolback()
 * 参数:     
 * 功能描述: 事务的开始   
 * 返回值:    
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */ 
function MysqRoolback(){ 
	 mysql_query("ROLLBACK");  
}  
/**
 *===========================================
 * 函数名:   MysqlEnd()
 * 参数:     
 * 功能描述: 事务的开始   
 * 返回值:    
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */ 
function MysqlEnd(){  
     mysql_query("END");//关闭连接池
} 
/**
 *===========================================
 * 函数名:   pwd_rand()
 * 参数:      
 * 功能描述: 随机密码 
 * 返回值:   字符
 * 作者:     nancy
 * 修改记录:
 *===========================================
 */ 
 
function pwd_rand(){ 
	$arr1 = range('0','9');  
	$str1 = array_rand($arr1);
	$str2 = array_rand($arr1);
	$str3 = array_rand($arr1);
	$str4 = array_rand($arr1); 
	$str5 = array_rand($arr1);
	$str6 = array_rand($arr1);
	return $arr1[$str1].$arr1[$str2].$arr1[$str3].$arr1[$str4].$arr1[$str5].$arr1[$str6];
  
}

//流量单位的换算
function flowUnit($num,$unit){
 if($unit=='byte' ){
	  if($num!=0){
		$KB=floor($num/1024);
		$kb=$num%1024;
		if($KB>=1024){
			$M  =floor($KB/1024);
			$KB =$KB%1024;
			if($M >= 1024){
				$G  =floor($M/1024);
				$M  =$M%1024;				
			}
		} 
		if($G>0){
			$str.=$G."G ";
		}		
		if($M>0){
			$str.=$M."M ";
		}			
		if($KB>=0){
			$str.=$KB."KB ";
		} 
	
	echo $str;
	}else{ 
      echo 0; 
	}
   }
 
   if($unit=='kb' ){
	  if($num!=0){
		$M=floor($num/1024);
		$KB=$num%1024;
		if($M>=1024){
			$G  =floor($M/1024);
			$M =$M%1024;
			if($M >= 1024){
				$G  =floor($G/1024);
				$T  =$G%1024;				
			}
		} 
		if($T>0) 
			$str.=$T."T "; 
		if($G>0) 
			$str.=$G."G "; 
		if($M>0) 
			$str.=$M."M "; 
		if($KB>0) 
			$str.=$KB."KB "; 
	
	    echo $str;
	}else{ 
        echo 0; 
	}
   }
}
 
?>