CREATE TABLE `apikor_logs` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid`      VARCHAR(32)  NOT NULL,
    `timestamp` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `origin`    TEXT             NULL,
    `message`   TEXT         NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `apikor_comments` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `table_name` VARCHAR(64)  NOT NULL,
    `record_id`  INT UNSIGNED NOT NULL,
    `value`      TEXT         NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `apikor_settings` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(64)  NOT NULL,
    `type`        VARCHAR(16)  NOT NULL,
    `value`       TEXT         NOT NULL,
    `description` TEXT             NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
