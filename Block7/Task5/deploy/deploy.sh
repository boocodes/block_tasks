#!/bin/bash

source .env

scp -r . $SERVER_USER@$SERVER_HOST:~/task5

ssh $SERVER_USER@$SERVER_HOST << EOF
    cd $SERVER_PATH
    docker compose down
    docker compose build
    docker compose up -d
    docker exec -i task5-db mysql -uroot -proot task5 < migrate.sql
    curl http://localhost/alive
EOF