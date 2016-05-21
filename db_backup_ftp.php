#!/bin/php
<?php
include("inc/conn.php");
$dir = "/usr/local/usr-gui/ftpbackup";
if(!is_dir($dir)){
	mkdir($dir, 0700);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo _("数据备份")?></title>
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
									<td width="89%" class="f-bulue1"><? echo _("系统备份")?></td>
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
			
			$status = $_POST["status"];
                        $host = 'localhost';
			$dbUser = $mysqluser;
			$dbPwd = $mysqlpwd;
			$savedDate = $_POST["savedDate"];
			$backupDir = $dir;
			$backupOption = $_POST["backupOption"];
			$options = "";
			if($backupOption){
					/*foreach($backupOption as $k=>$v){
						$options = $options.'"'.$v.'",';
					}
					$options = substr($options,0,strlen($options)-1);
					*/
					
				$options = implode(",",$backupOption);
			}
				
                        $ftpPort = $_POST["ftpPort"];
			$ftpHost = $_POST["ftpHost"];
			$ftpUser = $_POST["ftpUser"];
			$ftpPwd = $_POST["ftpPwd"];
			$interval=$_POST["interval"];
				
				
				if($savedDate == "" || $ftpPort == "" || $ftpHost == "" || $ftpUser == "" || $ftpPwd == "" || $interval == ""){
					$msgs[] = "<font style='color:red'>表单数据不完整</font>";
				} else {
					$rs = $db->select_one("*","ftpConfig","0=0 limit 0,1");
					if($rs){
						$sql = "update `ftpConfig` set `status`='$status',`savedDate`=$savedDate,`backupOption`='$options',`ftpPort`=$ftpPort,`ftpHost`='$ftpHost',`ftpUser`='$ftpUser',`ftpPwd`='$ftpPwd',`interval`=$interval where id=1";
					}else{
						$sql = "insert into `ftpConfig` values(null,'$status',$savedDate,'$backupDir','$options',$ftpPort,'$ftpHost','$ftpUser','$ftpPwd',$interval)";
					}
                                        
					$db->query($sql);
                                        //写入计划任务
                                    include '/usr/local/usr-gui/aaacron.php';
                                        $msgs[] = _("设置成功");
				}
			
			
			
			show_msg($msgs);
		} else {
			$msgs[]=_("服务器备份目录为ftpbackup");
			#$msgs[]=_("对于较大的数据表，强烈建议使用分卷备份");
			#$msgs[]=_("只有选择备份到服务器，才能使用分卷备份功能");
			show_msg($msgs);
		}
		
		$rs = $db->select_one("*","ftpConfig","0=0 limit 0,1");
		if($rs){
			$status = $rs['status'];
			$interval = $rs['interval'];
			$savedDate = $rs['savedDate'];
			$backupOption = $rs['backupOption'];
			$ftpPort = $rs['ftpPort'];
			$ftpHost = $rs['ftpHost'];
			$ftpUser = $rs['ftpUser'];
			$ftpPwd = $rs['ftpPwd'];
		}
?>
<table width="100%">
        <tr>
                <td>
