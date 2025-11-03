CREATE TABLE `_app_tabletemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `apiv` tinyint(4) NOT NULL DEFAULT '1',
  `manual` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `_enum_tabletemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `value` int(11) NOT NULL,
  `note` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `apikor_app_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `apiv` tinyint(4) NOT NULL DEFAULT '1',
  `manual` tinyint(1) NOT NULL DEFAULT '0',
  `nick` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `apikor_app_users` (`id`, `active`, `timestamp`, `updated`, `apiv`, `manual`, `nick`, `email`, `password`) VALUES (1,1,'2024-11-27 20:22:26','2025-10-28 21:57:03',1,1,'Admin','email@example.com','');
CREATE TABLE `apikor_enum_user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `value` int(11) NOT NULL,
  `note` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `apikor_enum_user_roles` (`id`, `active`, `name`, `value`, `note`) VALUES (1,1,'superadmin',1000,'');