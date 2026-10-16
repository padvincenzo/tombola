# Strumenti di controllo e formattazione del codice PHP (PSR-12)
FROM php:8.2-cli

ADD --chmod=755 https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/releases/download/v3.64.0/php-cs-fixer.phar /usr/local/bin/php-cs-fixer
ADD --chmod=755 https://github.com/PHPCSStandards/PHP_CodeSniffer/releases/download/3.10.3/phpcs.phar /usr/local/bin/phpcs
ADD --chmod=755 https://github.com/PHPCSStandards/PHP_CodeSniffer/releases/download/3.10.3/phpcbf.phar /usr/local/bin/phpcbf

WORKDIR /app
