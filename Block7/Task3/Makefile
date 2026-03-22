#.PHONY: up down migrate seed test lint

up:
	docker compose up -d
down:
	docker compose down
migrate:
	docker exec -i task3-db mysql -uroot -proot task3 > migrate.sql
seed:
	docker exec -i task3-db mysql -uroot -proot task3 > seed.sql
test:
	docker exec task3-app php -l /var/www/html/index.php
lint:
	docker exec task3-app find /var/www/html -name "*.php" -exec php -l {}\;