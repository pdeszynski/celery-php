<?php

namespace Celery\Backend;

/**
 * Backend interface for each celery backend
 * 
 * @package Celery\Backend
 */
interface BackendInterface extends ExchangeDirectInterface, ExchangeFanoutInterface, ExchangeTopicInterface
{
}
