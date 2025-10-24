<?php

namespace Saade\FilamentFullCalendar\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Saade\FilamentFullCalendar\FilamentFullCalendarServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders(): array
    {
        return [
            FilamentFullCalendarServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}