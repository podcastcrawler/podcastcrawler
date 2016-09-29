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
 * @version   v1.0.0
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
     * The number of results to return
     *
     * @var int
     */
    const RESULTS = 15;

    /**
     * Specifies the kind of format the podcast search service produces
     *
     * @var string
     */
    const FORMAT = "rss";

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
        $this->defaultQuery = http_build_query([
            'appid'   => self::APP_ID,
            'results' => self::RESULTS,
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
