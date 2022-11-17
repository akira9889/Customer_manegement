FROM php:8-apache

WORKDIR /var/www/html

# PHP で必要なライブラリをインストール
RUN apt-get update \
  && apt-get install -y libonig-dev libzip-dev unzip mariadb-client vim nodejs npm graphicsmagick \
  && docker-php-ext-install pdo_mysql mysqli mbstring zip \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

RUN npm install n -g \
  && n 16.18.0

RUN npm i gulp node-sass -g

COPY ./package.json /var/www/html/
RUN npm install

# ファイルのコピー
COPY ./src /var/www/html
COPY ./gulpfile.js /var/www/html/
COPY ./.babelrc /var/www/html/
COPY ./.eslintrc.js /var/www/html/
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini
COPY ./docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

CMD [ "gulp" ]
