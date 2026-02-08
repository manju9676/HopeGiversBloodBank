FROM php:8.2-apache
RUN docker-php-ext-install mysqli
COPY . /var/www/html/
RUN echo "<?php http_response_code(200); echo 'OK'; ?>" > /var/www/html/health.php
EXPOSE 80
