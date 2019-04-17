# Phikaru

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
$phikaru->upload('three_cows_walking_on_a_road, '/path/to/cows.jpg');

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
[license]: https://github.com/99designs/phumbor/blob/master/LICENSE