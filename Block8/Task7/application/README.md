# CRUD application with webhooks integration

## Description 

Application allow to create Projects -> Tasks -> Comments
Integrated authorization

## Setup 

git clone 
cd 
docker-compose build --no-cache --progress=plain app
docker-compose up -d


### Webhook
Webhook working with queue from redis. At jobs is acting Webhook attempts (for saving attempts - failed response or success), webhook processeds (for saving finaly deliveried webhooks)