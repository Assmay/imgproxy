<?php

namespace AlexGiuvara\ImgProxy;

use AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface;
use AlexGiuvara\ImgProxy\Exceptions\InvalidKey;
use AlexGiuvara\ImgProxy\Exceptions\InvalidSalt;
use AlexGiuvara\ImgProxy\Exceptions\MissingKey;
use AlexGiuvara\ImgProxy\Exceptions\MissingSalt;
use Illuminate\Support\Str;

class ImageSignature implements ImageSignatureInterface
{
    /**
     * @var Image
     */
    private $img;
    /**
     * @var int
     */
    private $signature_size = null;

    /**
     * TODO ImageInterface
     * @param ImageInterface $img
     */
    public function __construct($img)
    {
        if (config('img-proxy.signature_size')){
            $signature_size = config('img-proxy.signature_size');
            if (is_numeric($signature_size))
                $this->signature_size = (int)$signature_size;
        }
        $this->img = $img;
    }

    /**
     * Take picture signature
     * [domain]/signature.jpg is the resized image path
     * @return string
     */
    public function take(): string
    {
        $path      = $this->getPath();
        $signature = hash_hmac(
            'sha256',
            $this->getBinarySalt() . $path,
            $this->getBinaryKey(),
            true
        );
        if ($this->signature_size)
            $signature = pack('A'.$this->signature_size, $signature);

        $signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
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
        if ($this->img->getPreset()){
            $path = sprintf(
                "/%s/%s%s",
                $this->img->getPreset(),
                $this->getEncodedURL(),
                $this->img->getExtension()
            );
        }else{
            $path = "/rs:{$this->img->getResize()}:{$this->img->getWidth()}:{$this->img->getHeight()}:{$this->img->getEnlarge()}/g:{$this->img->getGravity()}/{$this->getEncodedURL()}{$this->img->getExtension()}";
        }
        return $path;
    }
}
