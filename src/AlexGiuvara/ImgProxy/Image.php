<?php

namespace AlexGiuvara\ImgProxy;

use AlexGiuvara\ImgProxy\Exceptions\InvalidFormat;
use Illuminate\Support\Str;

/**
 * Anemic class.
 * Image that is going to be resized by imgproxy micro-service
 */
class Image
{
    const DEFAULT_RESIZE = 'fit';
    /**
     * north (top edge)
     */
    const DEFAULT_GRAVITY = 'no';
    const MAX_ENLARGE     = 5;
    const MIN_ENLARGE     = 0;

    /**
     * @var string
     */
    protected $resize;
    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $height;
    /**
     * @var string
     */
    protected $gravity;
    /**
     * @var int
     */
    protected $enlarge;
    /**
     * @var string
     */
    protected $extension;
    /**
     * @var mixed
     */
    protected $url;

    /**
     * Init most common resize settings. Later you can update defaults
     * @param string $path
     * @param int $width
     * @param int $height
     */
    public function __construct(string $path, int $width, int $height)
    {
        $this->setOriginalPictureUrl($path)
            ->setWidth($width)
            ->setHeight($height)
            ->setResize('fit')
            ->setGravity('no')
            ->setEnlarge(0);
        //convert img to extension
        $this->setExtension('jpg');
    }

    /**
     * @param string $argument1
     * @return mixed
     */
    public function setResize(string $argument1 = null)
    {
        $argument1 = Str::lower($argument1);

        $this->resize = ( ! in_array($argument1, config('img-proxy.resize_values'), true))
        ? self::DEFAULT_RESIZE
        : $argument1;

        return $this;
    }

    /**
     * @return string
     */
    public function getResize(): string
    {
        return $this->resize;
    }

    /**
     * @param int $argument1
     */
    public function setWidth(int $argument1 = 1)
    {
        $argument1 = abs($argument1) ?: 1;
        if ($argument1 > config('img-proxy.max_dim_px')) {
            $argument1 = config('img-proxy.max_dim_px');
        }
        $this->width = $argument1;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $argument1
     */
    public function setHeight(int $argument1 = 1)
    {
        $argument1 = abs($argument1) ?: 1;
        if ($argument1 > config('img-proxy.max_dim_px')) {
            $argument1 = config('img-proxy.max_dim_px');
        }
        $this->height = $argument1;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param string $argument1
     * @return mixed
     */
    public function setGravity(string $argument1 = null)
    {
        $argument1     = Str::lower($argument1);
        $this->gravity = ( ! in_array($argument1, config('img-proxy.gravity_values')))
        ? self::DEFAULT_GRAVITY
        : $argument1;

        return $this;
    }

    /**
     * @return string
     */
    public function getGravity(): string
    {
        return $this->gravity;
    }

    /**
     * @param int $argument1
     * @return mixed
     */
    public function setEnlarge(int $argument1 = 0)
    {
        $argument1 = abs($argument1);
        if ($argument1 > self::MAX_ENLARGE) {
            $argument1 = self::MAX_ENLARGE;
        }
        $this->enlarge = $argument1;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnlarge(): int
    {
        return $this->enlarge;
    }

    /**
     * @param $argument1
     */
    public function setExtension(string $argument1)
    {
        $argument1 = Str::lower($argument1);

        if ( ! in_array($argument1, config('img-proxy.formats'))) {
            throw new InvalidFormat($argument1);
        }

        $this->extension = $argument1;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $argument1
     * @return this
     */
    public function setOriginalPictureUrl(string $argument1)
    {
        $this->url = $argument1;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalPictureUrl(): string
    {
        return $this->url;
    }
}
