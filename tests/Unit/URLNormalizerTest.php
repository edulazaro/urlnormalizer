<?php

namespace Edulazaro\URLNormalizer;

use PHPUnit\Framework\TestCase;

use Edulazaro\URLNormalizer\URLNormalizer;

final class URLNormalizerTest extends TestCase
{
    private URLNormalizer $URLNormalizer;

    public function setUp(): void
    {
        parent::setUp();

        $this->URLNormalizer = new URLNormalizer();
    }

    public function test_normalize_percent_encoding()
    {
        $expectedResult = "https://example.com/param%2A%3A";

        $result = $this->URLNormalizer->normalizePercentEncoding("https://example.com/param%2a%3a");

        $this->assertEquals($expectedResult, $result, 'The percent encoded symbols are returned in uppercase');
    }

    public function test_the_query_string_is_correctly_sorted()
    {
        $expectedResult = "a=3&b=2&c=1";
        $result = $this->URLNormalizer->normalizeURLQueryString('c=1&b=2&a=3');
        $this->assertEquals($expectedResult, $result, 'The query string must be corretly sorted');

        $expectedResult = '';
        $result = $this->URLNormalizer->normalizeURLQueryString('');
        $this->assertEquals($expectedResult, $result, 'An empty query string should return empty');

        $expectedResult = "a=1";
        $result = $this->URLNormalizer->normalizeURLQueryString('a=1');
        $this->assertEquals($expectedResult, $result, 'When there is only a single parameter, the query string ramains unchanged');
    }

    public function test_decoded_unreserved_characters()
    {
        $expectedResult = 'https://example.com/3var-Q';
        $result = $this->URLNormalizer->decodeUnreservedCharacters('https://example.com/%33var%2D%51');
        $this->assertEquals($expectedResult, $result, 'The URL must have the percent-encoded unreserved characters decoded.');
    }

    public function test_dot_segments_are_removed_from_path()
    {
        $expectedResult = 'a/b';
        $result = $this->URLNormalizer->removeDotSegments('a/./b/.');
        $this->assertEquals($expectedResult, $result, 'The DOT segments must be removed.');
    }

    public function test_the_top_level_domain_is_returned()
    {
        $expectedResult = 'example.com';

        $result = $this->URLNormalizer->getURLTopLevelDomain('https://test.example.com');
        $this->assertEquals($expectedResult, $result, 'The DOT segments must be removed.');
    }

    public function test_urls_are_normalized()
    {
        $expectedResult = 'http://www.example.com/';
        $result = $this->URLNormalizer->normalize('HTTP://www.Example.com/');
        $this->assertEquals($expectedResult, $result, 'Transform characters to lowercase correctly.');

        $expectedResult = 'http://www.example.com/path';
        $result = $this->URLNormalizer->normalize('http://www.example.com:80/path');
        $this->assertEquals($expectedResult, $result, 'The standard ports are removed.');


        $expectedResult = 'http://example.com/a/b/c/d.html';
        $result = $this->URLNormalizer->normalize('http://example.com/../a/b/c/./d.html');
        $this->assertEquals($expectedResult, $result, 'The dots are removed.');

        $expectedResult = 'http://example.com/?a=1&b=2';
        $result = $this->URLNormalizer->normalize('http://example.com/?b=2&a=1');
        $this->assertEquals($expectedResult, $result, 'The query string params are ordered.');

    }
}