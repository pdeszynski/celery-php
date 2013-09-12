<?php

namespace Celery\Backend;

use Celery\Exception;

/**
 * Celery backend Redis
 * 
 * @package Celery
 */
class Redis extends BackendAbstract
{
    /**
     *
     * @var App\Celery\Backend\RedisOptions
     */
    protected $options;
    /**
     * Constructor
     *
     * @param RedisOptions $options Redis options object
     */
    public function __construct(RedisOptions $options = null)
    {
        if (!extension_loaded('redis')) {
            throw new Exception\ExtensionNotLoadedException("Redis extension is not loaded");
        }

        if ($options !== null) {
            $this->setOptions($options);
        }
    }

    /**
     * Sets options
     *
     * @param RedisOptions $options Redis options used by Celery
     * 
     * @return Redis Fluent interface
     */
    public function setOptions(RedisOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Returns redis queue options
     *
     * @return RedisOptions Options
     */
    public function getOptions()
    {
        if ($this->options === null) {
            $this->options = new RedisOptions();
        }
        return $this->options;
    }

    /**
     * Get connection to Redis
     *
     * @return \Redis Redis connection
     */
    public function getConnection()
    {
        return $this->getOptions()->getConnection();
    }

    /**
     * Sets new connection for backend
     *
     * @param \Redis $connection Redis connection
     * 
     * @return Redis
     */
    public function setConnection(\Redis $connection)
    {
        $this->getOptions()->setConnection($connection);

        return $this;
    }

    /**
     * Pushes task into redis
     *
     * @param string $queueName Name of the queue where tasks are stored
     * @param string $task      Serialized task
     *
     * @return boolean Status of pushing into Redis (not a status of task)
     */
    protected function internalPushDirectTask($queueName, $task)
    {
        try {
            return $this->getOptions()->getConnection()->lPush($queueName, $task);
        } catch (\Exception $e) {
            throw new Exception\ConnectionException("An error occured while pushing task into backend", 1, $e);
        }
    }
    
    /**
     * Pushes task into redis
     *
     * @param string $queueName Name of the queue where tasks are stored
     * @param string $task      Serialized task
     *
     * @return boolean Status of pushing into Redis (not a status of task)
     */
    protected function internalPushFanoutTask($queueName, $task)
    {
        try {
            return $this->getOptions()->getConnection()->publish($queueName, $task);
        } catch (\Exception $e) {
            throw new Exception\ConnectionException("An error occured while pushing task into backend", 1, $e);
        }
    }
    
    /**
     * Pushes task into redis
     * 
     * @param type $queueName Name of the queue where tasks are stored
     * @param type $task      Serialized task
     * 
     * @throws \Exception
     * 
     * @return boolean Status of pushing into Redis (not a status of task)
     */
    protected function internalPushTopicTask($queueName, $task)
    {
        throw new \Exception('pushTopicTask() is not implement yet');
    }
}