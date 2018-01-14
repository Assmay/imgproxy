<?php

if ( ! function_exists('imgProxy')) {

    /**
     * @param string $path
     * @param int $width
     * @param int $height
     */
    function imgProxy(string $path, int $width, int $height): string
    {
        $pic       = app(\AlexGiuvara\ImgProxy\Image::class, compact('path', 'width', 'height'));
        $signature = app(\AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface::class)->setImage($pic);

        return config('img-proxy.base_url') . $signature->take();
    }
}
