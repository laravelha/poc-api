<?php

namespace Laravelha\News\Tests;

use Illuminate\Foundation\Application;
use Laravelha\News\Providers\NewsServiceProvider;
use Laravelha\News\Providers\RouteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * add the package provider
     *
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            NewsServiceProvider::class,
            RouteServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
