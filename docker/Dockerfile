FROM php:8-apache-bookworm

RUN apt-get update \
 && apt-get install -y --no-install-recommends ffmpeg python3 \
 && rm -rf /var/lib/apt/lists/* \
 && curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp \
 && chmod a+rx /usr/local/bin/yt-dlp

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
 && mkdir -p /downloads \
 && chown www-data:www-data /downloads

EXPOSE 80
