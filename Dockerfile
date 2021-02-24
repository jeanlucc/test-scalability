FROM php:7.4-fpm-alpine

ARG php_env

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

RUN apk upgrade
RUN apk add --no-cache --virtual build-dependencies build-base bash

RUN apk add --update --no-cache --virtual .build-deps \
	$PHPIZE_DEPS postgresql-dev; \
    docker-php-ext-install pdo pdo_pgsql; \
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
		| tr ',' '\n' \
		| sort -u \
		| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
	apk del .build-deps

COPY . /app
WORKDIR /app

RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

EXPOSE 9000
