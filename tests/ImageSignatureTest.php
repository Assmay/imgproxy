<?php

namespace Tests;

use AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface;
use AlexGiuvara\ImgProxy\Image;
use Mockery as m;

class ImageSignatureTest extends TestCase
{
    public function test_signature()
    {
        $path      = 'http://original-img-path.com/img.bla';
        $newWidth  = 500;
        $newHeight = 500;

        $img = m::mock(Image::class);
        $img->shouldReceive('getOriginalPictureUrl')->once()->andReturn($path);
        $img->shouldReceive('getWidth')->once()->andReturn($newWidth);
        $img->shouldReceive('getHeight')->once()->andReturn($newHeight);
        $img->shouldReceive('getResize')->once()->andReturn('fit');
        $img->shouldReceive('getGravity')->once()->andReturn('no');
        $img->shouldReceive('getEnlarge')->once()->andReturn(0);
        $img->shouldReceive('getExtension')->once()->andReturn('png');

        $this->app->instance(Image::class, $img);
        $sig = $this->app->make(ImageSignatureInterface::class);

        $this->assertSame($sig->take(), '/xY4r15fc-n86JV2N02Cz8APAW8vPpIX7ocC0Fmc-m1w/fit/500/500/no/0/aHR0cDovL29yaWdpbmFsLWltZy1wYXRoLmNvbS9pbWcuYmxh.png');
    }
}
