<?php
date_default_timezone_set('UTC');
require_once __DIR__.'/../vendor/autoload.php';

// useful for docker
if (!extension_loaded('memcache')) {
    dl('memcache.so');
}
if (!extension_loaded('mongo')) {
    dl('mongo.so');
}