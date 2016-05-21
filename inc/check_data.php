
<?php 
/**
 ************************************
 * 文件名:   checkTable.php
 * 文件描述: 比较设置表和数据库中现有表的结构，并且修改
 * 创建人:   
 * 创建日期:
 * 版本号:   1.0
 * 修改记录: 
 ************************************
 */

$db   =@mysql_connect("localhost","root","root");
$conn =@mysql_select_db("test_trigger");

/**
 *********************************
 *  这是设计的数据库字段、
 *
 *********************************
 */
$configTable=array(
	"card"=>array(
		"ID"         =>"int(16) unsigned auto_increment PRIMARY KEY",
		"cardNumber" =>"varchar(256)",
		"prefix"     =>"varchar(256)",
		"starNum"    =>"int(16) unsigned",
		"endNum"     =>"int(16) unsigned",
		"money"      =>"int(16) unsigned",
		"ivalidTime" =>"DATETIME",
		"sold"       =>"int(2) unsigned",
		"recharge"   =>"int(2) unsigned",
		"solder"     =>"varchar(256)",
		"soldTime"   =>"DATETIME",
		"operator"   =>"varchar(256)",
		"actviation" =>"varchar(256)",
		"remark"     =>"varchar(1024)",
		"cardAddTime"=>"DATETIME"
	),
	"cardlog"=>array(
		"ID"       =>"int(16) unsigned auto_increment PRIMARY KEY",
		"type"     =>"int(2) unsigned",
		"addTime"  =>"datetime",
		"operator" =>"varchar(256)",
		"content"  =>"varchar(1024)"
	),
	"config"=>array(
		"ID"             =>"int(16) unsigned auto_increment PRIMARY KEY",
		"site"           =>"varchar(256)",
		"copyright"      =>"varchar(1024)",
		"speedStatus"    =>"varchar(16)",
		"macStatus"      =>"varchar(16)",
		"onlinenum"      =>"int(4)",
		"picTopLeft"     =>"varchar(256)",
		"picTopRight"    =>"varchar(256)",
		"picBottomLeft"  =>"varchar(256)",
		"picBottomRight" =>"varchar(256)",
	),
	"credit"=>array(
		"ID"           =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"       =>"int(16) unsigned not null",
		"money"        =>"float unsigned",
		"type"         =>"varchar(256)",
		"operator"     =>"varchar(256)",
		"adddatetime"  =>"datetime"
	),	
	"loginlog"=>array(
		"ID"             =>"int(16) unsigned auto_increment PRIMARY KEY",
		"name"           =>"varchar(256)",
		"logindatetime"  =>"datetime null default '0000-00-00 00:00:00'",
		"logoutdatetime" =>"datetime null default '0000-00-00 00:00:00'",
		"loginip"        =>"varchar(16)",
		"content"        =>"varchar(1024)"
	),	
	"manager"=>array(
		"ID"                 =>"int(16) unsigned auto_increment PRIMARY KEY",
		"manager_account"    =>"varchar(256)",
		"manager_passwd"     =>"varchar(256)",
		"manager_name"       =>"varchar(256)",
		"manager_phone"      =>"varchar(256)",
		"manager_mobile"     =>"varchar(256)",
		"manager_permision"  =>"text",
		"manager_groupID"    =>"int(16)",
		"manager_project"    =>"text"
	),
	"managergroup"=>array(
		"ID"             =>"int(16) unsigned auto_increment PRIMARY KEY",
		"group_name"     =>"varchar(256)",
		"group_permision"=>"text",
		"group_project"  =>"text"
	),
	"managerpermision"=>array(
		"ID"                 =>"int(16) unsigned auto_increment PRIMARY KEY",
		"permision_name"     =>"varchar(256)",
		"permision_param"    =>"varchar(156)",
		"permision_parentID" =>"int(16) unsigned",
		"permision_rank"     =>"int(16) unsigned"
	),
	"orderinfo"=>array(
		"ID"         =>"int(16) unsigned auto_increment PRIMARY KEY",
		"productID"  =>"int(16) unsigned",
		"userID"     =>"int(16) unsigned",
		"status"     =>"int(2) unsigned",
		"adddatetime"=>"datetime",
		"operator"   =>"varchar(256)",
		"remark"     =>"varchar(1024)"
	),	
	"orderlog"=>array(
		"ID"         =>"int(16) unsigned auto_increment PRIMARY KEY",
		"orderID"    =>"int(16) unsigned",
		"userID"     =>"int(16) unsigned",
		"type"       =>"varchar(256)",
		"adddatetime"=>"datetime",
		"operator"   =>"varchar(256)",
		"content"    =>"varchar(1024)"
	),	
	"orderrefund"=>array(
		"ID"         =>"int(16) unsigned auto_increment PRIMARY KEY",
		"orderID"    =>"int(16) unsigned",
		"userID"     =>"int(16) unsigned",
		"money"      =>"float unsigned",
		"facmoney"   =>"float unsigned",
		"type"       =>"varchar(256)",
		"operator"   =>"varchar(256)",
		"remark"     =>"varchar(1024)",
		"adddatetime"=>"datetime"
	),	
	"product"=>array(
		"ID"             =>"int(16) unsigned auto_increment PRIMARY KEY",
		"name"           =>"varchar(256)",
		"type"           =>"varchar(256)",
		"period"         =>"int(16)",
		"price"          =>"float",
		"unitprice"      =>"float",
		"capping"        =>"float",
		"creditline"     =>"varchar(256)",
		"upbandwidth"    =>"int(16)",
		"downbandwidth"  =>"int(16)",
		"description"    =>"varchar(256)",
		"adddatetime"    =>"datetime"
	),
	"productandproject"=>array(
		"productID"  =>"int(16)",
		"projectID"  =>"int(16)"
	),	
	"project"=>array(
		"ID"          =>"int(16) unsigned auto_increment PRIMARY KEY",
		"name"        =>"varchar(256) not null",
		"beginip"     =>"varchar(32) not null",
		"endip"       =>"varchar(32) not null",
		"device"      =>"varchar(256)",
		"description" =>"varchar(1024)",
		"mtu"         =>"varchar(256) not null"
	),
	"radcheck"=>array(
		"ID"        =>"int(16) unsigned auto_increment PRIMARY KEY",
		"UserName"  =>"varchar(256) not null",
		"Attribute" =>"varchar(64) not null",
		"op"        =>"varchar(8) not null",
		"Value"     =>"varchar(256)"
	),	
	"radreply"=>array(
		"ID"        =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"    =>"int(16) unsigned",
		"UserName"  =>"varchar(256) not null",
		"Attribute" =>"varchar(64) not null",
		"op"        =>"varchar(8) not null",
		"Value"     =>"varchar(256)"
	),	
	"repair"=>array(
		"ID"           =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"       =>"int(16) unsigned",
		"type"		   =>"int(4) unsigned",
		"UserName"     =>"varchar(256)",
		"reason"       =>"varchar(10240)",
		"reply"        =>"varchar(10240)",
		"operator"     =>"varchar(256)",
		"status"       =>"int(2)",
		"startdatetime"=>"datetime",
		"enddatetime"  =>"datetime"
	),	
	"repairdisposal"=>array(
		"ID"           =>"int(16) unsigned auto_increment PRIMARY KEY",
		"repairID"     =>"int(16) unsigned not null",
		"sender"       =>"varchar(256)",
		"recevier"     =>"varchar(256)",
		"reason"       =>"varchar(10240)",
		"status"       =>"int(2)",
		"startdatetime"=>"datetime",
		"enddatetime"  =>"datetime",
		"type"		   =>"int(4) unsigned",
		"days"		   =>"int(4) unsigned"
	),	
	"runinfo"=>array(
		"ID"          =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"      =>"int(16) unsigned not null",
		"orderID"     =>"int(16) unsigned not null",
		"price"       =>"float unsigned",
		"status"      =>"int(2)",
		"adddatetime" =>"datetime"
	),	
	"speedrule"=>array(
		"ID"        =>"int(16) unsigned auto_increment PRIMARY KEY",
		"scrip"     =>"varchar(256)",
		"dsrip"     =>"varchar(256)",
		"projectID" =>"int(16) not null",
		"srcport"   =>"int(16)",
		"dsccport"  =>"int(16)",
		"upload"    =>"int(16)",
		"download"  =>"int(16)"
	),	
	"ticket"=>array(
		"ID"    =>"int(16) unsigned auto_increment PRIMARY KEY",
		"type"  =>"varchar(256)",
		"name"  =>"varchar(256)",
		"tel"   =>"varchar(256)",
		"mark"  =>"varchar(256)"
	),	
	"userattribute"=>array(
		"ID"        =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"    =>"int(16) not null",
		"userName"  =>"varchar(256) not null",
		"orderID"   =>"int(16) not null",
		"status"    =>"int(2) not null",
		"macbind"   =>"int(2) null default 0",
		"speedrule" =>"int(2) null default 0",
		"closing"   =>"int(2) null default 0",
		"onlinenum" =>"int(4) null default 0",
		"nasbind"   =>"int(2) null default 0",
		"stop"      =>"int(2) null default 0",
		"pause"     =>"int(4) null default 0"
	),	
	"userbill"=>array(
		"ID"          =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"      =>"int(16) not null",
		"type"        =>"varchar(256)",
		"money"       =>"float unsigned",
		"operator"    =>"varchar(256)",
		"remark"      =>"varchar(1024)",
		"adddatetime" =>"datetime"
	),	
	"userinfo"=>array(
		"ID"            =>"int(16) unsigned auto_increment PRIMARY KEY",
		"UserName"      =>"varchar(256)",
		"account"       =>"varchar(256)",
		"password"      =>"varchar(256)",
		"projectID"     =>"int(16)",
		"name"          =>"varchar(256)",
		"cardid"        =>"varchar(256)",
		"workphone"     =>"varchar(256)",
		"homephone"     =>"varchar(256)",
		"mobile"        =>"varchar(256)",
		"email"         =>"varchar(256)",
		"address"       =>"varchar(256)",
		"money"         =>"int(16)",
		"adddatetime"   =>"datetime",
		"closedatetime" =>"datetime",
		"MAC"           =>"varchar(256)",
		"NAS_IP"        =>"varchar(256)"
	),	
	"userlog"=>array(
		"ID"          =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"      =>"int(16) not null",
		"type"        =>"varchar(256)",
		"operator"    =>"varchar(256)",
		"content"     =>"varchar(1024)",
		"adddatetime" =>"datetime"
	),	
	"userrun"=>array(
		"ID"              =>"int(16) unsigned auto_increment PRIMARY KEY",
		"userID"          =>"int(16) not null",
		"orderID"         =>"int(16) not null",
		"begindatetime"   =>"datetime",
		"enddatetime"     =>"datetime",
		"orderenddatetime"=>"datetime",
		"stopdatetime"    =>"datetime",
		"restoredatetime" =>"datetime",
		"stats"           =>"varchar(256)",
		"status"          =>"int(16)"
	)					
);


