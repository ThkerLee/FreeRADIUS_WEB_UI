#!/bin/php
<?php 
include("inc/conn.php"); 
require_once("evn.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("数据备份")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script src="js/ajax.js" type="text/javascript"></script>
<SCRIPT  src="js/jquery-1.4.js"></SCRIPT>
<!--这是点击帮助的脚本-2014.06.07-->
    <link href="js/jiaoben/css/chinaz.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jiaoben/js/jquery-1.4.4.js"></script>   
    <script type="text/javascript" src="js/jiaoben/js/jquery-ui-1.8.1.custom.min.js"></script> 
    <script type="text/javascript" src="js/jiaoben/js/jquery.easing.1.3.js"></script>        
    <script type="text/javascript" src="js/jiaoben/js/jquery-chinaz.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {  		
        $('#Firefoxicon').click(function() {
          $('#Window1').chinaz({
            WindowTitle:          '<b>备份恢复</b>',
            WindowPositionTop:    'center',
            WindowPositionLeft:   'center',
            WindowWidth:          500,
            WindowHeight:         300,
            WindowAnimation:      'easeOutCubic'
          });
        });		
      });
    </script>
   <!--这是点击帮助的脚本-结束-->
</head>
<body  style="overflow-x:hidden;overflow-y:auto">
<table width="100%" height="500" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" height="43" valign="top" background="images/li_r6_c4.jpg"><img name="li_r4_c4" src="images/li_r4_c4.jpg" width="14" height="43" border="0" id="li_r4_c4" alt="" /></td>
    <td height="43" align="left" valign="top" background="images/li_r4_c5.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="4%" height="35"><img src="images/li1.jpg" width="39" height="22" /></td>
			<td width="93%" height="35" valign="middle"><font color="#FFFFFF" size="2"><? echo _("用户管理")?></font></td>
                        <td width="3%" height="35">
                           <div id="Firefoxicon" class="bz" style="text-align:right; cursor: pointer; color:#FFF; line-height: 35px; ">帮助<img src="/js/jiaoben/images/bz.jpg" width="20" height="20"  title="帮助" style="vertical-align:middle;"/></div>
                       </td> <!------帮助--2014.06.07----->                       
		  </tr>
   		</table>
	</td>
    <td width="14" height="43" valign="top" background="images/li_r6_c14.jpg"><img name="li_r4_c14" src="images/li_r4_c14.jpg" width="14" height="43" border="0" id="li_r4_c14" alt="" /></td>
  </tr>
  
  <tr>
    <td width="14" background="images/li_r6_c4.jpg">&nbsp;</td>
    <td height="500" valign="top">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bd">
  <tr>
    <td align="center">
      <table width="100%" align="center" class="title_bg2 border_t border_l border_r">
        <tr>
          <td width="89%" class="f-bulue1"><? echo _("系统备份")?></td>
          <td width="11%" align="right"></td>
        </tr>
      </table>
<?php

