#!/bin/bash

set -e

docker compose down

docker compose build --no-cache

docker compose up -d

docker exec -i task6-db mysql -uroot -proot task6 < migrate.sql || true

curl -f http://localhost/alive
