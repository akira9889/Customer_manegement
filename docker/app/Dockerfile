FROM php:8-apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public/

WORKDIR /var/www/html

# PHP で必要なライブラリをインストール
RUN apt-get update \
  && apt-get install -y libonig-dev libzip-dev unzip mariadb-client vim nodejs npm graphicsmagick \
  && docker-php-ext-install pdo_mysql mysqli mbstring zip \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && a2enmod rewrite

RUN npm install n -g \
  && n 16.18.0

RUN npm i gulp node-sass -g

COPY ./package.json /var/www/html/
RUN npm install

# composer のインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY ./composer.json /var/www/html/
RUN composer install

# ファイルのコピー
COPY ./src /var/www/html
# COPY ./phpunit.xml /var/www/html/
COPY ./gulpfile.js /var/www/html/
COPY ./.babelrc /var/www/html/
COPY ./.eslintrc.js /var/www/html/
COPY ./.env /var/app/customer_manegement/.env
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini
COPY ./docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# セッションデータの保存先ディレクトリを作成
RUN mkdir -p /var/lib/php/sessions

# 所有者とグループをwww-dataに変更
RUN chown -R www-data:www-data /var/lib/php/sessions

# パーミッションを700に設定
RUN chmod -R 700 /var/lib/php/sessions
