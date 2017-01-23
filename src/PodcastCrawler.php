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
use Exception;

/**
 * Class Podcastcrawler\Podcastcrawler enables the search for podcasts to get details and mp3 files through many APIs
 *
 * @version   v1.0.0
 * @link      https://github.com/podcastcrawler/podcastcrawler
 * @license   https://github.com/podcastcrawler/podcastcrawler/blob/master/LICENSE.md MIT
 * @copyright 2016 Podcast Crawler
 * @author    Dorian Neto <doriansampaioneto@gmail.com>
 */
class PodcastCrawler
{
    /**
     * The provider
     *
     * @var ProviderInterface
     */
    private $provider;

    /**
     * The construct of the object
     *
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Returns the podcasts
     *
     * @param  string $value The keyword
     * @return array
     */
    public function get($value)
    {
        try {
            $response = $this->search(new Request, $value);
            $output   = $this->provider->build($response);
        } catch (Exception $except) {
            $output = [
                'status_code' => $except->getCode(),
                'message'     => $except->getMessage()
            ];
        }

        return $output;
    }

    /**
     * Get podcasts sought by the term
     *
     * @param  Request $request The Request object
     * @param  string  $value   The URL-encoded keyword you want to search for
     * @return array
     * @throws Exception
     */
    private function search(Request $request, $value)
    {
        $response = $request->create($this->provider->generateUrl($value));

        if (is_null($response)) {
            throw new Exception("Request to Itunes API failed", $request->getStatusCode());
        }

        return [
            'search'      => $response,
            'status_code' => $request->getStatusCode(),
        ];
    }

    /**
     * Returns the podcast details
     *
     * @param  string $feedUrl The podcast feed URL
     * @return array
     */
    public function find($feedUrl)
    {
        try {
            $response = $this->read(new Request, $feedUrl);

            libxml_use_internal_errors(true);

            try {
                $feed = new SimpleXMLElement($response['feed'], LIBXML_NOCDATA, false);
            } catch (Exception $except) {
                $response_repaired = Xml::repair($response['feed']);
                $feed              = new SimpleXMLElement($response_repaired, LIBXML_NOCDATA, false);
            }

            return $this->provider->buildFeed($feed);
        } catch (Exception $except) {
            return [
                'status_code' => $except->getCode(),
                'message'     => $except->getMessage()
            ];
        }
    }

    /**
     * Set a limit to search
     * @param  int    $limit
     * @return PodcastCrawler\PodcastCrawler
     */
    public function limit($limit)
    {
        $this->provider->setLimit($limit);
        $this->provider->setDefaultQuery();

        return $this;
    }

    /**
     * Get podcasts RSS sought by feed URL
     *
     * @param  Request $request The Request object
     * @param  string  $feedUrl The podcast feed URL
     * @return array
     * @throws Exception
     */
    private function read(Request $request, $feedUrl)
    {
        $output = $request->create($feedUrl);

        if (is_null($output)) {
            throw new Exception("Request to RSS failed", $request->getStatusCode());
        }

        return [
            'feed'        => $output,
            'status_code' => $request->getStatusCode(),
        ];
    }
}
