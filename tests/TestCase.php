<?php

namespace poldixd\QueueClearAll\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use poldixd\QueueClearAll\QueueClearAllServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            QueueClearAllServiceProvider::class,
        ];
    }
}
