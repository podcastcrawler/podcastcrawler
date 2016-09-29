<?php

namespace PodcastCrawler\Tests;

use PodcastCrawler\Tests\PodcastCrawlerBaseTest as Base;
use PodcastCrawler\PodcastCrawler;
use PodcastCrawler\Provider\Itunes;

class PodcastCrawlerTest extends Base
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
        $this->instance = new PodcastCrawler(new Itunes());
    }

    /**
     * Test to validate the return of the search sought by the term
     */
    public function testGet()
    {
        $result = $this->instance->get(self::TERM);

        $this->assertInternalType('array', $result);
    }

    /**
     * Test to validate the return of the feed sought by RSS URL
     */
    public function testFind()
    {
        $result = $this->instance->find(self::RSS_URL);

        $this->assertInternalType('array', $result);
    }
}
