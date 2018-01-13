<?php

namespace AlexGiuvara\ImgProxy;

use AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface;
use AlexGiuvara\ImgProxy\Exceptions\InvalidKey;
use AlexGiuvara\ImgProxy\Exceptions\InvalidSalt;
use AlexGiuvara\ImgProxy\Exceptions\MissingKey;
use AlexGiuvara\ImgProxy\Exceptions\MissingSalt;
use AlexGiuvara\ImgProxy\Image;
use Illuminate\Support\Str;

class ImageSignature implements ImageSignatureInterface
{
    /**
     * @var Image
     */
    private $img;

    /**
     * TODO ImageInterface
     * @param ImageInterface $img
     */
    public function setImage($img)
    {
        $this->img = $img;

        return $this;
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
        if (empty($key = config('img-proxy.key'))) {
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
        if (empty($salt = config('img-proxy.salt'))) {
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
        return rtrim(strtr(base64_encode($this->img->getOriginalPictureUrl()), '+/', '-_'), '=');
    }

    public function getPath(): string
    {
        return sprintf(
            "/%s/%d/%d/%s/%d/%s.%s",
            $this->img->getResize(),
            $this->img->getWidth(),
            $this->img->getHeight(),
            $this->img->getGravity(),
            $this->img->getEnlarge(),
            $this->getEncodedURL(),
            $this->img->getExtension()
        );
    }
}
