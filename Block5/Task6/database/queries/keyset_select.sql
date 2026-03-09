SELECT id
FROM orders
WHERE user_id = :hook_id
  AND id < :last_id
ORDER BY id DESC LIMIT :limit_value