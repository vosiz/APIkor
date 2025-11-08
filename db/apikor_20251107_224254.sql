
ALTER TABLE `apikor_app_users` CHANGE `password` `password` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL;

UPDATE `apikor_app_users` 
SET 
`nick` = 'TempAdmin', 
`email` = 'youremail@example.com', 
`password` = 'f581ef9e2a2907d488f4b0230ed97a880a51e699ea68998e384497b2df4825b7' 
WHERE `apikor_app_users`.`id` = 1;

INSERT INTO `apikor_enum_user_roles` (`id`, `active`, `name`, `value`, `note`) 
VALUES 
(2,1,'admin',666,'most permissions'),
(3,1,'tool-admin',600,'external permissions'),
(4,1,'moderator',512,'moderation permissions'),
(5,1,'user',256,'user permissions'),
(6,1,'visitor',16,'nearly no permissions'),
(7,1,'nobody',0,'no permissions');

UPDATE `apikor_enum_user_roles` 
SET 
`value` = 9000, 
`note` = 'all permissions'
WHERE `apikor_enum_user_roles`.`id` = 1;