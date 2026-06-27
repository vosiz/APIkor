CREATE TABLE `roles` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(32)  NOT NULL,
    `value`       VARCHAR(64)  NOT NULL,
    `description` VARCHAR(128)     NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(64)  NOT NULL,
    `value`       VARCHAR(64)  NOT NULL,
    `description` VARCHAR(128)     NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_permissions` (
    `role_id`       INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`)       REFERENCES `roles`       (`id`),
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`name`, `value`, `description`) VALUES
('superadmin', 'Super Administrator', NULL),
('admin',      'Administrator',       NULL),
('user',       'User',                NULL);

INSERT INTO `permissions` (`name`, `value`, `description`) VALUES
('*',       'Can do anything',  'Can do anything'),
('stats.*', 'Statistics',       NULL);

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1);
