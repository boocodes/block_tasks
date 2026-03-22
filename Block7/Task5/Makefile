#.PHONY: up down migrate seed test lint

up:
	docker compose up -d
down:
	docker compose down
migrate:
	docker exec -i task5-db mysql -uroot -proot task5 > migrate.sql
seed:
	docker exec -i task5-db mysql -uroot -proot task5 > seed.sql
test:
	docker exec task5-app php -l /var/www/html/index.php
lint:
	docker exec task5-app find /var/www/html -name "*.php" -exec php -l {}\;