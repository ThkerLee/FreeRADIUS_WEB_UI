#!/bin/php
<?php 
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
 }

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
	$deviceArray=array(
						"natshell" =>"蓝海卓越",
						"hi-spider"=>"海蜘蛛",
						"ma5200f"  =>"华为MA5200F",
						"8021X"  =>"802.1X ",
						"mikrotik" =>"Other"
				  );
	echo "<select  name='device'>";
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
						"natshell" =>"蓝海卓越",
						"hi-spider"=>"海蜘蛛",
						"ma5200f"  =>"华为MA5200F",
						"8021X"  =>"802.1X ",
						"mikrotik" =>"Other"
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
				echo "<option value=".$gradeRs["ID"]." selected>".$gradeRs["name"]."</option>";
			}else{
				echo "<option value=".$gradeRs["ID"].">".$gradeRs["name"]."</option>";
			}
		}
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
			$error_msg="当前版本项目数为".$systemconfig["max_project"];
		}else{
			$db->insert_new("project",$record);
			$man_projectID=$db->insert_id();
			$manRs=$db->select_one("*","manager","ID=1");
			$man_projectID=($manRs["manager_project"])?$manRs["manager_project"].",".$man_projectID:$man_projectID;
			$db->update_new("manager","ID=1",array("manager_project"=>$man_projectID));
			$error_msg="添加成功";
			$_SESSION["auth_project"] .=",".$man_projectID;
		}
	}else{
		$db->insert_new("project",$record);
		$man_projectID=$db->insert_id();
		$manRs=$db->select_one("*","manager","ID=1");
		$man_projectID=($manRs["manager_project"])?$manRs["manager_project"].",".$man_projectID:$man_projectID;
		$db->update_new("manager","ID=1",array("manager_project"=>$man_projectID));
		$error_msg="添加成功";
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
	echo "<select name='projectID'>";
	echo "<option value=''>选择项目</option>";
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
			return "包年";
			break;		
		case "month":
			return "包月";
 			break;	
		case "days":
			return "包天";
 			break;		
		case "hour":
			return "包时";
			break;		
		case "flow":
			return "计流量";
			break;			
	}
}

/**
 * 根据传入字id值，返回项目记录
 *
 * @param id 
 * @return project selectoption 
 */
