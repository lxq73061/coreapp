DROP TABLE IF EXISTS `user`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `grade`, `name`, `gender`, `mobile`, `email`, `url`, `remark`) VALUES
(1, 'core', '1234', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'mvc', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
