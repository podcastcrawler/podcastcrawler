<?php

namespace PodcastCrawler;

class Request
{
    /**
     * @var int $statusCode
     */
    private $statusCode = null;

    /**
     * Makes a HTTP request
     * @param string $url URL to be requested
     * @param array $options CURL options
     * @return string
     */
    public function request($url, array $options = [])
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
        $this->statusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);

        return $result;
    }

    /**
     * Return the HTTP status code
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
