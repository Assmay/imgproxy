<?php

use AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface;
use AlexGiuvara\ImgProxy\Image;

if (! function_exists('imgProxy')) {

    /**
     * @param string $path
     * @param int $width
     * @param int $height
     */
    function imgProxy(string $path, int $width, int $height, $extension=null): string
    {
        app()->instance(Image::class, (new Image)->make($path, $width, $height, $extension));

        return config('img-proxy.base_url') .
        app(ImageSignatureInterface::class)->take();
    }

    function imgProxyPreset(string $path, string $preset, $extension=null): string
    {
        app()->instance(Image::class, (new Image)->makePreset($path, $preset, $extension));

        return config('img-proxy.base_url') .
            app(ImageSignatureInterface::class)->take();
    }
}
