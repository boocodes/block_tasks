EXPLAIN ANALYZE
SELECT order_items.product_id, SUM(order_items.qty) as total_qty
FROM order_items
         INNER JOIN orders ON orders.id = order_items.order_id
WHERE orders.created_at >= :date_from
  AND orders.created_at <= :date_to
GROUP BY order_items.product_id
ORDER BY total_qty DESC LIMIT 50;
