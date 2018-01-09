# img - in progress

Laravel Service Provider for Golang ImgProxy micro-service https://evilmartians.com/chronicles/introducing-imgproxy

## Install
- add to composer.json:
```
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/alexgiuvara/imgproxy"
        }
    ],
```
- `composer require alexgiuvara/imgproxy`
- `php artisan vendor:publish --provider="AlexGiuvara\\ImgProxy\\ImgProxyServiceProvider"`