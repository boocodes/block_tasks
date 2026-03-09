CREATE INDEX orders_created_at_index ON orders (created_at);

CREATE INDEX orders_status_index ON orders (status);


CREATE INDEX orders_user_id_index ON orders (user_id);

CREATE INDEX order_items_order_id_index ON order_items (order_id);

CREATE INDEX order_items_product_id ON order_items (product_id);

CREATE INDEX order_items_product_and_order_id_index ON order_items (product_id, order_id);

CREATE INDEX payments_order_id_index ON payments (order_id);

CREATE INDEX payments_status_index ON payments (status);

CREATE INDEX payments_created_at ON payments (created_at);

CREATE INDEX payments_created_at_and_status_index ON payments (created_at, status);

CREATE INDEX products_sku ON products (sku);

CREATE INDEX products_created_at ON products (created_at);

CREATE INDEX products_title ON products (title);

CREATE INDEX user_email_index ON user (email);

CREATE INDEX user_created_at_index ON user (created_at);

CREATE INDEX user_name_index ON user (name);

CREATE INDEX audit_log_created_at_index ON audit_log (created_at);

CREATE INDEX audit_log_entity_id_index ON audit_log (entity_id);

CREATE INDEX audit_log_action_index ON audit_log (action);