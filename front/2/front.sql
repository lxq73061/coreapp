--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `module_front_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) NULL COMMENT '用户名',
  `password` varchar(20) NULL COMMENT '密码',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `module_front_user` (`user_id`, `username`, `password`) VALUES
(1, 'core', '1234'),
(2, 'mvc', '123');
