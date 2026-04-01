FROM php:8.5.4-fpm

COPY index.php /var/www/html/index.php
COPY nginx.conf /var/www/html/nginx.conf

EXPOSE 81

CMD ["php", "-S", "0.0.0.0:81"]