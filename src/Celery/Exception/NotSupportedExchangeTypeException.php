<?php

namespace Celery\Exception;

/**
 * An exception which should be thrown ehen backend does not support
 * one of the Celery exchange types.
 */
class NotSupportedExchangeTypeException extends \RuntimeException
{
}
