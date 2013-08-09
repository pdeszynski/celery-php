<?php

namespace Celery\Backend;

use Celery;

/**
 * Class responsible for setting up redis object
 * 
 * @package Celery
 */
class RedisOptions
{
    /**
     *
     * @var array
     */
    protected $options;

    /**
     * Holds current redis connection
     *
     * @var \Redis
     */
    protected $connection;

    /**
     * Constructor
     *
     * @param array $options Backend options
     */
    public function __construct(array $options = null)
    {
        $this->options = $options;
    }

    /**
     * Get connection to Redis server instance
     *
     * @return \Redis PhpRedis object
     */
    public function getConnection()
    {
        if ($this->connection === null) {

            $this->connection = new \Redis;

            if (isset($this->options['host']) && $this->options['port']) {
                $timeout = isset($options['timeout']) ? $options['timeout'] : 0;
                $this->connect($this->options['host'], $this->options['port'], $timeout);
                if (isset($this->options['auth'])) {
                    $this->setAuth($this->options['auth']);
                }
                if (isset($this->options['dbNum'])) {
                    $this->setDbNum($this->options['dbNum']);
                }
            }
        }

        return $this->connection;
    }

    /**
     * Set backend connection
     *
     * @param \Redis $connection PhpRedis connection
     * 
     * @return RedisOptions Fluent interface
     */
    public function setConnection(\Redis $connection)
    {
        $this->connection = $connection;
        $this->options['auth'] = $connection->getAuth();
        $this->options['host'] = $connection->getHost();
        $this->options['port'] = $connection->getPort();
        $this->options['dbNum'] = $connection->getDBNum();

        return $this;
    }

    /**
     * Set auth for Redis
     *
     * @param string $auth Redis password
     * 
     * @return RedisOptions
     */
    public function setAuth($auth)
    {
        $this->options['auth'] = $auth;
        $this->getConnection()->auth($auth);

        return $this;
    }

    /**
     * Get auth if set, otherwise null
     *
     * @return string Auth or null if nothing was set
     */
    public function getAuth()
    {
        return $this->getConnection()->getAuth();
    }

    /**
     * Set db number
     *
     * @param int $dbNum Db number for current connection
     * 
     * @return RedisOptions
     */
    public function setDbNum($dbNum)
    {
        $this->options['dbNum'] = $dbNum;
        $this->getConnection()->select($dbNum);

        return $this;
    }

    /**
     * Get currently set db number
     *
     * @return int
     */
    public function getDbNum()
    {
        return $this->getConnection()->getDbNum();
    }

    /**
     * Connect to specified host and port
     *
     * @param string $host    Host
     * @param int    $port    Port
     * @param int    $timeout Connection timeout
     *
     * @return null
     */
    public function connect($host = null, $port = null, $timeout = 0)
    {
        $host = $host ?: $this->getConnection()->getHost();
        $port = $port ?: $this->getConnection()->getPort();
        $this->options['host'] = $host;
        $this->options['port'] = $port;
        $this->options['timeout'] = $timeout;
        $this->connection->connect($host, $port, $timeout);
        $dbNum = $this->getDbNum();
        if ($this->getAuth() !== null) {
            //reauth connection after new connect
            $this->setAuth($this->getAuth());
        }
        if ($dbNum !== false && $dbNum !== null) {
            $this->setDbNum($dbNum);
        }
    }
}
