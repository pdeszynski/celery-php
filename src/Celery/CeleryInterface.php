<?php

namespace Celery;

interface CeleryInterface
{
    const EXCHANGE_TYPE_DIRECT = 'direct';
    const EXCHANGE_TYPE_FANOUT = 'fanout';
    
    const DELIVERY_MODE_ONLY_MEMORY = 1;
    const DELIVERY_MODE_STORE_HDD = 2;

    const DEFAULT_PRIORITY = 0;

    /**
     * Pushes one task into backend
     *
     * @param string $exchange     Exchange name
     * @param string $queueName    Celery queue name
     * @param string $taskName     Task name
     * @param string $exchangeType Exchange type
     * @param array  $args         Array of task arguments
     * @param int    $priority     Task priority
     *
     * @return boolean Status
     */
    public function pushTask(
        $exchange,
        $queueName,
        $taskName,
        $exchangeType,
        array $args,
        $priority = self::DEFAULT_PRIORITY
    );

    /**
     * Sets new template for each celery task
     *
     * @param array $template Task template in an array
     * 
     * @return CeleryInterface Fluent interface
     */
    public function setTaskTemplate(array $template);

    /**
     * Returns currently set task template
     *
     * @return array
     */
    public function getTaskTemplate();

    /**
     * Sets backend which persists task
     *
     * @param Backend\BackendInterface $backend Backend
     * 
     * @return CeleryInterface Fluent interface
     */
    public function setBackend(Backend\BackendInterface $backend);

    /**
     * Get currently set backend
     *
     * @return Backend\BackendInterface
     */
    public function getBackend();
}
