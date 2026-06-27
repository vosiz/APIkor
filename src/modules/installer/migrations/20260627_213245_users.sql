CREATE TABLE `users` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `active`     TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `apiv`       TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `manual`     TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `email`      VARCHAR(128)     NOT NULL,
    `password`   VARCHAR(255)     NOT NULL,
    `nick`       VARCHAR(64)      NOT NULL,
    `valid`      TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `role_id`    INT UNSIGNED         NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_email` (`email`),
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
