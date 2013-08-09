<?php

namespace CeleryTest;

use Mockery;

use Celery;
use Celery\CeleryInterface;

class CeleryTest extends \PHPUnit_Framework_TestCase
{
    public function testBackendSetThroughConstructor()
    {
        $celery = new Celery\Celery(new Celery\Backend\Redis(new Celery\Backend\RedisOptions()));

        $this->assertInstanceOf('Celery\Backend\Redis', $celery->getBackend());
    }

    public function testGetSetBackend()
    {
        $celery = new Celery\Celery;
        $this->assertEquals(null, $celery->getBackend());

        $celery->setBackend(new Celery\Backend\Redis(new Celery\Backend\RedisOptions()));

        $this->assertInstanceOf('Celery\Backend\Redis', $celery->getBackend());

    }

    public function testBackendTaskPushCalled()
    {
        $params = array(1, 2, 3);

        $expected = array(
            'body' => "eyJ0YXNrIjoidGFzayIsImt3YXJncyI6WzEsMiwzXSwiaWQiOiJkZWM3NDMzODgwOGY0M2I4Y2NjMWMwMGI3YWNjYThkMTQxNTcyODY5In0=",
            'headers' => array(),
            'content-type' => "application/json",
            'properties' => array(
                'body_encoding' => "base64",
                'delivery_info' => array(
                    'priority' => 0,
                    'routing_key' => "celery",
                    'exchange' => "exchange",
                ),
                'delivery_mode' => 2,
                'delivery_tag' => "d75c853384d553851b9abe6b6152578dc72835fc",
                'content-encoding' => "utf-8",
            ),
        );

        $celeryBackend = Mockery::mock('Celery\Backend\BackendAbstract[pushDirectTask]');
        $celeryBackend->shouldReceive('pushDirectTask')->once()->with('queue', $expected)->andReturn(true);

        $celery = new Celery\Celery($celeryBackend);
        $return = $celery->pushTask('exchange', 'queue', 'task', CeleryInterface::EXCHANGE_TYPE_DIRECT, $params);
        $this->assertTrue($return);
    }

    public function testGetSetTemplateTask()
    {
        $celery = new Celery\Celery;

        $taskTemplate = array('test' => '123', 'args' => array(1, 2, 3));

        $celery->setTaskTemplate($taskTemplate);
        $this->assertEquals($taskTemplate, $celery->getTaskTemplate(), 'Task template was not set correctly');
    }

}
