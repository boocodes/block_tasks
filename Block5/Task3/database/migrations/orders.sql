CREATE TABLE IF NOT EXISTS orders
(
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id      INT   UNSIGNED NOT NULL,
    status       ENUM('new', 'paid', 'cancelled') NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    created_at   TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user (id)
);