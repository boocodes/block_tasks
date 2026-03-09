SELECT COUNT(user_id)
FROM `orders`
WHERE created_at >= :date_from
  AND created_at <= :date_to
  AND status = 'paid';