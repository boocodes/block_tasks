FROM php:8.5.4-fpm

COPY index.php /var/www/html/index.php

RUN echo 'FallbackResource /index.php' > /var/www/html/.htaccess

EXPOSE 80