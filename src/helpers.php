<?php

use AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface;
use AlexGiuvara\ImgProxy\Image;

if ( ! function_exists('imgProxy')) {

    /**
     * @param string $path
     * @param int $width
     * @param int $height
     */
    function imgProxy(string $path, int $width, int $height): string
    {
        app()->instance(Image::class, (new Image)->make($path, $width, $height));

        return config('img-proxy.base_url') .
        app(ImageSignatureInterface::class)->take();
    }
}
