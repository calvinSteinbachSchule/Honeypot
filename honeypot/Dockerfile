FROM php:8.5-apache
RUN docker-php-ext-install pdo_mysql && a2enmod remoteip headers
COPY docker/apache-honeypot.conf /etc/apache2/conf-available/honeypot.conf
RUN a2enconf honeypot
COPY docker/php.ini /usr/local/etc/php/conf.d/honeypot.ini