<form name="form1" method="post" action="db_backup_ftp.php" onsubmit="return checkForm();">
        <table width="100%" border="0" align="center" cellpadding='5' cellspacing='0'>
                <tr class='header'>
                        <td colspan="3"><? echo _("数据备份")?></td>
                </tr>
                <tr>
                        <td><? echo _("服务状态")?></td>
                        <td width="280">
                                <input type="radio" name="status" value="enable" <? if($status && $status == "enable") echo 'checked';?> />启用
                                <input type="radio" name="status" value="disable" <? if(!$status || $status == "disable") echo 'checked';?> />禁用
                        </td>
                        <td>&nbsp;</td>
                </tr>
                <tr>
                        <td><? echo _("备份间隔周期")?></td>
                        <td>
                                <?
                                if(!$interval){
                                        $interval = 5;
                                }
                                ?>
                                <input type="text" name="interval" id="interval" value="<?=$interval?>" />
                        </td>
                        <td>单位:小时&nbsp;<span id="intervalMsg"></span></td>
                </tr>
                <tr>
                        <td><? echo _("本地保存期")?></td>
                        <td>
                                <?
                                if(!$savedDate){
                                        $savedDate = 7;
                                }
                                ?>
                                <input type="text" name="savedDate" id="savedDate" value="<?=$savedDate?>" />
                        </td>
                        <td>天&nbsp;<span id="savedDateMsg"></span></td>
                </tr>
                <tr>
                        <td><? echo _("数据备份选项")?></td>
                        <td>
                                <?php
                                if($backupOption){														
                                ?>
                                <input type="checkbox" name="backupOption[]" value="cardlog" <?if(strpos($backupOption,'cardlog') !== false) echo 'checked';?> />不备份卡片日志&nbsp;
                                <input type="checkbox" name="backupOption[]" value="orderlog" <?if(strpos($backupOption,'orderlog') !== false) echo 'checked';?> />不备份订单日志&nbsp;<br />
                                <input type="checkbox" name="backupOption[]" value="userlog" <?if(strpos($backupOption,'userlog') !== false) echo 'checked';?> />不备份用户日志&nbsp;
                                <input type="checkbox" name="backupOption[]" value="loginlog" <?if(strpos($backupOption,'loginlog') !== false) echo 'checked';?> />不备份登录记录&nbsp;<br />
                                <input type="checkbox" name="backupOption[]" value="systemlog" <?if(strpos($backupOption,'systemlog') !== false) echo 'checked';?> />不备份系统日志&nbsp;
                                <input type="checkbox" name="backupOption[]" value="bak_acct" <?if(strpos($backupOption,'bak_acct') !== false) echo 'checked';?> />不备份上网日志
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
                                                <td><? echo _("FTP端口")?></td>
                                                <td>
                                                        <?
                                                        if(!$ftpPort){
                                                                $ftpPort = '21';
                                                        }
                                                        ?>
                                                        <input type="text" name="ftpPort" id="ftpPort" value="<?=$ftpPort?>" />
                                                </td>
                                                <td><==默认端口：21,端口范围：0-65535<span id="ftpPortMsg"></span></td>
                                        </tr>
                                        <tr>
                                                <td><? echo _("FTP主机")?></td>
                                                <td>
                                                        <?
                                                        if(!$ftpHost){
                                                                $ftpHost = '';
                                                        }
                                                        ?>
                                                        <input type="text" name="ftpHost" id="ftpHost" value="<?=$ftpHost?>" />
                                                </td>
                                                <td>FTP服务器IP或域名&nbsp;<span id="ftpHostMsg"></span></td>
                                        </tr>
                                        <tr>
                                                <td><? echo _("FTP用户")?></td>
                                                <td>
                                                        <?
                                                        if(!$ftpUser){
                                                                $ftpUser = '';
                                                        }
                                                        ?>
                                                        <input type="text" name="ftpUser" id="ftpUser" value="<?=$ftpUser?>" />
                                                </td>
                                                <td><span id="ftpUserMsg"></span></td>
                                        </tr>
                                        <tr>
                                                <td><? echo _("FTP密码")?></td>
                                                <td>
                                                        <?
                                                        if(!$ftpPwd){
                                                                $ftpPwd = '';
                                                        }
                                                        ?>
                                                        <input type="text" name="ftpPwd" id="ftpPwd" value="<?=$ftpPwd?>" />
                                                </td>
                                                <td><span id="ftpPwdMsg"></span></td>
                                        </tr>
                                        <tr>
                                                <td colspan="3">
                                                        <input type="submit" name="act" id="act" value="备份" />
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
        备份恢复-> <strong>FTP备份</strong>
      </p>
      <ul>
          <li>通过WEB页面对数据库定期做手动备份，并传送到FTP服务器上。</li>
          <li>可选择多久备份一次和不需要备份的数据。</li>
      </ul>

    </div>
<!---------------------------------------------->
</body>
</html>