/*--------------界面--------------*/
if(!$_POST['act']){/*----------------------*/
$msgs[]=_("服务器备份目录为backup");
$msgs[]=_("对于较大的数据表，强烈建议使用分卷备份");
$msgs[]=_("只有选择备份到服务器，才能使用分卷备份功能");
show_msg($msgs);


?>
      <table width="100%">
        <tr><td>
	  <form name="form1" method="post" action="db_backup.php" >
        <table width="100%" border="0" align="center" cellpadding='5' cellspacing='0'>
          <tr align="center" class='header'>
            <td colspan="2"><? echo _("数据备份")?></td>
          </tr>
          <tr>
            <td colspan="2"><? echo _("备份方式")?></td>
          </tr>
          <tr>
            <td width="37%"><input type="radio" name="bfzl" id="bfzl" value="quanbubiao">
            <span class="f-bulue1">  <? echo _("备份全部数据")?></td></span>
            <td width="63%">--<? echo _("备份全部数据表中的数据到一个备份文件")?></td>
          </tr>
		  <tr>
            <td width="37%"><input  type="checkbox" name="delbak" id="delbak" value="delbak" >
              <? echo _("清空上网记录")?></td>
            <td width="63%">--<? echo _("用户拨号记录如果过大，则会影响数据恢复(只针对备份全部数据)")?></td>
          </tr> 
          <tr>
            <td><input type="radio" name="bfzl" value="danbiao" >
            <span class="f-bulue1">  <? echo _("备份单张表数据")?></span>
              <select name="tablename">
                <option value=""><? echo _("请选择")?></option>
					<?php
					$d->query("show table status from $mysqldb");
					while($d->nextrecord()){
						echo "<option value='".$d->f('Name')."'>".$d->f('Name')."</option>";
					} 
					?>
              </select></td>
            <td>--<? echo _("备份选中数据表中的数据到单独的备份文件")?></td>
          </tr>
           <tr>
            <td width="37%"><input type="radio" name="bfzl" id="need" value="need" checked="checked">
            <span class="f-bulue1">  <? echo _("备份必要数据")?></td></span>
            <td width="63%">--<? echo _("备份必要数据表中的数据到一个备份文件")?></td>
          </tr>  
          <tr>
            <td colspan="2"><? echo _("使用分卷备份")?></td>
          </tr>
          <tr>
            <td><input type="checkbox" name="fenjuan" value="yes">
              <? echo _("分卷备份最大允许使用")?>
              <input name="filesize" type="text" size="10">
              Kb</td>
            <td>--<? echo _("留空表示无限制")?></td>
          </tr>
          <tr>
            <td colspan="2"><? echo _("选择目标位置")?></td>
          </tr>
          <tr>
            <td colspan="2"><input type="radio" name="weizhi" value="server" checked>
              <? echo _("备份到服务器")?></td>
          </tr>
          <tr class="cells">
            <td colspan='2'><input type="radio" name="weizhi" value="localpc">
              <? echo _("备份到本地")?></td>
          </tr>
		  
		  <tr>
            <td colspan="2" align='left'> 
              <span ><font color="red"><? echo _("提示:")."</font>" . _("用户拨号记录如果过大，则会影响数据恢复，是否需要在备份前清空该记录？ 如需清空 请选择")."【"._("清空上网记录")."】" ?> </span></td>
          </tr> 
          <tr>
            <td colspan="2" align='left'>&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="submit" name="act" value="<? echo _("备份")?>"   > </td>
          </tr>
        </table>
      </form>
	  		  </td>
			  <td width="300"><? echo _("如需下载备份数据库请点击下载")?><br><br><?php lookdir("backup");?>
			  </td>
			  </tr>
	   </table>
      <?php
	  
/*-------------界面结束-------------*/

}else{/*--------------主程序-----------------------------------------*/

	if($_POST['weizhi']=="localpc"&&$_POST['fenjuan']=='yes')
	{
		$msgs[]=_("只有选择备份到服务器，才能使用分卷备份功能");
		show_msg($msgs); pageend();
	}
	if($_POST['fenjuan']=="yes"&&!$_POST['filesize'])
	{
		$msgs[]=_("您选择了分卷备份功能，但未填写分卷文件大小");
		show_msg($msgs); pageend();
	}
	if($_POST['weizhi']=="server"&&!writeable("./backup"))
	{
		$msgs[]=_("备份文件存放目录'./backup'不可写，请修改目录属性");
		show_msg($msgs); pageend();
	}

	/*----------备份全部表-------------*/
	if($_POST['bfzl']=="quanbubiao"){/*----*/

		/*----不分卷*/
		if($_POST['delbak']=="delbak"){
		 $d->query("truncate table `bak_acct`");//清空上网记录表
		}
       //truncate table `bak_acct`

		if(!$_POST['fenjuan']){ /*--------------------------------*/
			if(!$tables=$d->query("show table status from $mysqldb"))
			{
				$msgs[]=_("读数据库结构错误"); show_msg($msgs); pageend();
			}
			$sql="";
			while($d->nextrecord($tables))
			{
				$table=$d->f("Name");
				$sql.=make_header($table);
				$d->query("select * from $table");
				$num_fields=$d->nf();
				while($d->nextrecord())
				{
					$sql.=make_record($table,$num_fields);
				}
			}
			$filename=date("Ymd",time())."_".date("Gis",time()).".sql";
			if($_POST['weizhi']=="localpc") 
			{
				down_file($sql,$filename);
				
			}elseif($_POST['weizhi']=="server"){
				if(write_file($sql,$filename))
				{
					$msgs[]=_("全部数据表数据备份完成,生成备份文件")."'./backup/$filename'";
					$msgs[]="<a href='?'><img valign=bottom src='images/back.gif' border=0 width=50 height=15></a>";
				}else{ 
					$msgs[]=_("备份全部数据表失败");
				}
				show_msg($msgs);
				pageend();
			}
		/*----不要卷结束*/
		}else{/*------------------/*-----------------分卷*-------*/
			if(!$_POST['filesize'])
			{
				$msgs[]="请填写备份文件分卷大小"; show_msg($msgs);pageend();
			}
			if(!$tables=$d->query("show table status from $mysqldb"))
			{
				$msgs[]="读数据库结构错误"; show_msg($msgs); pageend();
			}
			$sql=""; $p=1;
			$filename=date("Ymd",time())."_all";
			while($d->nextrecord($tables))
			{
				$table=$d->f("Name");
				$sql.=make_header($table);
				$d->query("select * from $table");
				$num_fields=$d->nf();
				while($d->nextrecord())
				{
					$sql.=make_record($table,$num_fields);
					if(strlen($sql)>=$_POST['filesize']*1000)
					{
						$filename.=("_v".$p.".sql");
						if(write_file($sql,$filename))
						$msgs[]=_("全部数据表-卷-").$p._("-数据备份完成,生成备份文件")."'./backup/$filename'";
						else $msgs[]=_("备份表-").$_POST['tablename']._("-失败");
						$p++;
						$filename=date("Ymd",time())."_all";
						$sql="";}
					}
				}
			}
			if($sql!=""){$filename.=("_v".$p.".sql");		
			if(write_file($sql,$filename))
			{
				$msgs[]=_("全部数据表-卷-").$p._("-数据备份完成,生成备份文件")."'./backup/$filename'";
			}
			show_msg($msgs);
		}/*--------------------------------------*//*--------备份全部表结束*/
		
	}elseif($_POST['bfzl']=="danbiao"){/*------------*/
		if(!$_POST['tablename'])
		{
			$msgs[]=_("请选择要备份的数据表"); show_msg($msgs); pageend();
		}
		/*/*--------不分卷*/
		if(!$_POST['fenjuan'])
		{
			$sql=make_header($_POST['tablename']);
			$d->query("select * from ".$_POST['tablename']);
			$num_fields=$d->nf();
			while($d->nextrecord())
			{
				$sql.=make_record($_POST['tablename'],$num_fields);
			}
			$filename=date("Ymd",time())."_".$_POST['tablename'].".sql";
			if($_POST['weizhi']=="localpc") 
			{
				down_file($sql,$filename);
			}elseif($_POST['weizhi']=="server"){
				if(write_file($sql,$filename))
				{
					$msgs[]=_("表-").$_POST['tablename']._("-数据备份完成,生成备份文件")."'./backup/$filename'";
				}else{
					 $msgs[]=_("备份表-").$_POST['tablename']._("-失败");
				}
				show_msg($msgs);
				pageend();
			}
	/*----------------不要卷结束*/
		}else{/*-分卷*-------------------------------------*/
			if(!$_POST['filesize'])
			{
				$msgs[]=_("请填写备份文件分卷大小"); show_msg($msgs);pageend();
			}
			$sql=make_header($_POST['tablename']); $p=1; 
			$filename=date("Ymd",time())."_".$_POST['tablename'];
			$d->query("select * from ".$_POST['tablename']);
			$num_fields=$d->nf();
			while ($d->nextrecord()) 
			{	
				$sql.=make_record($_POST['tablename'],$num_fields);
				if(strlen($sql)>=$_POST['filesize']*1000){
					$filename.=("_v".$p.".sql");
					if(write_file($sql,$filename))
					$msgs[]=_("表-").$_POST['tablename']._("-卷-").$p._("-数据备份完成,生成备份文件")."'./backup/$filename'";
					else $msgs[]=_("备份表-").$_POST['tablename']._("-失败");
					$p++;
					$filename=date("Ymd",time())."_".$_POST['tablename'];
					$sql="";
				}
			}
			if($sql!="")
			{
				$filename.=("_v".$p.".sql");		
				if(write_file($sql,$filename)){
					$msgs[]=_("表-").$_POST['tablename']._("-卷-").$p._("-数据备份完成,生成备份文件")."'./backup/$filename'";
				}
			}
			show_msg($msgs);
		}/*----------分卷结束*/
	}/*-/*----------备份单表结束*/
	else if ($_POST['bfzl']=="need"){ 
		 $needTable = array("card","client_notice","config","credit","finance","grade","manager","managergroup","managerpermision","maturity_notice","nas", "orderinfo","orderrefund","product","productandproject","project","project_ros","radcheck","radreply","runinfo","sync_mysql","ticket","userattribute","userbill","userinfo","userrun","userlog"); 
		if(!$_POST['fenjuan']){/*--------------------------------*/
		 	/*if(!$tables=$d->query("show table status from $mysqldb"))
			{
				$msgs[]=_("读数据库结构错误"); show_msg($msgs); pageend();
			}*/   
			$sql="";
			
			foreach ($needTable as $tables){ 
				$table=$tables;
				$sql.=make_header($table);
				$d->query("select * from $table");
				$num_fields=$d->nf();
				while($d->nextrecord())
				{
					$sql.=make_record($table,$num_fields);
				}
			}
			$filename=date("Ymd",time())."_need.sql";
			if($_POST['weizhi']=="localpc") 
			{
				down_file($sql,$filename);
				
			}elseif($_POST['weizhi']=="server"){
				if(write_file($sql,$filename))
				{
					$msgs[]=_("必要数据表数据备份完成,生成备份文件")."'./backup/$filename'";
					$msgs[]="<a href='?'><img valign=bottom src='images/back.gif' border=0 width=50 height=15></a>";
				}else{ 
					$msgs[]=_("备份必要数据表失败");
				}
				show_msg($msgs);
				pageend();
			}
		/*----不要卷结束*/
		}else{/*------------------/*-----------------分卷*-------*/
			if(!$_POST['filesize'])
			{
				$msgs[]="请填写备份文件分卷大小"; show_msg($msgs);pageend();
			}
		
		/*	if(!$tables=$d->query("show table status from $mysqldb"))
			{
				$msgs[]="读数据库结构错误"; show_msg($msgs); pageend();
			}
			*/
			$sql=""; $p=1;
			$filename=date("Ymd",time())."_need";
			foreach ($needTable as $tables){
			{
				$table=$tables;
				$sql.=make_header($table);
				$d->query("select * from $table");
				$num_fields=$d->nf();
				while($d->nextrecord())
				{
					$sql.=make_record($table,$num_fields);
					if(strlen($sql)>=$_POST['filesize']*1000)
					{
						$filename.=("_v".$p.".sql");
						if(write_file($sql,$filename))
						$msgs[]=_("必要数据表-卷-").$p._("-数据备份完成,生成备份文件")."'./backup/$filename'";
						else $msgs[]=_("备份表-").$_POST['tablename']._("-失败");
						$p++;
						$filename=date("Ymd",time())."_all";
						$sql="";}
					}
				}
			}
			if($sql!=""){$filename.=("_v".$p.".sql");		
			if(write_file($sql,$filename))
			{
				$msgs[]=_("必要数据表-卷-").$p._("-数据备份完成,生成备份文件")."'./backup/$filename'";
			}
			show_msg($msgs);
		}
  	}/*--------------------------------------*//*--------备份全部表结束*/ 
	}/*-------------------------必要表备份结束*/ 
}/*-------------主程序结束------------------------------------------*/
 


