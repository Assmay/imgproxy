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
- env file:
```bash
# img-proxy.base_url
IMGPROXY_URL=https://img-proxy-example.com
# your img-proxy key
IMGPROXY_KEY=943b421c9eb07c830af81030552c86009268de4e532ba2ee2eab8247c6da0881
# your img-proxy salt
IMGPROXY_SALT=520f986b998545b4785e0defbc4f3c1203f22de2374a3d53cb7a7fe9fea309c5
```

## Usage

helper:
```php
imgProxy('https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.jpg', 640, 360)
```

```php
use AlexGiuvara\ImgProxy\Contracts\ImageSignatureInterface;
use AlexGiuvara\ImgProxy\Image;

Route::get('/img-test', function () {
    $path      = 'https://www.nasa.gov/sites/default/files/images/528131main_PIA13659_full.jpg';
    $width     = 640;
    $height    = 360;
    $pic       = new Image;
    $pic->setOriginalPictureUrl($path)
        ->setWidth($width)
        ->setHeight($height)
        ->setResize('fit')
        ->setGravity('no')
        ->setEnlarge(0)
        ->setExtension('png');
    app()->instance(Image::class, $pic);
    $signature = app(ImageSignatureInterface::class);

    echo '
    Resized: <img src="' . config('img-proxy.base_url') . $signature->take() . '" alt="Resized">
    <br>
    Original: <img src="' . $path . '" alt="Original">
    ';

});
```

