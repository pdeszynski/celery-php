<?php

namespace Celery\Backend;

use Celery\CeleryInterface;

abstract class BackendAbstract implements BackendInterface
{
    /**
     * Pushes task into backend for Celery.
     * Status of pushed task is not an status if the task
     * succeeded. It's only a status that backend obtained given task.
     *
     * @param string $queueName Name of the queue for task
     * @param array  $task      Celery task in an array
     *
     * @return boolean Status of task push.
     */
    public function pushDirectTask($queueName, array $task)
    {
        return $this->internalPushDirectTask(
            $queueName,
            $this->serialize($task)
        );
    }
    
    /**
     * Pushes task into backend for Celery.
     * Status of pushed task is not an status if the task
     * succeeded. It's only a status that backend obtained given task.
     *
     * @param string $queueName Name of the queue for task
     * @param array  $task      Celery task in an array
     *
     * @return boolean Status of task push.
     */
    public function pushFanoutTask($queueName, array $task)
    {
        return $this->internalPushFanoutTask(
            $queueName,
            $this->serialize($task)
        );
    }
    
    /**
     * Pushes task into backend for Celery.
     * Status of pushed task is not an status if the task
     * succeeded. It's only a status that backend obtained given task.
     *
     * @param string $queueName Name of the queue for task
     * @param array  $task      Celery task in an array
     *
     * @return boolean Status of task push.
     */
    public function pushtopicTask($queueName, array $task)
    {
        return $this->internalPushTopicTask(
            $queueName,
            $this->serialize($task)
        );
    }

    /**
     * Serializes task for backend
     *
     * @param array $task Task data in an array
     *
     * @return string Serialized task
     */
    protected function serialize(array $task)
    {
        return json_encode($task);
    }
    
    /**
     * Each backend should implement this method. It should obtain
     * serialized task and push it into correct backend
     *
     * @param string $queueName Name of the queue where to store the task
     * @param string $task      Serialized task
     *
     * @return boolean Task push result
     */
    abstract protected function internalPushDirectTask($queueName, $task);

    /**
     * Each backend should implement this method. It should obtain
     * serialized task and push it into correct backend
     *
     * @param string $queueName Name of the queue where to store the task
     * @param string $task      Serialized task
     *
     * @return boolean Task push result
     */
    abstract protected function internalPushFanoutTask($queueName, $task);
    
    /**
     * Each backend should implement this method. It should obtain
     * serialized task and push it into correct backend
     *
     * @param string $queueName Name of the queue where to store the task
     * @param string $task      Serialized task
     *
     * @return boolean Task push result
     */
    abstract protected function internalPushTopicTask($queueName, $task);
}
