-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2010 年 10 月 26 日 15:32
-- 服务器版本: 5.1.37
-- PHP 版本: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `my_office`
--

-- --------------------------------------------------------

--
-- 表的结构 `channel`
--

CREATE TABLE IF NOT EXISTS `channel` (
  `channel_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `component` varchar(20) CHARACTER SET utf8 NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sort` int(4) NOT NULL DEFAULT '0',
  `user_id` smallint(5) NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `channel`
--

INSERT INTO `channel` (`channel_id`, `parent_id`, `component`, `name`, `sort`, `user_id`, `path`) VALUES
(1, 0, '', 'abcde', 0, 1, ',00001'),
(2, 1, '', 'ddd', 0, 1, ',00001,00002'),
(3, 0, '', 'cccc', 0, 1, ',00003');

-- --------------------------------------------------------

--
-- 表的结构 `doc`
--

CREATE TABLE IF NOT EXISTS `doc` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `doc`
--


-- --------------------------------------------------------

--
-- 表的结构 `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `site_id` int(4) NOT NULL AUTO_INCREMENT,
  `typeid` int(4) NOT NULL DEFAULT '0',
  `title` varchar(80) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `create_date` date DEFAULT NULL COMMENT '创建日期',
  `create_time` time DEFAULT NULL COMMENT '创建时间',
  `update_date` date DEFAULT NULL COMMENT '修改日期',
  `update_time` time DEFAULT NULL COMMENT '修改时间',
  `url` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '来源',
  PRIMARY KEY (`site_id`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `site`
--

INSERT INTO `site` (`site_id`, `typeid`, `title`, `content`, `user_id`, `create_date`, `create_time`, `update_date`, `update_time`, `url`) VALUES
(1, 1, '百度ss', 'ddddddddddddd', 1, '2010-10-26', '15:06:36', '2010-10-26', '15:28:03', 'http://www.baid.com/'),
(2, 1, '百度ss', '百度搜索ss', 1, '2010-10-26', '15:11:56', '2010-10-26', '15:30:40', 'http://www.baid.com/');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `grade`, `name`, `gender`, `mobile`, `email`, `url`, `remark`) VALUES
(1, 'core', '1234', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'mvc', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'test', 'test', 1, '1管理', 1, '15999639032', 'lxq73061@163.com', '', '');
