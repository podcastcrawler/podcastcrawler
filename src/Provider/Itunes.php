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

/**
 * Class Podcastcrawler\Provider\Itunes
 *
 * @version   v1.0.0
 * @link      https://github.com/podcastcrawler/podcastcrawler
 * @license   https://github.com/podcastcrawler/podcastcrawler/blob/master/LICENSE.md MIT
 * @copyright 2016 Podcast Crawler
 * @author    Dorian Neto <doriansampaioneto@gmail.com>
 */
class Itunes extends AbstractProvider implements ProviderInterface
{
    /**
     * Base url to search by keyword
     *
     * @var string
     */
    const SEARCH_URL = "https://itunes.apple.com/search";

    /**
     * Base url to search by Collection ID
     *
     * @var string
     */
    const LOOKUP_URL = "https://itunes.apple.com/lookup";

    /**
     * The number of search results you want the iTunes Store to return
     *
     * @var int
     */
    const LIMIT = 15;

    /**
     * The type of results you want returned, relative to the specified media type
     *
     * @var string
     */
    const ENTITY = "podcast";

    /**
     * The media type you want to search for
     *
     * @var string
     */
    const MEDIA = "podcast";

    /**
     * Array with default query string values to implement in selected base url
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
            'limit'     => self::LIMIT,
            'entity'    => self::ENTITY,
            'media'     => self::MEDIA
        ]);
    }

    /**
     * Generate an URL to enable the searching
     *
     * @param  string $value Can be a term, ID etc
     * @return string
     */
    public function generateUrl($value)
    {
        $value = is_string($value) ? urlencode($value) : $value;
        $url   = is_int($value) ? self::LOOKUP_URL . "?id={$value}" : self::SEARCH_URL . "?term={$value}";

        return $url . '&' . $this->defaultQuery;
    }

    /**
     * Structuring the response from API through the search
     *
     * @param  array  $response Response from API
     * @return array
     */
    public function build(array $response)
    {
        $response = json_decode($response['search']);
        $output['result_count'] = $response->resultCount;

        foreach($response->results as $value) {
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

        return $output;
    }
}
