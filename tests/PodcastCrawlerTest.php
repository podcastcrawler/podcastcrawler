<?php

namespace PodcastCrawler\Tests;

use PHPUnit_Framework_TestCase as PHPUnit;
use PodcastCrawler\PodcastCrawler;

class PodcastCrawlerTest extends PHPUnit
{
    /**
     * @var object $instance
     */
    private $instance;

    /**
     * @var string TERM
     */
    const TERM = 'jovem';

    /**
     * @var int ID
     */
    const ID = 381816509;

    /**
     * Set up the tests
     */
    public function setUp()
    {
        $this->instance = new PodcastCrawler();
        $this->instance->responseJson = true;
    }

    /**
     * Test to validate the return of the list sought by the term
     * @runInSeparateProcess
     */
    public function testListByTerm()
    {
        $list = $this->instance->search(self::TERM);
        $list_decoded = json_decode($list, true);

        $this->assertInternalType('string', $list);
        $this->assertInternalType('array', $list_decoded);
        $this->assertEquals(200, $this->instance->getStatusCode());
        $this->assertArrayHasKey('result_count', $list_decoded);
        $this->assertArrayHasKey('podcasts', $list_decoded);
    }

    /**
     * Test to validate the return of the fields in the list sought by the term
     * @runInSeparateProcess
     */
    public function testListFieldsByTerm()
    {
        $list = json_decode($this->instance->search(self::TERM), true);

        $this->assertGreaterThanOrEqual(0, $list['result_count']);

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
        $list_decoded = json_decode($list, true);

        $this->assertInternalType('string', $list);
        $this->assertInternalType('array', $list_decoded);
        $this->assertEquals(200, $this->instance->getStatusCode());
        $this->assertArrayHasKey('result_count', $list_decoded);
        $this->assertArrayHasKey('podcasts', $list_decoded);
    }

    /**
     * Test to validate the return of the fields in the list sought by the ID
     * @runInSeparateProcess
     */
    public function testListFieldsByID()
    {
        $list = json_decode($this->instance->search(self::TERM), true);

        $this->assertGreaterThanOrEqual(0, $list['result_count']);

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
        $list_decoded = json_decode($list, true);

        $this->assertInternalType('string', $list);
        $this->assertInternalType('array', $list_decoded);
        $this->assertEquals(200, $this->instance->getStatusCode());
        $this->assertArrayHasKey('itunes_id', $list_decoded);
        $this->assertArrayHasKey('title', $list_decoded);
        $this->assertArrayHasKey('description', $list_decoded);
        $this->assertArrayHasKey('image', $list_decoded);
        $this->assertInternalType('array', $list_decoded['links']);
        $this->assertArrayHasKey('links', $list_decoded);
        $this->assertArrayHasKey('site', $list_decoded['links']);
        $this->assertArrayHasKey('rss', $list_decoded['links']);
        $this->assertArrayHasKey('itunes', $list_decoded['links']);
        $this->assertArrayHasKey('genre', $list_decoded);
        $this->assertArrayHasKey('language', $list_decoded);
        $this->assertArrayHasKey('episodes', $list_decoded);
    }

    /**
     * Test to validate the return of the fields in the feed sought by the id
     * @runInSeparateProcess
     */
    public function testFeedMp3Fields()
    {
        $list = json_decode($this->instance->feed(self::ID), true);

        $this->assertGreaterThan(0, $list['episodes']);

        foreach ($list['mp3'] as $item) {
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('mp3', $item);
            $this->assertArrayHasKey('description', $item);
            $this->assertArrayHasKey('link', $item);
            $this->assertArrayHasKey('published_at', $item);
        }
    }
}
