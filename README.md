# Phikaru

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]

[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coverage]][link-coverage]
[![Quality Score][ico-code-quality-scrutinizer]][link-code-quality-scrutinizer]

PHP Hikaru image thumbnail server methods binding.
Allows to operate with [Hikaru][hikaru] image thumbnail server

## Requirements
- PHP >= 7.1

## Installation
```bash
composer require urvin/phikaru
```

## Usage
```php
// Create a phikaru object, define Hikaru URL and secret signature salt
$phikaru = new \urvin\phikaru\Phikaru('http://hikaru.local', 'not_safe');

// Upload image
$phikaru->upload('three_cows_walking_on_a_road', '/path/to/cows.jpg');

// Create a thumbnail URL as you want
echo $phikaru->thumbnail()
    ->filename('three_cows_walking_on_a_road')
    ->extension('webp')
    ->width(600)
    ->height(600)
    ->cast(\urvin\phikaru\UrlBuilder::CAST_RESIZE_INVERSE)
    ->cast(\urvin\phikaru\UrlBuilder::CAST_TRIM)
    ->cast(\urvin\phikaru\UrlBuilder::CAST_EXTENT);

// When a moment comes, delete source and all thumbnails from server
$phikaru->remove('three_cows_walking_on_a_road');
```

## Author
Yuriy Gorbachev <yuriy@gorbachev.rocks>

## License
This module is licensed under the MIT license; see [`LICENSE`][license]

[hikaru]:<https://github.com/Urvin/hikaru>
[license]:<https://github.com/Urvin/phikaru/blob/master/LICENSE>

[ico-version]: https://img.shields.io/badge/packagist-dev-yellow.svg
[ico-license]: https://img.shields.io/packagist/l/urvin/phikaru.svg
[ico-travis]: https://travis-ci.org/Urvin/phikaru.svg?branch=master
[ico-coverage]: https://scrutinizer-ci.com/g/Urvin/phikaru/badges/coverage.png?b=master
[ico-code-quality-scrutinizer]: https://img.shields.io/scrutinizer/g/urvin/phikaru.svg
[ico-downloads]: https://img.shields.io/packagist/dt/urvin/phikaru.svg

[link-packagist]: https://packagist.org/packages/urvin/phikaru
[link-travis]: https://travis-ci.org/Urvin/phikaru
[link-coverage]: https://scrutinizer-ci.com/g/Urvin/phikaru/?branch=master
[link-code-quality-scrutinizer]: https://scrutinizer-ci.com/g/urvin/phikaru
[link-downloads]: https://packagist.org/packages/urvin/phikaru