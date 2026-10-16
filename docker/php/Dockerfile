# Ambiente di sviluppo: stessa versione di PHP dell'hosting di produzione
FROM php:8.2-apache

RUN docker-php-ext-install mysqli \
 && cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Abilita i file .htaccess, come sull'hosting
COPY apache.conf /etc/apache2/conf-enabled/tombola.conf
