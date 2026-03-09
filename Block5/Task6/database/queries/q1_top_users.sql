EXPLAIN ANALYZE
SELECT
    user.id as user_id,
    user.email,
    user.name,
    COUNT(DISTINCT orders.id) as orders_count,
    SUM(orders.total_amount) as total_spend,
    MAX(orders.created_at) as last_order_date
FROM `user` user
    JOIN `orders` orders ON user.id = orders.user_id
WHERE orders.status = 'paid'
  AND orders.created_at >= NOW() - INTERVAL 30 DAY
GROUP BY user.id, user.email, user.name
ORDER BY total_spend DESC
    LIMIT 20;