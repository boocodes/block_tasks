#!/bin/bash

source .env

scp -r . $SERVER_USER@$SERVER_HOST:~/task6

ssh $SERVER_USER@$SERVER_HOST << EOF
    cd $SERVER_PATH
    docker compose down
    docker compose build
    docker compose up -d
    docker exec -i task6-db mysql -uroot -proot task6 < migrate.sql
    curl http://localhost/alive
EOF