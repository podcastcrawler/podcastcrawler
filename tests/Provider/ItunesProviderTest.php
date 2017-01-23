<?php

namespace PodcastCrawler\Tests\Provider;

use PodcastCrawler\Tests\PodcastCrawlerBaseTest as Base;
use PodcastCrawler\PodcastCrawler;
use PodcastCrawler\Provider\Itunes;

class ItunesProviderTest extends Base
{
    /**
     * @var Itunes $providerInstance
     */
    protected $providerInstance;

    /**
     * @var PodcastCrawler $instance
     */
    protected $instance;

    /**
     * Set up the tests
     */
    public function setUp()
    {
        $this->providerInstance = new Itunes();
        $this->instance = new PodcastCrawler($this->providerInstance);
    }

    public function testLimit() {
        $limit = 10;

        $this->providerInstance->setLimit($limit);

        $this->assertEquals($limit, $this->providerInstance->getLimit());
    }

    public function testItunesBuild()
    {
        $result = $this->instance->get(self::TERM);

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('result_count', $result);
        $this->assertArrayHasKey('podcasts', $result);

        foreach ($result['podcasts'] as $podcast) {
            $this->assertArrayHasKey('itunes_id', $podcast);
            $this->assertArrayHasKey('author', $podcast);
            $this->assertArrayHasKey('title', $podcast);
            $this->assertArrayHasKey('episodes', $podcast);
            $this->assertArrayHasKey('image', $podcast);
            $this->assertArrayHasKey('rss', $podcast);
            $this->assertArrayHasKey('itunes', $podcast);
            $this->assertArrayHasKey('genre', $podcast);
        }
    }

    public function testItunesGenerateUrl()
    {
        $url = $this->providerInstance->generateUrl(self::TERM);

        $this->assertInternalType('string', $url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED));
    }
}
