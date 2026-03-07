CREATE TABLE IF NOT EXISTS products
(
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sku        varchar(30)    NOT NULL UNIQUE,
    title      varchar(30)    NOT NULL,
    price      DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);