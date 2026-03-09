SELECT
    COUNT(*) as total_orders,
    COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid_orders,
    ROUND(COUNT(CASE WHEN status = 'paid' THEN 1 END) * 100.0 / COUNT (*)) as conversion
FROM `orders`
WHERE created_at >= :date_from
AND created_at <= :date_to;