//**********************判断结构图在是否存在表
if(is_array($configTable)){
	foreach($configTable as $baseTableKey=>$configValue){
		//调用函数 $baseTablekey->baseTable(数据库现有的表)，$configValue->$configTable(用户配置的表单);
		echo  checkTable($baseTableKey,$configValue);
	}
}


function buildSQL ($tableName, $struct, $temporary = false) {
	$buffer = array();
	foreach ($struct as $fieldName => $definition) {
		$buffer[$fieldName] = "{$fieldName} {$definition}";
	}
	$tempSQL = $temporary ? ' TEMPORARY' : '';
	return "CREATE{$tempSQL} TABLE if not exists {$tableName} (".implode(', ', $buffer).')';
}


/**
* 构造表结构
*
* @static
* @access public
* @param string $sql SQL语句
* @return array 表结构
*/ 
function buildStruct ($sql) {
	$struct = array();
	foreach(explode(',', preg_replace('~^[^(]+\((.+)\)$~s', "$1", $sql)) as $definition) {
		$definition = trim($definition);
		preg_match('~\[([^\]]+)\]\s+(.+)~', $definition, $matchClips);
		$struct[$matchClips[1]] = $matchClips[2];
	}
	return $struct;
}

/**
 *===========================================
 * 函数名:    checkTable
 * 参数：     $baseTable $configTable 
 * 功能描述:  通过获得表，比对表结构，当不同时修改数据库中的表类型
 * 返回值：   成功->true, 失败 false
 * 作者:     
 * 修改记录:
 *===========================================
 */  
function checkTable($baseTable,$configTable){
	
	//
	$create_sql= buildSQL($baseTable,$configTable);
	mysql_query($create_sql);

	
	//开始显示当前数据库中表的字段属性，进行比较表的字段
	$describe_sql     ="show columns from ".$baseTable."";
	$describe_result  =mysql_query($describe_sql);
	while($row=mysql_fetch_array($describe_result)){//查询表中的所有字段名，写入$baseFieldArr.
		$baseFieldArr[]=$row["Field"];
	}
	if(is_array($configTable)){
		foreach($configTable as $configKey=>$configValue){//这里是循环出用户配置的列表
			if(!in_array($configKey,$baseFieldArr)){//判断用户设置的字段是否存在于现有数据库的字段
					$sql="alter table ".$baseTable." add ".$configKey."  ".$configValue."";
					mysql_query($sql);
			}	
		}//end if foreach
	}//end if $configTable
	
}//end function





?>