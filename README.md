# installation

``` bash

git clone git@github.com:grigorovaaa/mintos-back-home-task.git

cd mintos-back-home-task

docker-compose up -d

docker-compose exec php-fpm composer install

docker-compose exec php-fpm ./bin/console --no-interaction doctrine:migrations:migrate

docker-compose exec php-fpm ./bin/console app:import-the-register-feed

```