<?php
/**
 *  This file is part of the Podcast Crawler package.
 *
 *  Copyright (c) 2016 Dorian Neto
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace PodcastCrawler;

use SimpleXMLElement;
use DateTime;
use Exception;

/**
 * Class Podcastcrawler\Podcastcrawler enables the search for podcasts to get details and mp3 files through Itunes API
 *
 * @version v0.15.1-beta
 * @link https://github.com/podcastcrawler/podcastcrawler
 * @license https://github.com/podcastcrawler/podcastcrawler/blob/master/LICENSE.md MIT
 * @copyright 2016 Podcast Crawler
 * @author Dorian Neto <doriansampaioneto@gmail.com>
 */
class PodcastCrawler
{
    /**
     * Base url to search by keyword
     * @var string SEARCH_URL
     */
    const SEARCH_URL = "https://itunes.apple.com/search";

    /**
     * Base url to search by Collection ID
     * @var string LOOKUP_URL
     */
    const LOOKUP_URL = "https://itunes.apple.com/lookup";

    /**
     * The number of search results you want the iTunes Store to return
     * @var int LIMIT
     */
    const LIMIT = 15;

    /**
     * The type of results you want returned, relative to the specified media type
     * @var string ENTITY
     */
    const ENTITY = "podcast";

    /**
     * The media type you want to search for
     * @var string MEDIA
     */
    const MEDIA = "podcast";

    /**
     * Array with default query string values to implement in selected base url
     * @var string $defaultQuery
     */
    private $defaultQuery = null;

    /**
     * @return PodcastCrawler
     */
    public function __construct()
    {
        $this->defaultQuery = http_build_query([
            'limit'     => self::LIMIT,
            'entity'    => self::ENTITY,
            'media'     => self::MEDIA
        ]);
    }

    /**
     * Returns the podcasts
     *
     * @param string|int $value A keyword or an ID
     * @return array
     */
    public function get($value)
    {
        try {
            $response = $this->search($value);
            $output['result_count'] = $response['search']->resultCount;

            foreach($response['search']->results as $value) {
                $output['podcasts'][] = [
                    'itunes_id' => $value->collectionId,
                    'author'    => utf8_decode($value->artistName),
                    'title'     => utf8_decode($value->collectionName),
                    'episodes'  => $value->trackCount,
                    'image'     => $value->artworkUrl100,
                    'rss'       => $value->feedUrl,
                    'itunes'    => $value->collectionViewUrl,
                    'genre'     => $value->primaryGenreName,
                ];
            }
        } catch (Exception $except) {
            $output = [
                'status_code' => $except->getCode(),
                'message'     => $except->getMessage()
            ];
        }

        return $output;
    }

    /**
     * Get podcasts sought by the term or the Collection ID
     *
     * @param string|int $value The URL-encoded keyword or ID int you want to search for
     * @return array
     * @throws Exception
     */
    private function search($value)
    {
        $Request  = new Request;
        $value    = is_string($value) ? urlencode($value) : $value;
        $url      = is_int($value) ? self::LOOKUP_URL . "?id={$value}" : self::SEARCH_URL . "?term={$value}";
        $url      = $url . '&' . $this->defaultQuery;
        $response = $Request->request($url);

        if (is_null($response)) {
            throw new Exception("Request to Itunes API failed", $Request->getStatusCode());
        }

        $output = [
            'search'      => json_decode($response),
            'status_code' => $Request->getStatusCode(),
        ];

        return $output;
    }

    /**
     * Returns the podcast details
     *
     * @param int $id The podcast ID
     * @return array
     */
    public function feed($id)
    {
        try {
            $response = $this->getRss($id);

            libxml_use_internal_errors(true);

            try {
                $feed = new SimpleXMLElement($response['feed'], LIBXML_NOCDATA, false);
            } catch (Exception $except) {
                $response_repaired = Xml::repair($response['feed']);
                $feed              = new SimpleXMLElement($response_repaired, LIBXML_NOCDATA, false);
            }

            $output = [
                'itunes_id'      => $response['search']->results[0]->collectionId,
                'title'          => (string) utf8_decode($feed->channel->title),
                'description'    => (string) utf8_decode($feed->channel->description),
                'image'          => (string) $feed->channel->image->url,
                'links'          => [
                    'site'   => (string) $feed->channel->link,
                    'rss'    => $response['search']->results[0]->feedUrl,
                    'itunes' => $response['search']->results[0]->collectionViewUrl
                ],
                'genre'          => $response['search']->results[0]->primaryGenreName,
                'language'       => (string) $feed->channel->language,
                'episodes_total' => (int) count($feed->channel->item)
            ];

            foreach($feed->channel->item as $value) {
                $published_at = new DateTime($value->pubDate);
                $published_at = $published_at->format('Y-m-d');

                $output['episodes'][] = [
                    'title'        => (string) utf8_decode($value->title),
                    'mp3'          => isset($value->enclosure) ? (string) $value->enclosure->attributes()->url : null,
                    'description'  => (string) utf8_decode($value->description),
                    'link'         => (string) $value->link,
                    'published_at' => $published_at,
                ];
            }
        } catch (Exception $except) {
            $output = [
                'status_code' => $except->getCode(),
                'message'     => $except->getMessage()
            ];
        }

        return $output;
    }

    /**
     * Get podcasts RSS sought by Collection ID
     *
     * @param int $id The podcast id
     * @return array
     * @throws Exception
     */
    private function getRss($id)
    {
        $response = $this->search($id);

        if (!isset($response['search']->results[0], $response['search']->results[0]->feedUrl)) {
            throw new Exception("Data response by Itunes are inconsistent", $response['status_code']);
        }

        // Request the RSS
        $Request = new Request;
        $rss_url = $response['search']->results[0]->feedUrl;
        $output  = $Request->request($rss_url);

        if (is_null($output)) {
            throw new Exception("Request to RSS failed", $Request->getStatusCode());
        }

        $output = [
            'feed'        => $output,
            'search'      => $response['search'],
            'status_code' => $Request->getStatusCode(),
        ];

        return $output;
    }
}
