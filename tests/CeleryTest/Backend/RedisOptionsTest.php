<?php

namespace CeleryTest\Backend;

use Mockery as m;

use Celery\Backend;

class RedisOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testRedisCreatedBasedOnOptionsHostAndPort()
    {
        $redisOptions = new Backend\RedisOptions(array('host' => 'localhost', 'port' => 123));
        $mock = m::mock('Redis');
        $mock->shouldReceive('getAuth')->once();
        $mock->shouldReceive('getHost')->once();
        $mock->shouldReceive('getPort')->once();
        $mock->shouldReceive('getDBNum')->once();
        $mock->shouldReceive('isConnected')->once()->andReturn(true);
        $redisOptions->setConnection($mock);
        $this->assertInstanceOf('Redis', $redisOptions->getConnection(), 'Redis object was not created correctly based on options');
        $this->assertTrue($redisOptions->getConnection()->isConnected());
    }

    public function testGetSetConnection()
    {
        $redisOptions = new Backend\RedisOptions(array());
        $mock = m::mock('Redis');
        $mock->shouldReceive('getAuth')->once();
        $mock->shouldReceive('getHost')->once();
        $mock->shouldReceive('getPort')->once();
        $mock->shouldReceive('getDBNum')->once();
        $redisOptions->setConnection($mock);
        $this->assertInstanceOf('Redis', $redisOptions->getConnection());
    }

    public function testRedisUpdateConnectionInfo()
    {
        $mock = m::mock('Redis');
        $mock->shouldReceive('getAuth')->atLeast()->once();
        $mock->shouldReceive('getHost')->atLeast()->once()->andReturn('new-host');
        $mock->shouldReceive('getPort')->atLeast()->once()->andReturn(1234);
        $mock->shouldReceive('getDBNum')->atLeast()->once()->andReturn(1);
        $mock->shouldReceive('connect')->once();
        $mock->shouldReceive('select')->atLeast()->once();
        $redisOptions = new Backend\RedisOptions(array('host' => 'localhost', 'port' => 123));
        $redisOptions->setConnection($mock);
        $redisOptions->connect('new-host', 1234, 0);
        $redis = $redisOptions->getConnection();
        $this->assertEquals(1234, $redis->getPort());
        $this->assertEquals('new-host', $redis->getHost());
    }

}
