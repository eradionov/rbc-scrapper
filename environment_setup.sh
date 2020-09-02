#!/bin/bash

date
echo -e "Building docker..."

cd ./parser
docker-compose build

date
echo -e "Composer install..."

docker-compose run composer install

date
echo -e "Starting docker..."

docker-compose up