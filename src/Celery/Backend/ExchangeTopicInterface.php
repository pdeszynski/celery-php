<?php

namespace Celery\Backend;

/**
 * Backend interface for backend with exchange topic
 * 
 * @package Celery\Backend
 */
interface ExchangeTopicInterface
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
    public function pushTopicTask($queueName, array $task);
}
