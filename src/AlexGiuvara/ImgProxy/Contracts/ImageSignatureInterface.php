<?php

namespace AlexGiuvara\ImgProxy\Contracts;

interface ImageSignatureInterface
{
    /**
     * @param $img
     */
    public function setImage($img);
    public function take(): string;
}
