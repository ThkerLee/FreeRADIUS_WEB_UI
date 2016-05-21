
#!/usr/local/bin/php
<?php

require("guiconfig.inc");

$pgtitle = array(gettext("Services"), gettext("UPS"));
exec("rm -f  /usr/local/www/backup");
if($_GET){
	if($_GET['action'] == "del"){
		exec("rm -rf /mnt/mysql/backup/".$_GET['file']);
		Header("Location: services_mysql.php");
		exit;
	}
	if($_GET['action'] == "download"){
		exec("/bin/ln -s /mnt/mysql/backup backup");
		Header("Location: /backup/".$_GET['file']);
		exit;
	}	
	
	
}


$mysql = &$config['radius'];

if ($_POST) {
	unset($input_errors);
	$pconfig = $_POST;

	// Input validation.
	if ($_POST['backup']) {
		if(file_exists("/mnt/mysql/backup")){
			exec("/usr/local/bin/mysqldump radius > /mnt/mysql/backup/".date("YmdHis").".sql");
		}else{
			exec("mkdir /mnt/mysql/backup");
			exec("/usr/local/bin/mysqldump radius > /mnt/mysql/backup/".date("YmdHis").".sql");
		}
	}

	if (!$input_errors) {
		echo "";
		}

		$savemsg = get_std_save_message($retval);
		
		
	if($_POST['save']){
		$mysql["ip"]       = $_POST["ip"];
		$mysql["database"] = $_POST["database"];
		$mysql["user"]     = $_POST["user"];
		$mysql["password"]     = $_POST["password"];
		write_config();
		
		$str="<?php
			global $mysqlhost, $mysqluser, $mysqlpwd, $mysqldb;
			$mysqlhost= '".$_POST["ip"]."';//  localhost;
			$mysqluser='".$_POST["user"]."';//  login name
			$mysqlpwd='".$_POST["password"]."';//  password
			$mysqldb='".$_POST["database"]."';//name of database
		?>";	
		echo $str;
		echo "OK";
		$fp=fopen("/usr/local/user-gui/inc/db_config.php","w+");
		if($fp){
			fwrite($fp,$str);
		}
		fclose($fp);		

	}
}

?>
<?php include("fbegin.inc");?>

<form action="services_mysql.php" method="post" name="iform" id="iform">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
	    <td class="tabcont">
				<?php if ($input_errors) print_input_errors($input_errors);?>
				<?php if ($savemsg) print_info_box($savemsg);?>
			  <table width="100%" border="0" cellpadding="6" cellspacing="0">
			  				  	<tr>
						<td colspan="2" valign="top" class="listtopic">数据库设置</td>
					</tr>
			  	
					<? //php html_passwordbox("root_password", "root用户密码", "", "MySQL数据root用户的密码.此密码需要高度保密", true);?>
					<? //php html_passwordbox("radius_password", "radius用户密码", "", "MySQL数据radius用户的密码.此密码需要高度保密",  true);?>
					
					
					<tr><td class="data_td"><b>服务端地址</b> </td>
				<td class="data_td"><input type="text" value="<?=$mysql["ip"]?>" name="ip"  id="ip" />
				
				</td></tr>
				
				<tr><td class="data_td"><b>数据库名</b> </td>
				<td class="data_td"><input type="text" value="<?=$mysql["database"]?>" name="database"  id="database"/>
				
				</td></tr>
				<?  //连接数据库用户名  ?>
				<tr><td class="data_td"><b>用户名称</b> </td>
				<td class="data_td"><input type="text" value="<?=$mysql["user"]?>" name="user" id="user" />
				
				</td></tr>
				<?  //连接数据库密码  ?>
				<tr><td class="data_td"><b>用户密码</b> </td>
				<td class="data_td"><input  type="password" value="<?=$mysql["password"]?>"  name="password"  id="password"/>
				
				</td></tr>
				
					
					<tr>
			      <td width="22%" valign="top">&nbsp;</td>
			      <td width="78%">
			        <input name="save" type="submit" class="formbtn" value="保存" onClick="chang(),enable_change(true)">
			      </td>
			    </tr>
			    <tr>
							<td colspan="2" class="listtopic">备份/恢复</td>
					
					</tr>
					<tr>
							<td width="22%" class="listr">恢复备份</td>
							<td width="78%" class="listr"><input name="ulfile" type="file" class="formfld" size="40">&nbsp;&nbsp;&nbsp;
								<input name="restore" type="submit"  value="恢复" >
								
								
								</td>
					</tr>
					<tr>
							<td width="22%" class="listr">备份数据库</td>
							<td width="78%" class="listr"><input name="backup" type="submit"  value="备份" ></td>
					</tr>					
<?php
		if(file_exists("/mnt/mysql/backup")){
			$handle=opendir('/mnt/mysql/backup');
		}else{
			exec("mkdir /mnt/mysql/backup");
			$handle=opendir('/mnt/mysql/backup');
		}


while ($file = readdir($handle)) {
	if($file <> "." and $file <> ".."){
?>
					<tr>
							<td width="22%" class="listr"><?=$file;?></td>
							<td width="78%" class="listr">
								文件大小：<?=filesize("/mnt/mysql/backup/".$file);?>&nbsp;&nbsp;&nbsp;日期：<?=date("Y-m-d H:i:s",filectime("/mnt/mysql/backup/".$file));?>
								&nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=del&file=<?=$file; ?>">删除</a>&nbsp;&nbsp;&nbsp;<a href="?action=download&file=<?=$file; ?>">下载</a></td>
					</tr>		
	
<?	
	
  }
}
closedir($handle); 
?>
					
			  </table>
			</td>
		</tr>
	</table>
</form>

<?php include("fend.inc");?>

<script>

function chang(){
  var localhost =document.getElementById('localhost').value;//服务端地址
 
var database=document.getElementById('database').value;//数据库名

var database_name=document.getElementById('database_name').value;//数据库用户名
 
var database_password=document.getElementById('database_password').value;//数据库用户密码
 
if(localhost==""){
		alert("服务端地址不能为空");
		
	}
else if(database==""){
		alert("数据库名不能为空");
		
	}
else if(database_name==""){
		alert("数据库用户名不能为空");
		
	}
/*else if(database_password==""){
		alert("数据库密码不能为空");
		
	}*/
	else{
		<?
		
		//实例化数据库对像
	$db_config="<?php 
global \$mysqlhost, \$mysqluser, \$mysqlpwd, \$mysqldb;
\$mysqlhost= \"".$_POST["localhost"]."\";//  localhost;
\$mysqluser=\"".$_POST["database_name"]."\";//  login name
\$mysqlpwd=\"".$_POST["database_password"]."\";//  password
\$mysqldb=\"".$_POST["database"]."\";//name of database

 ?>";


		$fp = fopen("../user-gui/inc/db_config.php","w");
		
		fwrite($fp,$db_config);  //打开文件的资源变量   第二个参数就是要写入的内容

		fclose($fp);  //关闭流
		
		
		
		 ?>
	
	
	}
	
}


</script>
