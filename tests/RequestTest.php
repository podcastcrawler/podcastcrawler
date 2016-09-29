<?php

namespace PodcastCrawler\Tests;

use PodcastCrawler\Tests\PodcastCrawlerBaseTest as Base;
use PodcastCrawler\Request;

class RequestTest extends Base
{
    /**
     * @var object $instance
     */
    protected $instance;

    /**
     * Set up the tests
     */
    public function setUp()
    {
        $this->instance = new Request();
    }

    /**
     * Test the request from url SEARCH
     */
    public function testRequestSearchUrl($value = null)
    {
        $value   = empty($value) ? self::SEARCH_URL : $value;
        $request = $this->instance->create($value);

        $this->assertNotEmpty($request);
        $this->assertInternalType('string', $request);
        $this->assertEquals(200, $this->instance->getStatusCode());
        $this->assertNotEmpty($this->instance->getContentType());
    }

    /**
     * Test the request from url RSS
     */
    public function testRequestRssUrl()
    {
        $this->testRequestSearchUrl(self::RSS_URL);
    }
}
