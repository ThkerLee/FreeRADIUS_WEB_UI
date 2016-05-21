#!/bin/php
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-ridate");
header("Pragma: no-cache"); 
header('Content-Type:text/html;charset=utf-8');
require_once("inc/conn.php");
require_once("inc/timeOnLine.php");
//这是添加项目的
if(isset($_GET["projectName"])){
	if(empty($_GET["projectName"])){
		echo "<font color=\"#ff0000\">"._("名称不能为空").".</font>".$_GET["UserName"];
	}else{
		echo "<font color=\"#00923F\">"._("正确")."</font>";
	}
}

if(isset($_GET["beginip"])){
	if(empty($_GET["beginip"])){
		echo "<font color=\"#ff0000\">"._("开始IP地址不能为空")."</font>";
	}elseif(!is_ip($_GET["beginip"])){
		echo "<font color=\"#ff0000\">"._("所输入的IP地址错误")."</font>";
	}else{
		echo "<font color=\"#00923F\">"._("正确")."</font>";
	}
}

if(isset($_GET["endip"])){
	if(empty($_GET["endip"])){
		echo "<font color=\"#ff0000\">"._("结束IP地址不能为空")."</font>";
	}elseif(!is_ip($_GET["endip"])){
		echo "<font color=\"#ff0000\">"._("所输入的IP地址错误")."</font>";
	}else{
		echo "<font color=\"#00923F\">"._("正确")."</font>";
	}
}
if(isset($_GET["nasip"])){
	/*if(empty($_GET["nasip"])){
		echo "<font color=\"#ff0000\">NASIP地址不能为空</font>";
	}else*/
	if(!is_ip($_GET["nasip"]) && $_GET["nasip"]!=""){
		echo "<font color=\"#ff0000\">"._("所输入的NASIP地址错误")."</font>";
	}else{
		echo "<font color=\"#00923F\">"._("正确")."</font>";
	}
}


if(isset($_GET["check_account"])){
	global $db;
	if(empty($_GET["check_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空").".</font>".$_GET["check_account"];
	}elseif(strpos($_GET["check_account"],'#')==true){
		echo "<font color=\"#ff0000\">"._("用户账号含违禁字符")."#</font>";
	}elseif(strpos($_GET["check_account"],'&')==true){
		echo "<font color=\"#ff0000\">"._("用户账号含违禁字符")."&</font>";
	}/*elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET["check_account"])){
		echo "<font color=\"#ff0000\">必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成</font>";
	}*/else{
		$num=$db->select_count("userinfo","account='".$_GET["check_account"]."'");
		if($num>0){
			echo "<font color=\"#ff0000\">"._("帐号存在于用户系统之中")."</font>";
			echo "<input type='hidden' name='usercheck' value='1'>";
		}else{
			echo "<font color=\"#00923F\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
			echo "<input type='hidden' name='usercheck' value='0'>";
		}		
	}
}

//编辑用户显示子账号
if(isset($_GET["user_edit_account"])){
	global $db;
	$account=$_GET["user_edit_account"];
	$Mname=$db->select_one('Mname','userinfo','account="'.$account.'"');
	$momName=$Mname['Mname'];
	   echo "<input type='hidden' name='usercheck' value='0'>";
	if($momName!='' && strpos($momName,"#")===false){ 
	   echo "<font color=\"#00923F\">"._("母账号为:$momName")." </font>";
	}else{
	  echo "<font color=\"#ff0000\">"._("该账号为非子账号")."</font>"; 
	}
}

//子账号添加验证
if(isset($_GET["check_Mname"])){
	global $db;
	if(empty($_GET["check_Mname"])){
		echo "<font color=\"#ff0000\">"._("母账号不能为空. ")."</font>".$_GET["check_Mname"];
	}else if(strpos($_GET["check_Mname"],"#")){//子母账号，母账号的子账号的分割符不为禁用符
	 echo "<font color=\"#ff0000\">"._("用户名含违禁字符 ")."</font>"; 
	}
	/*elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET["check_account"])){
		echo "<font color=\"#ff0000\">必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成</font>";
	}*/else{
		$num=$db->select_count("userinfo","account='".$_GET["check_Mname"]."' ");
		if($num>0){//用户表中存在该账号
		     echo "<input type='hidden' name='Hchild' value='1'>";
			 $mname=$db->select_one('Mname',"userinfo","account='".$_GET["check_Mname"]."'");
			 if($mname['Mname']!='' && strpos($mname['Mname'],"#")===false){//身为子账号
				echo "<font color=\"#ff0000\">"._("帐号是子账号,不允许作为母账号添加子账号 ")."</font>";
				echo "<input type='hidden' name='Musercheck' value='0'>";
			 }else if(($mname['Mname']!='' && strpos($mname['Mname'],"#")) || $mname['Mname']==''){//不为子账号 可作为母账号
				echo "<font color=\"#00923F\">"._("该账号可作为母账号进行注册子账号 ")."</font>";
				echo "<input type='hidden' name='Musercheck' value=1'>";
			 }	
		}else{//不存在该帐号 不允许添加
		     echo "<font color=\"#ff0000\">"._("系统中不存在该帐号，你可以注册此帐号 ")."</font>";
		     echo "<input type='hidden' name='Hchild' value='0'>";
		} 	
	}
}

if(isset($_GET["check_prefix"]) || isset($_GET["check_startID"]) || $_GET["check_endID"]){
	global $db;
	if(isset($_GET["check_prefix"])){
	  if(empty($_GET["check_prefix"])){
		echo "<font color=\"#ff0000\">"._("标识符不能为空.")."</font>".$_GET["check_prefix"];
	  }elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET["check_prefix"])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的标识符! 标识符由@a-zA-Z0-9_-或者数字0-9组成 ")."</font>";
	  }
	}
} 
  
