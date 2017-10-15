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

/**
 * Class Podcastcrawler\Request
 *
 * @version   v1.0.0
 * @link      https://github.com/podcastcrawler/podcastcrawler
 * @license   https://github.com/podcastcrawler/podcastcrawler/blob/master/LICENSE.md MIT
 * @copyright 2016 Podcast Crawler
 * @author    Dorian Neto <doriansampaioneto@gmail.com>
 */
class Request
{
    /**
     * Status code of the HTTP request
     *
     * @var int $statusCode
     */
    private $contentType = null;

    /**
     * Status code of the HTTP request
     *
     * @var int $statusCode
     */
    private $statusCode = null;

    /**
     * Curl error code
     *
     * @var int $errorCode
     */
    private $errorCode = null;

    /**
     * Curl error
     *
     * @var string $error
     */
    private $error = null;

    /**
     * Creates a Request based on a given URI and configuration
     *
     * @param  string $url     URI to be requested
     * @param  array  $options CURL options
     * @return string
     */
    public function create($url, array $options = [])
    {
        $request = curl_init($url);

        $default_options = [
            CURLOPT_FAILONERROR    => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLINFO_HEADER_OUT    => true
        ] + $options;

        curl_setopt_array($request, $default_options);

        $result = curl_exec($request);
        if ($result === false) {
            $this->errorCode = curl_errno($request);
            $this->error = curl_error($request);
        }
        $this->statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        $this->contentType = curl_getinfo($request, CURLINFO_CONTENT_TYPE);
        curl_close($request);

        return $result;
    }

    /**
     * Returns the HTTP status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns the HTTP content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Returns the Curl error code
     *
     * @return int
     */
    public function getCurlErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Returns the Curl error
     *
     * @return string
     */
    public function getCurlError()
    {
        return $this->error;
    }
}
