<?php
namespace Opine;

use PHPUnit_Framework_TestCase;
use Opine\Container\Service as Container;
use Opine\Config\Service as Config;

class SearchTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        date_default_timezone_set('UTC');
        $root = __DIR__.'/../public';
        $config = new Config($root);
        $config->cacheSet();
        $container = Container::instance($root, $config, $root.'/../config/containers/test-container.yml');
    }

    public function testSample()
    {
        $this->assertTrue(true);
    }
}
