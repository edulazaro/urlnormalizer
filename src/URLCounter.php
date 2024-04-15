<?php

namespace Edulazaro\URLNormalizer;;

use Edulazaro\URLNormalizer\URLNormalizer;

class URLCounter
{
    private URLNormalizer $urlNormalizer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->urlNormalizer = new URLNormalizer();
    }

    /**
     * Counts unique URLs from a provided array after normalizing them.
     *
     * @param array $urls An array of URLs to be normalized and counted.
     * @return int Returns the count of unique normalized URLs.
     */
    public function countUniqueUrls(array $urls)
    {
        $normalizedURLs = [];

        foreach ($urls as $url) {
            $normalizedURL = $this->urlNormalizer->normalize($url);

            if (empty($normalizedURLs[$normalizedURL])) {
                $normalizedURLs[$normalizedURL] = 1;
            } else {
                $normalizedURLs[$normalizedURL]++;
            }
        }

        return count($normalizedURLs);
    }

    /**
     * Counts unique normalized URLs per top-level domain (TLD) from a provided URL array.
     *
     * @param array $urls An array of URLs to be normalized and counted by their TLD.
     * @return array Returns an associative array with TLDs as keys and counts of unique URLs as values.
     */
    public function countUniqueUrlsPerTopLevelDomain($urls)
    {
        $uniqueURLs = []; // To hold unique URLs normalized
        $uniqueURLsPerDomain = [];  // To hold counts per TLD

        foreach ($urls as $url) {

            $normalizedUrl = $this->urlNormalizer->normalize($url);

            $domain = $this->urlNormalizer->getURLTopLevelDomain($url);

            if (!$domain) continue;

            $uniqueURLs[$domain][$normalizedUrl] = true;
        }

        foreach ($uniqueURLs as $domain => $urls) {
            $uniqueURLsPerDomain[$domain] = count($urls);
        }

        return $uniqueURLsPerDomain;
    }
}
