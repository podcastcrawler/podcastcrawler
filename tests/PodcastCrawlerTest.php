<?php

namespace PodcastCrawler\Tests;

use PHPUnit_Framework_TestCase as PHPUnit;
use PodcastCrawler\PodcastCrawler;

class PodcastCrawlerTest extends PHPUnit
{
    /**
     * @var string TERM
     */
    const TERM = 'jovem';

    /**
     * @var int ID
     */
    const ID = 381816509;

    /**
     * @var object $instance
     */
    private $instance;

    /**
     * Set up the tests
     */
    public function setUp()
    {
        $this->instance = new PodcastCrawler();
    }

    /**
     * Test to validate the return of the list sought by the term
     * @runInSeparateProcess
     */
    public function testListByTerm()
    {
        $list = $this->instance->search(self::TERM);

        $this->assertEquals(200, $this->instance->getStatusCode());
        $this->assertInternalType('array', $list);
        $this->assertArrayHasKey('result_count', $list);
        $this->assertArrayHasKey('podcasts', $list);
    }

    /**
     * Test to validate the return of the fields in the list sought by the term
     * @runInSeparateProcess
     */
    public function testListFieldsByTerm()
    {
        $list = $this->instance->search(self::TERM);

        foreach ($list['podcasts'] as $item) {
            $this->assertArrayHasKey('itunes_id', $item);
            $this->assertArrayHasKey('author', $item);
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('episodes', $item);
            $this->assertArrayHasKey('image', $item);
            $this->assertArrayHasKey('rss', $item);
            $this->assertArrayHasKey('genre', $item);
        }
    }

    /**
     * Test to validate the return of the list sought by the ID
     * @runInSeparateProcess
     */
    public function testListByID()
    {
        $list = $this->instance->search(self::ID);

        $this->assertEquals(200, $this->instance->getStatusCode());
        $this->assertInternalType('array', $list);
        $this->assertArrayHasKey('result_count', $list);
        $this->assertArrayHasKey('podcasts', $list);
    }

    /**
     * Test to validate the return of the fields in the list sought by the ID
     * @runInSeparateProcess
     */
    public function testListFieldsByID()
    {
        $list = $this->instance->search(self::ID);

        foreach ($list['podcasts'] as $item) {
            $this->assertArrayHasKey('itunes_id', $item);
            $this->assertArrayHasKey('author', $item);
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('episodes', $item);
            $this->assertArrayHasKey('image', $item);
            $this->assertArrayHasKey('rss', $item);
            $this->assertArrayHasKey('genre', $item);
        }
    }

    /**
     * Test to validate the return of the feed sought by the id
     * @runInSeparateProcess
     */
    public function testFeed()
    {
        $list = $this->instance->feed(self::ID);

        $this->assertEquals(200, $this->instance->getStatusCode());
        $this->assertInternalType('array', $list);
        $this->assertArrayHasKey('itunes_id', $list);
        $this->assertArrayHasKey('title', $list);
        $this->assertArrayHasKey('description', $list);
        $this->assertArrayHasKey('image', $list);
        $this->assertInternalType('array', $list['links']);
        $this->assertArrayHasKey('links', $list);
        $this->assertArrayHasKey('site', $list['links']);
        $this->assertArrayHasKey('rss', $list['links']);
        $this->assertArrayHasKey('itunes', $list['links']);
        $this->assertArrayHasKey('genre', $list);
        $this->assertArrayHasKey('language', $list);
        $this->assertArrayHasKey('episodes', $list);
    }

    /**
     * Test to validate the return of the fields in the feed sought by the id
     * @runInSeparateProcess
     */
    public function testFeedMp3Fields()
    {
        $list = $this->instance->feed(self::ID);

        foreach ($list['mp3'] as $item) {
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('mp3', $item);
            $this->assertArrayHasKey('description', $item);
            $this->assertArrayHasKey('link', $item);
            $this->assertArrayHasKey('published_at', $item);
        }
    }
}