if(isset($_GET["check_startID"])){
	global $db;
	if(empty($_GET["check_startID"])){
		echo "<font color=\"#ff0000\">"._("开始ID不能为空或0").".</font>";
	} elseif(is_numeric($_GET["check_startID"]) < 0 ||  !is_numeric($_GET["check_startID"])){ 
	    echo "<font color=\"#ff0000\">"._("必须输入一个有效的开始ID!数字0-9组成")."</font>"; 
	 } 
}

//结束IP

if(isset($_GET["ippoolID"])){
	global $db;
	$ippoolID = $_GET["ippoolID"];  
	if(!empty($ippoolID)) {
	 $ip=$db->select_one('*','ippool','ID="'.$ippoolID.'"');
	 if($ip){
	 echo "<input type='text' value='".$ip['endip']."' disabled='disabled'>"; 
	 } 
	}else{
	  echo "<input type='text'  disabled='disabled'>";
	
	 }
}


if(isset($_GET["check_endID"]) ){
	global $db;
	 if(empty($_GET["check_endID"])){
		echo "<font color=\"#ff0000\">结束ID不能为空或</font>";
	 } elseif(is_numeric($_GET["check_endID"]) < 0 ||  !is_numeric($_GET["check_endID"])){ 
	    echo "<font color=\"#ff0000\">"._("必须输入一个有效的结束ID!数字0-9组成")."</font>"; 
	 }
}
   
