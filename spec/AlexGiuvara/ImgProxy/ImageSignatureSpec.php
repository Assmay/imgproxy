<?php

namespace spec\AlexGiuvara\ImgProxy;

use AlexGiuvara\ImgProxy\Exceptions\InvalidKey;
use AlexGiuvara\ImgProxy\Exceptions\InvalidSalt;
use AlexGiuvara\ImgProxy\Exceptions\InvalidToken;
use AlexGiuvara\ImgProxy\Exceptions\MissingKey;
use AlexGiuvara\ImgProxy\Exceptions\MissingSalt;
use AlexGiuvara\ImgProxy\Exceptions\MissingToken;
use AlexGiuvara\ImgProxy\Image;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tests\SpecMatchers;

class ImageSignatureSpec extends ObjectBehavior
{
    use SpecMatchers;

    /**
     * @param Image $pic
     */
    public function let(Image $pic)
    {
        $this->beConstructedWith($pic);
    }

    public function it_throws_exception_when_key_is_not_set()
    {
        app('config')->set('img-proxy.key', null);
        $this->shouldThrow(MissingKey::class)->duringGetKey();
    }

    public function it_throws_exception_when_key_is_malformed()
    {
        app('config')->set('img-proxy.key', 2);
        $this->shouldThrow(InvalidKey::class)->duringGetKey();
    }

    public function it_throws_exception_when_salt_is_not_set()
    {
        app('config')->set('img-proxy.salt', null);
        $this->shouldThrow(MissingSalt::class)->duringGetSalt();
    }

    public function it_throws_exception_when_salt_is_malformed()
    {
        app('config')->set('img-proxy.salt', 2);
        $this->shouldThrow(InvalidSalt::class)->duringGetSalt();
    }

    public function it_process_binary_key()
    {
        $hash = 'd52ee658e421b97e6582b4ae91efa5f5';

        app('config')->set('img-proxy.key', $hash);

        $this->getBinaryKey()->shouldBe(pack("H*", $hash));
    }

    public function it_process_binary_salt()
    {
        $hash = 'd52ee658e421b97e6582b4ae91efa5f5';

        app('config')->set('img-proxy.salt', $hash);

        $this->getBinarySalt()
            ->shouldBe(pack("H*", $hash));
    }

    /**
     * @param Image $pic
     */
    public function it_gets_signature(Image $pic)
    {
        $pic->getResize()->willReturn('fit');
        $pic->getWidth()->willReturn(640);
        $pic->getHeight()->willReturn(360);
        $pic->getGravity()->willReturn('no');
        $pic->getEnlarge()->willReturn(0);
        $pic->getExtension()->willReturn('jpg');
        $pic->getOriginalPictureUrl()->willReturn('https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.jpg');
        $this->beConstructedWith($pic);

        app('config')->set('img-proxy.key', 'd52ee658e421b97e6582b4ae91efa5f6');

        $this->take()->shouldBe("/BOE5kJMsj8KMQCGZH0iNLoLjeI55hep113aw7HVXNWM/fit/640/360/no/0/aHR0cHM6Ly93d3cubmFzYS5nb3Yvc2l0ZXMvZGVmYXVsdC9maWxlcy9pbWFnZXMvNTI4MTMxbWFpbl9QSUExMzY1OV9mdWxsLmpwZw.jpg");
    }

    public function its_signature_can_convert_PNG_to_JPG()
    {
        $pic = app(Image::class)->make(
            'https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.PNG',
            640,
            360
        );
        $this->beConstructedWith($pic);

        app('config')->set('img-proxy.key', 'd52ee658e421b97e6582b4ae91efa5f6');

        $this->take()->shouldBe("/J_lUMTV4ZZL_uD3cRM5IiOWyiVPA_-9JarE2ckjDtYw/fit/640/360/no/0/aHR0cHM6Ly93d3cubmFzYS5nb3Yvc2l0ZXMvZGVmYXVsdC9maWxlcy9pbWFnZXMvNTI4MTMxbWFpbl9QSUExMzY1OV9mdWxsLlBORw.jpg");
    }

}
