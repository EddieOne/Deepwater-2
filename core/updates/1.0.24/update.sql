CREATE TABLE IF NOT EXISTS `plugins` ( `pid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'plugin id', `name` varchar(100) NOT NULL COMMENT 'name from plugin config', `name_slug` varchar(100) NOT NULL COMMENT 'the directory that plugin resides in', `version` varchar(10) NOT NULL COMMENT 'version of plugin from config', `installed` tinyint(2) unsigned NOT NULL COMMENT '1 = installed, 0 = not installed', `date` int(13) unsigned NOT NULL COMMENT 'unix time installed', PRIMARY KEY (`pid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
INSERT INTO `nodes` (`nid`, `sid`, `vid`, `user_id`, `status`, `created`, `changed`, `type`, `alias_route`, `alias`, `options`, `title`, `description`, `keywords`) VALUES ('23', '1', '0.001', '1', '1', '1425773388', '1425773388', 'core', 'admin', '/admin/plugin/', '', 'Plugins', '', '');
INSERT INTO `nodes_roles` (`pid`, `nid`, `rid`, `auth`) VALUES (NULL, '23', '1', 'rw');
INSERT INTO `nodes` (`nid`, `sid`, `vid`, `user_id`, `status`, `created`, `changed`, `type`, `alias_route`, `alias`, `options`, `title`, `description`, `keywords`) VALUES ('24', '1', '0.001', '1', '1', '1425773388', '1425773388', 'core', 'admin', '/admin/plugin/do/{action}/{name_slug}/', '', 'Plugin Action', '', '');
INSERT INTO `nodes_roles` (`pid`, `nid`, `rid`, `auth`) VALUES (NULL, '24', '1', 'rw');