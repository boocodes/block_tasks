#!/bin/bash

set -e

docker compose down

docker compose build --no-cache

docker compose up -d

docker exec -i task7-db mysql -uroot -proot task7 < migrate.sql || true

curl -f http://localhost/alive
