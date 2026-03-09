CREATE TABLE IF NOT EXISTS audit_log
(
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    entity_type TEXT,
    entity_id   INT UNSIGNED,
    action      TEXT,
    meta        JSON,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);