<?php
/**
* 数据库操作
*/
//!defined('IN_MUDDER') && exit('Access Denied');
class Db_class {
 var $query_num = 0;
 var $link;
 
 function Db_class($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0) {
  	$this->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
 }
 /**
  * 连接数据库
  *
  * @param string $dbhost 数据库服务器地址
  * @param string $dbuser 数据库用户名
  * @param string $dbpw 数据库密码
  * @param string $dbname 数据库名
  * @param integer $pconnect 是否持久链接 [0=否] [1=是]
  */
 function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0) {
	global $dbcharset;
	$dbcharset="utf8";
	$func = $pconnect= 0 ? "mysql_connect" : "mysql_pconnect";
	if(!$this->link = @$func($dbhost, $dbuser, $dbpw)) {
	
		$this->halt("Can not connect to MySQL server");
		
	}
  	$pconnect == 0 ? @mysql_connect($dbhost, $dbuser, $dbpw) : @mysql_pconnect($dbhost, $dbuser, $dbpw);
	
    mysql_errno() != 0 && $this->halt("Connect($pconnect) to MySQL ($dbhost,$dbuser) failed");
	
    if($this->server_info() > '4.1' && $dbcharset)
    	mysql_query("SET NAMES '" . $dbcharset . "'", $this->link);
		
    if($this->server_info() > '5.0') 
   		mysql_query("SET sql_mode=''", $this->link);
		
    if($dbname) {
   		if (!@mysql_select_db($dbname, $this->link)) $this->halt('Cannot use database '.$dbname);
  	}
 }
 /**
  * 选择一个数据库
  *
  * @param string $dbname 数据库名
  */
 function select_db($dbname) {
 
  $this->dbname = $dbname;
  
  if (!@mysql_select_db($dbname, $this->link)) 
   $this->halt('Cannot use database '.$dbname);
   
 }
 /**
  * 查询数据库版本信息
  *
  * @return string
  */
 function server_info() {
  return mysql_get_server_info();
 }
 
 function version() {
 
  return mysql_get_server_info();
  
 }
 
 
 /**
  * 发送一条 MySQL 查询
  *
  * @param string $SQL SQL语法 
  * @param string $method 查询方式 [空=自动获取并缓存结果集] [unbuffer=并不获取和缓存结果的行]
  * @return resource 资源标识符
  */
  
function query($SQL, $method = '') { 

 	if($method == 'unbuffer' && function_exists('mysql_unbuffered_query')){ 
	
 		$query = mysql_unbuffered_query($SQL);//向 MySQL 发送一条 SQL 查询，并不获取和缓存结果的行   , $this->link 
	
 	}else{
	
		$query = mysql_query($SQL);//, $this->link 
	}
 	if (!$query && $method != 'SILENT') 
 		$this->halt('MySQL Query Error: ' . $SQL);
 		$this->query_num++;
		
		
		return $query;

}
 
/**
* 发送一条用于更新，删除的 MySQL 查询
*
* @param string $SQL
* @return resource
*/
function update($SQL) {
	return $this->query($SQL, 'unbuffer');
}
 
