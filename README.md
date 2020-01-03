# installation

``` bash

git clone https://github.com/grigorovaaa/mintos-back-home-task.git

cd mintos-back-home-task

docker-compose up -d

docker-compose exec php-fpm composer install

docker-compose exec php-fpm php ./bin/console --no-interaction doctrine:migrations:migrate

docker-compose exec php-fpm php ./bin/console app:import-the-register-feed

```

# tests
``` bash

docker-compose exec php-fpm php ./bin/phpunit

```

# todo

* ssh keys have been added to the repository to make it easier for you to install
* for updating data in the feed, the command ./bin/console app:import-the-register-feed should be added to the cron
* if we need only feed displaying, without analyzing it's data, then it is better to put it in redis storage

