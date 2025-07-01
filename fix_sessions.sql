-- Alleen de ontbrekende Laravel systeem tabellen aanmaken
USE voedselbankdag_03;

CREATE TABLE IF NOT EXISTS sessions (
    id varchar(255) NOT NULL,
    user_id bigint unsigned DEFAULT NULL,
    ip_address varchar(45) DEFAULT NULL,
    user_agent text,
    payload longtext NOT NULL,
    last_activity int NOT NULL,
    PRIMARY KEY (id),
    KEY sessions_user_id_index (user_id),
    KEY sessions_last_activity_index (last_activity)
);

CREATE TABLE IF NOT EXISTS cache (
    `key` varchar(255) NOT NULL,
    value mediumtext NOT NULL,
    expiration int NOT NULL,
    PRIMARY KEY (`key`)
);

CREATE TABLE IF NOT EXISTS cache_locks (
    `key` varchar(255) NOT NULL,
    owner varchar(255) NOT NULL,
    expiration int NOT NULL,
    PRIMARY KEY (`key`)
);

CREATE TABLE IF NOT EXISTS jobs (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    queue varchar(255) NOT NULL,
    payload longtext NOT NULL,
    attempts tinyint unsigned NOT NULL,
    reserved_at int unsigned DEFAULT NULL,
    available_at int unsigned NOT NULL,
    created_at int unsigned NOT NULL,
    PRIMARY KEY (id),
    KEY jobs_queue_index (queue)
);

SELECT 'Laravel systeem tabellen aangemaakt!' AS Message;
