FROM phpunit/phpunit:5.0.3

RUN apt-get update && apt-get install -y libssl-dev
RUN printf "\n" | pecl install memcache
RUN pecl install mongo