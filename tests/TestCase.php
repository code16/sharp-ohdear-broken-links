<?php

namespace Code16\SharpOhdearBrokenLinks\Tests;

use Code16\SharpOhdearBrokenLinks\SharpOhdearBrokenLinksServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SharpOhdearBrokenLinksServiceProvider::class,
        ];
    }
}
