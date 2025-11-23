
CREATE TABLE `apikor_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('bool','int','string','enum') NOT NULL DEFAULT 'int',
  `name` varchar(64) NOT NULL,
  `value` varchar(256) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `apikor_settings` (`id`, `type`, `name`, `value`, `note`) 
VALUES 
(1,'bool','production','0','[Bool]\r\nThis database is production\r\n\r\n1 = yeah\r\n0 = nah');

INSERT INTO `apikor_settings` (`id`, `type`, `name`, `value`, `note`) 
VALUES 
(2, 'string', 'version', '0.0.0-unreleased', '[string]\r\nVersion of assembly\r\n\r\nDefine your own version string.');
