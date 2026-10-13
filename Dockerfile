# syntax=docker/dockerfile:1

# ============================================================================
# Image de production du portfolio — Nginx + PHP-FPM dans un seul conteneur.
#
# Trois étapes :
#   1. assets  : compile Tailwind v4 + Vite (Node)
#   2. vendor  : installe les dépendances PHP de production (Composer)
#   3. runtime : image finale servie par Nginx, sans Node ni Composer
#
# Les caches Laravel (config, routes, vues) sont volontairement générés au
# démarrage du conteneur et non ici : les variables d'environnement de Render
# ne sont pas disponibles pendant le build.
# ============================================================================


# ---------------------------------------------------------------------------
# 1. Compilation des assets front
# ---------------------------------------------------------------------------
FROM node:20-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json .npmrc ./
RUN npm ci --no-audit --no-fund

# Tailwind v4 scanne les templates à la compilation : les vues Blade doivent
# être présentes, et le dossier des vues compilées doit exister même vide.
COPY vite.config.js ./
COPY resources ./resources
RUN mkdir -p storage/framework/views && npm run build


# ---------------------------------------------------------------------------
# 2. Dépendances PHP
# ---------------------------------------------------------------------------
FROM php:8.3-cli-alpine AS vendor

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

# Extensions nécessaires pour que `php artisan` démarre pendant le build.
RUN install-php-extensions intl zip gd bcmath pdo_pgsql

WORKDIR /app

# Les dépendances sont installées avant le code applicatif : tant que
# composer.lock ne change pas, Docker réutilise cette couche.
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --prefer-dist \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --no-autoloader

COPY . .

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && php artisan package:discover --ansi


# ---------------------------------------------------------------------------
# 3. Image finale
# ---------------------------------------------------------------------------
FROM php:8.3-fpm-alpine AS runtime

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        zip \
    && apk add --no-cache nginx supervisor gettext tzdata \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-portfolio.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-portfolio.conf
COPY docker/nginx/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint \
    && mkdir -p storage/framework/cache/data \
               storage/framework/sessions \
               storage/framework/views \
               storage/logs \
               bootstrap/cache \
               /var/lib/nginx/tmp \
               /var/lib/nginx/logs \
               /run/nginx \
    && chown -R www-data:www-data storage bootstrap/cache /var/lib/nginx /run/nginx

# Render fournit le port réel via la variable PORT ; 10000 n'est qu'un défaut.
ENV PORT=10000
EXPOSE 10000

ENTRYPOINT ["entrypoint"]
