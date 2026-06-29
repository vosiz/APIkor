CREATE TABLE `apikor_info` (
    `current_version` VARCHAR(16)  NOT NULL DEFAULT '0.0.0',
    `installed`       DATETIME     NOT NULL DEFAULT '1970-01-01 00:00:01',
    `updated`         DATETIME     NOT NULL DEFAULT '1970-01-01 00:00:01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `apikor_info` (`current_version`, `installed`, `updated`) VALUES
('0.0.0', '1970-01-01 00:00:01', '1970-01-01 00:00:01');
