mongo --host 127.0.0.1 phpunit --eval 'db.createUser({"user": "unit", "pwd": "test", "roles": [{ "role": "readWrite", "db": "phpunit"}]});' > /dev/null 2>&1
docker run \
    -e "OPINE_ENV=docker" \
    --rm \
    --link opine-memcached:memcached \
    --link opine-mongo:mongo \
    --link opine-elastic:elasticsearch \
    --link opine-beanstalkd:beanstalkd \
    -v "$(pwd)/../":/app \
    opine:phpunit-search \
    --bootstrap /app/tests/bootstrap.php