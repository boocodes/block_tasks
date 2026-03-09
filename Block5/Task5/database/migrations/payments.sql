CREATE TABLE IF NOT EXISTS payments
(
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id    INT UNSIGNED NOT NULL UNIQUE,
    provider    ENUM('stripe', 'paypal', 'cash') NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'paid', 'failed') NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);