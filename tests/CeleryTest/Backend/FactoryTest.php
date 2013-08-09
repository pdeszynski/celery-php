<?php

namespace CeleryTest\Backend;

use Mockery as m;

use Celery\Backend\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->redis = m::mock('Redis');
    }

    public function testIsRedisBackendCreated()
    {
        $object = Factory::factory('redis', array($this->redis));
        $this->assertInstanceOf('Celery\Backend\Redis', $object, 'Wrong type of backend returned, it should be Redis backend');
    }

    public function testBackendOptionsSet()
    {
        $object = Factory::factory('redis', array($this->redis));
        $this->assertInstanceOf('Celery\Backend\BackendInterface', $object);
        $this->assertInstanceOf('\Redis', $object->getConnection(), "Factory didn't pass parameters correctly");
    }
}
