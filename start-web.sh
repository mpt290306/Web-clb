#!/bin/sh
set -eu

if [ -d /var/data ]; then
    mkdir -p /var/data/content /var/data/uploads
    chown -R www-data:www-data /var/data
fi

exec apache2-foreground