/**
* 发送一条SQL查询，并要求返回一个字段值
*
* @param string $SQL
* @param int $result_type
* @return string
*/
function get_value($SQL, $result_type = MYSQL_NUM) {
	$query = $this->query($SQL,'unbuffer');
	@$rs =& mysql_fetch_array($query, MYSQL_NUM);
	return $rs[0];
}
    /**
     * 发送一条SQL查询，并返回一组数据集
     *
     * @param string $SQL
     * @return array
     */
 function get_one($SQL) {
	  $query = $this->query($SQL,'unbuffer');
	  @$rs =& mysql_fetch_array($query, MYSQL_ASSOC);
	 // if($rs) return $rs;
	 // else return true;
	  return $rs; //以前的  现在添加事务需要返回值 如果当只为空时返回的就是false所以 当只为空时返回以为 true 
	
 }
 
 /**
  * 发送一条SQL查询，并返回全部数据集
  *
  * @param string $SQL
  * @param int $result_type
  * @return array
  */
    function get_all($SQL, $result_type = MYSQL_ASSOC) {
	
        $query = $this->query($SQL);
        while($row = mysql_fetch_array($query, $result_type)) $result[] = $row;
        return @$result;
		
    }
    /**
     * 从结果集中取得一行作为关联数组，或数字数组，或二者兼有
     *
     * @param resource $query
     * @param int $result_type
     * @return array
     */
    function fetch_array($query, $result_type = MYSQL_ASSOC) {
        return mysql_fetch_array($query, $result_type);
    }
    /**
     * 返回上一次执行SQL后，被影响修改的条(行)数
     *
     * @return int
     */
 function affected_rows() {
 	return mysql_affected_rows($this->link);
 }
 /**
  * 从结果集中取得一行作为枚举数组
  *
  * @param resource $query
  * @return array
  */
 function fetch_row($query) {
 	return mysql_fetch_row($query);
 }
 /**
  * 取得结果集中行的数目
  *
  * @param resource $query
  * @return int
  */
 function num_rows($query) {
  return mysql_num_rows($query);
 }
 /**
  * 取得结果集中字段的数目
  *
  * @param resource $query
  * @return int
  */
 function num_fields($query) {
 	return mysql_num_fields($query);
 }
 
 /**
  * 取得结果数据
  *
  * @param resource $query
  * @param int $row 字段的偏移量或者字段名
  * @return mixed
  */
 function result($query, $row) {
	$query = mysql_result($query, $row);
	return $query;
 }
 /**
  *  释放结果内存
  *
  * @param resource $query
  * @return bool 
  */
 function free_result($query) {
 	return mysql_free_result($query);
 }
 /**
  * 取得上一步 INSERT 操作产生的 ID 
  *
  * @return int
  */
 function insert_id() {
 	return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
 }
 /**
  * 关闭 MySQL 连
  *
  * @return bool
  */
 function close() {
  return mysql_close($this->link);
 }
 
	/**
	* 返回上一个 MySQL 操作产生的文本错误信息
	*
	* @return string
	*/
	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}
    /**
     * 返回上一个 MySQL 操作中的错误信息的数字编码 
     *
     * @return integer
     */
    function errno() {
        return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
    }
	
	
    /**
     * 查询并返回数据集（只支持单一数据表）
     * 
     * @param string $fields 多字段","分隔
     * @param string $table 数据表名
     * @param array $where 查询条件
     * @return resource
     */
    function select_query($fields, $table, $where) {
        if(!$fields) return;
        if(!$table) return;
        $where = $where ? "WHERE $where" : '';
        return $this->query("SELECT $fields FROM $table $where");
    }
	
	function select_one($fields, $table, $where) {
		if(!$fields) return;
		if(!$table) return;
		$where = $where ? "WHERE $where" : '';
		return $this->get_one("SELECT $fields FROM $table $where");
		//Nancy 添加 释放资源 2012-02-23
		close();
	}
	
    function select_all($fields, $table, $where,$pagesize="") {
        if(!$fields) return;
        if(!$table) return;
        $where = $where ? "WHERE $where" : '';
		$this->select = $fields;
		$this->where = $where;//这是把条件放入where 条件里
		$sql="SELECT $fields FROM $table $where";
		if(!empty($pagesize))//把SQL语句转换了
			$sql=$this->getpage($sql,$pagesize);
		return $this->get_all($sql);	
		//Nancy 添加 释放资源 2012-02-23
		close();
    }
    function select_value($field, $table, $where) {
        if(!$field) return;
        if(!$table) return;
        $where = $where ? "WHERE $where" : '';
        return $this->get_value("SELECT $field FROM $table $where");
    }
    // 查询返回数量
    function select_count($table, $where) {
        return $this->select_value("COUNT(*)", $table, $where);
    }
    // 删除某条记录
    function delete_new($table, $where) {
        if(!$table) return;
        $where = $where ? "WHERE $where" : '';
        return $this->query("DELETE FROM $table $where");        
    }
    /**
     * 插入/加入表数据
     * 
     * @param string $table 数据表名
     * @param array $uplist 数据数组,数组名对应字段名
     * @return resource
     */
     function insert_new($table, $inlist) {
        if(!$table) return;
        if(!is_array($inlist) || count($inlist) == 0) return;
        foreach($inlist as $key => $val) {
            $set[] = "$key='".$val."'";
        }
        $SQL = "INSERT $table SET ".implode(", ", $set)." $where";
        return $this->query($SQL);
     }
    /**
     * 更新表数据
     * 
     * @param string $table 数据表名
     * @param string $where 更新条件
     * @param array $uplist 更新的数据数组,数组名对应字段名
     * @return resource
     */
    function update_new($table,$where,$uplist) {
        if(!$table) return;
        if(!is_array($uplist) || count($uplist) == 0) return;
        $where = $where ? "WHERE $where" : '';
        foreach($uplist as $key => $val) {
            $set[] = "$key='". $val ."'";
        }
        $SQL = "UPDATE $table SET ".implode(", ", $set)." $where";

        return $this->update($SQL);
    }
	
    /**
     * 查询数据分页
     * 
     * @param string $table 数据表名
     * @param string $where 更新条件
     * @param array $uplist 更新的数据数组,数组名对应字段名
     * @return resource
     */
	 
	var $page,$prepage,$nextpage,$pages,$sums;  //out param
	var $queryString;

	function getpage($sql,$pagesize){	  	
	  $this->page     = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 0;//得到当前页数
	 
      $pagesize       = $pagesize;//设置分页数
	  $query          = $this->query($sql);
	  $num_rows       = $this->num_rows($query);
	  $this->sums     = $num_rows;//总记录数
      $this->pages    = ceil( ($this->sums-0.5) / $pagesize )-1;
      $this->prepage  = ($this->page>0)?$page-1:0;
      $this->extpage  = ($this->page<$this->pages)?$this->page+1:$this->pages;  
      $startpos       = $this->page*$pagesize;//得到起始页面
	  if($startpos<=0) $startpos =0;       
   	  $sql .=" limit $startpos,$pagesize ";	
	  //echo $sql;
	  return $sql; 		
	}
	
	function page($queryString=""){
			$shownum   =10/2;//设置显示页数的个数
			$startpage =($this->page>=$shownum)?$this->page-$shownum:0;//这里是设置下面循环的起始页
			$endpage   =($this->page+$shownum<=$this->pages)?$this->page+$shownum:$this->pages;
			
			echo _("共")."&nbsp;".$this->sums."&nbsp;"._("记录")."&nbsp;";
			echo _("当前").($this->page+1)."/".($this->pages+1)._("页")."&nbsp;"; 
			
			//if($this->page>0){
			//	echo "<span class=textpagestyle><a href=$PHP_SELF?page=0&$queryString>首页</a></span>";
			//}
			//if($startpage>0){
			//	echo "...<b><a href=$PHP_SELF?page=".($this->page-$shownum*2)."&$queryString>«</a></b>";	
			//}
			echo "<a href=".$_SERVER['PHP_SELF']."?page=0&".$queryString.">"._("首页")."</a>";	
			for($i=$startpage;$i<=$endpage;$i++){
				if($i==$this->page){
					echo "&nbsp;<b>[ ".($i+1)." ]</b>&nbsp;";
				}else{
					echo " <a href=".$_SERVER['PHP_SELF']."?page=".$i."&$queryString>[ ".($i+1)." ]</a> ";
				}
			}
			echo "<a href=".$_SERVER['PHP_SELF']."?page=".$this->pages."&$queryString>"._("尾页")."</a>";
			//if($endpage<$this->pages){
			//	echo "<b><a href=$PHP_SELF?page=".($this->page+$shownum*2)."&$queryString>»</a></b> ... ";
			//}
			//if($this->page<$this->pages){
			//	echo "<span class=textpagestyle><a href=$PHP_SELF?page=".$this->pages."&$queryString>尾页</a></span>";
			//}
	}
	
	
 function halt($msg = '') {//报错函数
      global $charset;
	  $message = "<html>\n<head>\n";
	  $message .= "<meta content=\"text/html; charset=$charset\" http-equiv=\"Content-Type\">\n";
	  $message .= "<style type=\"text/css\">\n";
	  $message .=  "body,p,pre {\n";
	  $message .=  "font:12px Verdana;\n";
	  $message .=  "}\n";
	  $message .=  "</style>\n";
	  $message .= "</head>\n";
	  $message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";
	  $message .= "<p>数据库出错:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
	  $message .= "<b>Mysql error description</b>: ".htmlspecialchars($this->error())."\n<br />";
	  $message .= "<b>Mysql error number</b>: ".$this->errno()."\n<br />";
	  $message .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br />";
	  $message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br />";
	  $message .= "</body>\n</html>";
	  echo $message;
	  exit;
	 }
}



//$all=$db->select_all("*","articleclass","0=0",5);
// while(list($key,$value)=each($all)){
// 	echo "<hr>".$value["ParentID"]."<br>----".$value["ClassName"];
// }
// foreach($all as $key=>$value){
// 	echo "<br>".$value["ClassName"];
// }
// $db->page();
// $v=$db->select_one("*","articleclass","ClassID=3");
// echo "<hr>".$v["ClassName"];
// $c=$db->select_count("articleclass","");
// echo "sum:".$c;
// 
//$iv=array(
//	"id"=>1,
//	"year"=>date("Y-m-j",time()),
//	"a"=>"this is good idear!"
//);
////$insert=$db->insert_new("t2",$iv);
//
//$update=array("a"=>"this is not idear! Are you think?");
//$db->update_new("t2","",$update);

?>