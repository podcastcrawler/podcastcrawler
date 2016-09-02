<?php

namespace PodcastCrawler\Tests;

use PodcastCrawler\Tests\PodcastCrawlerBaseTest as Base;
use PodcastCrawler\PodcastCrawler;

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
        $this->instance = new PodcastCrawler();
    }

    /**
     * Test to validate the return of the search sought by the term
     */
    public function testSearch($value = null)
    {
        $value  = empty($value) ? self::TERM : $value;
        $result = $this->instance->get($value);

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('result_count', $result);
        $this->assertArrayHasKey('podcasts', $result);
    }

    /**
     * Test to validate the return of the fields in the search sought by the term
     */
    public function testSearchValues($value = null)
    {
        $value  = empty($value) ? self::TERM : $value;
        $result = $this->instance->get($value);

        foreach ($result['podcasts'] as $podcast) {
            $this->assertArrayHasKey('itunes_id', $podcast);
            $this->assertArrayHasKey('author', $podcast);
            $this->assertArrayHasKey('title', $podcast);
            $this->assertArrayHasKey('episodes', $podcast);
            $this->assertArrayHasKey('image', $podcast);
            $this->assertArrayHasKey('rss', $podcast);
            $this->assertArrayHasKey('genre', $podcast);
        }
    }

    /**
     * Test to validate the return of the search sought by the ID
     */
    public function testSearchByID()
    {
        $this->testSearch(self::ID);
    }

    /**
     * Test to validate the return of the fields in the search sought by the ID
     */
    public function testSearchValuesByID()
    {
        $this->testSearchValues(self::ID);
    }

    /**
     * Test to validate the return of the feed sought by the ID
     */
    public function testFeed()
    {
        $result = $this->instance->find(self::ID);

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('itunes_id', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('image', $result);
        $this->assertArrayHasKey('links', $result);
        $this->assertInternalType('array', $result['links']);
        $this->assertArrayHasKey('site', $result['links']);
        $this->assertArrayHasKey('rss', $result['links']);
        $this->assertArrayHasKey('itunes', $result['links']);
        $this->assertArrayHasKey('genre', $result);
        $this->assertArrayHasKey('language', $result);
        $this->assertArrayHasKey('episodes_total', $result);
        $this->assertArrayHasKey('episodes', $result);
        $this->assertInternalType('array', $result['episodes']);
    }

    /**
     * Test to validate the return of the fields in the feed sought by the ID
     */
    public function testFeedValues()
    {
        $result = $this->instance->find(self::ID);

        foreach ($result['episodes'] as $episode) {
            $this->assertArrayHasKey('title', $episode);
            $this->assertArrayHasKey('mp3', $episode);
            $this->assertArrayHasKey('description', $episode);
            $this->assertArrayHasKey('link', $episode);
            $this->assertArrayHasKey('published_at', $episode);
        }
    }
}
