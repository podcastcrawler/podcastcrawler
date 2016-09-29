<?php

namespace PodcastCrawler\Tests\Provider;

use PodcastCrawler\Tests\PodcastCrawlerBaseTest as Base;
use PodcastCrawler\PodcastCrawler;
use PodcastCrawler\Provider\Itunes;

class AbstractProviderProviderTest extends Base
{
    /**
     * @var PodcastCrawler $instance
     */
    protected $instance;

    /**
     * Set up the tests
     */
    public function setUp()
    {
        $this->instance = new PodcastCrawler(new Itunes());
    }

    public function testBuildFeed()
    {
        $result = $this->instance->find(self::RSS_URL);

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('image', $result);
        $this->assertArrayHasKey('site', $result);
        $this->assertArrayHasKey('language', $result);
        $this->assertArrayHasKey('episodes_total', $result);
        $this->assertArrayHasKey('episodes', $result);
        $this->assertInternalType('array', $result['episodes']);

        foreach ($result['episodes'] as $episode) {
            $this->assertArrayHasKey('title', $episode);
            $this->assertArrayHasKey('mp3', $episode);
            $this->assertArrayHasKey('description', $episode);
            $this->assertArrayHasKey('link', $episode);
            $this->assertArrayHasKey('published_at', $episode);
        }
    }
}
