FROM laravelfans/laravel:9


ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y vim

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions imagick

# Setup working directory
WORKDIR /var/www/

# Copy the code into the working directory
COPY . /var/www/
#COPY run.sh /run.sh
#RUN chmod +x run.sh
RUN composer install
RUN composer install --no-scripts --ignore-platform-req=ext-exif --ignore-platform-req=ext-bcmath

EXPOSE 8000
