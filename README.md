### Overview
Current project parses rbc.ru news feed, 15 items at a time.
If news article, is a landing page, or doesn't contain some predefined data, it won't be saved.
Articles are identified by external key, so the same article won't be saved twice.

Project uses goutte component for DOM parsing.
Console component for interaction with CLI.
Routing component for routing.
Twig for rendering templates.
### First-time project initialization

# Environment variables setup
cp ./parser/.env.dist ./parser/.env

Populate varialbes in .env file

# Invoke environment_setup.sh script
./environment_setup.sh

# Invoke schema setup after docker up and running
docker-compose run parser_php bin/console doctrine:schema:create

### To Invoke parsing script
cd ./parser
docker-compose run parser_php bin/console app:parse_feeds

### Access to web interface
http://localhost:8100

### To invoke cs-fixer
docker-compose run parser_php php-cs-fixer fix -vvv

### To invoke phpunit tests
docker-compose run parser_php bin/phpunit
