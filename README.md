# img-proxy

Laravel Service Provider for Golang ImgProxy micro-service https://evilmartians.com/chronicles/introducing-imgproxy

## Install
Tested with Laravel 5.5+, but could work with 5.1+ versions

- `composer require alexgiuvara/imgproxy`
- if you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php
```php
AlexGiuvara\ImgProxy\ImgProxyServiceProvider::class,
```
- copy the package config to your local config with the publish command:
```php
php artisan vendor:publish --provider="AlexGiuvara\\ImgProxy\\ImgProxyServiceProvider"
```

## Usage
```php
Route::get('/img-test', function () {
    $path      = 'https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.jpg';
    $width     = 640;
    $height    = 360;
    $pic       = app(Image::class, compact('path', 'width', 'height'));
    //more options: $pic->setResize('fit')->setGravity('no')->setEnlarge(0)->setExtension('png');
    $signature = app(ImageSignatureInterface::class)->setImage($pic);

    echo '
    Resized: <img src="' . config('img-proxy.base_url') . $signature->take() . '" alt="Resized">
    <br>
    Original: <img src="' . $path . '" alt="Original">
    ';

});
```

helper:
```php
Resized: <img src="<?php echo imgProxy('https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.jpg', 640, 360); ?>">
```

env file:
```php
# img-proxy.base_url
IMGPROXY_URL=https://img-proxy-example.com
# your img-proxy key
IMGPROXY_KEY=aaa
# your img-proxy salt
IMGPROXY_SALT=bbb
```