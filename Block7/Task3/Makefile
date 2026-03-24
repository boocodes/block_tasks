#.PHONY: up down migrate seed test lint

up:
	docker compose up -d
down:
	docker compose down
migrate:
	docker exec -i task7-db mysql -uroot -proot task7 > migrate.sql
seed:
	docker exec -i task7-db mysql -uroot -proot task7 > seed.sql
test:
	docker exec task7-app php -l /var/www/html/index.php
lint:
	docker exec task7-app find /var/www/html -name "*.php" -exec php -l {}\;
build-dev:
	docker compose build --build-arg ENVIRONMENT=dev
build-prod:
	docker compose build --build-arg ENVIRONMENT=prod
shell:
	docker exec -it task7-app /bin/bash