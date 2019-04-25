#!/bin/sh
echo -e "*** Seeding DB 'laravel_vuejs_exam' ***"
docker exec -it laravel_vuejs_exam_web_app  php artisan db:seed
