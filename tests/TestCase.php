<?php

namespace Yormy\Dateformatter\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Yormy\Dateformatter\DateformatterServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DateformatterServiceProvider::class,
        ];
    }
}
