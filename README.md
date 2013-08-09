# Celery-PHP library

## What is it?

This library allows to add tasks for celery using different backends and simplifies access to it.

## Usage example

```php
<?php
//create new celery redis class
use Celery;
use Celery\Backend\Factory;

$celery = new Celery(
    Factory::factory('redis', array(/*host port etc*/));
);

//you can also create by hand instance of Redis backend
use Celery\Backend\Redis;
use Celery\Backend\RedisOptions;

$celery = new Celery(
    new Redis(
        RedisOptions(
            array(
                //host port etc.
            )
        )
    )
);

$celery->pushTask(
        $exchangeName,
        $queueName,
        $taskName,
        $exchangeType,
        array $args
    );
?>
```

### Unittests
To run unittests be sure fistly to run

```bash
make init
```

Later you should be able to use

```
make phpunit
```

It's also possible to use **phpunit** from your system installation. If you have it already installed just

```
cd tests
phpunit
```

Be sure thought to still run ```make init``` because it will install necessary **Mockery**.

### Developer instructions

To add new Celery backend you should create **two** classes of name:

 * BackendName in *Celery/Backend/BackendName.php* file
 * BackendNameOptions in *Celery/Backend/BackendNameOptions.php* file

First class should implement ```BackendInterface``` (can also extend ```BackendAbstract``` class). This class handles task itself encapsulating backend specific functions (it's an Adapter).

Second class should encapsulate **backend specific** initialization.

If one of backends cannot implement one of the methods required by interface because it doesn't support one of the exchange type, it should throw an exception ```Celery\Exception\NotSupportedExchangeTypeException```