function write_file($sql,$filename)
{
	$re=true;
	if(!@$fp=fopen("./backup/".$filename,"w+")) {$re=false; echo "failed to open target file";}
	if(!@fwrite($fp,$sql)) {$re=false; echo "failed to write file";}
	if(!@fclose($fp)) {$re=false; echo "failed to close target file";}
	return $re;
}

function down_file($sql,$filename)
{
	write_file($sql,$filename);
	$msgs[]=_("备份文件己生成");
	show_msg($msgs);
	echo "<script>window.location.href='backup/".$filename."'</script>";
	echo "<script>window.location.href='backup.php'</script>";
	echo $sql;
	echo $filename;
	exit;
	
/*	ob_end_clean();
	header("Content-Encoding: none");
	header("Content-Type: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
			
	header("Content-Disposition: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ')."filename=".$filename);
			
	header("Content-Length: ".strlen($sql));
	header("Pragma: no-cache");
			
	header("Expires: 0");
	echo $sql;
	$e=ob_get_contents();
	ob_end_clean();*/
}

function writeable($dir)
{
	
	if(!is_dir($dir)) {
	@mkdir($dir, 0777);
	}
	
	if(is_dir($dir)) 
	{
	
	if($fp = @fopen("$dir/test.test", 'w'))
		{
@fclose($fp);
	@unlink("$dir/test.test");
	$writeable = 1;
} 
	else {
$writeable = 0;
	}
	
}
	
	return $writeable;

}