function productSelected($ID=""){
	global $db;
	//查询项目集合
	$productResult=$db->select_all("*","product","hide=0");
	echo "<select id='productID' name='productID' onchange=ajaxInput('ajax_check.php','productID','productID','productTXT')>";
	if(is_array($productResult)){
		echo "<option value=''>请您选择产品</option>";
		foreach($productResult as $key=>$productRs){
			if($productRs["ID"]==$ID){
				echo "<option value=".$productRs["ID"]." selected>".$productRs["name"]."</option>";
			}else{
				echo "<option value=".$productRs["ID"].">".$productRs["name"]."</option>";
			}
		}
	}
	echo "</select><span id='productTXT'></span>";
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
	}
	return $unitprice;
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
function userAddSave($record){
	global $db,$systemconfig;
	$num=$db->select_count("userinfo","");
	if($systemconfig["max_user"]!=0){
		if($num>=$systemconfig["max_user"]){
			$error_msg="当前版本用户数为".$systemconfig["max_user"];
		}
	}
	

	$account       =$record["account"];
	$password      =$record["password"];
	$name          =$record["name"];
	$projectID     =$record["projectID"];
	$cardid        =$record["cardid"];
	$workphone     =$record["workphone"];
	$homephone     =$record["homephone"];
	$mobile 	   =$record["mobile"];
	$email         =$record["email"];
	$address       =$record["address"];
	$money         =$record["money"];
	$productID     =$record["productID"];
	$timetype  	   =$record["timetype"];
	$iptype        =$record["iptype"];
	$ipaddress	   =$record["ipaddress"];
	$begindatetime =$record["begindatetime"];
	$gradeID	   =$record["gradeID"];
	$adddatetime   =date("Y-m-d H:i:s",time());
	$closedatetime ='0000-00-00 00:00:00';//设置用户销户时间
	$status        =1;//设置订单正常运行1表示正在使用的订单
	$macbind	   =(empty($macbind))?0:$record["macbind"];
	$nasbind  	   =(empty($nasbind))?0:$record["nasbind"];
	$MAC 		   =$record["MAC"];
	$NAS_IP		   =$record["NAS_IP"];
	$onlinenum     =$record["onlinenum"];
	$installcharge_type =$record["installcharge_type"];
	$installcharge =$record["installcharge"];
	$zjry	        =$record["zjry"];
	$receipt	   =$record["receipt"];
	$num=$db->select_count("userinfo","UserName='$account'");
	if($num>0){
		$error_msg="系统中存在相同帐号名称";
	}
	
	$sql=array(
		"UserName"		 =>$account,
		"account"    	 =>$account,
		"password"		 =>$password,
		"name"			 =>$name,
		"projectID"	     =>$projectID,
		"cardid"	     =>$cardid,
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
		"gradeID"		 =>$gradeID,
		"zjry"           =>$zjry,
		"receipt"        =>$receipt
	);
	if(!$error_msg){
		if(!$db->insert_new("userinfo",$sql)){
			mysql_query("ROOLBACK");
		}
		$userID=$db->insert_id();
		
		//添加用户验证密码信息
		addUserPassword($account,$password);
		addUserOnline($account,$onlinenum);
		
		//更新用户帐单
		addUserBillInfo($userID,"0",$money);//记录用户帐单

		if($installcharge_type==1){
			$db->query("update userinfo set money=money-".$installcharge." where ID='".$userID."'");
			addUserBillInfo($userID,"8",$installcharge);//记录用户帐单
		}
		
		//查入订单,成功则返回订单编号
		$orderID=addOrder($userID,$productID,$status,$operator,$receipt);
	
		//插入用户属性，以作为用户拨号的凭据,1=正常用户
		addUserAttribute($userID,$orderID,$status,$macbind,$onlinenum,$nasbind);
	
		if($timetype=="1"){//用户技术参数,当订单立即起效果写入或更改用户技术参数的~
			addUserParaminfo($userID,$productID);
		}
		//用户运行时间
		addUserRuntime($userID,$productID,$orderID,$status,$timetype,$begindatetime);
		
		addUserIpaddress($userID,$iptype,$ipaddress);
		
		//记录用户操作记录
		addUserLogInfo($userID,"0",$_SERVER['REQUEST_URI']);
		
		//增加订单记录
		addOrderLogInfo($userID,$orderID,"0",$_SERVER['REQUEST_URI']);
		
		//记录财务记录
		addCreditInfo($userID,"0",$money);
		$error_msg="添加成功";	
	}
	
	return $error_msg;
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
	if($rs){
		return $rs["ID"];
	}
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
	if($rs){
		return $rs["account"];
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
	$query_str3="delete from radcheck where Attribute='User-Password' And UserName='".$UserName."'";
	$db->query($query_str3);
	//添加密码属性
	$UserName   = $UserName;
	$Attribute 	= "User-Password";
	$op			= ":=";
	$Value		=  $password;
	$strSQL = "INSERT INTO radcheck(UserName,Attribute,op,Value) values('$UserName','$Attribute','$op','$Value');";
	$db->query($strSQL);
	return true;

}


function addUserOnline($UserName,$num){
	global $db;
	$query_str3="delete from radcheck where Attribute='Simultaneous-Use' And UserName='".$UserName."'";
	$db->query($query_str3);
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
 * 添加用户技术参数信息
 *
 * @param resource product $value
 * @return true or false
 */
function addUserParaminfo($userID,$productID){
	global $db;
	$configRs =$db->select_one("*","config","0=0 order by ID desc limit 0,1");
	$userRs   =$db->select_one("*","userinfo","ID='".$userID."'");
	$productRs=$db->select_one("*","product","ID='".$productID."'");// 查询出产品的信息
	$projectRs=$db->select_one("p.device,p.mtu","project as p,userinfo as u","p.ID=u.projectID and u.ID='".$userID."'");//
	
	
		
	//用户设备信息
	$device      =$projectRs["device"];//设备信息
	$mtu		 =$projectRs["mtu"];
	$speedStatus =$configRs["speedStatus"];
	$projectID   =$userRs["projectID"];
	$UserName    =$userRs["account"];
	//产品带宽信息
	$upload_bandwidth   = $productRs['upbandwidth'];
	$download_bandwidth = $productRs['downbandwidth']; 
	$upload             = $productRs["upbandwidth"] * 1024;
	$download           = $productRs["downbandwidth"] * 1024;
	$Up_Burst           = $productRs["upbandwidth"] * 1024/8 * 1.5;
	$Down_Burst         = $productRs["downbandwidth"]  * 1024/8 * 1.5;	
	

	//添加用户mtu值
	$mtu_del="delete from radreply where userID='".$userID."' and Attribute='Framed-MTU'";
	$db->query($mtu_del);
	$mtu_sql="insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Framed-MTU','+=','$mtu')";
	$db->query($mtu_sql);	

	//根据不同的设备添加用户的技术参数
	if($device=='natshell'){
		if($speedStatus=="1"){//当启用内部限速规则
			$speed=$db->select_all("*","speedrule","projectID='".$projectID."'");
			$speedCount=count($speed);
			if(is_array($speed)){
				foreach($speed as $speedKey=>$speedRs){
					$dstip[$speedKey]    =$speedRs["dstip"];
				}
			}
			$del_sql="delete from radreply where userID='".$userID."' and (Attribute='mpd-limit' or Attribute='mpd-filter' or Attribute='Mikrotik-Rate-Limit')";
			$db->query($del_sql);
			if($speedCount){
				$speedCount=$speedCount*2;$j=0;
				for($i=0;$i<$speedCount;$i+=2){
					$str =  "1#".($i+1)."=nomatch dst net ".$dstip[$j];
					$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-filter','+=','".$str."');"; 
					$db->query($strShaperSQL);
					$str =  "2#".($i+1)."=nomatch src net ".$dstip[$j];
					$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-filter','+=','".$str."');"; 
					$db->query($strShaperSQL);
				}//end for
			}//end if
			$j=$j*2;
			$str =  "in#1=flt1  rate-limit ".$upload;
			$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','".$str."');"; 
			$db->query($strShaperSQL);
			$str =  "out#2=flt2  rate-limit ".$download;
			$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','".$str."');"; 
			$db->query($strShaperSQL);	
		}else{
			$db->delete_new("radreply","userID='".$userID."' and (Attribute='mpd-limit' or Attribute='mpd-filter' or Attribute='Mikrotik-Rate-Limit')");	
									
																																										
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','in#1=all shape $upload  $Up_Burst pass');";
			$db->query($strSQL);
			$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','mpd-limit','+=','out#2=all shape $download $Down_Burst pass');";
			$db->query($strSQL);
		}//end speedStatus
					
	}else if($device=="mikrotik"){//另外一种ROS 
		$db->delete_new("radreply","userID='".$userID."' and (Attribute='mpd-limit' or Attribute='mpd-filter' or Attribute='Mikrotik-Rate-Limit')");				
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Mikrotik-Rate-Limit','+=','$upload_bandwidth"."k/".$download_bandwidth."k');";
		$db->query($strSQL);			
			
	}else if($device=="ma5200f"){
		$product_upload_bandwidth    = $upload_bandwidth * 1024;
		$product_download_bandwidth  = $download_bandwidth * 1024;
		//删除已有的带宽参数 
		$strSQL = "delete  from radreply where UserName='".$UserName."'";
		$db->query($strSQL);
		
		$peak_product_upload_bandwidth = $product_upload_bandwidth * 5;
		$peak_product_download_bandwidth = $product_download_bandwidth * 5;
	
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
		$strShaperSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('userID','$UserName','Class','+=','".$str."');"; 
		$db->query($strShaperSQL);
	}elseif($device=="hi-spider"){
//		$product_upload_bandwidth    = $upload_bandwidth/8;
//		$product_download_bandwidth  = $download_bandwidth/8;	
//		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Upload-Rate-Limit ','+=','$product_upload_bandwidth');";
//		$db->query($strSQL);
//		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','Download-Rate-Limit ','+=','$product_download_bandwidth');";
//		$db->query($strSQL);			
		$product_upload_bandwidth    = $upload_bandwidth/8;
		$product_download_bandwidth  = $download_bandwidth/8;	
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Upstream-Speed-Limit','+=','$product_upload_bandwidth');";
		$db->query($strSQL);
		$strSQL = "insert into radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','RP-Downstream-Speed-Limit','+=','$product_download_bandwidth');";
		$db->query($strSQL);				
	
	}
	return true;
}

/***************************************
 * 添加用户相关属性信息，这里这作为用户登录的凭据
 *
 * @param $userID,$iptype,$ipaddress
 * @return true or false
 */
function addUserAttribute($userID,$orderID,$status,$macbind,$onlinenum="",$nasbind=""){
	global $db;
	$UserName=getUserName($userID);
	$sql=array(
			"userID"=>$userID,
			"UserName"=>$UserName,
			"orderID"=>$orderID,
			"status"=>$status,
			"macbind"=>$macbind,
			"onlinenum"=>$onlinenum,
			"nasbind"=>$nasbind
		);
	$db->insert_new("userattribute",$sql);
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
	$db->update_new("userattribute","userID='".$userID."'",$record);
	return true;
}

/***************************************
 * 添加用户IP地址信息
 *
 * @param $userID,$iptype,$ipaddress
 * @return true or false
 */
function addUserIpaddress($userID,$iptype,$ipaddress){
	global $db;
	$UserName   = getUserName($userID);
	$attribute 	= "Framed-IP-Address";
	$op			= ":=";
	$value		= $ipaddress;	
	$db->delete_new("radreply","userID='$userID' and Attribute='$attribute'");
	if($iptype==1){//当类型等于1时间表示要系统分配IP地址
		$strSql     = "INSERT INTO radreply(userID,UserName,Attribute,op,Value) values('$userID','$UserName','$attribute','$op','$value');";
		if($db->query($strSql)){
			return true;
		}else{
			return false;
		}	
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
		if($stopdatetime!="0000-00-00 00:00:00"){

			if($productRs['type']=="year"){
				$datestr = $stopdatetime." -".$productRs['period']." year";
			}elseif($productRs['type']=="month"){
				$datestr = $stopdatetime." -".$productRs['period']." month";
			}elseif($productRs['type']=="days"){
				$datestr = $stopdatetime." -".$productRs['period']." day";
			}elseif($productRs['type']=="hour"){
				$datestr = $stopdatetime." -1 month";
			}elseif($productRs['type']=="flow"){
				$datestr = $stopdatetime." -1 month";
			}
			$begindatetime     = date('Y-m-d H:i:s',strtotime($datestr));
			$enddatetime       = $stopdatetime; 
			$orderenddatetime  = $stopdatetime;	
		} else {
			if($productRs['type']=="year"){
				$datestr = $todaytime." +".$productRs['period']." year";
			}elseif($productRs['type']=="month"){
				$datestr = $todaytime." +".$productRs['period']." month";
			}elseif($productRs['type']=="days"){
				$datestr = $todaytime." +".$productRs['period']." day";
			}elseif($productRs['type']=="hour"){
				$datestr = $todaytime." +1 month";
			}elseif($productRs['type']=="flow"){
				$datestr = $todaytime." +1 month";
			}
			$begindatetime     = $todaytime;//开进运行时间 
			$enddatetime       = date('Y-m-d H:i:s',strtotime($datestr));//运行结束时间
			$orderenddatetime  = date('Y-m-d H:i:s',strtotime($datestr));//订单应该结束时间

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

	if($db->insert_new("userrun",$sql)){
		return true;
	}else{
		return false;
	}
		
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
	}elseif($productRs['type']=="flow"){
		$datestr = $todaytime." +1 month";
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
function addUserLogInfo($userID,$type,$content,$operator=""){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$sql=array(
		"userID"=>$userID,
		"type"=>$type,
		"content"=>$content,
		"operator"=>$operator,
		"adddatetime"=>date("Y-m-d H:i:s",time()),
	);
	if($db->insert_new("userlog",$sql)){
		return true;
	}else{
		return false;
	}
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
	if($result){
		return true;
	}else{
		return false;
	}				
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
	if($runRs["status"]==0){//表示还没有开始上线的用户
		$second=0;
	}else{
		$second =abs(strtotime(date("Y-m-d H:i:s"))- strtotime($runRs["begindatetime"]));//计算用户使用的时间
	}
	$unitprice   =productRate($orderRs["productID"]);//根据产品的编号计算本产品的费率
	if($productRs["type"]=="hour"){
		$mRs=$db->select_one("sum(stats) as toaldata","runinfo","orderID='$orderID'");
		$mData=$mRs["toaldata"];
		$useMoney=ceil($unitprice*$mData);
	}else if($productRs["type"]=="flow"){//流量设置
		$mRs=$db->select_one("sum(stats) as toaldata","runinfo","orderID='$orderID'");
		$mData=$mRs["toaldata"]; 	
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
		$orderID=$db->insert_id();
		//同时扣除用户帐号中的金额
		$productRs   =$db->select_one("price","product","ID='".$productID."'");
		$productPrice=(int)$productRs["price"];
		$db->query("update userinfo set money=money-".$productPrice." where ID='".$userID."'");
		addUserBillInfo($userID,"4",$productRs["price"],'',$operator);		
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
 * 作废一张订单
 *
 * @param resource $orderID
 * @return true or false
 */
function cancelOrder($userID,$orderID){
	global $db;
	addOrderLogInfo($userID,$orderID,"1",$_SERVER['REQUEST_URI'],"SYSTEM_fn_canelOrder");//加写订单日志
	$surplusMoney=computeUserProductSurplus($orderID);//得到此订单应该返回的金额
	addRechargeInfo($userID,$surplusMoney);//把些订单的剩余的费用返回给用户
	addUserBillInfo($userID,"3",$surplusMoney);	//写用户帐单记录，3为退费
	$db->query("update userrun set enddatetime='".date("Y-m-d H:i:s",time()-86400)."',status=4 where orderID='".$orderID."'");
	$db->query("update orderinfo set status=4 where ID='".$orderID."'");
	//addOrderRefund($userID,0,$surplusMoney,$surplusMoney,$orderID,"SYSTEM_fn_cancelOrder"); //同生成退费订单
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
function addCreditInfo($userID,$type,$money,$operator=""){
	global $db;
	$operator=empty($operator)?$_SESSION["manager"]:$operator;
	$sql=array(
		"userID"=>$userID,
		"type"=>$type,
		"money"=>$money,
		"operator"=>$operator,
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
	for($i=$startNum;$i<=$endNum;$i++){
		$cardNumber=$prefix.$i;
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
	}
	//insert new cardlog infomation 
	addCardLog(0,"Creat Card:'$prefix'-'$startNum'-'$endNum'");
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
	$db->insert_new("cardlog",$sql);
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
			$str="<font color='#519A01'>等待运行</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>正在使用</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>到期使用</font>";
			break;
		case "3":
			$str="<font color='#ff0000'>欠费停用</font>";
			break;
		case "4":
			$str="<font color='#ccccc'>完成</fon>";
			break;
		case "5":
			$str="<font color='#F4C103'>暂停使用</font>";
			break;
		default :
			$str="未知";
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
	switch($num){
		case "0":
			$str="<font color='#519A01'>开户预存</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>前台续费</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>卡片充值</font>";
			break;
		case "3":
			$str="<font color='#ff0000'>订单退还</font>";
			break;
		case "4":
			$str="<font color='#ccccc'>订单扣费</fon>";
			break;
		case "5":
			$str="<font color='#F4C103'>销户退费</font>";
			break;
		case "6":
			$str="<font color='#F4C103'>用户冲帐</font>";
			break;
		case "7":
			$str="<font color='#F4C103'>用户移机</font>";
			break;
		case "8":
			$str="<font color='#F4C103'>安装费用</font>";
			break;
		default :
			$str="未知";
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
			$str="<font color='#519A01'>新增</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>续费</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>修改</font>";
			break;
		case "3":
			$str="<font color='#ff0000'>暂停</fon>";
			break;
		case "4":
			$str="<font color='#ff0000'>取消暂停</fon>";
			break;
		case "5":
			$str="<font color='#ff0000'>销户</font>";
			break;
		case "6":
			$str="<font color='#ff0000'>设置停机</font>";
			break;
		case "7":
			$str="<font color='#ff0000'>更改产品</font>";
			break;
		case "8":
			$str="<font color='#ff0000'>删除用户</font>";
			break;			
		default :
			$str="未知";
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
function orderRefundStatus($num){
	switch($num){
		case "0":
			$str="<font color='#519A01'>订单退费</font>";
			break;
		case "1":
			$str="<font color='#00ff00'>销户退费</font>";
			break;
		case "2":
			$str="<font color='#FFBC42'>冲帐退费</font>";
			break;
		default :
			$str="未知";
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
	echo "<select name='operator'>";
	echo "<option value=''>选择人员</option>";
	if($mResult){
		foreach($mResult as $mKey=>$mRs){
			echo "<option value='".$mRs["manager_account"]."'";
			if($manager==$mRs["manager_account"]) echo "selected";
			echo ">".$mRs["manager_account"]."</option>";
		}
	}
	echo "</select>";
}


//流量单位的换算
function flowUnit($num,$unit){
	if($unit=='kb'){
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
		if($KB>0){
			$str.=$KB."KB ";
		}

	
	}
	echo $str;

}

?>