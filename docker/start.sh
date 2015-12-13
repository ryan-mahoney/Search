docker run -v /data/db --name opine-mongo-data -d tianon/true
docker run -p 27017:27017 --name opine-mongo --volumes-from opine-mongo-data -d mongo:2.6 --bind_ip=0.0.0.0
docker run -v /usr/share/elasticsearch/data --name opine-elastic-data -d tianon/true
docker run --name opine-elastic --volumes-from opine-elastic-data -d elasticsearch:1.7.3
docker run -p 11211:11211 --name opine-memcached -d memcached:1.4.24
docker run -p 11300:11300 --name opine-beanstalkd -d schickling/beanstalkd