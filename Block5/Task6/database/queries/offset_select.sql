SELECT id
FROM `orders`
WHERE user_id = :hook_id LIMIT :limit_value
OFFSET :offset_value;