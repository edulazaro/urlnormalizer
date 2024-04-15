<?php

namespace Edulazaro\URLNormalizer;

use PHPUnit\Framework\TestCase;

use Edulazaro\URLNormalizer\URLCounter;

final class CountUniqueURLsPerDomainTest extends TestCase
{
    private URLCounter $URLCounter;

    public function setUp(): void
    {
        parent::setUp();

        $this->URLCounter = new URLCounter();
    }

    /** @tests */
    public function test_a_domains_with_same_top_domain_return_single_key_and_counter()
    {
        $testURLS = [
            'https://example.com', 'https://test.example.com'
        ];

        $this->assertEquals($this->URLCounter->countUniqueUrlsPerTopLevelDomain($testURLS), [
            'example.com' => 2
        ]);
    }
    
    /** @tests */
    public function test_domains_with_different_top_domains_return_separate_keys()
    {
        $testURLS = [
            'https://example2.com',
            'http://test.example2.com?b=2',
            'http://test.test.example2.com',
            'http://test.example2.com?a=1',
            'https://test.example.com',
            'http://example.com',
            'http://example.com?a=1'
        ];

        $this->assertEquals($this->URLCounter->countUniqueUrlsPerTopLevelDomain($testURLS), [
            'example2.com' => 4,
            'example.com' => 3
        ]);
    }
}
