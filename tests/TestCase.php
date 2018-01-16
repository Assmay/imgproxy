<?php

namespace Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \AlexGiuvara\ImgProxy\ImgProxyServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application    $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = require 'config/img-proxy.php';

        $app['config']->set('app.key', '12345678901234567890123456789123');
        $app['config']->set('img-proxy', $config);
        $app['config']->set('img-proxy.salt', 'd52ee658e421b97e6582b4ae91efa5f6');
        $app['config']->set('img-proxy.key', 'd52ee658e421b97e6582b4ae91efa5f6');
    }
}
