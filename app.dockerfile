FROM php:7.4-apache as builder

WORKDIR /var/www/html

Run apt-get update && apt-get -y install cron

RUN a2enmod rewrite

COPY ./apache-config.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# setup php modules
RUN docker-php-ext-configure pdo_mysql && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql mysqli 

COPY . /var/www/html

RUN cp .env-dist .env
RUN rm -rf logs
RUN mkdir -p logs \
    &&  chmod -R a+rwx logs
RUN php /var/www/html/console/migrations/migration.php

COPY update-cron /etc/cron.d/update-cron
RUN chmod 0644 /etc/cron.d/update-cron
RUN crontab /etc/cron.d/update-cron

RUN touch /var/log/cron.log

# CMD cron
