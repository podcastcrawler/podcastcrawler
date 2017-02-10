<?php
/**
 *  This file is part of the Podcast Crawler package.
 *
 *  Copyright (c) 2016 Dorian Neto
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace PodcastCrawler\Provider;

use PodcastCrawler\ProviderInterface;
use DateTime;
use SimpleXMLElement;

/**
 * Class Podcastcrawler\Provider\DigitalPodcast
 *
 * @version   v1.1.0
 * @link      https://github.com/podcastcrawler/podcastcrawler
 * @license   https://github.com/podcastcrawler/podcastcrawler/blob/master/LICENSE.md MIT
 * @copyright 2016 Podcast Crawler
 * @author    Dorian Neto <doriansampaioneto@gmail.com>
 */
class DigitalPodcast extends AbstractProvider implements ProviderInterface
{
    /**
     * The Application ID
     *
     * @var string
     */
    const APP_ID = "podcastsearchdemo";

    /**
     * Base url to search by keyword
     *
     * @var string
     */
    const SEARCH_URL = "http://api.digitalpodcast.com/v2r/search";

    /**
     * Specifies the kind of format the podcast search service produces
     *
     * @var string
     */
    const FORMAT = "rss";

    /**
     * The number of results to return
     *
     * @var int
     */
    private $limit = 15;

    /**
     * Array with default query string values to implement in base url
     *
     * @var string
     */
    private $defaultQuery = null;

    /**
     * The construct of the object
     */
    public function __construct()
    {
        $this->setDefaultQuery();
    }

    /**
     * Returns the limit for search
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the limit for search
     *
     * @param int $limit The limit
     */
    public function setLimit($limit)
    {
        // `-1` being used here because the API returns 3 items when `results=2`.
        $this->limit = ((int) $limit - 1);
    }

    /**
     * Set default URL query for search
     */
    public function setDefaultQuery()
    {
        $this->defaultQuery = http_build_query([
            'results' => $this->limit,
            'appid'   => self::APP_ID,
            'format'  => self::FORMAT
        ]);
    }

    /**
     * Generate an URL to enable the searching
     *
     * @param  string $value The keyword to be searching
     * @return string
     */
    public function generateUrl($value)
    {
        $value = urlencode($value);
        $url   = self::SEARCH_URL . "?keywords={$value}";

        return $url . '&' . $this->defaultQuery;
    }

    /**
     * Structuring the response from API through the search
     *
     * @param  array $response Response from API
     * @return array
     */
    public function build(array $response)
    {
        $xml = new SimpleXMLElement($response['search']);
        $xml = $xml->channel;

        $output['result_count'] = count($xml->item);

        foreach($xml->item as $value) {
            $output['podcasts'][] = [
                'title' => utf8_decode($value->title),
                'rss'   => (string) $value->source,
                'link'  => (string) $value->link,
            ];
        }

        return $output;
    }
}
