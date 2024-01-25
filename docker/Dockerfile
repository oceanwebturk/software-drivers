FROM php:7.4-apache

RUN a2enmod rewrite

COPY ./install-composer.sh ./

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && sh ./install-composer.sh \
    && rm ./install-composer.sh

WORKDIR /var/www/html
