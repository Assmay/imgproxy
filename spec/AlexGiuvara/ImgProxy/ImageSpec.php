<?php

namespace spec\AlexGiuvara\ImgProxy;

use AlexGiuvara\ImgProxy\Exceptions\InvalidFormat;
use AlexGiuvara\ImgProxy\Image;
use Illuminate\Support\Str;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImageSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Image::class);
    }

    public function its_resize_values_are_specific()
    {
        $this->setResize('bla')
            ->getResize()
            ->shouldBeAnyOf('fit', 'fill', 'crop');
    }

    public function its_empty_resize_values_has_default()
    {
        $this->setResize()
            ->getResize()
            ->shouldBe('fit');
    }

    public function its_width_has_a_limited_dimension()
    {
        $this->setWidth(99999)
            ->getWidth()
            ->shouldBe(config('img-proxy.max_dim_px'));
    }

    public function its_width_is_always_positive()
    {
        $this->setWidth(-3)->getWidth()->shouldBe(3);
    }

    public function its_width_is_always_gte_1()
    {
        $this->setWidth(0)->getWidth()->shouldBe(1);
    }

    public function its_height_has_a_limited_dimension()
    {
        $this->setHeight(99999)
            ->getHeight()
            ->shouldBe(config('img-proxy.max_dim_px'));
    }

    public function its_height_is_always_positive()
    {
        $this->setHeight(-3)->getHeight()->shouldBe(3);
    }

    public function its_height_is_always_gte_1()
    {
        $this->setHeight(0)->getHeight()->shouldBe(1);
    }

    public function its_gravity_values_are_specific()
    {
        $this->setGravity('bla')
            ->getGravity()
            ->shouldBeAnyOf('no', 'so', 'ea', 'we', 'ce', 'sm');
    }

    public function its_gravity_value_has_default()
    {
        $this->setGravity('bla')
            ->getGravity()
            ->shouldBe('no');
    }

    public function its_enlarge_is_alwasy_positive()
    {
        $this->setEnlarge(-3)->getEnlarge()->shouldBe(3);
    }

    public function it_enlarges_to_a_max_threshold()
    {
        $this->setEnlarge(2000)->getEnlarge()->shouldBe(Image::MAX_ENLARGE);
    }

    public function it_supports_specific_formats()
    {
        $this->shouldThrow(InvalidFormat::class)->duringSetExtension('bla');

    }
    public function it_converts_string_to_lower()
    {
        $this->setExtension('JPEG')
            ->getExtension()
            ->shouldBeEqualTo('jpeg')
        ;
    }

    public function its_make_acts_as_a_shorthand()
    {
        $img = $this->make(
            'https://remote.example/image.bla',
            640,
            360
        );
        $img->getOriginalPictureUrl()->shouldBe('https://remote.example/image.bla');
        $img->getWidth()->shouldBe(640);
        $img->getHeight()->shouldBe(360);
        $img->getResize()->shouldBe('fit');
        $img->getGravity()->shouldBe('no');
        $img->getEnlarge()->shouldBe(0);
        //default conversion .bla to .jpg
        $img->getExtension()->shouldNotBe('bla');
        $img->getExtension()->shouldBe('jpg');
    }
}
