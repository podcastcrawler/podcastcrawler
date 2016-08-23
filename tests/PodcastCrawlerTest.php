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
    }

    /**
     * Test to validate the return of the list sought by the term
     * @runInSeparateProcess
     */
    public function testListByTerm()
    {
        $list = $this->instance->getList(self::TERM);
        $list_decoded = json_decode($list, true);

        $this->assertInternalType('string', $list);
        $this->assertInternalType('string', self::TERM);
        $this->assertInternalType('array', $list_decoded);
        $this->assertArrayHasKey('resultCount', $list_decoded);
        $this->assertArrayHasKey('results', $list_decoded);
    }

    /**
     * Test to validate the return of the fields in the list sought by the term
     * @runInSeparateProcess
     */
    public function testListFieldsByTerm()
    {
        $list = json_decode($this->instance->getList(self::TERM), true);

        $this->assertGreaterThanOrEqual(0, $list['resultCount']);

        foreach ($list['results'] as $item) {
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

    /**
     * Test to validate the return of the list sought by the id
     * @runInSeparateProcess
     */
    public function testListById()
    {
        $list = $this->instance->getList(self::ID);
        $list_decoded = json_decode($list, true);

        $this->assertInternalType('string', $list);
        $this->assertInternalType('int', self::ID);
        $this->assertInternalType('array', $list_decoded);
        $this->assertArrayHasKey('resultCount', $list_decoded);
        $this->assertArrayHasKey('results', $list_decoded);
    }

    /**
     * Test to validate the return of the fields in the list sought by the id
     * @runInSeparateProcess
     */
    public function testListFieldsById()
    {
        $list = json_decode($this->instance->getList(self::ID), true);

        $this->assertGreaterThanOrEqual(0, $list['resultCount']);

        foreach ($list['results'] as $item) {
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
