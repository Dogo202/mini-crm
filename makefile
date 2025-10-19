.PHONY: up down build init seed key link assets test logs sh

up:
\tdocker compose up -d

down:
\tdocker compose down

build:
\tdocker compose build app

init: up
\tdocker compose exec app composer install
\tdocker compose exec app php artisan key:generate
\tdocker compose exec app php artisan migrate --seed
\tdocker compose exec app php artisan storage:link
\tmake assets
\tdocker compose exec app php artisan optimize:clear

assets:
\tdocker compose run --rm node npm ci
\tdocker compose run --rm node npm run build

seed:
\tdocker compose exec app php artisan migrate:fresh --seed

test:
\tdocker compose exec app php artisan test

logs:
\tdocker compose logs -f nginx

sh:
\tdocker compose exec app bash
