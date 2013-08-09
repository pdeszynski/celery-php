<?php

namespace CeleryTest\Backend;

use Mockery;

use Celery\Backend;

class RedisTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->redis = Mockery::mock('Redis[getAuth,getHost,getPort,getDBNum]');

        $this->redisOptions = new Backend\RedisOptions();
        $this->redis->shouldReceive('getAuth');
        $this->redis->shouldReceive('getHost');
        $this->redis->shouldReceive('getPort');
        $this->redis->shouldReceive('getDBNum');
        $this->redisOptions->setConnection($this->redis);
        $this->redisBackend = new Backend\Redis($this->redisOptions);
    }

    public function testIsConstructorConnectionSetupedCorrectly()
    {
        $this->assertEquals($this->redis, $this->redisBackend->getConnection(), 'Constructor connection was not set correctly');
    }

    public function testInternalPushCalled()
    {
        $mock = $this->getMock('Celery\Backend\Redis', array('internalPushDirectTask'));
        $mock->expects($this->once())->method('internalPushDirectTask');

        $mock->pushDirectTask('testTask', array(1, 2, 3));
    }

    public function testAreArgsSaved()
    {
        $args = array(1, 2, 3);
        
        $mock = $this->getMock('Celery\Backend\Redis', array('internalPushDirectTask'));
        $mock->expects($this->once())
            ->method('internalPushDirectTask')
            ->with($this->equalTo('testTask'), $this->equalTo(json_encode($args)));
        
        $mock->pushDirectTask('testTask', $args);
    }

    public function testGetSetConnection()
    {
        $redisBackend = new Backend\Redis;
        $this->assertFalse($redisBackend->getConnection()->getHost(), 'Connection was not reset');

        $redisBackend->setConnection($this->redis);
        $this->assertEquals($this->redis, $redisBackend->getConnection() , 'Connection was not set correctly');
    }
}
