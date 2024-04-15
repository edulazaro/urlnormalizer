<?php

namespace Edulazaro\URLNormalizer;

/**
 * Class to manage and normalize URLs
 */
class URLNormalizer
{
    /**
     * Normalize a URL
     *
     * @param string $url The URL to normalize
     * @return string|false The normalized URL
     *
    */
    public function normalize($url)
    {
        $url = $this->decodeUnreservedCharacters($url);
        $url = $this->normalizePercentEncoding($url);
        $url = rtrim($url, '?');

        $urlComponents = parse_url($url);

        if (isset($urlComponents['port'])) {
            if (($urlComponents['scheme'] === 'http' && $urlComponents['port'] == 80) ||
                ($urlComponents['scheme'] === 'https' && $urlComponents['port'] == 443)
            ) {
                unset($urlComponents['port']);
            }
        }

        if (!empty($urlComponents['path'])) {
            $urlComponents['path'] = $this->removeDotSegments($urlComponents['path']);
        }

        if (empty($urlComponents['path'])) {
            $urlComponents['path'] = '/';
        }

        $urlComponents['path'] = '/' . ltrim($urlComponents['path'], '/');

        if (!empty($urlComponents['query'])) {
            $urlComponents['query'] = $this->normalizeURLQueryString($urlComponents['query']);
        }

        if (!empty($urlComponents['scheme'])) {
            $urlComponents['scheme'] = strtolower($urlComponents['scheme']);
        }

        if (!empty($urlComponents['host'])) {
            $urlComponents['host'] = strtolower($urlComponents['host']);
        }

        return $this->buildURLFromParts($urlComponents);
    }

    /**
     * Extracts the top level domain from a URL.
     *
     * @param string $url The URL
     * @return string|false The top-level domain of the URL or false if not found
     *
    */
    public function getURLTopLevelDomain($url)
    {
        $urlComponents = parse_url($url);

        if (!empty($urlComponents['host'])) {

            $hostParts = explode('.', $urlComponents['host']);
            $count = count($hostParts);

            if ($count >= 2) {
                return $hostParts[$count - 2] . '.' . $hostParts[$count - 1];
            }

            return $url;
        }

        return false;
    }

    /**
     * Compose a URL using its parts
     *
     * @param array $parts Array with the standard parts of a URL: 'scheme', 'host', 'port', 'path', 'query', 'fragment'
     * @return string A URL.
     */
    private function buildURLFromParts(array $parts)
    {
        $scheme = !empty($parts['scheme']) ? "{$parts['scheme']}://" : '';
        $host = !empty($parts['host']) ? "{$parts['host']}" : '';
        $port = !empty($parts['port']) ? ":{$parts['port']}" : '';
        $path = !empty($parts['path']) ? "{$parts['path']}" : '/';
        $query = !empty($parts['query']) ? "?{$parts['query']}" : '';
        $fragment = !empty($parts['fragment']) ? "#{$parts['fragment']}" : '';

        return $scheme . $host . $port . $path . $query . $fragment;
    }

    /**
     * Removes dot segmentsfrom a URL path according to RFC 3986.
     *
     * @param string $path The path with dot segments
     * @return string The normalized path withput dot segments
     */
    public function removeDotSegments($path)
    {
        $fixedPath = [];

        $segments = explode('/', $path);

        foreach ($segments as $segment) {
            if ($segment === '..') {

                if (!empty($fixedPath)) array_pop($fixedPath);
            } elseif ($segment !== '.' && $segment !== '') {
                $fixedPath[] = $segment;
            }
        }

        $fixedPath = implode('/', $fixedPath);

        return $fixedPath;
    }

    /**
     * Decodes unreserved characters of a URL.
     * 
     * @param string $url The URL to decode
     * @return string The URL with the unreserved characters decoded
     */
    public function decodeUnreservedCharacters($url)
    {
        $unreservedChars = [
            '%2D' => '-', '%2E' => '.', '%5F' => '_', '%7E' => '~',
            '%30' => '0', '%31' => '1', '%32' => '2', '%33' => '3',
            '%34' => '4', '%35' => '5', '%36' => '6', '%37' => '7',
            '%38' => '8', '%39' => '9', '%41' => 'A', '%42' => 'B',
            '%43' => 'C', '%44' => 'D', '%45' => 'E', '%46' => 'F',
            '%47' => 'G', '%48' => 'H', '%49' => 'I', '%4A' => 'J',
            '%4B' => 'K', '%4C' => 'L', '%4D' => 'M', '%4E' => 'N',
            '%4F' => 'O', '%50' => 'P', '%51' => 'Q', '%52' => 'R',
            '%53' => 'S', '%54' => 'T', '%55' => 'U', '%56' => 'V',
            '%57' => 'W', '%58' => 'X', '%59' => 'Y', '%5A' => 'Z',
            '%61' => 'a', '%62' => 'b', '%63' => 'c', '%64' => 'd',
            '%65' => 'e', '%66' => 'f', '%67' => 'g', '%68' => 'h',
            '%69' => 'i', '%6A' => 'j', '%6B' => 'k', '%6C' => 'l',
            '%6D' => 'm', '%6E' => 'n', '%6F' => 'o', '%70' => 'p',
            '%71' => 'q', '%72' => 'r', '%73' => 's', '%74' => 't',
            '%75' => 'u', '%76' => 'v', '%77' => 'w', '%78' => 'x',
            '%79' => 'y', '%7A' => 'z'
        ];

        return str_replace(array_keys($unreservedChars), array_values($unreservedChars), $url);
    }

    /**
     * Sort the query string alphabetically
     *
     * @param string $queryString The query string
     * @return string The sorted query string
     */
    public function normalizeURLQueryString($queryString = '')
    {
        if (empty($queryString)) {
            return $queryString;
        }

        parse_str($queryString, $queryParts);
        ksort($queryParts);

        return http_build_query($queryParts);
    }

    /** 
     * Transforms to uppercase the percent encoded characters.
     *
     * @param string $URL The URL to normalize
     * @return string TheURL with the percent encoded values in uppercase
     */
    public function normalizePercentEncoding($URL)
    {
        return preg_replace_callback('/%[0-9a-fA-F]{2}/', function ($matches) {
            return strtoupper($matches[0]);
        }, $URL);
    }
}
