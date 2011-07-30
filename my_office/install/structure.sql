
-- --------------------------------------------------------

--
-- 表的结构 `ecm_channel`
--

DROP TABLE IF EXISTS `ecm_channel`;
CREATE TABLE `ecm_channel` (
  `channel_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `component` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sort` int(4) NOT NULL DEFAULT '0',
  `user_id` smallint(5) NOT NULL,
  `path` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_diary`
--

DROP TABLE IF EXISTS `ecm_diary`;
CREATE TABLE `ecm_diary` (
  `diary_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `title` varchar(80) NOT NULL,
  `content` text,
  `user_id` int(4) DEFAULT '0',
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `mood` varchar(10) DEFAULT NULL COMMENT '心情',
  `weather` varchar(10) DEFAULT NULL COMMENT '天气',
  `diary_date` date DEFAULT NULL,
  PRIMARY KEY (`diary_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_doc`
--

DROP TABLE IF EXISTS `ecm_doc`;
CREATE TABLE `ecm_doc` (
  `doc_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `title` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `copyfrom` varchar(200) NOT NULL COMMENT '来源',
  `keyword` varchar(200) NOT NULL,
  `hit` int(11) NOT NULL,
  PRIMARY KEY (`doc_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_site`
--

DROP TABLE IF EXISTS `ecm_site`;
CREATE TABLE `ecm_site` (
  `site_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `title` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `url` varchar(200) NOT NULL COMMENT '来源',
  PRIMARY KEY (`site_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_user`
--

DROP TABLE IF EXISTS `ecm_user`;
CREATE TABLE `ecm_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '用户名',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `grade` int(11) DEFAULT NULL COMMENT '级别:1超级管理员/2管理员/3普通用户',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `gender` int(11) DEFAULT NULL COMMENT '性别:1男/2女',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `url` varchar(20) DEFAULT NULL COMMENT '网址',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_address`
--

DROP TABLE IF EXISTS `ecm_address`;
CREATE TABLE `ecm_address` (
  `address_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `name` varchar(80) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `qq` varchar(80) DEFAULT NULL,
  `msn` varchar(80) DEFAULT NULL,
  `mobile` varchar(80) DEFAULT NULL,
  `office_phone` varchar(80) DEFAULT NULL,
  `home_phone` varchar(80) DEFAULT NULL,
  `remarks` varchar(80) DEFAULT NULL,
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`address_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_book`
--

DROP TABLE IF EXISTS `ecm_book`;
CREATE TABLE `ecm_book` (
  `book_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `item` smallint(5) unsigned NOT NULL DEFAULT '0',
  `item_txt` varchar(10) DEFAULT NULL,
  `remark` varchar(50) NOT NULL DEFAULT '',
  `ccy` char(3) NOT NULL DEFAULT 'CNY',
  `amount` float(9,2) NOT NULL DEFAULT '0.00',
  `net` float(10,2) NOT NULL,
  `otype` enum('IN','OUT') NOT NULL,
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `user_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`book_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_related`
--

DROP TABLE IF EXISTS `ecm_related`;
CREATE TABLE IF NOT EXISTS `ecm_related` (
  `related_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_type` enum('address','book','channel','diary','doc','site','user') NOT NULL,
  `t_type` enum('address','book','channel','diary','doc','site','user') NOT NULL,
  `s_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`related_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
