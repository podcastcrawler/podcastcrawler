<?php

namespace PodcastCrawler\Tests;

use PHPUnit_Framework_TestCase as PHPUnit;
use PodcastCrawler\PodcastCrawler;

class PodcastCrawlerTest extends PHPUnit
{
    /**
     * @var array $list
     */
    private $list;

    /**
     * @var string TERM
     */
    const TERM = 'jovem';

    /**
     * Set up the tests
     */
    public function setUp()
    {
        $instance = new PodcastCrawler();
        $this->list = json_decode($instance->getList(self::TERM), true);
    }

    /**
     * Test to validate the return of the list sought by the term
     * @runInSeparateProcess
     */
    public function testList()
    {
        $this->assertInternalType('array', $this->list);
        $this->assertArrayHasKey('resultCount', $this->list);
        $this->assertArrayHasKey('results', $this->list);
    }

    /**
     * Test to validate the return of the fields in the list
     * @runInSeparateProcess
     */
    public function testListFields()
    {
        $this->assertGreaterThanOrEqual(0, $this->list['resultCount']);

        foreach ($this->list['results'] as $item) {
            $this->assertArrayHasKey('feedUrl', $item);
            $this->assertArrayHasKey('artistName', $item);
            $this->assertArrayHasKey('collectionName', $item);
            $this->assertArrayHasKey('collectionId', $item);
            $this->assertArrayHasKey('collectionViewUrl', $item);
            $this->assertArrayHasKey('artworkUrl100', $item);
            $this->assertArrayHasKey('artworkUrl600', $item);
            $this->assertArrayHasKey('country', $item);
            $this->assertArrayHasKey('primaryGenreName', $item);
        }
    }
}
