<?php

namespace Orchestra\Model\Tests\Feature;

use Illuminate\Auth\AuthServiceProvider as BaseServiceProvider;
use Orchestra\Auth\AuthServiceProvider as OverrideServiceProvider;
use Orchestra\Database\SearchServiceProvider;
use Orchestra\Testbench\TestCase as Testbench;

abstract class TestCase extends Testbench
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../factories');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SearchServiceProvider::class,
        ];
    }

    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function overrideApplicationProviders($app): array
    {
        return [
            BaseServiceProvider::class => OverrideServiceProvider::class,
        ];
    }
}
