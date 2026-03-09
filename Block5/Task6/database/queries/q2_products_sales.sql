EXPLAIN ANALYZE
SELECT orders.id,
       orders.user_id,
       orders.status,
       user.email,
       user.name,
       orders.status,
       orders.total_amount,
       orders.created_at
FROM `orders` orders
         LEFT JOIN `user` user
ON orders.user_id = user.id
WHERE orders.status = 'paid'
ORDER BY orders.created_at DESC
    LIMIT 100;

SELECT order_items.id,
       order_items.order_id,
       order_items.product_id,
       products.sku,
       products.title,
       order_items.qty,
       order_items.price,
       (order_items.qty * order_items.price)
FROM `order_items` order_items
         LEFT JOIN `products` products ON order_items.product_id = products.id
ORDER BY order_items.id;