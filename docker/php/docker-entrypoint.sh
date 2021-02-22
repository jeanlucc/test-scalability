#!/bin/sh
set -e
set -x

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
	mkdir -p var/cache var/log
	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
		>&2 echo "Waiting for Postgres to be ready..."
		until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
			sleep 1
		done
		bin/console doctrine:migrations:migrate --no-interaction
		bin/console doctrine:fixtures:load --no-interaction
	fi
fi

exec docker-php-entrypoint "$@"
