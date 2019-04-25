#!/bin/sh
echo -e "*** DROPPING AND CREATING 'laravel_vuejs_exam' DB ***"
docker exec -it laravel_vuejs_exam_mysql mysql -uroot -proot -e "DROP DATABASE IF EXISTS laravel_vuejs_exam; CREATE DATABASE laravel_vuejs_exam;"

echo -e "\n*** Migrating DB 'laravel_vuejs_exam' ***"
docker exec -it laravel_vuejs_exam_web_app  php artisan migrate
