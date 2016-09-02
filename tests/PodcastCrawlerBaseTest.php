<?php

namespace PodcastCrawler\Tests;

use PHPUnit_Framework_TestCase as PHPUnit;

abstract class PodcastCrawlerBaseTest extends PHPUnit
{
    /**
     * @var string TERM
     */
    const TERM = 'jovem nerd';

    /**
     * @var int ID
     */
    const ID = 1103141552;

    /**
     * @var string SEARCH_URL
     */
    const SEARCH_URL = 'https://itunes.apple.com/search?term=jovem&limit=15&entity=podcast&media=podcast';

    /**
     * @var string LOOKUP_URL
     */
    const LOOKUP_URL = 'https://itunes.apple.com/lookup?id=1103141552&limit=15&entity=podcast&media=podcast';

    /**
     * @var string RSS_URL
     */
    const RSS_URL = 'https://jovemnerd.com.br/feed-nerdcast/';
}
