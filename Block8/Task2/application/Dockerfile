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
    && docker-php-ext-configure gd --width-freetype --width-jpeg \
    && docker-php-ext-install -j${nproc} \
        pdo \
        pdo_mysql \
        zip \
        bcmath \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latests /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN composer install --optimize-autoloader --prefer-dist

COPY . .

RUN mkdir -p storage/framework/{cache, sessions, testing, views} storage/logs bootstrap/cache && \ chown -R www-data:www-data storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chown -R 775 storage bootstrap/cache


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
        bcmath \
        gd \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN composer install --no-dev --optimize-autoloader --prefer-dist

COPY . .

RUN if [-f artisan]; then \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache; \
    fi

RUN mkdir -p storage/framework/{cache, sessions, testing, views} storage/logs bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chomd -R 775 storage bootstrap/cache

RUN echo 'opcache.enable=1' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    echo 'opcache.memory_consumption=128' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    echo 'opcache.max_accelerated_files=10000' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    echo 'opcache.revalidated_freq=0' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

FROM ${ENVIRONMENT:-dev} AS final

EXPOSE 8000

CMD ['php-fpm']