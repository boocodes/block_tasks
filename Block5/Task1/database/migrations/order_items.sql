CREATE TABLE IF NOT EXISTS order_items
(
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id   INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    qty        INT UNSIGNED NOT NULL CHECK (qty > 0),
    price      DECIMAL(10, 2) UNSIGNED NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)

);