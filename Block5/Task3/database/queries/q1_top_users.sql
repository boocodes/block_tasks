SELECT *
FROM `orders`
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
  AND status = 'paid'
ORDER BY total_amount DESC LIMIT 20