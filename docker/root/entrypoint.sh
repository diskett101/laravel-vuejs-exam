#!/bin/bash

set -e

chown -R www-data:www-data /var/www /var/log/php
chmod 777 /
exec /usr/bin/supervisord --nodaemon -c /etc/supervisor/supervisord.conf