function make_header($table)
{global $d;
$sql="DROP TABLE IF EXISTS ".$table."\n";
$d->query("show create table ".$table);
$d->nextrecord();
$tmp=preg_replace("/\n/","",$d->f("Create Table"));
$sql.=$tmp."\n";
return $sql;
}

function make_record($table,$num_fields)
{global $d;
$comma="";
$sql .= "INSERT INTO ".$table." VALUES(";
for($i = 0; $i < $num_fields; $i++) 
{$sql .= ($comma."'".mysql_escape_string($d->record[$i])."'"); $comma = ",";}
$sql .= ")\n";
return $sql;
}

function show_msg($msgs)
{
$title=_("提示:");
echo "<table width='95%' border='0'  cellpadding='5' cellspacing='0' align='center'>";
echo "<tr><td>".$title."</td></tr>";
echo "<tr><td><br><ul>";
while (list($k,$v)=each($msgs))
	{
	echo "<li style='line-height:25px;'>".$v."</li>";
	}
echo "</ul></td></tr></table>";
}

function pageend()
{
exit();
}
//-------------------这是对文件夹的操作
function lookdir($d){ 
        echo "<table width=100% border='0' id=\"myTable\"  cellpadding='5' cellspacing='0' class=bd>";
	echo "<tr><td width=70% class=bd_b>&nbsp;<input type='checkbox' name='allID' id='allID' value='allID' onclick='change_all();' >"._("备份数据库名")."</td><td class='bd_b bd_l'>"._("操作")."</td></tr>";
	echo "<form action ='?' method ='post'";
	$handle=opendir($d);
	$i=0;
	while(false !== ($file=readdir($handle))){ 
            if($file!="." && $file!=".."){ 
                         echo"$file";
                 /*echo "<tr><td width=70% class=bd_b>".($i+1) ."<input type='checkbox' name='ID[]' id='ID'
			 value='".$file."' >".$file."</td><td class='bd_b bd_l'><a href='./download.php?file=$file'>"._("下载")."</a>&nbsp;&nbsp;<a href='?action=del&sqlname=".$file."' onClick=\"javascript:return(confirm('". _("确认删除")."'));\">"._("删除")."</a></td></tr>";*/
			 $i++;
                   $f.=$file.",";    
                }   
            }//将文件进行排序
               //$s=substr($f,0, -1);//截取最后一个逗号
              $a=explode(',',$f); //转换成数组
               rsort($a);//排序
               for($i=0; $i<count($a)-1;  $i++){
                echo "<tr><td width=70% class=bd_b>".($i+1) ."<input type='checkbox' name='ID[]' id='ID'
			 value='".$a[$i]."' >".$a[$i]."</td><td class='bd_b bd_l'><a href='./download.php?file=$a[$i]'>"._("下载")."</a>&nbsp;&nbsp;<a href='?action=del&sqlname=".$a[$i]."' onClick=\"javascript:return(confirm('". _("确认删除")."'));\">"._("删除")."</a></td></tr>";     
                        }        
            ?>
	
	 <tr>
	  <td width="70%" class="bd_b" clospan="2">
	  <input type="submit"   name="dellall"  title="<?php echo _("批量删除")?>" value="<?php echo _("批量删除")?>" onClick="javascript:return window.confirm('<?php echo _("确认删除")?>?');" >
	 <!-- <input type="submit"   name="downlall" title="<? echo _("批量下载")?>" value="Down All"  onClick="javascript:return window.confirm('<? echo _("确认下载")?>?');" > -->
	  </td>
	 </tr> 
	 </form>
 </table>  
<?php
}
if($_GET["action"]=="del"){
	@unlink("backup/".$_GET["sqlname"]);
    echo "<script>alert('"._("删除成功!")."');window.location.href='db_backup.php'</script>"; 
}
if($_POST){
 if($_POST["dellall"]){//批量删除
   $dellall = $_POST; 
   if(is_array($dellall)){ 
     foreach($dellall as $del){ 
	  foreach($del as $val){
	    @unlink("backup/".$val);
	  }  
	 }
   } 
 }
 /*elseif($_POST["downlall"]){//批量下载
  $dellall = $_POST; 
   if(is_array($dellall)){ 
     foreach($dellall as $del){ 
	  foreach($del as $val){
	    
	  }  
	 }
   } 
 
 }*/
 
   
    echo "<script>alert('"._("删除成功!")."');window.location.href='db_backup.php'</script>"; 
}
 

 
?>
      <br />
      </td>
  </tr>
