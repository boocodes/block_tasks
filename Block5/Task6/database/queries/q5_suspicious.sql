EXPLAIN ANALYZE
SELECT orders.user_id
FROM `payments` payments
         INNER JOIN `orders` orders ON payments.order_id = orders.id
         LEFT JOIN `user` user
ON orders.user_id = user.id
WHERE payments.status = 'failed'

GROUP BY orders.user_id
HAVING COUNT(*) > 3
