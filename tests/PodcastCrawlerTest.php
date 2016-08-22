<?php

namespace PodcastCrawler\Tests;

use PHPUnit_Framework_TestCase as PHPUnit;
use PodcastCrawler\PodcastCrawler;

class PodcastCrawlerTest extends PHPUnit
{
    /**
     * Object tested
     * @var object
     */
    private $instance;

    /**
     * Set up the tests
     */
    public function setUp()
    {
        $this->instance = new PodcastCrawler('jovem');
    }

    /**
     * Test to validate the return of the list sought by the term
     */
    public function testList()
    {
        $list = $this->instance->getList();

        $this->assertInternalType('array', $list);
        $this->assertArrayHasKey('resultCount', $list);
        $this->assertArrayHasKey('results', $list);
    }

    /**
     * Test to validate the return of the fields in the list
     */
    public function testListFields()
    {
        $list = $this->instance->getList();

        $this->assertGreaterThanOrEqual(0, $list['resultCount']);

        foreach ($list['results'] as $item) {
            $this->assertArrayHasKey('feedUrl', $item);
            $this->assertArrayHasKey('artistName', $item);
            $this->assertArrayHasKey('collectionName', $item);
            $this->assertArrayHasKey('artworkUrl100', $item);
            $this->assertArrayHasKey('artworkUrl600', $item);
            $this->assertArrayHasKey('country', $item);
            $this->assertArrayHasKey('primaryGenreName', $item);
        }
    }
}
