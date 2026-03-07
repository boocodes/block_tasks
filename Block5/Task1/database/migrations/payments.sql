CREATE TABLE IF NOT EXISTS payments
(
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id    INT UNSIGNED NOT NULL,
    provider    ENUM('stripe', 'paypal', 'cash') NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);