<?php

namespace Edulazaro\URLNormalizer;

use PHPUnit\Framework\TestCase;

use Edulazaro\URLNormalizer\URLCounter;


final class CountUniqueURLsTest extends TestCase
{

    private URLCounter $URLCounter;

    public function setUp(): void
    {
        parent::setUp();

        $this->URLCounter = new URLCounter();
    }

    /** @tests */
    public function test_equal_urls_are_the_same()
    {
        $testURLS = [
            'https://example.com', 'https://example.com'
        ];

        $this->assertEquals($this->URLCounter->countUniqueUrls($testURLS), 1);
    }

    /** @tests */
    public function test_urls_with_different_scheme_are_not_unique()
    {
        $testURLS = [
            "https://example.com", "http://example.com"
        ];
    
        $this->assertEquals($this->URLCounter ->countUniqueUrls($testURLS), 2);
    }


    /** @tests */
    public function test_urls_with_trailing_question_mark_are_the_same()
    {    
        $testURLS = [
            "https://example.com?", "https://example.com"
        ];
                
        $this->assertEquals($this->URLCounter->countUniqueUrls($testURLS), 1);
    }


    /** @tests */
    public function test_urls_with_query_string_in_different_order_are_the_same()
    {    
        $testURLS = [
            "https://example.com?a=1&b=2", "https://example.com?b=2&a=1"
        ];
                                
        $this->assertEquals($this->URLCounter->countUniqueUrls($testURLS), 1);
    }

    /** @tests */
    public function test_urls_with_different_query_string_params_are__not_the_same()
    {        
        $testURLS = [
            "https://example.com?a=1&b=2", "https://example.com?a=2&b=2"
        ];
                                        
        $this->assertEquals($this->URLCounter->countUniqueUrls($testURLS), 2);
    }


    /** @tests */
    public function test_urls_with_different_domain_are_not_the_same()
    {        
        $testURLS = [
            "https://test.example.com", "https://example.com"
        ];
                                        
        $this->assertEquals($this->URLCounter->countUniqueUrls($testURLS), 2);
    }

    /** @tests */
    public function test_many_urls_return_the_right_value()
    {        
        $testURLS = [
            "https://test.example.com",
            "https://example.com",
            "https://example.com",
            "https://example.com?a=1&b=2",
            "https://example.com?a=2&b=2",
            "https://example.com?a=1&b=2",
            "https://example.com?b=2&a=1",
            "https://example.com?",
            "http://example.com"
        ];
                                            
        $this->assertEquals($this->URLCounter->countUniqueUrls($testURLS), 5);
    }
}
