# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# TNTMTECH — image de production (Apache + mod_php)
# Conçue pour Railway : Apache écoute sur $PORT, injecté au démarrage.
# ---------------------------------------------------------------------------

FROM php:8.4-apache

# Composer, repris depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- Extensions PHP requises par Laravel 12 ---------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libzip-dev \
        libicu-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && rm -rf /var/lib/apt/lists/*

# --- Configuration PHP pour la production -----------------------------------
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini "$PHP_INI_DIR/conf.d/zz-tntmtech.ini"

# --- Configuration Apache ----------------------------------------------------
# mod_php impose le MPM prefork. Selon la révision de l'image de base, un second
# MPM peut être actif : Apache refuse alors de démarrer avec
# « AH00534: More than one MPM loaded ». On force donc un MPM unique.
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork \
    && a2enmod rewrite headers expires remoteip \
    && a2dissite 000-default
COPY docker/vhost.conf /etc/apache2/sites-available/tntmtech.conf
COPY docker/ports.conf /etc/apache2/ports.conf
RUN a2ensite tntmtech \
    && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

# --- Dépendances PHP (couche mise en cache séparément du code) --------------
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --no-progress

# --- Code applicatif ---------------------------------------------------------
COPY . .

# Autoloader optimisé, généré une fois le code présent
RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && composer clear-cache

# --- Permissions -------------------------------------------------------------
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

# --- Démarrage ---------------------------------------------------------------
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENV PORT=8080
EXPOSE 8080

# Valide la configuration Apache dès la construction : une erreur de config
# fait échouer le build au lieu de provoquer un redémarrage en boucle.
RUN apache2ctl -t && apache2ctl -M | grep -c mpm_ | grep -qx 1

ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["apache2-foreground"]
