﻿# Host: localhost  (Version: 5.7.26)
# Date: 2021-07-19 14:29:01
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "rf_common_menu"
#

DROP TABLE IF EXISTS `rf_common_menu`;
CREATE TABLE `rf_common_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `app_id` varchar(20) NOT NULL DEFAULT '' COMMENT '应用',
  `addons_name` varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称',
  `is_addon` tinyint(1) unsigned DEFAULT '0' COMMENT '是否插件',
  `cate_id` tinyint(5) unsigned DEFAULT '0' COMMENT '分类id',
  `pid` int(50) unsigned DEFAULT '0' COMMENT '上级id',
  `url` varchar(100) DEFAULT '' COMMENT '路由',
  `icon` varchar(50) DEFAULT '' COMMENT '样式',
  `level` tinyint(1) unsigned DEFAULT '1' COMMENT '级别',
  `dev` tinyint(4) unsigned DEFAULT '0' COMMENT '开发者[0:都可见;开发模式可见]',
  `sort` int(5) DEFAULT '999' COMMENT '排序',
  `params` json DEFAULT NULL COMMENT '参数',
  `tree` varchar(300) NOT NULL DEFAULT '' COMMENT '树',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COMMENT='系统_菜单导航表';

#
# Data for table "rf_common_menu"
#

INSERT INTO `rf_common_menu` VALUES (1,'网站设置','backend','',0,2,0,'/common/config/edit-all','fa-cog',1,0,0,X'5B5D','tr_0 ',1,1572328434,1572417529),(2,'用户权限','backend','',0,2,0,'backendMemberAuth','fa-user-secret',1,0,2,X'5B5D','tr_0 ',1,1572328496,1572843384),(3,'后台用户','backend','',0,2,2,'/base/member/index','',2,0,999,X'5B5D','tr_0 tr_2 ',1,1572328535,1572560511),(4,'角色管理','backend','',0,2,2,'/base/auth-role/index','',2,0,999,X'5B5D','tr_0 tr_2 ',1,1572329079,1582377757),(5,'权限管理','backend','',0,2,2,'/base/auth-item/index','',2,1,999,X'5B5D','tr_0 tr_2 ',1,1572329162,1582377769),(6,'系统功能','backend','',0,2,0,'commonFunction','fa-list-ul',1,0,1,X'5B5D','tr_0 ',1,1572329735,1572843337),(7,'系统基础','backend','',0,2,0,'commonTool','fa-microchip',1,0,5,X'5B5D','tr_0 ',1,1572329902,1575548007),(8,'应用管理','backend','',0,2,0,'/common/addons/index','fa-plug',2,0,4,X'5B5D','tr_0 ',1,1572330081,1575548006),(9,'配置管理','backend','',0,2,6,'/common/config/index','',2,1,2,X'5B5D','tr_0 tr_6 ',1,1572330103,1572560457),(10,'开放授权','backend','',0,2,0,'/oauth2/client/index','fa-square',1,0,3,X'5B5D','tr_0 ',1,1572330249,1573021721),(11,'资源文件','backend','',0,2,7,'/common/attachment/index','',2,0,2,X'5B5D','tr_0 tr_7 ',1,1572330586,1572330797),(12,'日志记录','backend','',0,2,7,'commonLog','',2,0,3,X'5B5D','tr_0 tr_7 ',1,1572330619,1572330799),(13,'行为日志','backend','',0,2,12,'/common/action-log/index','',3,0,0,X'5B5D','tr_0 tr_7 tr_12 ',1,1572330641,1572330724),(14,'短信日志','backend','',0,2,12,'/common/sms-log/index','',3,0,1,X'5B5D','tr_0 tr_7 tr_12 ',1,1572330658,1572330725),(15,'支付日志','backend','',0,2,12,'/common/pay-log/index','',3,0,2,X'5B5D','tr_0 tr_7 tr_12 ',1,1572330673,1572330726),(16,'全局日志','backend','',0,2,12,'/common/log/index','',3,0,3,X'5B5D','tr_0 tr_7 tr_12 ',1,1572330707,1572330727),(17,'黑名单','backend','',0,2,7,'/common/ip-blacklist/index','fa-shield',2,0,4,X'5B5D','tr_0 tr_7 ',1,1572330752,1587734338),(18,'行为监控','backend','',0,2,7,'/common/action-behavior/index','',2,1,5,X'5B5D','tr_0 tr_7 ',1,1572330768,1572560378),(19,'系统信息','backend','',0,2,7,'/common/system/info','',2,0,6,X'5B5D','tr_0 tr_7 ',1,1572330788,1572560299),(20,'会员管理','backend','',0,1,0,'indexMember','fa-user',1,0,999,X'5B5D','tr_0 ',1,1572331063,1572331063),(21,'会员信息','backend','',0,1,20,'/member/member/index','',2,0,0,X'5B5D','tr_0 tr_20 ',1,1572331081,1575548156),(22,'第三方授权','backend','',0,1,20,'/member/auth/index','',2,0,2,X'5B5D','tr_0 tr_20 ',1,1572331105,1575548159),(23,'会员级别','backend','',0,1,20,'/member/level/index','',2,0,1,X'5B5D','tr_0 tr_20 ',1,1572331117,1575548180),(24,'菜单管理','backend','',0,2,6,'/common/menu/index','',2,1,1,X'5B5D','tr_0 tr_6 ',1,1572408688,1572560447),(29,'公告管理','backend','',0,2,6,'/base/notify-announce/index','',2,0,3,X'5B5D','tr_0 tr_6 ',1,1572473709,1572473862),(30,'私信管理','backend','',0,2,6,'/base/notify-message/index','',2,0,4,X'5B5D','tr_0 tr_6 ',1,1572473732,1572473863),(31,'提醒设置','backend','',0,2,6,'/base/notify-subscription-config/index','',2,0,5,X'5B5D','tr_0 tr_6 ',1,1572473760,1572473864),(32,'会员日志','backend','',0,1,0,'memberCreditsLog','fa-file-text',1,0,999,X'5B5D','tr_0 ',1,1575548068,1577974475),(33,'积分日志','backend','',0,1,32,'/member/credits-log/integral','',2,0,2,X'5B5D','tr_0 tr_32 ',1,1575548100,1575706094),(34,'消费日志','backend','',0,1,32,'/member/credits-log/index','',2,0,1,X'5B5D','tr_0 tr_32 ',1,1575548113,1575706094),(35,'余额日志','backend','',0,1,32,'/member/credits-log/money','',2,0,0,X'5B5D','tr_0 tr_32 ',1,1575706113,1575716988),(36,'充值配置','backend','',0,1,0,'/member/recharge-config/index','fa-paypal',1,0,999,X'5B5D','tr_0 ',1,1576319527,1576319527),(37,'会员管理','merchant','',0,4,0,'indexMember','fa-user',1,0,0,X'5B5D','tr_0 ',1,1577672865,1577699157),(38,'会员信息','merchant','',0,4,37,'/member/member/index','',2,0,999,X'5B5D','tr_0 tr_37 ',1,1577672881,1577672881),(39,'会员级别','merchant','',0,4,37,'/member/level/index','',2,0,999,X'5B5D','tr_0 tr_37 ',1,1577672900,1577672900),(40,'第三方授权','merchant','',0,4,37,'/member/auth/index','',2,0,999,X'5B5D','tr_0 tr_37 ',1,1577672925,1577672925),(41,'充值配置','merchant','',0,4,0,'/member/recharge-config/index','fa-paypal',1,0,2,X'5B5D','tr_0 ',1,1577672961,1577699158),(42,'会员日志','merchant','',0,4,0,'memberCreditsLog','fa-file-text',1,0,1,X'5B5D','tr_0 ',1,1577699197,1577699202),(43,'余额日志','merchant','',0,4,42,'/member/credits-log/money','',2,0,999,X'5B5D','tr_0 tr_42 ',1,1577699219,1577699219),(44,'消费日志','merchant','',0,4,42,'/member/credits-log/index','',2,0,999,X'5B5D','tr_0 tr_42 ',1,1577699235,1577699235),(45,'积分日志','merchant','',0,4,42,'/member/credits-log/integral','',2,0,999,X'5B5D','tr_0 tr_42 ',1,1577699248,1577699248),(60,'华为物联网','backend','',0,8,0,'consoleHuawei','fa-product-hunt',1,0,1,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1615874731,1626396637),(61,'产品','backend','',0,8,60,'/console-huawei/product/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_60 ',1,1615874747,1618278115),(62,'设备','backend','',0,8,60,'/console-huawei/device/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_60 ',1,1615874762,1618278126),(63,'规则引擎','backend','',0,8,0,'indexRule','fa-gavel',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1616049086,1616049328),(64,'场景联动','backend','',0,8,63,'/console-rule/simple/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_63 ',1,1616049319,1617343825),(65,'监控运维','backend','',0,8,0,'consoleLk','fa-television',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1616049436,1617343849),(66,'告警中心','backend','',0,8,65,'/console-lk/alarm/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_65 ',1,1616049477,1617343866),(67,'实时监控','backend','',0,8,65,'/console-lk/monitor/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_65 ',1,1616049681,1617343879),(73,'规则','backend','',0,8,63,'/console-rule/rule/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_63 ',1,1616050752,1617343833),(74,'规则报警','backend','',0,8,63,'/console-rule/warn/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_63 ',1,1616050783,1617343839),(76,'项目管理','backend','',0,9,0,'indexProject','fa-sitemap',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1617341559,1617341559),(77,'项目','backend','',0,9,76,'/monitor-project/item/index','',2,0,1,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_76 ',1,1617341583,1625036694),(78,'房屋','backend','',0,9,76,'/monitor-project/house/index','',2,0,2,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_76 ',1,1617341601,1625036696),(79,'分组','backend','',0,9,76,'/monitor-project/ground/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_76 ',1,1617341620,1617342857),(80,'规则引擎','backend','',0,9,0,'monitorRule','fa-gavel',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1617342121,1617343343),(81,'危房报警规则','backend','',0,9,80,'/monitor-rule/simple/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_80 ',1,1617342144,1620349085),(84,'监控运维','backend','',0,9,0,'monitorLk','fa-television',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1617342386,1618814698),(85,'实时监测','backend','',0,9,84,'/monitor-lk/monitor/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_84 ',1,1617342975,1617343905),(86,'告警中心','backend','',0,9,84,'/monitor-lk/alarm/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_84 ',0,1617343035,1620267251),(87,'自动运维','backend','',0,9,0,'monitorCreate','fa-play-circle',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1617343164,1617343640),(89,'场景联动','backend','',0,9,87,'/monitor-create/simple/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_87 ',1,1617343223,1617343223),(90,'任务管理','backend','',0,9,0,'monitorService','fa-calendar-minus-o',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1617343258,1620373310),(91,'任务','backend','',0,9,90,'/monitor-service/service/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_90 ',1,1617343285,1617343285),(93,'员工管理','backend','',0,1,0,'indexWorker','fa-male',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1618191823,1618191840),(94,'员工信息','backend','',0,1,93,'/worker/worker/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_93 ',1,1618191898,1618195576),(95,'角色管理','backend','',0,1,93,'/company-base/auth-role/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_93 ',1,1618192002,1618194819),(96,'权限管理','backend','',0,1,93,'/company-base/auth-item/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_93 ',1,1618192042,1618194825),(97,'员工日志','backend','',0,1,0,'WorkCreditsLog','fa-file-text',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1618192170,1618192192),(98,'阿里物联网','backend','',0,8,0,'consoleAli','fa-product-hunt',1,0,2,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1618213465,1626396638),(99,'产品','backend','',0,8,98,'/console-ali/product/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_98 ',1,1618213488,1618213488),(100,'设备','backend','',0,8,98,'/console-ali/device/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_98 ',1,1618213510,1618213510),(101,'日志服务','backend','',0,8,60,'/console-huawei/log/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_60 ',1,1618280630,1618280630),(102,'日志服务','backend','',0,8,98,'/console-ali/log/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_98 ',1,1619052268,1619052268),(103,'基础操作','merchant','',0,10,0,'base','',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1619578283,1619578283),(104,'个人信息','merchant','',0,10,103,'/v1/member/member/view','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_103 ',1,1619578335,1619578335),(105,'角色管理','backend','',0,1,20,'/member-base/auth-role/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_20 ',1,1619589793,1619589793),(106,'权限管理','backend','',0,1,20,'/member-base/auth-item/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_20 ',1,1619589811,1619589811),(107,'分组','backend','',0,1,20,'/member-base/ground/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_20 ',1,1619595314,1619595314),(108,'日志服务','backend','',0,9,0,'monitorLog','fa-calendar',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1619763471,1620373332),(109,'数据变更日志','backend','',0,9,108,'/monitor-log/value/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_108 ',1,1619763505,1619763505),(110,'数据管理','backend','',0,9,0,'monitorData','fa-database',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1620267505,1620267520),(111,'审核通过','backend','',0,9,110,'/monitor-data/value/index','',2,0,999,X'5B7B226B6579223A20227374617465222C202276616C7565223A202231227D5D','tr_0 tr_110 ',1,1620267585,1620267820),(112,'回收站','backend','',0,9,110,'/monitor-data/value/recycle','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_110 ',1,1620267599,1620267691),(113,'待审核','backend','',0,9,110,'/monitor-data/value/index','',2,0,888,X'5B7B226B6579223A20227374617465222C202276616C7565223A202232227D5D','tr_0 tr_110 ',1,1620267792,1620267792),(114,'审核驳回','backend','',0,9,110,'/monitor-data/value/index','',2,0,999,X'5B7B226B6579223A20227374617465222C202276616C7565223A202230227D5D','tr_0 tr_110 ',1,1620267862,1620267862),(116,'监测点报警日志','backend','',0,9,108,'/monitor-log/point-simple/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_108 ',1,1620368614,1620368873),(117,'规则报警日志','backend','',0,9,108,'/monitor-log/simple/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_108 ',1,1620368720,1620368720),(118,'首页','backend','',0,10,0,'/sim/home/index','fa-home',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1624414008,1624438558),(119,'资产管理','backend','',0,10,0,'indexAssets','fa-list',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1624414085,1624438578),(120,'卡列表','backend','',0,10,119,'/sim-list/card/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_119 ',1,1624414173,1624415728),(121,'分类管理','backend','',0,10,119,'/sim/card-cate/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_119 ',0,1624414193,1624438868),(122,'日志管理','backend','',0,10,0,'/sim/log/index','fa-calendar',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',0,1624414273,1624438863),(123,'续费管理','backend','',0,10,0,'indexRenewal','fa-money',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1624414325,1624438813),(124,'续费','backend','',0,10,123,'/sim-renewal/card/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_123 ',1,1624414346,1624434985),(125,'标签','backend','',0,10,119,'/sim/tag/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_119 ',0,1624414434,1624438868),(126,'续费记录','backend','',0,10,123,'/sim-renewal/log/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_123 ',1,1624434970,1624435222),(127,'监测首页','backend','',0,9,0,'/monitor-main/site/index','fa-tachometer',1,0,0,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1624849688,1624850089),(128,'报警管理','backend','',0,9,76,'/monitor-project/warn/index','',2,0,3,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_76 ',1,1625036684,1625036697),(129,'提醒管理','backend','',0,9,76,'/monitor-project/bell/index','',2,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 tr_76 ',1,1625190753,1625190753),(130,'监测异常','backend','',0,1,0,'/monitor-notify/remind','fa-bell',1,0,999,X'5B7B226B6579223A2022222C202276616C7565223A2022227D5D','tr_0 ',1,1626316834,1626316834);