</table>
	</td>
    <td width="14" background="images/li_r6_c14.jpg">&nbsp;</td>
  </tr>
  
  <tr>
    <td width="14" height="14"><img name="li_r16_c4" src="images/li_r16_c4.jpg" width="14" height="14" border="0" id="li_r16_c4" alt="" /></td>
    <td width="1327" height="14" background="images/li_r16_c5.jpg"><img name="li_r16_c5" src="images/li_r16_c5.jpg" width="100%" height="14" border="0" id="li_r16_c5" alt="" /></td>
    <td width="14" height="14"><img name="li_r16_c14" src="images/li_r16_c14.jpg" width="14" height="14" border="0" id="li_r16_c14" alt="" /></td>
  </tr>
</table>
<!-----------这里是点击帮助时显示的脚本--2014.06.07----------->
 <div id="Window1" style="display:none;">
      <p>
        备份恢复-> <strong>数据备份</strong>
      </p>
      <ul>
          <li>通过WEB页面对数据库定期做手动备份，并传送到数据库备用机保存已实现数据的容灾恢复。</li>
          <li>备份全部数据： 对整个数据库进行备份，包括数据库内的各项内容，备份的数据会以备份当天的日期命令生成一个备份文件。如果一天之内进行过多次备份，则只会保留最后一次备份的文件。</li>
          <li>备份单张表数据：对指定的表单进行备份，如 userinfo 则表示为用户信息的表单。</li>
          <li>使用分卷备份：分卷备份最大允许使用的最大存储空间，单位为 Kb，留空表示无限制。</li>
          <li>备份到服务器：即在服务器上的存储空间进行备份，也可从本地进行恢复。</li>
          <li>备份到本地： 即将数据备份后下载到本地电脑， 可以将其恢复到本服务器或其他服务器上。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
<script>
 function checkForm(){
   var ch = document.getElementById('delbak').checked;
   var alltable = document.getElementById('bfzl').checked;
   if(alltable==true){
	   if(ch==false){
		   if(window.confirm("用户拨号记录如果过大，则会影响数据恢复，是否需要在备份前清空该记录？\n \n 如需清空 请选择 确定【选择 清空上网记录】 ，不需清空 请选择 取消")==1){
		   return false;
		   } else{
		   return  true;
		   }
	   }
   } 
}
 
function change_all(){
	ide=document.getElementById("allID").checked;
	div=document.getElementById("myTable").getElementsByTagName("input");
	for(i=0;i<div.length;i++){ 
		div[i].checked=ide;
	}  
} 
function show(){
alert("show");
}
</script>
</html>