#!/bin/bash

composer install

echo "------------------------------"
echo "---- SETANDO PERMISSOES ------"
echo "------------------------------"
chmod -R 755 /var/www/storage
chmod -R 755 /var/www/bootstrap

echo "------------------------------"
echo "------ FIM PERMISSOES --------"
echo "------------------------------"

echo "------------------------------"
echo "-------- PHP ARTISAN ---------"
echo "------------------------------"

php artisan migrate:fresh --seed
php artisan key:generate
php artisan storage:link

echo "------------------------------"
echo "------ FIM PHP ARTISAN -------"
echo "------------------------------"

# Inicia o PHP-FPM
php-fpm