if(isset($_GET["repair_check"])){
	global $db;
	if(empty($_GET["repair_check"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空")."</font>".$_GET["repair_check"];
		
	}elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET["repair_check"]) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET["repair_check"])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为00:24:21:19:BD:E4")."</font>";
	}else{
		$userRs  =$db->select_one("*","userinfo","account='".$_GET["repair_check"]."'");
		if($userRs){
			echo "<input type='hidden' name='userID' value='".$userRs["ID"]."'>";
			echo "<font color=\"#00923F\">"._("帐号存在于用户系统之中")."</font>";
		}else{
			echo "<input type='hidden' name='userID' value=''>";
			echo "<font color=\"#ff0000\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	}
}

if(isset($_GET["order_check_account"])){
	global $db;
	if(empty($_GET["order_check_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空").".</font>".$_GET["order_check_account"];
		     
	}/*elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET["order_check_account"]) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET["order_check_account"])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为")."00:24:21:19:BD:E4</font>";
	}*/else{
		$userRs  =$db->select_one("ID,money,name","userinfo","account='".$_GET["order_check_account"]."'");
		$aRs	 =$db->select_one("p.name as productName,p.ID as productID,p.price as productPrice","userattribute as a,orderinfo as o,product as p","a.orderID=o.ID and o.productID=p.ID and a.UserName='".$_GET["order_check_account"]."'");
		if($userRs){
			echo "<input type='hidden' name='userID' id='userID' value='".$userRs["ID"]."'>";
			echo "<input type='hidden' name='money' id='money' value='".$userRs["money"]."'>";
			echo "<input type='hidden' name='old_productID' id='old_productID' value='".$aRs["productID"]."'>";
			echo "<input type='hidden' name='productPrice' id='productPrice' value='".$aRs["productPrice"]."'>"; 
			echo "<br>"._("用户姓名")."：".$userRs["name"]."";
			echo "<br>"._("用户当前余额为：￥").$userRs["money"].""; 
			echo "<br>"._("用户当前产品为：").$aRs["productName"];
			echo "<br>"._("当前产品价格为：￥").$aRs["productPrice"]; 
		}else{
			echo "<input type='hidden' name='userID' value=''>";
			echo "<font color=\"#00923F\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	}
}
if(isset($_GET['MTC_check_account'])){//人工收费
 global $db;
 $account=$_GET['MTC_check_account'];
 if(empty($account)){
    echo "<font color=\"#ff0000\">"._(" 用户名不能为空").".</font>"; 
 }elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $account) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $account)){
		echo "<font color=\"#ff0000\">"._(" 必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为")."00:24:21:19:BD:E4</font>";
 }else{//输入正确。。判断用户名是否存在
   $userRs  =$db->select_one("account,money","userinfo","account='".$account."'"); 
   if($userRs){
    echo "<input type='hidden' name='userName' value='".$userRs['account']."'>";
	echo "<input type='hidden' name='E_money' value='".$userRs['money']."'>";
	
	echo "<font color=\"#00923F\">"._(" 用户余额为：").$userRs['money']."</font>";
	
   }else{
    echo "<input type='hidden' name='userName' value=''>";
    echo "<font color=\"#00923F\">"._(" 系统中不存在此帐号，你可以注册此帐号")."</font>";
   }
 }
}
//获取手工费金额
if(isset($_REQUEST['financeID'])){
	global $db;
	$financeID= $_GET['financeID']; 
	$fRs=$db->select_one("money","finance","ID='".$financeID."'");
	if($fRs){
	  echo "<input type=\"text\" id=\"financemoney\" name=\"financemoney\"  value='".$fRs['money']."'onFocus=\"this.className='input_on'\" onBlur=\"this.className='input_out';\" class=\"input_out\">";
	}else{
	 echo "<input type=\"text\" id=\"financemoney\" name=\"financemoney\"  value='0'onFocus=\"this.className='input_on'\" onBlur=\"this.className='input_out';\" class=\"input_out\">";//<font color=\"red\"> 金额亦可自定义</font>
	} 
}

//获取手工费备注
if(isset($_REQUEST['remark'])){
    global $db;
	$financeID= $_GET['remark'];
	$fRs=$db->select_one("remark","finance","ID='".$financeID."'");
	if($fRs){
	  echo "<textarea name=\"remark\" cols=\"50\" rows=\"5\"  onFocus=\"this.className='textarea_on'\" onBlur=\"this.className='textarea_out';\" class=\"textarea_out\">".$fRs['remark']."</textarea>	";
	}else{
	  echo "<textarea name=\"remark\" cols=\"50\" rows=\"5\"   onFocus=\"this.className='textarea_on'\" onBlur=\"this.className='textarea_out';\" class=\"textarea_out\">".$fRs['remark']."</textarea>	";
	}  
}

if(isset($_GET['user_password_account'])){
global $db;
	if(empty($_GET["user_password_account"])){
		echo "<font color=\"#ff0000\">"._(" 用户名不能为空").".</font>".$_GET["user_password_account"]; 
	}/*elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET['user_password_account']) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET['user_password_account'])){
		echo "<font color=\"#ff0000\">必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为00:24:21:19:BD:E4</font>";
    }*/else{
		$userRs  =$db->select_one("password","userinfo","account='".$_GET["user_password_account"]."'"); 
		if($userRs){ 
			echo " 
				<input name=\"password\" type=\"text\" class=\"input_out\" id=\"password\" readonly=\"readonly\" onFocus=\"this.className='input_on'\"   onBlur=\"this.className='input_out';\" value=".$userRs['password']."> ";
		}else{
			echo "<input type='hidden' name='userID' value=''>";
			echo "<font color=\"#00923F\">"._(" 系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	} 
}

 


//暂停用户
if(isset($_GET["shutdown_account"])){
	global $db;
	if(empty($_GET["shutdown_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空").".</font>".$_GET["shutdown_account"];
	}elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET['shutdown_account']) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET['shutdown_account'])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为")."00:24:21:19:BD:E4</font>";
    }else{
		$userID  =getUserID($_GET["shutdown_account"]);
		$userRs  =$db->select_one("*","userinfo","UserName='".$_GET["shutdown_account"]."'");
		$att     =$db->select_one("*","userattribute","UserName='".$_GET["shutdown_account"]."'");
		$runRs   =$db->select_one("*","userrun","userID='".$userID."' and orderID='".$att["orderID"]."'");//得到正在运行订单信息
		
		if($att["closing"]=="1"){
			$status="<font color='#ff000'>"._("[已经销户]")."</font>";
		}else{
			$status=($att["pause"]=="1")?_("[用户暂停]"):_("[正常运行]");
		}
		if($userRs){
			echo "<input type='hidden' name='userID' value='".$userRs["ID"]."'>";
			echo "<input type='hidden' id='stopdate' name='stopdate' value='".$runRs["stopdatetime"]."'>";
			echo "<input type='hidden' id='restore' name='restore' value='".$runRs["restoredatetime"]."'>";
			echo "<input type='hidden' name='closing' value='".$att["closing"]."'>";
			echo "<br>"._(" 用户当前状态：").$status."<br>";
		}else{
			echo "<input type='hidden' name='userID' value=''>";
			echo "<font color=\"#00923F\">"._(" 系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	}
}

//系统用户权限
 
if(isset($_GET['manager_groupID'])){	

	$rs                 =$db->select_one("*","manager","manager_groupID='".$_GET['manager_groupID']."'");
	$permisionArr       =explode("#",$rs["manager_permision"]);
	$manager_projectArr =explode(",",$rs["manager_project"]);
	$manager_gradeArr   =explode(",",$rs["manager_gradeID"]);	       
  //2014.04.22修改权限：基础版、加强版、ISP版
        $file = popen("license -T","r");
         $data = fgets($file);//获取授权
         pclose($file);
         if($data == 1 ){ //ISP版权限
             		$result=$db->select_all("*","managerpermision","permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">".$rs["permision_name"]."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=160 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'";
					if(in_array($subRs["permision_param"],$permisionArr)) echo "checked";
					echo ">".$subRs["permision_name"]."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if  
         }elseif ($data == 2 ) { //加强版权限
                $result=$db->select_all("*","managerpermision","ID !=64 and ID !=165 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">".$rs["permision_name"]."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=140 and ID !=138 and ID !=161 and ID !=160 and  ID !=163 and ID !=128 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'";
					if(in_array($subRs["permision_param"],$permisionArr)) echo "checked";
					echo ">".$subRs["permision_name"]."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if 
    }elseif ($data == 3) { //基础版权限
        $result=$db->select_all("*","managerpermision","ID !=58 and ID !=96 and ID !=16 and ID !=64 and ID !=165 and ID !=36 and permision_parentID=0 order by permision_rank asc",50);
		if(is_array($result)){
			foreach($result as $key=>$rs){
			echo "<div class='bg1'><input type='checkbox' name='permision[]' id='".$rs["ID"]."' value='".$rs["permision_param"]."' onclick=permision_change('".$rs["ID"]."') ";
			if(in_array($rs["permision_param"],$permisionArr)) echo "checked";
			echo ">".$rs["permision_name"]."</div>";
			echo "<div id='sub".$rs["ID"]."' style='line-height:25px;'>&nbsp;&nbsp;&nbsp;"; 
			$subResult=$db->select_all("*","managerpermision","ID !=49 and ID !=80 and ID !=140 and ID !=137 and ID !=73 and ID !=84 and ID !=130 and ID !=43 and ID !=63 and ID !=35 and ID !=115 and ID !=31 and ID !=56 and ID !=162 and ID !=128 and ID !=163 and ID !=159 and ID !=160 and ID !=161 and ID !=155 and ID !=157 and ID !=158 and ID !=126 and ID !=118 and ID !=119 and ID !=120 and ID !=121 and ID !=164 and ID !=111 and ID !=113 and ID !=114 and ID !=107 and ID !=138 and permision_parentID='".$rs["ID"]."'");
			if(!empty($subResult)){
				foreach($subResult as $subRs){
					echo "<input type='checkbox' name='permision[]' value='".$subRs["permision_param"]."'";
					if(in_array($subRs["permision_param"],$permisionArr)) echo "checked";
					echo ">".$subRs["permision_name"]."&nbsp;&nbsp;";
				}//end sub foreach
			}//end sub if 
			echo "</div>";
		}//end foreach parent
	}//end if  
    }
 
	 
} 
if(isset($_GET["reverse_check_account"])){
	global $db;
	if(empty($_GET["reverse_check_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空").".</font>".$_GET["reverse_check_account"];
		
	}elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET['reverse_check_account']) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET['reverse_check_account'])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为")."00:24:21:19:BD:E4</font>";
    }else{
		$userRs=$db->select_one("*","userinfo","account='".$_GET["reverse_check_account"]."'");
		if($userRs){
			echo "<input type='hidden' name='userID' value='".$userRs["ID"]."'>";
			echo "<input type='hidden' name='surplusMoney' value='".$userRs["money"]."'>";
			echo _("用户当前余额为：￥").$userRs["money"]."";
		}else{
			echo "<input type='hidden' name='userID' value=''>";
			echo "<font color=\"#00923F\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	}
}


if(isset($_GET["reverse_check_account"])){
	global $db;
	if(empty($_GET["reverse_check_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空").".</font>".$_GET["reverse_check_account"];
		
	}elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET['reverse_check_account']) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET['reverse_check_account'])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为")."00:24:21:19:BD:E4</font>";
    }else{
		$userRs=$db->select_one("*","userinfo","account='".$_GET["reverse_check_account"]."'");
		if($userRs){
			echo "<input type='hidden' name='userID' value='".$userRs["ID"]."'>";
			echo "<input type='hidden' name='surplusMoney' value='".$userRs["money"]."'>";
			echo _("用户当前余额为：￥").$userRs["money"]."";
		}else{
			echo "<input type='hidden' name='userID' value=''>";
			echo "<font color=\"#00923F\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	}
}



//------------------------------2014.07.23添加押金退费显示押金-------------------------------------//
if(isset($_GET["user_pledgemoney_account"])){
	global $db;
	if(empty($_GET["user_pledgemoney_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空").".</font>".$_GET["user_pledgemoney_account"];
		
	}elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET['user_pledgemoney_account']) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET['user_pledgemoney_account'])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为")."00:24:21:19:BD:E4</font>";
    }else{
		$userRs=$db->select_one("*","userinfo","account='".$_GET["user_pledgemoney_account"]."'");
		if($userRs){
			echo "<input type='hidden' name='userID' value='".$userRs["ID"]."'>";
			echo "<input type='hidden' name='surplusMoney' value='".$userRs["pledgemoney"]."'>";
			echo _("用户当前押金为：￥").$userRs["pledgemoney"]."";
		}else{
			echo "<input type='hidden' name='userID' value=''>";
			echo "<font color=\"#00923F\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	}
}




//更换产品信息
if(isset($_GET["replac_prodcut_check_account"])){
	global $db; 
	if(empty($_GET["replac_prodcut_check_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空").".</font>".$_GET["replac_prodcut_check_account"];
		
	}elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET['replac_prodcut_check_account']) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET['replac_prodcut_check_account'])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为")."00:24:21:19:BD:E4</font>";
    }else{
		$userRs=$db->select_one("*","userinfo","account='".$_GET["replac_prodcut_check_account"]."'");
		$rs=$db->select_one("u.*,u.enddatetime as uendtime,o.*,p.*,p.name as productName,p.type as Ptype,o.ID as orderID","userrun as u,orderinfo as o,product as p"," u.orderID=o.ID and o.productID=p.ID and u.userID='".$userRs["ID"]."' and (u.status in (1,5))");//这里要计算出要退的订单	
		
		$productName      = (empty($rs["productName"]))?_("没有正在使用的产品"):$rs["productName"];
		//$surplusMoney     = computeUserProductSurplus($rs["orderID"]);
		//$time             = timesTamp($rs['begindatetime']); 
		
        if($rs["Ptype"]=="days"){
		    $time     = monthDays($rs['begindatetime'],time());//包天用户用时天
			//消费金额
		    $userdMoney  = $rs["price"]/$rs['period'] *$time ;//多少钱每天*用的天数 = 消费金额
			//未消费金额
		    $surplusMoney =$rs["price"] -  $userdMoney; 
	    }elseif($rs["Ptype"]=="month"){
		   if($rs['period']==1){//一个月
		        //消费天数	
		        $time        = monthDays($rs['begindatetime'],time());//包天用户消费时间 天		
			    //消费金额
		        $userdMoney  = $rs["price"]/ monthDays($rs['begindatetime'],$rs['uendtime']) *$time ;//多少钱每天*用的天数 = 消费金额
			    //未消费金额
		        $surplusMoney =$rs["price"] -  $userdMoney; 
		   }else{//多月
		       //消费月	
		       $times = (timesTamp($rs['begindatetime'])-1);	//算法 未满一个月也算为一个月 所以此处应该-1
		       //消费金额
		       $userdMoney = $rs["price"]/$rs['period']* $times; //（当前产品月单价 * 消费月 = 消费金额） 
		       //未消费金额
		       $surplusMoney =$rs["price"] -  $userdMoney;//套餐价格 -消费金额  = 剩余为消费的套餐金额。。。。一天为一月
		   }
		}elseif($rs['Ptype']=="year"){
		    //消费月	
		    $times = (timesTamp($rs['begindatetime'])-1);	//算法 未满一个月也算为一个月 所以此处应该-1
		    //消费金额
		    $userdMoney = $rs["price"]/$rs['period']/12 * $times; //（当前产品月单价 * 消费月 = 消费金额） 
		    //未消费金额
		    $surplusMoney =$rs["price"] -  $userdMoney;//套餐价格 -消费金额  = 剩余为消费的套餐金额。。。。一天为一月
		} 
		$surplusToalMoeny = $surplusMoney+$userRs["money"]; 
		$oneMonth         = monthDays($rs['begindatetime'],time());//一月用户用时天
		if($rs['Ptype']=="year" || ($rs['Ptype']=="month" && $rs['period']!=1 ) ){//数月和包年用户
		 	$date=" ".$times._(" 月");
			if($time==12){
				$date=_(" 1 年");
			}if($time==24){
				$date=_(" 2 年");
			}if($time==36){
				$date=_(" 3 年");
			}
		 }if(($rs['Ptype']=="month" && $rs['period']==1)|| $rs['Ptype']=="days"  ){// 一月用户  记天用户
		    $date = $oneMonth._(" 天"); 
		 } 
			
			if($userRs){
				echo "<input type='hidden' name='userID' value='".$userRs["ID"]."'>";
				echo "<input type='hidden' name='orderID' value='".$rs["orderID"]."'>";
				//echo "<input type='hidden' name='money' value='".$userRs["money"]."'>";
				echo "<input type='hidden' name='surplusMoeny' value='".floor($surplusMoney)."'>";//可退余额
				echo "<input type='hidden' name='surplusToalMoeny' value='".floor($surplusToalMoeny)."'>";//预存金+可退余额
				echo "<li class='check_li'>"._("当前用户余额为：￥").$userRs["money"]."元</li>";
				echo "<li class='check_li'>"._("当前使用的产品：").$productName."</li>";
				echo "<li class='check_li'>"._("当前消费的时间：").$date."</li>";
				//echo "<li class='check_li'>"._("注：消费一天也是为一月。天消费一小时视为一天")."</li>";
				$useDate=timesTamp($runinfoRs['begindatetime']); //当前产品所用时
				echo "<li class='check_li'>"._("当前消费金额：").ceil($userdMoney)."</li>";
				echo "<li class='check_li'>"._("当前可退还金额：").floor($surplusMoney)."</li>";
				echo "<li class='check_li'><font color=\"red\">"._("总共应退金额：").floor($surplusToalMoeny)."</font></li>"; 
				//echo "<li class='check_li'><font color=\"red\">"._("如：包4个月,1月1日到3月2-31日 视为消费3个月 实际足月消费2个月 剩余2个月按新产品计算")."</font></li>";
			  //  echo "<li class='check_li'><font color=\"red\">"._("注：消费一天也是为一月。天消费一小时视为一天").".&nbsp;"._("消费金额按实际足月计算，而非虚月，不足的月或天将根据新产品计算").",".("单月产品按差价预存/补交")."</font></li>";
				
			}else{
				echo "<input type='hidden' name='userID' value=''>";
				echo "<font color=\"#00923F\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
			} 
	}
} 

if(isset($_GET["productID"])){

	global $db;
	$checkProductRs=$db->select_one("*","product","ID='".$_GET["productID"]."'"); 
	if($checkProductRs){ 
		echo "&nbsp;"._("你当前选择的产品价格为：￥").$checkProductRs["price"]; 
		echo "<input type='hidden' name='productPrice' id='productPrice' value='".$checkProductRs["price"]."'>";
		echo "<input type='hidden' name='changeProductPrice' id='changeProductPrice' value='".$checkProductRs["price"]."'>";			 
	}else{ 
		echo _("请选择你要需的产品，此项为必选项"); 
	} 
}

if(isset($_GET["checkProductID"])){
	global $db;
	$ID = explode(";", $_GET["checkProductID"]);
	if(is_array($ID)){
		$rs             = $db->select_one("*","userinfo","UserName='".$ID[2]."'");//用户信息 
		$result         = $db->select_all("u.*,u.enddatetime as uendtime,o.*,p.*,p.name as productName,o.ID as orderID","userrun as u,orderinfo as o,product as p"," u.orderID=o.ID and o.productID=p.ID and u.userID='".$rs["ID"]."' and (u.status in (0,1,5))");
	  $result         = $result[0];
		$checkProductRs = $db->select_one("*","product","ID='".$ID[0]."'");//新产品信息
		$productRs      = $db->select_one("*","product","ID='".$ID[1]."'");//当前产品信息 
		$userRs=$db->select_one("*","userinfo","account='".$rs['account']."'");
		$balance        = $checkProductRs["price"]-$productRs["price"];//new -old
/******************* 包年用户***********************************/
		if($productRs['type']=='year'){
			$oldMonthBalance = $productRs["price"]/$productRs['period']/12 ;//当前产品月单价
			$newMonthBalance = $checkProductRs["price"]/$checkProductRs['period']/12;//更换产品的月单价
			$pmb             = $oldMonthBalance-$newMonthBalance;//月单价差  当前-更换后的  负数补额  正数为返还	
	    	//消费月	
		    $time            = (timesTamp($result['begindatetime'])-1);	//算法 未满一个月也算为一个月 所以此处应该-1
			//未消费月
			$leaveTime       = $productRs['period']*12-$time;
			//月差价
			$monthprice      = ($productRs["price"]-$checkProductRs["price"])/$productRs['period']/12;						 
            $buMoney         = $monthprice*$leaveTime;//预补费用 月差额*为消费月   负数为补额 正为退额			
	     }		 
/******************* 包数月用户***********************************/		 
		 elseif($productRs['type']=='month' ){
			  //echo print_r($result);
			if($productRs['period']==1){//一月用户
               $oldMonthBalance = $productRs["price"] ;//当前产品月单价
		       $newMonthBalance = $checkProductRs["price"];//更换产品的月单价 
			   $time            = monthDays($result['begindatetime'],time());//包天用户消费时间 天			    
			   $buMoney         = $oldMonthBalance-$newMonthBalance;	// 新产品 - 当前产品  负数为补额 正为退额
			}else{//数月用户  补交费用 =每月差额*（总月-消费月）月
 			   $oldMonthBalance = $productRs["price"]/$productRs['period'] ;//当前产品月单价
			   $newMonthBalance = $checkProductRs["price"]/$checkProductRs['period'];//更换产品的月单价 退额
               //未消费月
			   $time            = $productRs['period']-(timesTamp($result['begindatetime'])-1);//总月-消费月=剩余消费月
			   //月差价
			   $monthprice      = ($productRs["price"]-$checkProductRs["price"])/$productRs['period'];
			   
			   $buMoney         = $monthprice*$time;//预补费用 月差额*为消费月   负数为补额 正为退额 
			}		
	     }
/******************* 包天用户***********************************/		 
		 elseif($productRs['type']=='days'){
			$oldDayBalance   = $productRs["price"]/$productRs['period'] ;//当前产品天单价
			$newDayBalance   = $checkProductRs["price"]/$checkProductRs['period'];//更换产品的天单价
			//补交  old所退金额oldTuiMoney=old总价-（old总价 /天）*用户时   newdayMoney=(new总价/天)*剩下未使用天数
			//$buMoney = old所退金额 - newdayMoney //天单价差  当前-更换后的  负数补额  正数为返还
			$time            = monthDays($result['begindatetime'],time());//包天用户用时天
			$syDays          = $checkProductRs['period'] - $time;//新产品时间 - 已用天 = 剩余新产品 天数
			$now             = date("Y-m-d H:i:s", time());
			//更改后到期时间计算   剩余的天数 =新产品天数 - 已用的天数   结束时间 = time +剩余的天数 day
			$newEnddatetime  = date("Y-m-d H:i:s",strtotime(" $now + $syDays day"));
			$oldTuiMoney     = $productRs['price']-$oldDayBalance*$time;//当前产品退费
			$newdayMoney     = $newDayBalance*$syDays;//新产品剩余天应缴费用		
		  $buMoney         = $oldTuiMoney-$newdayMoney;//预补费  用当前产品退费 - 新产品剩余时长应缴费用   负数为补额
			echo "<input type='hidden' name='newEnddatetime' value='".$newEnddatetime."'>";// 更换后产品结束时间
	     }		 
/****************** 页面回显内容**********************************/		 
		 if($checkProductRs){
		 		if($buMoney>0){//退还
					$money = ceil($buMoney);
					$rechange_money =0; 
				}else{//补交
				    $money = floor($buMoney);$rechange_money =(ceil(abs($buMoney)-$userRs["money"]))<0?0:(ceil(abs($buMoney)-$userRs["money"])); 
				} 
				echo "<input type='hidden' name='money' value='".$userRs["money"]."'>";
			  echo "<input type='hidden' name='begindatetime' value='".$result['begindatetime']."'>";//订单  运行开始时间
				echo "<input type='hidden' name='enddatetime' value='".$result['uendtime']."'>";//订单  运行开始时间
				echo "<input type='hidden' name='bjMoney' value='".$money."'>";// 补交费用
				echo "<input type='hidden' name='checkPID' value='".$ID[0]."'>";// 更换后产品ID
				echo "<input type='hidden' name='orderID' value='".$result['orderID']."'>";// 原产品ID  				
				echo "<input type='hidden' name='userID' value='".$rs["ID"]."'>";// 用户ID
				//echo "<input type='hidden' name='checkProductOldID' value='".$ID[1]."'";//用户当前产品ID
                                echo "<input type='hidden' name='checkProductOldID' value='".$ID[1]."'>";//用户当前产品ID
			  echo "<li class='check_li'>"._("你当前选择的产品价格为：￥").$checkProductRs["price"]."</li>";
				echo "<li class='check_li'>"._("你当前使用的产品价格为：￥").$productRs["price"]."</li>";
				echo "<li class='check_li'>"._("你更换产品与当前产品总差价为：￥").$balance._(" (如果为正数则表示更换产品比当前使用产品整个周期高出的金额) ")."</li>";			
				 if($buMoney<0){ 
					// echo "<li class='check_li'>&nbsp;预补费用：￥".ceil(abs($buMoney))."</li>注：预补费用 两产品抵扣后仍需交的金额";  //(   负数补额) 用户余额+当前产品所退金额之和，不足以抵交更换后产品需补费用，仍需还需补交的费用。 而非产品间的差价
					// echo "<li class='check_li'>&nbsp;"._("预补费用：￥").round(ceil(abs($buMoney)),-1)."</li>";
				 echo "<li class='check_li'style='color:red;'>"._("预补费用：￥").round(ceil(abs($buMoney)),-1)."</li>"._("注：预补费用 两产品抵扣后仍需交的金额");  //(   负数补额) 用户余额+当前产品所退金额之和，不足以抵交更换后产品需补费用，仍需还需补交的费用。 而非产品间的差价
				}else{
				   echo "<li class='check_li' style='color:red;'>"._("返还费用：￥").ceil(abs($buMoney))." </li>"._("注：返还费用 两产品抵扣后需返还的金额");//(   负数补额)
		        }
				 echo "<li class='check_li' style='color:red;'>"._("补交费用：")."￥".$rechange_money." </li>";//(   负数补额)
		}else{
			echo _("请选择你要需的产品，此项为必选项");
		}
    }
}



//注销用户要返回的信息
if(isset($_GET["closing_check_account"])){
	global $db;
	if(empty($_GET["closing_check_account"])){
		echo "<font color=\"#ff0000\">"._("用户名不能为空 ").".</font>".$_GET["closing_check_account"];
		
	}elseif(!preg_match("/^[@a-zA-Z0-9_-]*$/", $_GET['closing_check_account']) && !preg_match("/^[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}+:[A-F0-9]{2}$/", $_GET['closing_check_account'])){
		echo "<font color=\"#ff0000\">"._("必须输入一个有效的用户名! 用户名由字母a-z, A-Z 或者数字0-9组成 或 有效MAC地址格式为 ")."00:24:21:19:BD:E4</font>";
    }else{
		$userRs=$db->select_one("*","userinfo","account='".$_GET["closing_check_account"]."'");
		$result=$db->select_all("u.*,u.status as ustatus,o.*,p.*,p.name as productName,o.ID as orderID","userrun as u,orderinfo as o,product as p"," u.orderID=o.ID and o.productID=p.ID and u.userID='".$userRs["ID"]."' and (u.status in (0,1,5))");
		//echo print_r($result);
		$att   =$db->select_one("*","userattribute","UserName='".$_GET["closing_check_account"]."'"); 
		if(is_array($result)){ 
			foreach($result as $key=>$rs){
				$orderID     .=$rs["orderID"]."##";
				if($rs['ustatus']==1)
				  $statusType = _("正在使用");
				if($rs['ustatus']==0)
				  $statusType = _("等待运行");
				if($rs['ustatus']==5)
				  $statusType = _("暂停使用");
				  
				$productName .=_("产品")."(".($key+1)."):&nbsp;".$rs["productName"]."&nbsp;【<font color='green'>".$statusType."</font>】&nbsp;&nbsp;";
				if($rs['penalty']!='' &&  is_numeric($rs['penalty'])){
					$penaltyShow .=_("产品")."(".($key+1)."):&nbsp;".$rs["productName"]."&nbsp;【<font color='red'>"._("违约金").$rs['penalty']._("元")."</font>】&nbsp;&nbsp;";
					$penalty  +=$rs['penalty']; //违约金额
				}
				$surplusMoney=$surplusMoney+computeUserProductSurplus($rs["orderID"]);
			}
		} 
		 
		$surplusToalMoeny=$surplusMoney+$userRs["money"]; //扣除违约金
		$productName=(empty($productName))?_("没有正在使用的产品"):$productName;
		if($userRs){
			echo "<input type='hidden' name='userID' value='".$userRs["ID"]."'>";
			echo "<input type='hidden' name='closedatetime' value='".$userRs["closedatetime"]."'>";
			echo "<input type='hidden' name='orderID' value='".$orderID."'>";
			echo "<input type='hidden' name='money' value='".$userRs["money"]."'>";
			echo "<input type='hidden' name='surplusMoeny' value='".$surplusMoney."' readonly>";
			echo "<input type='hidden' name='surplusToalMoeny' value='".$surplusToalMoeny."' readonly>";	
			echo "<input type='hidden' name='closing' value='".$att["closing"]."'>";
			echo "<input type='hidden' name='penaltyAjax' id='penaltyAjax' value='".$penalty."'>"; 
			echo "<li class='check_li'>"._(" 当前用户余额为：￥").$userRs["money"]._("元")."</li>";
			echo "<li class='check_li'>"._(" 当前使用的产品：").$productName."</li>";	
			echo "<li class='check_li'>"._(" 产品违约金额为：").$penaltyShow."</li>";
			echo "<li class='check_li'>"._(" 产品应退金额：").$surplusMoney._("元")."</li>";
			echo "<li class='check_li'>"._(" 总共应退金额：").$surplusToalMoeny._("元")."</li>";
		}else{  
			echo "<font color=\"#00923F\">"._("系统中不存在此帐号，你可以注册此帐号")."</font>";
		}		
	}
}


if(isset($_GET["productID1"])){
	global $db;
	$checkProductRs=$db->select_one("*","product","ID='".$_GET["productID1"]."'");
	if($checkProductRs){
	  echo 	$checkProductRs["price"];	
	}
}
if(isset($_GET['card_account'])){//卡片销售
 global $db;
 $account=$_GET['card_account'];
 if(empty($account)){
    echo "<font color=\"#ff0000\">"._(" 用户名不能为空").".</font>"; 
 } else{//输入正确。。判断用户名是否存在
   $userRs  =$db->select_one("account,money","userinfo","account='".$account."'"); 
   if(!$userRs){ 
    echo "<input type='hidden' name='userID' value=''>";
    echo "<font color=\"#00923F\">"._(" 系统中不存在此帐号，你可以注册此帐号")."</font>";
   }
 }
}  
//续费周期
if(isset($_GET["productIDPeriod"])){ //产品ID
global $db;
	$checkProductRs=$db->select_one("type","product","ID='".$_GET["productIDPeriod"]."'");  
	if($checkProductRs){
	    echo "<select id='period' name='period' onChange=\"totalMoneys();ajaxInputMore('ajax/project.php','pdoID','productID','timetype','timetype','begintime','begindatetime','period','period','periodTXT');\" >";
        echo "<option  value='' >"._("请您选择周期")."</option>"; 
        echo "<option value='1' selected >  1</option>"; 
        if($checkProductRs['type']!="flow" && $checkProductRs['type'] !="hour"){    
		  for($i=2;$i<=12;$i++){  
			 echo "<option value=".$i." >".$i."</option>"; 
		  }
		}
		echo "</select>";	
		echo "<span id='productPeriodTXT'></span> ";  
	}else{ 
	   echo "<select id='period' name='period' onChange=\"totalMoneys();\">";
       echo "<option  value='' >"._("请您选择周期")."</option>"; 
	   echo "</select>";	
	}

}

//充值卡号
if(isset($_GET["card_num"])){
global $db;
   $card_num = $_GET["card_num"];
   if(empty($card_num)){
    echo "<font color=\"#ff0000\">"._("充值卡号不能为空").".</font>"; 
   }else{
      //sold 1售出 0未售出  代理商卡片充值不存在  rechager = 0 未充值
       $cardRs=$db->select_one("*","card","cardNumber='".$card_num."' and sold in (0,1) and recharge in (0,1)"); 
	   $money = $cardRs["money"]; 
	   $password =$cardRs["actviation"]; 
       if(!is_array($cardRs)) {//充值卡号不存在
	      echo "<input type='hidden' name ='cardErr' id='cardErr' value ='1'>";//卡片不存在
		  echo "<input type='hidden' name ='cardLost' id='cardLost' value ='0'>";//卡片未失效
          echo "<font color=\"#ff0000\">"._("充值卡号不存在")."</font>"; 
       }elseif($cardRs["recharge"]==1){ //充卡失效  已经被充值 
	      echo "<input type='hidden' name ='cardErr' id='cardErr' value ='0'>";//卡片存在
	      echo "<input type='hidden' name ='cardLost' id='cardLost' value ='1'>";//卡片失效
          echo "<font color=\"#ff0000\">"._("充值卡号已经被充值")."</font>"; 
	   }else{
	      echo "<input type='hidden' name ='cardErr' id='cardErr' value ='0'>";//卡片存在
	      echo "<input type='hidden' name ='cardLost' id='cardLost' value ='0'>";//卡片未失效
	      echo "&nbsp;<input type='hidden' name ='rechangeMoney' id='rechangeMoney' value ='".$money."'>";//卡片充值金额
		  echo "&nbsp;<input type='hidden' name ='password' id='password' value ='".$password."'>";//卡片充值金额
	      echo _("充值金额:")."<font color=\"green\">".$money._("元")."</font>"; 
	   }
   }//end empty 
}
 //根据系统账号获取密码
 if(isset($_GET["manager_account"])){
	global $db;
	if(empty($_GET["manager_account"])){
		echo "<font color=\"#ff0000\">"._("管理员账号不能为空").".</font>".$_GET["manager_account"];
	}else{ 
		$userRs  =$db->select_one("manager_passwd","manager","manager_account='".$_GET["manager_account"]."'"); 
		if($userRs){  
		?> 
		  <input type="hidden" name="oldpwd" value="<?=$userRs["manager_passwd"]?>" >
			<input type="text" name="manager_passwd"readonly="readonly"   value="<?=$userRs["manager_passwd"]?>"><br><br>	 
			<input type="password" name="newpwd" value=""><br><br>		 
			<input type="password" name="newpwd1" value=""><br><br>	 
		<?php
		
		}else{
			echo "<input type='hidden' name='managerID' value=''>";
			echo "<font color=\"#00923F\">"._(" 系统中不存该管理员帐号，你可以注册此帐号")."</font>";
		}		
	}
}
 
?>