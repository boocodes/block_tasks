ARG ENVIRONMENT=dev

FROM php:8.5.4-fpm AS dev

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    zip \
    gd \
    && pecl install redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN if [ -f composer.json ]; then \
    composer install --optimize-autoloader --prefer-dist --no-interaction --no-scripts; \
    fi

COPY . .
RUN cp .env.example .env

RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap && \
    chmod -R 775 storage bootstrap


RUN if [ -f artisan ]; then \
    php artisan package:discover --ansi && \
    php artisan key:generate --ansi; \
    fi




FROM php:8.5.4-fpm AS prod

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    zip \
    gd \
    opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN if [ -f composer.json ]; then \
    composer install --optimize-autoloader --prefer-dist --no-interaction --no-scripts; \
    fi

COPY . .
RUN cp .env.example .env


RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap && \
    chmod -R 775 storage bootstrap

RUN if [-f artisan]; then \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache; \
    fi


RUN echo 'opcache.enable=1' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    echo 'opcache.memory_consumption=128' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    echo 'opcache.max_accelerated_files=10000' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    echo 'opcache.revalidated_freq=0' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN rm -f .env.example

FROM ${ENVIRONMENT:-dev} AS final


RUN rm -f .env.example

CMD ["php-fpm"] 