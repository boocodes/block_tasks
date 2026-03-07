CREATE TABLE IF NOT EXISTS user
(
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email      varchar(30) NOT NULL UNIQUE,
    name       varchar(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);