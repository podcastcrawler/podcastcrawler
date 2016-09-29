<?php

namespace PodcastCrawler\Tests\Provider;

use PodcastCrawler\Tests\PodcastCrawlerBaseTest as Base;
use PodcastCrawler\PodcastCrawler;
use PodcastCrawler\Provider\DigitalPodcast;

class DigitalPodcastProviderTest extends Base
{
    /**
     * @var DigitalPodcast $providerInstance
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
        $this->providerInstance = new DigitalPodcast();
        $this->instance = new PodcastCrawler($this->providerInstance);
    }

    public function testDigitalPodcastBuild()
    {
        $result = $this->instance->get(self::TERM);

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('result_count', $result);
        $this->assertArrayHasKey('podcasts', $result);

        foreach ($result['podcasts'] as $podcast) {
            $this->assertArrayHasKey('title', $podcast);
            $this->assertArrayHasKey('rss', $podcast);
            $this->assertArrayHasKey('link', $podcast);
        }
    }

    public function testDigitalPodcastGenerateUrl()
    {
        $url = $this->providerInstance->generateUrl(self::TERM);

        $this->assertInternalType('string', $url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED));
    }
}
