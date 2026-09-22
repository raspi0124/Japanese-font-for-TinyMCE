FROM php:5.6-apache@sha256:0a40fd273961b99d8afe69a61a68c73c04bc0caa9de384d3b2dd9e7986eec86d
RUN docker-php-ext-install mysqli
