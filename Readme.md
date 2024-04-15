
# URL Normalizer


<p align="center">
    <a href="https://packagist.org/packages/edulazaro/urlnormalizer"><img src="https://img.shields.io/packagist/dt/edulazaro/urlnormalizer" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/edulazaro/urlnormalizer"><img src="https://img.shields.io/packagist/v/edulazaro/urlnormalizer" alt="Latest Stable Version"></a>
</p>

## Introduction

URL nnormalizer allows to normalize a URL. A normalized URL refers to the format of a URL that has been standardized according to a set of rules. The purpose of URL normalization (or URL canonicalization) is to transform a URL into a normalized or canonical form. This way, URLs that are essentially identical but represented differently are considered equal by web servers or applications.

Normalization helps in reducing duplication of URLs where multiple URLs point to the same content. For instance, the following URLs might point to the same content but are represented differently:


For example, the URLs `http://edulazaro.com?a=1&b=2` and `http://edulazaro.com?b=2&a=1` are the same, and can be normalized to `http://edulazaro.com?a=1&b=2`.

The same happens with the URLs `http://edulazaro.com/` and `http://edulazaro.com`, where the forward slash is indifferent.

The same happens with dot segments like `/../` or `/./` and with the encoded unreserved characters like `%61`, which can be represented as an `a`.

## Installation

To install the package just execute this command:

```php
composer require edulazaro/urlnormalizer
```

## Usage

Just import the class `URLNormalizer` can use the `normalize` method:

```php
use Edulazaro\URLNormalizer\URLNormalizer;

$normalizer = new URLNormalizer();

$normalizedURL = $normalizer->normalize('http://edulazaro.com?a=1&b=2');
```

You can also get the top domain of a URL by using the `getURLTopLevelDomain` method:

```php
use Edulazaro\URLNormalizer\URLNormalizer;

$normalizer = new URLNormalizer();

$topDomain = $normalizer->getURLTopLevelDomain('http://test.edulazaro.com?a=1&b=2');
```

The class `URLCounter` is also included so you can count the number of unique normalized URLs in an array:

```php
use Edulazaro\URLNormalizer\URLCounter;

$normalizer = new URLCounter();

$normalizedURLs = $normalizer->countUniqueUrls([
    'http://edulazaro.com?a=1&b=2',
    'http://edulazaro.com?a=2&b=1'
]);
```

Or you can also count them per top level domain:

```php
use Edulazaro\URLNormalizer\URLCounter;

$normalizer = new URLCounter();

$normalizedURLsPerDomain = $normalizer->countUniqueUrlsPerTopLevelDomain([
    'http://test.edulazaro.com?a=1&b=2',
    'http://edulazaro.com?a=2&b=1',
    'http://neoguias.com'
]);
```

## Testing

To test the package run `composer test`.