--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `module_user_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) NULL COMMENT '用户名',
  `password` varchar(20) NULL COMMENT '密码',
  `grade` int(11) NULL COMMENT '级别:1超级管理员/2管理员/3普通用户',
  `name` varchar(20) NULL COMMENT '姓名',
  `gender` int(11) NULL COMMENT '性别:1男/2女',
  `mobile` varchar(20) NULL COMMENT '手机号',
  `email` varchar(20) NULL COMMENT '邮箱',
  `url` varchar(20) NULL COMMENT '网址',
  `remark` text NULL COMMENT '备注',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `module_user_user` (`user_id`, `username`, `password`) VALUES
(1, 'core', '1234'),
(2, 'mvc', '123');
