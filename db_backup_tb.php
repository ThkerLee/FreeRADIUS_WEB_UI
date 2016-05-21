#!/bin/php
<?php
include("inc/conn.php");
$dir = "/usr/local/usr-gui/tb_backup";
if(!is_dir($dir)){
	mkdir($dir, 0700);
}
$rs = $db->select_one("*","tbConfig","id=1");
$status=$rs['status'];
$backupOptions=explode(",",$rs['backupOption']);//数据备份选项用
$interval=$rs['interval'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("数据同步")?></title>
<link href="style/bule/bule.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.4.js"></script>
<script type="text/javascript" src="js/ftpbackup.js"></script>
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
<body style="overflow-x:hidden;overflow-y:auto">
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
									<td width="89%" class="f-bulue1"><? echo _("系统同步")?></td>
									<td width="11%" align="right"></td>
								</tr>
							</table>
		<?php

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
		
		if($_POST['act']){
                                $backupDir = $dir;
				$tbHost = $_POST["tbHost"];
				$tbUser = $_POST["tbUser"];
				$tbPwd = $_POST["tbPwd"];
				$interval=$_POST["interval"];
				$backupOption = $_POST["backupOption"];
			
                                $status = $_POST["status"];
                        $sql=array(
				"backupOption" => implode(",",$backupOption),
				"`status`" => $status,
				"backupDir" => $backupDir,
				"tbHost" => $tbHost,
				"tbUser" => $tbUser,
				"tbPwd" => $tbPwd,
				"`interval`" => $interval
					);
					
					if(!$rs){
						$db->insert_new("tbConfig",$sql);
					}else{
						
						$db->update_new("tbConfig","id=1",$sql); 
						}
                        
				if(  $tbHost == "" || $tbUser == "" || $tbPwd == "" || $interval == ""){
					$msgs[] = "<font style='color:red'>表单数据不完整</font>";
				} else {
                                  //  echo "123"."<br/>";
				   //写入计划任务
                                    include '/usr/local/usr-gui/aaacron.php';
				
                                   $msgs[] = _("设置成功");
                            }
	
			show_msg($msgs);
		} else {
			$msgs[]=_("服务器备份目录为tb_backup");
			#$msgs[]=_("对于较大的数据表，强烈建议使用分卷备份");
			#$msgs[]=_("只有选择备份到服务器，才能使用分卷备份功能");
			show_msg($msgs);
		}
?>
<table width="100%">
<tr>
        <td>
                <form name="form1" method="post" action="db_backup_tb.php" onsubmit="return checkForm();">
<table width="100%" border="0" align="center" cellpadding='5' cellspacing='0'>
        <tr class='header'>
                <td colspan="3"><? echo _("数据备份")?></td>
        </tr>
        <tr>
                <td><? echo _("服务状态")?></td>
                <td width="280">
                        <input type="radio" name="status" value="enable" <? if($status && $status == "enable") echo 'checked';?> />启用
                        <input type="radio" name="status" value="disable" <? if(!$status || $status== "disable") echo 'checked';?> />禁用
                </td>
                <td>&nbsp;</td>
        </tr>
        <tr>
                <td><? echo _("备份间隔周期")?></td>
                <td>

                        <input type="text" name="interval" id="interval" value="<?=$interval?>" />
                </td>
                <td>单位:小时&nbsp;<span id="intervalMsg"></span></td>
        </tr>

        <tr>
                <td><? echo _("数据备份选项")?></td>
                <td>
                        <?php
                        if($backupOptions && is_array($backupOptions)){
                                $list = "";
                                foreach($backupOptions as $v){
                                        $list = $list."$v,";
                                }

                        ?>
                        <input type="checkbox" name="backupOption[]" value="cardlog" <? $pos=strpos($list,'cardlog');if(is_int($pos) && $pos >= 0) echo 'checked';?> />不备份卡片日志&nbsp;
                        <input type="checkbox" name="backupOption[]" value="orderlog" <? $pos=strpos($list,'orderlog');if(is_int($pos) && $pos >= 0) echo 'checked';?> />不备份订单日志&nbsp;<br />
                        <input type="checkbox" name="backupOption[]" value="userlog" <? $pos=strpos($list,'userlog');if(is_int($pos) && $pos >= 0) echo 'checked';?> />不备份用户日志&nbsp;
                        <input type="checkbox" name="backupOption[]" value="loginlog" <? $pos=strpos($list,'loginlog');if(is_int($pos) && $pos >= 0) echo 'checked';?> />不备份登录记录&nbsp;<br />
                        <input type="checkbox" name="backupOption[]" value="systemlog" <? $pos=strpos($list,'systemlog');if(is_int($pos) && $pos >= 0) echo 'checked';?> />不备份系统日志&nbsp;
                        <input type="checkbox" name="backupOption[]" value="bak_acct" <? $pos=strpos($list,'bak_acct');if(is_int($pos) && $pos >= 0) echo 'checked';?> />不备份上网日志
                        <?php
                        }else{
                        ?>
                        <input type="checkbox" name="backupOption[]" value="cardlog" checked />不备份卡片日志&nbsp;
                        <input type="checkbox" name="backupOption[]" value="orderlog" checked />不备份订单日志&nbsp;<br />
                        <input type="checkbox" name="backupOption[]" value="userlog" />不备份用户日志&nbsp;
                        <input type="checkbox" name="backupOption[]" value="loginlog" checked />不备份登录记录&nbsp;<br />
                        <input type="checkbox" name="backupOption[]" value="systemlog" checked />不备份系统日志&nbsp;
                        <input type="checkbox" name="backupOption[]" value="bak_acct" checked />不备份上网日志
                        <?php
                        }
                        ?>
                </td>
                <td>&nbsp;</td>
        </tr>
        <tr>
                <td><? echo _("主机名：")?></td>
                <td>
                        <?
                        if(!$tbHost){
                                $tbHost = '';
                        }
                        ?>
                        <input type="text" name="tbHost" id="ftpHost" value="<?=$rs['tbHost'] ?>" />
                </td>
                <td>需要同步的主机服务器IP或域名&nbsp;<span id="ftpHostMsg"></span></td>
        </tr>
        <tr style="display:none;">
                <td><? echo _("用户名：")?></td>
                <td>
                        <?
                        if(!$tbUser){
                                $tbUser = '';
                        }
                        ?>
                        <input type="text" name="tbUser" id="ftpUser" value="<?=$mysqluser ?>" />
                </td>
                <td><span id="ftpUserMsg"></span></td>
        </tr>
        <tr style="display:none;">
                <td><? echo _("密码：")?></td>
                <td>
                        <?
                        if(!$tbPwd){
                                $tbPwd = '';
                        }
                        ?>
                        <input type="text" name="tbPwd" id="ftpPwd" value="<?=$mysqlpwd?>" />
                </td>
                <td><span id="ftpPwdMsg"></span></td>
        </tr>
        <tr>
                <td colspan="3">
                        <input type="submit" name="act" id="act" value="同步" />
                </td>
        </tr>
</table>
          </form>
        </td>
        <td width="300">
        </td>
        </tr>
</table>
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
        备份恢复-> <strong>数据同步</strong>
      </p>
      <ul>
          <li>通过WEB页面对数据库定期做手动备份，并传送到另一台服务器。</li>
          <li>可选择多久备份一次和不需要备份的数据。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>