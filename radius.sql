-- MySQL dump 10.13  Distrib 5.5.28, for Linux (i686)
--
-- Host: localhost    Database: radius
-- ------------------------------------------------------
-- Server version	5.5.28-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alcatel_notice`
--

DROP TABLE IF EXISTS `alcatel_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alcatel_notice` (
  `ID` int(10) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL COMMENT '启用或禁用通告',
  `addr` varchar(255) DEFAULT NULL COMMENT '重定向地址',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `client` varchar(255) DEFAULT NULL COMMENT '客户尊称',
  `img` varchar(255) DEFAULT NULL COMMENT '背景图片',
  `contents` varchar(1024) DEFAULT NULL COMMENT '内容',
  `description` varchar(1024) DEFAULT NULL COMMENT '描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alcatel_notice`
--

LOCK TABLES `alcatel_notice` WRITE;
/*!40000 ALTER TABLE `alcatel_notice` DISABLE KEYS */;
INSERT INTO `alcatel_notice` VALUES (1,'disable','','','','alcatel_bg.jpg','','');
/*!40000 ALTER TABLE `alcatel_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `badusers`
--

DROP TABLE IF EXISTS `badusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `badusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(30) DEFAULT NULL,
  `Date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Reason` varchar(200) DEFAULT NULL,
  `Admin` varchar(30) DEFAULT '-',
  PRIMARY KEY (`id`),
  KEY `UserName` (`UserName`),
  KEY `Date` (`Date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `badusers`
--

LOCK TABLES `badusers` WRITE;
/*!40000 ALTER TABLE `badusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `badusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bak_acct`
--

DROP TABLE IF EXISTS `bak_acct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bak_acct` (
  `bak_id` bigint(21) NOT NULL AUTO_INCREMENT,
  `RadAcctId` bigint(21) NOT NULL,
  `AcctSessionId` varchar(32) NOT NULL DEFAULT '',
  `AcctUniqueId` varchar(32) NOT NULL DEFAULT '',
  `UserName` varchar(64) NOT NULL DEFAULT '',
  `Realm` varchar(64) DEFAULT '',
  `NASIPAddress` varchar(15) NOT NULL DEFAULT '',
  `NASPortId` varchar(1024) DEFAULT NULL,
  `NASPortType` varchar(32) DEFAULT NULL,
  `AcctStartTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `AcctStopTime` datetime NOT NULL,
  `AcctSessionTime` int(12) DEFAULT NULL,
  `AcctAuthentic` varchar(32) DEFAULT NULL,
  `ConnectInfo_start` varchar(50) DEFAULT NULL,
  `ConnectInfo_stop` varchar(50) DEFAULT NULL,
  `AcctInputOctets` bigint(20) DEFAULT NULL,
  `AcctOutputOctets` bigint(20) DEFAULT NULL,
  `CalledStationId` varchar(50) NOT NULL DEFAULT '',
  `CallingStationId` varchar(50) NOT NULL DEFAULT '',
  `AcctTerminateCause` varchar(32) NOT NULL DEFAULT '',
  `ServiceType` varchar(32) DEFAULT NULL,
  `FramedProtocol` varchar(32) DEFAULT NULL,
  `FramedIPAddress` varchar(15) NOT NULL DEFAULT '',
  `AcctStartDelay` int(12) DEFAULT NULL,
  `AcctStopDelay` int(12) DEFAULT NULL,
  `XAscendSessionSvrKey` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`bak_id`),
  KEY `UserName` (`UserName`),
  KEY `FramedIPAddress` (`FramedIPAddress`),
  KEY `AcctSessionId` (`AcctSessionId`),
  KEY `AcctUniqueId` (`AcctUniqueId`),
  KEY `AcctStartTime` (`AcctStartTime`),
  KEY `AcctStopTime` (`AcctStopTime`),
  KEY `NASIPAddress` (`NASIPAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bak_acct`
--

LOCK TABLES `bak_acct` WRITE;
/*!40000 ALTER TABLE `bak_acct` DISABLE KEYS */;
/*!40000 ALTER TABLE `bak_acct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billbook`
--

DROP TABLE IF EXISTS `billbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billbook` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `billnumber` varchar(256) NOT NULL,
  `money` int(16) DEFAULT '0',
  `addoperator` varchar(256) DEFAULT NULL,
  `updateoperator` varchar(256) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT '0=生成，1=使用，',
  `stopdatetime` datetime DEFAULT NULL COMMENT '失效时间',
  `remark` varchar(1024) DEFAULT NULL COMMENT '失效时间',
  `UserName` varchar(1024) DEFAULT NULL COMMENT '充值的用户',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billbook`
--

LOCK TABLES `billbook` WRITE;
/*!40000 ALTER TABLE `billbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `billbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card`
--

DROP TABLE IF EXISTS `card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `cardNumber` varchar(256) DEFAULT NULL,
  `prefix` varchar(256) DEFAULT NULL,
  `startNum` int(16) unsigned DEFAULT NULL,
  `endNum` int(16) unsigned DEFAULT NULL,
  `money` int(16) unsigned DEFAULT NULL,
  `ivalidTime` datetime DEFAULT NULL,
  `sold` int(2) unsigned DEFAULT NULL,
  `recharge` int(2) unsigned DEFAULT NULL,
  `solder` varchar(256) DEFAULT NULL,
  `soldTime` datetime DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `actviation` varchar(256) DEFAULT NULL,
  `remark` varchar(1024) DEFAULT NULL,
  `cardAddTime` datetime DEFAULT NULL,
  `starNum` int(16) unsigned DEFAULT NULL,
  `UserName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card`
--

LOCK TABLES `card` WRITE;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
/*!40000 ALTER TABLE `card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cardlog`
--

DROP TABLE IF EXISTS `cardlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cardlog` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cardNumber` varchar(256) NOT NULL,
  `type` int(2) unsigned DEFAULT NULL,
  `UserName` varchar(256) NOT NULL,
  `addTime` datetime DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `content` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cardlog`
--

LOCK TABLES `cardlog` WRITE;
/*!40000 ALTER TABLE `cardlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `cardlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_notice`
--

DROP TABLE IF EXISTS `client_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_notice` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(16) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_notice`
--

LOCK TABLES `client_notice` WRITE;
/*!40000 ALTER TABLE `client_notice` DISABLE KEYS */;
INSERT INTO `client_notice` VALUES (1,0,'计费管理系统版权说明','');
/*!40000 ALTER TABLE `client_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `site` varchar(256) DEFAULT NULL,
  `copyright` varchar(1024) DEFAULT NULL,
  `speedStatus` varchar(16) DEFAULT NULL,
  `macStatus` varchar(16) DEFAULT NULL,
  `onlinenum` int(4) DEFAULT NULL,
  `picTopLeft` varchar(256) DEFAULT NULL,
  `picTopRight` varchar(256) DEFAULT NULL,
  `picBottomLeft` varchar(256) DEFAULT NULL,
  `picBottomRight` varchar(256) DEFAULT NULL,
  `version` varchar(256) DEFAULT NULL,
  `picLogin` varchar(256) DEFAULT '/images/login_bg.jpg',
  `WEB` varchar(256) DEFAULT 'http://www.natshell.com',
  `Name` varchar(256) DEFAULT '蓝海卓越系统',
  `copyrightLog` varchar(256) DEFAULT '版权归属:蓝海卓越所有',
  `CRStatement` text,
  `Contact` varchar(256) DEFAULT '',
  `vlanStatus` varchar(16) DEFAULT NULL COMMENT 'vlan绑定1启用0禁用',
  `nasStatus` varchar(16) DEFAULT NULL COMMENT '1绑定0不绑定',
  `scannum` varchar(16) DEFAULT '3' COMMENT '数据库备份保留个数,默认3',
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'蓝海卓越计费管理系统','版权所有:星锐蓝海网络科技有限公司','','',1,'logo/1354125865.jpg','logo/1354125879.jpg','logo/1338456892.jpg','logo/1338456900.jpg','1.9.8','images/1338456908.jpg','http://www.natshell.com    ','蓝海卓越计费管理系统','版权所有:星锐蓝海网络科技有限公司','1、本系统为商业授权，未经授权，不得以任何方式进行对本系统进行破解、复制、传播等行为;\r\n2、用户可自由选择是否使用本系统，任何未经授权的使用，在使用中出现的问题和由此造成的一切损失本公司不承担任何责任;\r\n3、您可以对本系统进行修改和美化，但必须保留完整的版权信息; \r\n4、本系统受中华人民共和国《著作权法》《计算机软件保护条例》《商标法》《专利法》等相关法律、法规保护，星锐蓝海网络科技有限公司保留一切权利。','028-86679789','','','10');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit`
--

DROP TABLE IF EXISTS `credit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned NOT NULL,
  `money` float unsigned DEFAULT NULL,
  `type` varchar(256) DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `projectID` int(16) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit`
--

LOCK TABLES `credit` WRITE;
/*!40000 ALTER TABLE `credit` DISABLE KEYS */;
/*!40000 ALTER TABLE `credit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance`
--

DROP TABLE IF EXISTS `finance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finance` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `money` int(16) DEFAULT NULL,
  `type` int(10) DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance`
--

LOCK TABLES `finance` WRITE;
/*!40000 ALTER TABLE `finance` DISABLE KEYS */;
/*!40000 ALTER TABLE `finance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grade`
--

DROP TABLE IF EXISTS `grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grade` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grade`
--

LOCK TABLES `grade` WRITE;
/*!40000 ALTER TABLE `grade` DISABLE KEYS */;
INSERT INTO `grade` VALUES (1,'显示'),(2,'隐藏');
/*!40000 ALTER TABLE `grade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guestbook`
--

DROP TABLE IF EXISTS `guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guestbook` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) NOT NULL,
  `status` int(16) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` text,
  `reply` text,
  `adddatetime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guestbook`
--

LOCK TABLES `guestbook` WRITE;
/*!40000 ALTER TABLE `guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip2ros`
--

DROP TABLE IF EXISTS `ip2ros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip2ros` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `rosipaddress` varchar(255) DEFAULT NULL,
  `rosusername` varchar(20) DEFAULT NULL,
  `rospassword` varchar(20) DEFAULT NULL,
  `inf` varchar(20) DEFAULT NULL,
  `projectID` int(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip2ros`
--

LOCK TABLES `ip2ros` WRITE;
/*!40000 ALTER TABLE `ip2ros` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip2ros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ippool`
--

DROP TABLE IF EXISTS `ippool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ippool` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `beginip` varchar(255) DEFAULT NULL COMMENT '开始IP',
  `endip` varchar(255) DEFAULT NULL COMMENT '结束IP',
  `addtime` date DEFAULT NULL COMMENT '添加时间',
  `operator` varchar(255) DEFAULT NULL COMMENT '操作人员',
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ippool`
--

LOCK TABLES `ippool` WRITE;
/*!40000 ALTER TABLE `ippool` DISABLE KEYS */;
/*!40000 ALTER TABLE `ippool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ippool_tmp`
--

DROP TABLE IF EXISTS `ippool_tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ippool_tmp` (
  `id` bigint(21) unsigned NOT NULL AUTO_INCREMENT,
  `projectID` bigint(21) DEFAULT NULL,
  `userID` bigint(21) DEFAULT NULL,
  `enddatetime` datetime DEFAULT NULL,
  `ippoolID` bigint(21) DEFAULT NULL,
  `framedIP` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ippool_tmp`
--

LOCK TABLES `ippool_tmp` WRITE;
/*!40000 ALTER TABLE `ippool_tmp` DISABLE KEYS */;
/*!40000 ALTER TABLE `ippool_tmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loginlog`
--

DROP TABLE IF EXISTS `loginlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loginlog` (
  `ID` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `logindatetime` datetime DEFAULT '0000-00-00 00:00:00',
  `logoutdatetime` datetime DEFAULT '0000-00-00 00:00:00',
  `loginip` varchar(256) DEFAULT NULL,
  `content` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loginlog`
--

LOCK TABLES `loginlog` WRITE;
/*!40000 ALTER TABLE `loginlog` DISABLE KEYS */;
INSERT INTO `loginlog` VALUES (1,'admin','2013-08-13 14:02:34','2013-08-13 14:26:23','192.168.100.2','/login.php'),(2,'admin','2013-08-13 14:12:39','2013-08-13 14:26:23','192.168.100.127','/login.php'),(3,'admin','2013-08-13 14:26:29','2013-08-13 14:26:41','192.168.100.127','/login.php'),(4,'admin','2013-08-13 14:26:43','2013-08-13 14:27:56','192.168.100.127','/login.php'),(5,'admin','2013-08-13 14:27:58','2013-08-13 14:28:06','192.168.100.127','/login.php'),(6,'admin','2013-08-13 14:28:08','2013-08-13 14:28:53','192.168.100.127','/login.php'),(7,'admin','2013-08-13 14:28:55','2013-08-13 14:37:17','192.168.100.127','/login.php'),(8,'admin','2013-08-13 14:30:11','2013-08-13 14:37:17','192.168.100.127','/login.php'),(9,'admin','2013-08-13 14:37:21','0000-00-00 00:00:00','192.168.100.2','/login.php'),(10,'admin','2013-08-13 15:06:54','0000-00-00 00:00:00','192.168.100.127','/login.php');
/*!40000 ALTER TABLE `loginlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manager`
--

DROP TABLE IF EXISTS `manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manager` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `manager_account` varchar(256) DEFAULT NULL,
  `manager_passwd` varchar(256) DEFAULT NULL,
  `manager_name` varchar(256) DEFAULT NULL,
  `manager_phone` varchar(256) DEFAULT NULL,
  `manager_mobile` varchar(256) DEFAULT NULL,
  `manager_permision` text,
  `manager_groupID` int(16) DEFAULT NULL,
  `manager_project` text,
  `manager_gradeID` varchar(256) DEFAULT NULL,
  `addusernum` int(16) DEFAULT '0' COMMENT '开户用户人数',
  `addusertotalnum` int(16) DEFAULT NULL COMMENT '总共分配的添加用户人数',
  `manager_totalmoney` int(16) DEFAULT NULL COMMENT '管理员最高收费金额限度',
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manager`
--

LOCK TABLES `manager` WRITE;
/*!40000 ALTER TABLE `manager` DISABLE KEYS */;
INSERT INTO `manager` VALUES (1,'admin','admin','','','','user#user.php#user_edit.php#user_del.php#user_add.php#user_replac_product.php#user_closing.php#user_show.php#user_dial_log.php#user_upcoming.php#user_maturity.php#user_pause.php#user_move.php#user_rewrite.php#user_change_banwith.php#user_normal_info.php#user_closing_info.php#user_show_passwprd.php#excel_userinfo.php#user_Mname_info.php#user_closing_info.php#excel_userlog.php#user_shutdown.php#pause.php#user_netbar.php#update_user_endtime#update_vlan#project#project.php#project_add.php#project_edit.php#project_del.php#ippool.php#ippool_add.php#ippool_edit.php#ippool_del.php#project_ros.php#product#product.php#product_add.php#product_edit.php#product_del.php#freeProduct#speedrule#speedrule.php#speedrule_add.php#speedrule_edit.php#speedrule_del.php#order#order.php#order_add.php#order_run.php#order_ticket.php#user_show_print.php#order_del.php#excel_orderinfo.php#recharge#recharge_user.php#recharge_reverse.php#rzgl#recharge_log.php#finance_report.php#user_bill.php#billbook_add.php#billbook.php#user_flow_monitor.php#recharge_add.php#financial_subjects.php#finance_MTC_add.php#user_hours_show.php#excel_credit.php#user_checkout.php#excel_rechangelog.php#finance_details.php#creditDell#card#card_add.php#card.php#card_search.php#card_del.php#card_sold_show.php#excel_card.php#repair#repair_add.php#repair.php#repair_disposal.php#repair_disposal_log.php#repair_disposal_edit.php#repair_disposal_del.php#repair_edit.php#repair_del.php#oprate#operate_online.php#operate_netplay_log.php#operate_userlog.php#operate_login_log.php#db#db_backup.php#db_restore.php#db_user_import.php#truncate_alltable.php#system_database.php#db_sync_mysql.php#db_auto.php#system_publicnotice.php#alcatel_notice.php#systempermsion#manager.php#manager_add.php#manager_edit.php#manager_del.php#managergroup#manager_group.php#manager_group_edit.php#manager_group_add.php#manager_group_del.php#systeminfo#main.php#system_upgrade.php#system_config.php#system_del_dial_log.php#system_ros.php#system_client_notice.php#system_configuration.php#chart#chart_user.php#chart_report.php#chart_product.php#chart_report_pie.php#guestbook.php#guestbook_reply.php#guestbook_del.php',1,'','1,2',0,1000,999999999);
/*!40000 ALTER TABLE `manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `managergroup`
--

DROP TABLE IF EXISTS `managergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `managergroup` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(256) DEFAULT NULL,
  `group_permision` text,
  `group_project` text,
  `group_gradeID` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `managergroup`
--

LOCK TABLES `managergroup` WRITE;
/*!40000 ALTER TABLE `managergroup` DISABLE KEYS */;
INSERT INTO `managergroup` VALUES (1,'Administrator','user#user.php#user_edit.php#user_del.php#project#project.php#project_add.php#project_edit.php#project_del.php#product#product.php#product_add.php#product_edit.php#product_del.php#speedrule#speedrule.php#speedrule_add.php#speedrule_edit.php#speedrule_del.php#user_add.php#user_replac_product.php#user_closing.php#order#order.php#order_add.php#recharge#recharge_user.php#recharge_reverse.php#rzgl#recharge_log.php#finance_report.php#user_bill.php#card#card_add.php#card.php#card_search.php#oprate#operate_online.php#operate_netplay_log.php#operate_userlog.php#db#db_backup.php#db_restore.php#systempermsion#manager.php#manager_add.php#manager_edit.php#manager_del.php#order_run.php#systeminfo#main.php#system_upgrade.php#system_config.php#managergroup#manager_group.php#manager_group_edit.php#manager_group_add.php#manager_group_del.php#operate_login_log.php#repair#repair_add.php#repair.php#repair_disposal.php#repair_disposal_log.php#repair_disposal_edit.php#repair_disposal_del.php#repair_edit.php#repair_del.php#order_ticket.php#user_show.php#user_show_print.php#user_dial_log.php#system_del_dial_log.php#user_upcoming.php#user_maturity.php#user_pause.php#db_user_import.php#order_del.php#user_move.php#billbook_add.php#billbook.php#chart#chart_user.php#chart_report.php#chart_product.php#card_del.php#system_ros.php#system_client_notice.php#guestbook.php#guestbook_reply.php#guestbook_del.php#user_rewrite.php#user_change_banwith.php#chart_report_pie.php#user_normal_info.php#user_closing_info.php#user_show_passwprd.php#user_flow_monitor.php#recharge_add.php#truncate_alltable.php#excel_userinfo.php#user_Mname_info.php#user_closing_info.php#financial_subjects.php#finance_MTC_add.php#user_hours_show.php#excel_userlog.php#user_shutdown.php#ippool.php#ippool_add.php#ippool_edit.php#ippool_del.php#card_sold_show.php#freeProduct#system_configuration.php#excel_credit.php#project_ros.php#system_database.php#system_database_XMLA.php#excel_orderinfo.php#db_auto.php#pause.php#user_netbar.php#user_checkout.php#excel_rechangelog.php#excel_card.php#update_user_endtime#system_publicnotice.php','','1,2'),(2,'','',NULL,NULL),(3,'','',NULL,NULL),(4,'','',NULL,NULL),(5,'','',NULL,NULL),(6,'','',NULL,NULL),(7,'','',NULL,NULL),(8,'','',NULL,NULL),(9,'','',NULL,NULL),(10,'','',NULL,NULL);
/*!40000 ALTER TABLE `managergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `managerpermision`
--

DROP TABLE IF EXISTS `managerpermision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `managerpermision` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `permision_name` varchar(256) DEFAULT NULL,
  `permision_param` varchar(256) DEFAULT NULL,
  `permision_parentID` int(16) unsigned DEFAULT NULL,
  `permision_rank` int(16) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `managerpermision`
--

LOCK TABLES `managerpermision` WRITE;
/*!40000 ALTER TABLE `managerpermision` DISABLE KEYS */;
INSERT INTO `managerpermision` VALUES (1,'用户管理','user',0,1),(3,'用户查询','user.php',1,0),(4,'用户修改','user_edit.php',1,0),(5,'用户删除','user_del.php',1,0),(6,'项目管理','project',0,2),(7,'项目查看','project.php',6,0),(8,'项目增加','project_add.php',6,0),(9,'项目修改','project_edit.php',6,0),(10,'项目删除','project_del.php',6,0),(11,'产品管理','product',0,3),(12,'产品查看','product.php',11,0),(13,'产品增加','product_add.php',11,0),(14,'产品修改','product_edit.php',11,0),(15,'产品删除 ','product_del.php',11,0),(16,'限速规则','speedrule',0,4),(17,'规则查看','speedrule.php',16,0),(18,'规则增加','speedrule_add.php',16,0),(19,'规则修改','speedrule_edit.php',16,0),(20,'规则删除','speedrule_del.php',16,0),(22,'用户增加','user_add.php',1,0),(23,'更改产品','user_replac_product.php',1,0),(25,'用户销户','user_closing.php',1,0),(26,'订单管理','order',0,5),(27,'订单记录','order.php',26,0),(28,'订单增加','order_add.php',26,0),(29,'收费管理','recharge',0,6),(30,'用户充值','recharge_user.php',29,0),(31,'用户冲帐','recharge_reverse.php',29,0),(32,'营帐管理','rzgl',0,7),(33,'充值记录','recharge_log.php',32,0),(34,'营业报表','finance_report.php',32,0),(35,'用户帐单','user_bill.php',32,0),(36,'卡片管理','card',0,8),(37,'生成卡片','card_add.php',36,0),(38,'卡片销售','card.php',36,0),(39,'卡片查询','card_search.php',36,0),(40,'运营管理','oprate',0,9),(41,'在线管理','operate_online.php',40,0),(42,'上网记录','operate_netplay_log.php',40,0),(43,'用户日志','operate_userlog.php',40,0),(44,'数据管理','db',0,10),(45,'数据备份','db_backup.php',44,0),(46,'数据恢复','db_restore.php',44,0),(47,'系统用户管理','systempermsion',0,11),(48,'系统用户查看','manager.php',47,0),(49,'系统用户增加','manager_add.php',47,0),(50,'系统用户修改','manager_edit.php',47,0),(51,'系统用户删除','manager_del.php',47,0),(52,'订单运行日志','order_run.php',26,0),(53,'系统信息','systeminfo',0,13),(54,'系统配置','main.php',53,0),(55,'系统升级','system_upgrade.php',53,0),(56,'配置管理','system_config.php',53,0),(58,'系统角色查询','managergroup',0,12),(59,'系统角色查询','manager_group.php',58,0),(60,'系统角色修改','manager_group_edit.php',58,0),(61,'系统角色增加','manager_group_add.php',58,0),(62,'系统角色删除','manager_group_del.php',58,0),(63,'登录记录','operate_login_log.php',40,0),(64,'报修管理','repair',0,8),(65,'报修登记','repair_add.php',64,0),(66,'报修管理','repair.php',64,0),(67,'报修订单分配','repair_disposal.php',64,0),(68,'报修处理记录','repair_disposal_log.php',64,0),(69,'处理记录修改','repair_disposal_edit.php',64,0),(70,'处理记录删除','repair_disposal_del.php',64,0),(71,'报修订单修改','repair_edit.php',64,0),(72,'报修订单删除','repair_del.php',64,0),(73,'票据设置','order_ticket.php',26,0),(77,'查看用户详细','user_show.php',1,0),(78,'打印票据','user_show_print.php',26,0),(79,'用户拨号详细','user_dial_log.php',1,0),(80,'删除拨号记录','system_del_dial_log.php',53,0),(81,'查看即将到期','user_upcoming.php',1,0),(82,'查到到期用户','user_maturity.php',1,0),(83,'查看暂停用户','user_pause.php',1,0),(84,'数据导入','db_user_import.php',44,0),(85,'订单撤消','order_del.php',26,0),(86,'用户移机','user_move.php',1,0),(87,'票据生成','billbook_add.php',32,0),(88,'票据查看','billbook.php',32,0),(89,'图表分析','chart',0,14),(90,'用户图表','chart_user.php',89,0),(91,'报表分析','chart_report.php',89,0),(92,'产品分析','chart_product.php',89,0),(93,'卡片删除','card_del.php',36,0),(94,'到期公告','system_ros.php',53,0),(95,'自助通告','system_client_notice.php',53,0),(96,'留言管理','guestbook.php',0,15),(97,'回复','guestbook_reply.php',96,0),(98,'删除','guestbook_del.php',96,0),(99,'用户重建','user_rewrite.php',1,0),(100,'更改带宽','user_change_banwith.php',1,0),(101,'图表分析','chart_report_pie.php',89,0),(102,'在网用户','user_normal_info.php',1,0),(103,'销户用户','user_closing_info.php',1,0),(106,'查询密码','user_show_passwprd.php',1,0),(107,'流量监控','user_flow_monitor.php',32,0),(108,'用户充值','recharge_add.php',32,0),(109,'数据库出厂设置','truncate_alltable.php',44,0),(110,'用户管理EXCEL导出','excel_userinfo.php',1,0),(111,'子母账号','user_Mname_info.php',1,0),(112,'销户用户','user_closing_info.php',1,0),(113,'科目管理','financial_subjects.php',32,0),(114,'人工收费','finance_MTC_add.php',32,0),(115,'时长监控','user_hours_show.php',32,0),(116,'EXCEL日志导出','excel_userlog.php',1,0),(117,'停机恢复/保号','user_shutdown.php',1,0),(118,'地址池管理','ippool.php',6,0),(119,'地址池添加','ippool_add.php',6,0),(120,'地址池修改','ippool_edit.php',6,0),(121,'地址池删除','ippool_del.php',6,0),(122,'销售预览','card_sold_show.php',36,0),(123,'添加免费产品/时长计费','freeProduct',11,0),(124,'批量配置','system_configuration.php',53,0),(125,'EXCEL财务报表导出','excel_credit.php',32,0),(126,'NAS同步','project_ros.php',6,0),(127,'数据库管理','system_database.php',44,0),(128,'数据库同步','db_sync_mysql.php',44,0),(129,'EXCEL订单记录导出','excel_orderinfo.php',26,0),(130,'自动备份','db_auto.php',44,0),(131,'立即暂停/恢复','pause.php',1,0),(132,'时长计费','user_netbar.php',1,0),(133,'时长计费结账','user_checkout.php',32,0),(134,'充值记录导出','excel_rechangelog.php',32,0),(135,'卡片导出','excel_card.php',36,0),(136,'修改结束时间','update_user_endtime',1,0),(137,'ROS通告','system_publicnotice.php',44,0),(138,'用户对账','finance_details.php',32,0),(139,'财务删除','creditDell',32,0),(140,'贝尔通告','alcatel_notice.php',44,0),(141,'修改VLAN','update_vlan',1,0),(142,'EXCEL在网用户导出','excel_normalinfo.php',1,0),(145,'EXCEL用户账单导出','excel_userbillinfo.php',32,0);
/*!40000 ALTER TABLE `managerpermision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maturity_notice`
--

DROP TABLE IF EXISTS `maturity_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maturity_notice` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(16) NOT NULL,
  `day_apart` int(16) NOT NULL,
  `port` int(16) NOT NULL,
  `content` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maturity_notice`
--

LOCK TABLES `maturity_notice` WRITE;
/*!40000 ALTER TABLE `maturity_notice` DISABLE KEYS */;
INSERT INTO `maturity_notice` VALUES (1,0,5,80,'');
/*!40000 ALTER TABLE `maturity_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mtotacct`
--

DROP TABLE IF EXISTS `mtotacct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mtotacct` (
  `MTotAcctId` bigint(21) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(64) NOT NULL DEFAULT '',
  `AcctDate` date NOT NULL DEFAULT '0000-00-00',
  `ConnNum` bigint(12) DEFAULT NULL,
  `ConnTotDuration` bigint(12) DEFAULT NULL,
  `ConnMaxDuration` bigint(12) DEFAULT NULL,
  `ConnMinDuration` bigint(12) DEFAULT NULL,
  `InputOctets` bigint(12) DEFAULT NULL,
  `OutputOctets` bigint(12) DEFAULT NULL,
  `NASIPAddress` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`MTotAcctId`),
  KEY `UserName` (`UserName`),
  KEY `AcctDate` (`AcctDate`),
  KEY `UserOnDate` (`UserName`,`AcctDate`),
  KEY `NASIPAddress` (`NASIPAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mtotacct`
--

LOCK TABLES `mtotacct` WRITE;
/*!40000 ALTER TABLE `mtotacct` DISABLE KEYS */;
/*!40000 ALTER TABLE `mtotacct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nas`
--

DROP TABLE IF EXISTS `nas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nas` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `nasname` varchar(256) DEFAULT NULL,
  `shortname` varchar(256) DEFAULT NULL,
  `type` varchar(256) DEFAULT NULL,
  `secret` varchar(256) DEFAULT NULL,
  `community` varchar(256) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `ports` int(16) unsigned DEFAULT NULL,
  `ip` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nas`
--

LOCK TABLES `nas` WRITE;
/*!40000 ALTER TABLE `nas` DISABLE KEYS */;
/*!40000 ALTER TABLE `nas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderinfo`
--

DROP TABLE IF EXISTS `orderinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderinfo` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `productID` int(16) unsigned NOT NULL,
  `userID` int(16) unsigned NOT NULL,
  `status` int(16) unsigned NOT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `remark` varchar(1024) DEFAULT NULL,
  `receipt` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`),
  KEY `projectID` (`productID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderinfo`
--

LOCK TABLES `orderinfo` WRITE;
/*!40000 ALTER TABLE `orderinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `orderinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderlog`
--

DROP TABLE IF EXISTS `orderlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderlog` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `orderID` int(16) unsigned NOT NULL,
  `type` varchar(256) DEFAULT NULL,
  `userID` int(10) unsigned NOT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `content` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderlog`
--

LOCK TABLES `orderlog` WRITE;
/*!40000 ALTER TABLE `orderlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `orderlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderrefund`
--

DROP TABLE IF EXISTS `orderrefund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderrefund` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderID` int(10) unsigned NOT NULL,
  `userID` int(16) unsigned NOT NULL,
  `money` float unsigned DEFAULT NULL,
  `factmoney` float unsigned DEFAULT NULL,
  `type` varchar(256) DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `remark` varchar(1024) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `facmoney` float unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderrefund`
--

LOCK TABLES `orderrefund` WRITE;
/*!40000 ALTER TABLE `orderrefund` DISABLE KEYS */;
/*!40000 ALTER TABLE `orderrefund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `period` int(16) NOT NULL,
  `price` float NOT NULL,
  `unitprice` float DEFAULT NULL,
  `capping` float DEFAULT NULL,
  `creditline` varchar(256) DEFAULT NULL,
  `upbandwidth` int(16) DEFAULT NULL,
  `downbandwidth` int(16) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `hide` int(2) DEFAULT NULL,
  `timetype` varchar(10) DEFAULT '',
  `begintime` time DEFAULT NULL,
  `endtime` time DEFAULT NULL,
  `limittype` varchar(10) DEFAULT '',
  `periodtime` varchar(10) DEFAULT '',
  `limitupbandwidth` int(16) DEFAULT NULL,
  `limitdownbandwidth` int(16) DEFAULT NULL,
  `dayparting` int(16) DEFAULT NULL,
  `starttime` time DEFAULT NULL,
  `stoptime` time DEFAULT NULL,
  `partingupbandwidth` int(16) DEFAULT NULL,
  `partingdownbandwidth` int(16) DEFAULT NULL,
  `enddatetime` date DEFAULT NULL,
  `penalty` float DEFAULT NULL,
  `auto` varchar(16) DEFAULT NULL COMMENT '是否自动续费正对产品价格为0的起效0，否，1是',
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`),
  KEY `name` (`name`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productandproject`
--

DROP TABLE IF EXISTS `productandproject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productandproject` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `productID` int(10) unsigned NOT NULL,
  `projectID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productandproject`
--

LOCK TABLES `productandproject` WRITE;
/*!40000 ALTER TABLE `productandproject` DISABLE KEYS */;
/*!40000 ALTER TABLE `productandproject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `beginip` varchar(32) NOT NULL,
  `endip` varchar(32) NOT NULL,
  `device` varchar(256) DEFAULT NULL,
  `description` varchar(1024) CHARACTER SET utf8 COLLATE utf8_estonian_ci DEFAULT NULL,
  `mtu` varchar(256) NOT NULL,
  `installcharge` int(16) DEFAULT NULL,
  `accounts` int(16) DEFAULT NULL,
  `nasip` varchar(256) DEFAULT '',
  `nasusername` varchar(256) DEFAULT '',
  `naspwd` varchar(256) DEFAULT '',
  `status` varchar(10) DEFAULT '',
  `remind` varchar(10) DEFAULT NULL,
  `ippoolID` int(255) DEFAULT NULL,
  `days` int(255) DEFAULT NULL,
  `userprefix` varchar(16) DEFAULT NULL COMMENT '所属项目用户名前缀',
  `userstart` varchar(16) DEFAULT NULL COMMENT '所属项目用户开始ID',
  `userpwd` varchar(16) DEFAULT NULL COMMENT '默认密码',
  `confname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_ros`
--

DROP TABLE IF EXISTS `project_ros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_ros` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `nasip` varchar(16) DEFAULT NULL,
  `username` varchar(16) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `projectID` int(4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_ros`
--

LOCK TABLES `project_ros` WRITE;
/*!40000 ALTER TABLE `project_ros` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_ros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publicnotice`
--

DROP TABLE IF EXISTS `publicnotice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publicnotice` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(16) NOT NULL,
  `period` int(16) NOT NULL,
  `fwd_ipaddr` varchar(16) NOT NULL,
  `fwd_port` int(16) NOT NULL,
  `product` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicnotice`
--

LOCK TABLES `publicnotice` WRITE;
/*!40000 ALTER TABLE `publicnotice` DISABLE KEYS */;
/*!40000 ALTER TABLE `publicnotice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radacct`
--

DROP TABLE IF EXISTS `radacct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radacct` (
  `RadAcctId` bigint(21) NOT NULL AUTO_INCREMENT,
  `AcctSessionId` varchar(32) NOT NULL DEFAULT '',
  `AcctUniqueId` varchar(32) NOT NULL DEFAULT '',
  `UserName` varchar(64) NOT NULL DEFAULT '',
  `Realm` varchar(64) DEFAULT '',
  `NASIPAddress` varchar(15) NOT NULL DEFAULT '',
  `NASPortId` varchar(1024) DEFAULT NULL,
  `NASPortType` varchar(32) DEFAULT NULL,
  `AcctStartTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `AcctStopTime` datetime NOT NULL,
  `AcctSessionTime` int(12) DEFAULT NULL,
  `AcctAuthentic` varchar(32) DEFAULT NULL,
  `ConnectInfo_start` varchar(50) DEFAULT NULL,
  `ConnectInfo_stop` varchar(50) DEFAULT NULL,
  `AcctInputOctets` bigint(20) DEFAULT NULL,
  `AcctOutputOctets` bigint(20) DEFAULT NULL,
  `CalledStationId` varchar(50) NOT NULL DEFAULT '',
  `CallingStationId` varchar(50) NOT NULL DEFAULT '',
  `AcctTerminateCause` varchar(32) NOT NULL DEFAULT '',
  `ServiceType` varchar(32) DEFAULT NULL,
  `FramedProtocol` varchar(32) DEFAULT NULL,
  `FramedIPAddress` varchar(15) NOT NULL DEFAULT '',
  `AcctStartDelay` int(12) DEFAULT NULL,
  `AcctStopDelay` int(12) DEFAULT NULL,
  `XAscendSessionSvrKey` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`RadAcctId`),
  KEY `UserName` (`UserName`),
  KEY `FramedIPAddress` (`FramedIPAddress`),
  KEY `AcctSessionId` (`AcctSessionId`),
  KEY `AcctUniqueId` (`AcctUniqueId`),
  KEY `AcctStartTime` (`AcctStartTime`),
  KEY `AcctStopTime` (`AcctStopTime`),
  KEY `NASIPAddress` (`NASIPAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radacct`
--

LOCK TABLES `radacct` WRITE;
/*!40000 ALTER TABLE `radacct` DISABLE KEYS */;
/*!40000 ALTER TABLE `radacct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radcheck`
--

DROP TABLE IF EXISTS `radcheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radcheck` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `UserName` varchar(64) NOT NULL DEFAULT '',
  `Attribute` varchar(32) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `Value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `UserName` (`UserName`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radcheck`
--

LOCK TABLES `radcheck` WRITE;
/*!40000 ALTER TABLE `radcheck` DISABLE KEYS */;
/*!40000 ALTER TABLE `radcheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radgroupcheck`
--

DROP TABLE IF EXISTS `radgroupcheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radgroupcheck` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(64) NOT NULL DEFAULT '',
  `Attribute` varchar(32) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `Value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `GroupName` (`GroupName`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radgroupcheck`
--

LOCK TABLES `radgroupcheck` WRITE;
/*!40000 ALTER TABLE `radgroupcheck` DISABLE KEYS */;
/*!40000 ALTER TABLE `radgroupcheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radgroupreply`
--

DROP TABLE IF EXISTS `radgroupreply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radgroupreply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(64) NOT NULL DEFAULT '',
  `Attribute` varchar(32) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `Value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `GroupName` (`GroupName`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radgroupreply`
--

LOCK TABLES `radgroupreply` WRITE;
/*!40000 ALTER TABLE `radgroupreply` DISABLE KEYS */;
/*!40000 ALTER TABLE `radgroupreply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radpostauth`
--

DROP TABLE IF EXISTS `radpostauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radpostauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `pass` varchar(64) NOT NULL DEFAULT '',
  `reply` varchar(32) NOT NULL DEFAULT '',
  `authdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radpostauth`
--

LOCK TABLES `radpostauth` WRITE;
/*!40000 ALTER TABLE `radpostauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `radpostauth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radreply`
--

DROP TABLE IF EXISTS `radreply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radreply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned NOT NULL,
  `UserName` varchar(64) NOT NULL DEFAULT '',
  `Attribute` varchar(32) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `Value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `UserName` (`UserName`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radreply`
--

LOCK TABLES `radreply` WRITE;
/*!40000 ALTER TABLE `radreply` DISABLE KEYS */;
/*!40000 ALTER TABLE `radreply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repair`
--

DROP TABLE IF EXISTS `repair`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repair` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned NOT NULL,
  `UserName` varchar(256) NOT NULL,
  `type` int(4) NOT NULL COMMENT '1=报装，2=报修',
  `reason` varchar(10240) DEFAULT NULL,
  `reply` varchar(10240) DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `status` int(2) DEFAULT NULL COMMENT '1=报修，2=处理中，3=完成',
  `startdatetime` datetime DEFAULT NULL COMMENT '报修订单生成日期',
  `enddatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repair`
--

LOCK TABLES `repair` WRITE;
/*!40000 ALTER TABLE `repair` DISABLE KEYS */;
/*!40000 ALTER TABLE `repair` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repairdisposal`
--

DROP TABLE IF EXISTS `repairdisposal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repairdisposal` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `repairID` int(16) unsigned NOT NULL,
  `sender` varchar(256) DEFAULT NULL,
  `receiver` varchar(256) DEFAULT NULL,
  `reason` varchar(10240) DEFAULT NULL,
  `startdatetime` datetime DEFAULT NULL,
  `enddatetime` datetime DEFAULT NULL,
  `status` int(2) unsigned DEFAULT NULL COMMENT '1=等待处理，2=转发，3=完成',
  `days` int(2) unsigned DEFAULT NULL COMMENT '时间要求',
  `recevier` varchar(256) DEFAULT NULL,
  `type` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repairdisposal`
--

LOCK TABLES `repairdisposal` WRITE;
/*!40000 ALTER TABLE `repairdisposal` DISABLE KEYS */;
/*!40000 ALTER TABLE `repairdisposal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ros_notice`
--

DROP TABLE IF EXISTS `ros_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ros_notice` (
  `ID` int(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL COMMENT '启用或禁用通告',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `client` varchar(255) DEFAULT NULL COMMENT '客户尊称',
  `img` varchar(255) DEFAULT NULL COMMENT '背景图片',
  `content` varchar(1024) DEFAULT NULL COMMENT '通告内容',
  `description` varchar(1024) DEFAULT NULL COMMENT '描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ros_notice`
--

LOCK TABLES `ros_notice` WRITE;
/*!40000 ALTER TABLE `ros_notice` DISABLE KEYS */;
INSERT INTO `ros_notice` VALUES (1,'disable','','','ros_bg.jpg','','');
/*!40000 ALTER TABLE `ros_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `runinfo`
--

DROP TABLE IF EXISTS `runinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `runinfo` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned NOT NULL,
  `orderID` int(16) unsigned NOT NULL,
  `stats` varchar(256) CHARACTER SET latin1 NOT NULL COMMENT '统计',
  `price` float NOT NULL DEFAULT '0' COMMENT '0',
  `adddatetime` datetime NOT NULL,
  `status` int(2) DEFAULT NULL,
  `limit` int(16) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `runinfo`
--

LOCK TABLES `runinfo` WRITE;
/*!40000 ALTER TABLE `runinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `runinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `speedrule`
--

DROP TABLE IF EXISTS `speedrule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `speedrule` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `srcip` varchar(50) DEFAULT '',
  `projectID` int(16) DEFAULT NULL,
  `dstip` varchar(50) DEFAULT '',
  `srcport` int(10) unsigned DEFAULT NULL,
  `dstport` int(10) unsigned DEFAULT NULL,
  `upload` int(10) unsigned DEFAULT NULL,
  `download` int(10) unsigned DEFAULT NULL,
  `scrip` varchar(256) DEFAULT NULL,
  `dsrip` varchar(256) DEFAULT NULL,
  `dsccport` int(16) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `speedrule`
--

LOCK TABLES `speedrule` WRITE;
/*!40000 ALTER TABLE `speedrule` DISABLE KEYS */;
/*!40000 ALTER TABLE `speedrule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sync_mysql`
--

DROP TABLE IF EXISTS `sync_mysql`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sync_mysql` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(10) DEFAULT NULL,
  `mode` varchar(50) DEFAULT NULL,
  `ipaddress` varchar(32) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sync_mysql`
--

LOCK TABLES `sync_mysql` WRITE;
/*!40000 ALTER TABLE `sync_mysql` DISABLE KEYS */;
INSERT INTO `sync_mysql` VALUES (1,'enable','server','192.168.100.101','111','111');
/*!40000 ALTER TABLE `sync_mysql` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `systemlog`
--

DROP TABLE IF EXISTS `systemlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `systemlog` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL,
  `fromfile` text,
  `fromfunc` text,
  `cmd` text,
  `msg` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `systemlog`
--

LOCK TABLES `systemlog` WRITE;
/*!40000 ALTER TABLE `systemlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `systemlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` bigint(21) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `value` varchar(64) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `tel` varchar(256) DEFAULT NULL,
  `mark` varchar(1024) DEFAULT NULL,
  `tbwidth` int(16) DEFAULT NULL,
  `tbheight` int(16) DEFAULT NULL,
  `lineheight` int(16) DEFAULT NULL,
  `fontsize` int(16) DEFAULT NULL,
  `tfontsize` int(16) DEFAULT NULL,
  `tbmarginbottom` int(16) DEFAULT NULL COMMENT '表格margin_bottom值',
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` VALUES (1,'auto','TEST','0x112','',800,200,20,12,20,0);
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmpusers`
--

DROP TABLE IF EXISTS `tmpusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmpusers` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL COMMENT '用户名',
  `password` varchar(64) DEFAULT NULL COMMENT '密码',
  `upbandwidth` int(16) DEFAULT NULL COMMENT '上行带宽',
  `downbandwidth` int(16) DEFAULT NULL COMMENT '下行带宽',
  `ip` varchar(255) DEFAULT NULL COMMENT 'IP',
  `duedate` datetime DEFAULT NULL COMMENT '产品结束时间',
  `operationtime` datetime DEFAULT NULL COMMENT '操作时间',
  `action` varchar(50) DEFAULT NULL COMMENT '动作',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmpusers`
--

LOCK TABLES `tmpusers` WRITE;
/*!40000 ALTER TABLE `tmpusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmpusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `totacct`
--

DROP TABLE IF EXISTS `totacct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `totacct` (
  `TotAcctId` bigint(21) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(64) NOT NULL DEFAULT '',
  `AcctDate` date NOT NULL DEFAULT '0000-00-00',
  `ConnNum` bigint(12) DEFAULT NULL,
  `ConnTotDuration` bigint(12) DEFAULT NULL,
  `ConnMaxDuration` bigint(12) DEFAULT NULL,
  `ConnMinDuration` bigint(12) DEFAULT NULL,
  `InputOctets` bigint(12) DEFAULT NULL,
  `OutputOctets` bigint(12) DEFAULT NULL,
  `NASIPAddress` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`TotAcctId`),
  KEY `UserName` (`UserName`),
  KEY `AcctDate` (`AcctDate`),
  KEY `UserOnDate` (`UserName`,`AcctDate`),
  KEY `NASIPAddress` (`NASIPAddress`),
  KEY `NASIPAddressOnDate` (`AcctDate`,`NASIPAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `totacct`
--

LOCK TABLES `totacct` WRITE;
/*!40000 ALTER TABLE `totacct` DISABLE KEYS */;
/*!40000 ALTER TABLE `totacct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userattribute`
--

DROP TABLE IF EXISTS `userattribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userattribute` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned NOT NULL,
  `UserName` varchar(256) NOT NULL,
  `orderID` int(16) unsigned NOT NULL COMMENT '当前使用的订单',
  `status` int(2) unsigned NOT NULL COMMENT '用户当前状态',
  `macbind` int(2) unsigned DEFAULT '0' COMMENT '是否绑定MAc 0=不绑定，1=绑定',
  `speedrule` int(2) unsigned DEFAULT '0' COMMENT '0=不启用，1=启用',
  `closing` int(2) unsigned DEFAULT '0' COMMENT '0=正常，1=表示注销',
  `onlinenum` int(4) unsigned DEFAULT NULL,
  `nasbind` int(2) unsigned DEFAULT NULL COMMENT '0=不绑定，1=红宝石',
  `stop` int(2) DEFAULT '0' COMMENT '0=正常，1=停机',
  `pause` int(2) unsigned DEFAULT '0' COMMENT '0=正常，1=停机',
  `vlanbind` int(2) unsigned DEFAULT '0' COMMENT '是否绑定VLAN 0=不绑定，1=绑定',
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`),
  KEY `userID` (`userID`),
  KEY `orderID` (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userattribute`
--

LOCK TABLES `userattribute` WRITE;
/*!40000 ALTER TABLE `userattribute` DISABLE KEYS */;
/*!40000 ALTER TABLE `userattribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userbill`
--

DROP TABLE IF EXISTS `userbill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userbill` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned DEFAULT NULL,
  `type` varchar(256) DEFAULT NULL,
  `money` float unsigned DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `remark` varchar(1024) DEFAULT NULL,
  `check` int(11) DEFAULT '0' COMMENT '0 未对账 1已对账 默认为0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userbill`
--

LOCK TABLES `userbill` WRITE;
/*!40000 ALTER TABLE `userbill` DISABLE KEYS */;
/*!40000 ALTER TABLE `userbill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usergroup`
--

DROP TABLE IF EXISTS `usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroup` (
  `UserName` varchar(64) NOT NULL DEFAULT '',
  `GroupName` varchar(64) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT '1',
  KEY `UserName` (`UserName`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usergroup`
--

LOCK TABLES `usergroup` WRITE;
/*!40000 ALTER TABLE `usergroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `usergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userinfo` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `UserName` varchar(256) NOT NULL,
  `account` varchar(256) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `projectID` int(16) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `cardid` varchar(256) DEFAULT NULL,
  `workphone` varchar(256) DEFAULT NULL,
  `homephone` varchar(256) DEFAULT NULL,
  `mobile` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `money` int(16) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `closedatetime` datetime DEFAULT NULL,
  `MAC` varchar(256) NOT NULL,
  `NAS_IP` varchar(50) DEFAULT NULL,
  `gradeID` int(4) DEFAULT NULL,
  `zjry` varchar(256) DEFAULT NULL,
  `receipt` varchar(256) DEFAULT NULL,
  `remark` varchar(256) DEFAULT '',
  `Mname` varchar(256) DEFAULT NULL,
  `VLAN` varchar(1024) DEFAULT '',
  `sex` varchar(6) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `onlinetime` int(16) DEFAULT NULL,
  `checkout` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UserName` (`UserName`(255)),
  KEY `ID` (`ID`),
  KEY `projectID` (`projectID`),
  KEY `name` (`name`),
  KEY `money` (`money`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userinfo`
--

LOCK TABLES `userinfo` WRITE;
/*!40000 ALTER TABLE `userinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `userinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userlog`
--

DROP TABLE IF EXISTS `userlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userlog` (
  `ID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned NOT NULL,
  `type` varchar(256) DEFAULT NULL,
  `adddatetime` datetime DEFAULT NULL,
  `content` varchar(1024) DEFAULT NULL,
  `operator` varchar(256) DEFAULT NULL,
  `name` varchar(256) DEFAULT '',
  `account` varchar(256) DEFAULT '',
  `projectID` int(16) DEFAULT NULL,
  `money` varchar(256) DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userlog`
--

LOCK TABLES `userlog` WRITE;
/*!40000 ALTER TABLE `userlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `userlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userrun`
--

DROP TABLE IF EXISTS `userrun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userrun` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(16) unsigned NOT NULL,
  `orderID` int(16) NOT NULL,
  `begindatetime` datetime DEFAULT NULL,
  `enddatetime` datetime DEFAULT NULL,
  `orderenddatetime` datetime DEFAULT NULL,
  `stopdatetime` datetime DEFAULT '0000-00-00 00:00:00',
  `restoredatetime` datetime DEFAULT '0000-00-00 00:00:00',
  `stats` varchar(256) DEFAULT NULL,
  `status` int(16) unsigned DEFAULT NULL,
  `balance` float DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`),
  KEY `userID` (`userID`),
  KEY `orderID` (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userrun`
--

LOCK TABLES `userrun` WRITE;
/*!40000 ALTER TABLE `userrun` DISABLE KEYS */;
/*!40000 ALTER TABLE `userrun` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-13 15:11:30
