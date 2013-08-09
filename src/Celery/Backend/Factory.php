<?php

namespace Celery\Backend;

abstract class Factory
{
    /**
     * Creates Celery task backend based on it's name.
     *
     * @param string $name   Backend name
     * @param array  $params Additional backend params if required
     *
     * @return BackendInterface
     */
    public static function factory($name, array $params = null)
    {
        $className = __NAMESPACE__ . '\\' . ucfirst($name);

        if ($params === null) {
            return new $className;
        } else {
            $optionsClass = $className . 'Options';
            return new $className(new $optionsClass($params));
        }
    }
}
