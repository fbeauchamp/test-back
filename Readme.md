# backend demonstration

## Installation

### classic
install PHP, symfony, composer, mysql or postgresql or sqlite

setup your .env.local with a DATABASE_URL
run

    composer install
    bin/console doctrine:migrations:migrate
    symfony serve

### Docker
install docker

go to `./docker` directory 

run `docker-compose up` and wait for dependency installing.

You should not have a service already using the 3306 or the 80 port on your machine.

## Documentation

 `/api/doc` serve a openapi 2.0 documentation
 
 a dump of this json is in the `docs` repository