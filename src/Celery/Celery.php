<?php

namespace Celery;

class Celery implements CeleryInterface
{

    protected $mapExchangeToMethod = array(
        'direct' => 'pushDirectTask',
        'fanout' => 'pushFanoutTask',
        'topic'  => 'pushTopicTask',
    );

    /**
     * Default task template used by celery class.
     *
     * @var array
     */
    protected $defaultTaskTemplate = array(
        'body' => array(
            'task' => null, //here task name should go like task package.module.function_name
            'kwargs' => array(
            ),
            'id' => null,
        ),
        'content-type' => 'application/json',
        'properties' => array(
            'body_encoding' => 'base64',
            'delivery_info' => array(
                'priority' => 0,
                'routing_key' => 'celery',
                'exchange' => null,
            ),
            'delivery_mode' => self::DELIVERY_MODE_STORE_HDD,
            'delivery_tag' => null,
            'content-encoding' => 'utf-8',
        ),
    );

    /**
     * Task template
     *
     * @var array
     */
    protected $taskTemplate;

    /**
     * Backend used for persisting Celery tasks
     *
     * @var Backend\BackendInterface
     */
    protected $backend;

    /**
     * Constructor, takes backend as an optional parameter
     *
     * @param Backend\BackendInterface $backend Interface for backend storage for celery tasks
     */
    public function __construct(Backend\BackendInterface $backend = null)
    {
        //set the default task template
        $this->setTaskTemplate($this->defaultTaskTemplate);
        if ($backend !== null) {
            $this->setBackend($backend);
        }
    }

    /**
     * Changes task template if it's necessary.
     *
     * @param array $taskTemplate Celery task template.
     *
     * @return Celery Fluent interface
     */
    public function setTaskTemplate(array $taskTemplate)
    {
        $this->taskTemplate = $taskTemplate;

        return $this;
    }

    /**
     * Gets currenlty set task template
     *
     * @return array
     */
    public function getTaskTemplate()
    {
        return $this->taskTemplate;
    }

    /**
     * Sets backend for Celery task
     *
     * @param Backend\BackendInterface $backend Backend
     *
     * @return Celery Fluent interface
     */
    public function setBackend(Backend\BackendInterface $backend)
    {
        $this->backend = $backend;

        return $this;
    }

    /**
     * Gets currently set backend for celery
     *
     * @return Backend\BackendInterface
     */
    public function getBackend()
    {
        return $this->backend;
    }

    /**
     * Pushes task into Celery backend
     *
     * @param string $exchangeName Name of the Celery exchange
     * @param string $queueName    Name of the Celery queue used for task
     * @param string $taskName     Celery task name
     * @param string $exchangeType Exchange type, default is direct
     * @param array  $args         Arguments for celery task required to process
     * @param int    $priority     Priority for a task
     *
     * @return boolean True if task was pushed into backend
     */
    public function pushTask(
        $exchangeName,
        $queueName,
        $taskName,
        $exchangeType,
        array $args,
        $priority = CeleryInterface::DEFAULT_PRIORITY
    ) {
        $task = $this->getTaskTemplate();
        $task['body']['task'] = $taskName;
        $task['body']['kwargs'] = $args;
        $task['body']['id'] = sha1(json_encode($task['body']));
        $task['properties']['delivery_info']['priority'] = $priority;
        $task['body'] = base64_encode(json_encode($task['body']));
        $task['properties']['delivery_tag'] = sha1(json_encode($task['body']));
        $task['properties']['delivery_info']['exchange'] = $exchangeName;

        $method = $this->mapExchangeToMethod[$exchangeType];

        return $this->getBackend()->{$method}($queueName, $task);
    }
}
