--
-- 表的结构 `ecm_channel`
--
DROP TABLE IF EXISTS `ecm_channel`;
CREATE TABLE `ecm_channel` (
  `channel_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `component` varchar(20) CHARACTER SET utf8 NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sort` int(4) NOT NULL DEFAULT '0',
  `user_id` smallint(5) NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_diary`
--
DROP TABLE IF EXISTS `ecm_diary`;
CREATE TABLE `ecm_diary` (
  `diary_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `title` varchar(80) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `mood` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '心情',
  `weather` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '天气',
  `diary_date` date NOT NULL,
  PRIMARY KEY (`diary_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_doc`
--
DROP TABLE IF EXISTS `ecm_doc`;
CREATE TABLE `ecm_doc` (
  `doc_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `title` varchar(80) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `copyfrom` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '来源',
  `keyword` varchar(200) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`doc_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_site`
--
DROP TABLE IF EXISTS `ecm_site`;
CREATE TABLE `ecm_site` (
  `site_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `title` varchar(80) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `url` varchar(200) NOT NULL COMMENT '来源',
  PRIMARY KEY (`site_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_user`
--
DROP TABLE IF EXISTS `ecm_user`;
CREATE TABLE `ecm_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '用户名',
  `password` varchar(20) DEFAULT NULL COMMENT '密码',
  `grade` int(11) DEFAULT NULL COMMENT '级别:1超级管理员/2管理员/3普通用户',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `gender` int(11) DEFAULT NULL COMMENT '性别:1男/2女',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `url` varchar(20) DEFAULT NULL COMMENT '网址',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM ;
