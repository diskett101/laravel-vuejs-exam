FROM ubuntu:16.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y
RUN apt-get install software-properties-common python-software-properties -y
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
RUN apt-get update -y
RUN apt-get upgrade -y

## Install php nginx mysql supervisor
RUN apt-get install -y php7.1-fpm php7.1-cli php7.1-mcrypt php7.1-mysql php7.1-curl php7.1-mbstring php7.1-gd php7.1-dom php7.1-zip nginx curl supervisor vim git unzip cron && \
    rm -rf /var/lib/apt/lists/*

## Configuration
RUN sed -i 's/^listen\s*=.*$/listen = 127.0.0.1:9000/' /etc/php/7.1/fpm/pool.d/www.conf && \
    sed -i 's/^\;error_log\s*=\s*syslog\s*$/error_log = \/var\/log\/php\/cgi.log/' /etc/php/7.1/fpm/php.ini && \
    sed -i 's/^\;error_log\s*=\s*syslog\s*$/error_log = \/var\/log\/php\/cli.log/' /etc/php/7.1/cli/php.ini

## Install Composer
RUN curl -s http://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash

RUN apt-get -y install nodejs

# RUN npm install -g bower

COPY docker/root /

COPY web-app /var/www/html

WORKDIR /var/www/html

VOLUME /var/www/html

RUN usermod -u 1000 www-data
RUN chown -R www-data /var/www/html

RUN composer install

## Start PHP 7.1-FPM Service
RUN service php7.1-fpm start

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
