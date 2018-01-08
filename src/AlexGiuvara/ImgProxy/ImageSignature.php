<?php

namespace AlexGiuvara\ImgProxy;

use AlexGiuvara\ImgProxy\Exceptions\InvalidKey;
use AlexGiuvara\ImgProxy\Exceptions\InvalidSalt;
use AlexGiuvara\ImgProxy\Exceptions\MissingKey;
use AlexGiuvara\ImgProxy\Exceptions\MissingSalt;
use AlexGiuvara\ImgProxy\Image;
use Illuminate\Support\Str;

class ImageSignature
{
    /**
     * @var Image
     */
    private $pic;

    /**
     * @param Image $pic
     */
    public function __construct(Image $pic)
    {
        $this->pic = $pic;
    }

    /**
     * Take picture signature
     * [domain]/signature.jpg is the resized image path
     * @return string
     */
    public function take(): string
    {
        $path      = $this->getPath();
        $signature = rtrim(strtr(base64_encode(hash_hmac(
            'sha256',
            $this->getBinarySalt() . $path,
            $this->getBinaryKey(),
            true
        )), '+/', '-_'), '=');

        return sprintf("/%s%s", $signature, $path);
    }
    // below are the methods used by take()

    /**
     * @return string
     */
    public function getKey(): string
    {
        if (empty($key = config('picture.key'))) {
            throw new MissingKey;
        }

        if (Str::length($key) < 32) {
            throw new InvalidKey;
        }
        return $key;
    }

    /**
     * @return mixed
     */
    public function getSalt(): string
    {
        if (empty($salt = config('picture.salt'))) {
            throw new MissingSalt;
        }

        if (Str::length($salt) < 32) {
            throw new InvalidSalt;
        }
        return $salt;
    }

    /**
     * @return string
     * @throws InvalidKey
     */
    public function getBinaryKey(): string
    {
        if (empty($keyBin = pack("H*", $this->getKey()))) {
            throw new InvalidKey('Key expected to be hex-encoded string');
        }

        return $keyBin;
    }

    /**
     * @return string
     * @throws InvalidSalt
     */
    public function getBinarySalt(): string
    {
        if (empty($saltBin = pack("H*", $this->getSalt()))) {
            throw new InvalidSalt('Salt expected to be hex-encoded string');
        }

        return $saltBin;
    }

    public function getEncodedUrl(): string
    {
        return rtrim(strtr(base64_encode($this->pic->getOriginalPictureUrl()), '+/', '-_'), '=');
    }

    public function getPath(): string
    {
        return sprintf(
            "/%s/%d/%d/%s/%d/%s.%s",
            $this->pic->getResize(),
            $this->pic->getWidth(),
            $this->pic->getHeight(),
            $this->pic->getGravity(),
            $this->pic->getEnlarge(),
            $this->getEncodedURL(),
            $this->pic->getExtension()
        );
    }
}
