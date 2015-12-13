<?php
namespace Opine;

use PHPUnit_Framework_TestCase;
use Opine\Container\Service as Container;
use Opine\Config\Service as Config;
use Exception;

class SearchTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        date_default_timezone_set('UTC');
        $root = __DIR__.'/../public';

        $config = new Config($root);
        $config->cacheSet();
        $this->container = Container::instance($root, $config, $root.'/../config/containers/test-container.yml');
        $this->search = $this->container->get('search');
    }

    public function testCreateDefaultIndex()
    {
        try {
            $result = $this->search->indexDrop('testindex');
        } catch (Exception $e) {}

        $result = $this->search->indexCreateDefault('testindex');
        $this->assertTrue($result['acknowledged'] == true);
    }

    public function testIndexDocument()
    {
        $result = $this->search->indexToDefault(
            'abc123',
            'test',
            'test document',
            null,
            null,
            ['a', 'b', 'c'],
            [],
            null,
            null,
            null,
            'published',
            'f',
            ['public'],
            '',
            '',
            '',
            'testindex'
        );

        $this->assertTrue($result['created'] == 1);
    }

    public function testSearchDocument ()
    {
        sleep(3);
        $result = $this->search->search('test document', null, 20, 0, 'testindex');
        $this->assertTrue($result['hits']['hits'][0]['_id'] == 'abc123');
    }
}
