FROM phpdockerio/php80-fpm:latest

WORKDIR "/application"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install vim zip php-memcached php8.0-mysql php8.0-sqlite3 php8.0-gd php8.0-intl php8.0-xmlrpc php8.0-xsl \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
