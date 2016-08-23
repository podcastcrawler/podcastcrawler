<?php

namespace PodcastCrawler;

class PodcastCrawler
{
    /**
     * @var string $defaultQueryString
     */
    private $defaultQueryString = null;

    /**
     * @var string SEARCH_URL
     */
    const SEARCH_URL = "https://itunes.apple.com/search";

    /**
     * @var string LOOKUP_URL
     */
    const LOOKUP_URL = "https://itunes.apple.com/lookup";

    /**
     * @var int LIMIT The number of search results you want the iTunes Store to return
     */
    const LIMIT = 15;

    /**
     * @var string ENTITY The type of results you want returned, relative to the specified media type
     */
    const ENTITY = "podcast";

    /**
     * @var string MEDIA The media type you want to search for
     */
    const MEDIA = "podcast";

    /**
     * @return void
     */
    public function __construct()
    {
        $this->defaultQueryString = http_build_query([
            'limit'     => self::LIMIT,
            'entity'    => self::ENTITY,
            'media'     => self::MEDIA
        ]);
    }

    /**
     * Return the podcasts found sought by the term (string) or ID (int)
     * @param string|int $value The URL-encoded text string or id int you want to search for
     * @return string
     */
    public function search($value)
    {
        $value     = is_int($value) ? self::LOOKUP_URL . "?id={$value}" : self::SEARCH_URL . "?term={$value}";
        $to_search = urldecode($value . '&' . $this->defaultQueryString);
        return $this->request($to_search);
    }

    /**
     * Send a http request and return the response
     * @param string $url
     * @param array $options CURL options
     * @return string
     */
    private function request($url, array $options = [])
    {
        $ch = curl_init($url);

        $default_options = [
            CURLOPT_FAILONERROR    => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLINFO_HEADER_OUT    => true
        ] + $options;

        curl_setopt_array($ch, $default_options);

        $result    = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->responseJson($result, $http_code);
    }

    /**
     * Response the data in json format
     * @param string|array $data
     * @param int $httpCode
     * @return string
     */
    private function responseJson($data, $httpCode)
    {
        header_remove();
        http_response_code($httpCode);

        $status = [
            200 => '200 OK',
            400 => '400 Bad Request',
            500 => '500 Internal Server Error'
        ];

        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json; charset=utf-8');
        header('Status: ' . $status[$httpCode]);

        return $data;
    }
}
