<?php

namespace Tests;

use AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface;
use AlexGiuvara\ImgProxy\ImageSignature;
use Mockery as m;

class HelperTest extends TestCase
{
    public function test_helper_works()
    {
        app('config')->set('img-proxy.base_url', 'http://imgproxy-microservice');

        $sig = m::mock(ImageSignature::class);
        $sig->shouldReceive('take')->once()->andReturn('/bla.jpg');
        $this->app->instance(ImageSignatureInterface::class, $sig);
        $result = imgProxy('remote', 640, 360);

        $this->assertSame('http://imgproxy-microservice/bla.jpg', $result);
    }
}
