<?php
use Illuminate\Contracts\Console\Kernel;

/**
 * PhpSpec testing env
 */
putenv('APP_ENV=testing');

require_once __DIR__ . '/vendor/autoload.php';
$l = new class extends \Tests\TestCase
{
    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;
    /**
     * @return Application
     */
    function app()
    {
        parent::setUp();

        return $this->app;
    }
};
$app = $l->app();
$app->make(Kernel::class)->bootstrap();
// dd($app['config']['img-proxy']);
