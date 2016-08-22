<?php

namespace PodcastCrawler;

class PodcastCrawler
{
    /**
     * @var string $urlToSearch URL to search for podcasts
     */
    private $urlToSearch = null;

    /**
     * @var string BASE_URL
     */
    const BASE_URL = "https://itunes.apple.com/search";

    /**
     * @var int LIMIT The number of search results you want the iTunes Store to return
     */
    const LIMIT = 10;

    /**
     * @var string ENTITY The type of results you want returned, relative to the specified media type
     */
    const ENTITY = "podcast";

    /**
     * @var string MEDIA The media type you want to search for
     */
    const MEDIA = "podcast";

    /**
     * @param string $term The URL-encoded text string you want to search for
     * @return void
     */
    public function __construct($term)
    {
        $query_string = http_build_query([
            'term'      => $term,
            'limit'     => self::LIMIT,
            'entity'    => self::ENTITY,
            'media'     => self::MEDIA
        ]);
        $this->urlToSearch = self::BASE_URL . '?' . $query_string;
    }

    /**
     * Return the list of podcasts sought by the term
     * @return array
     */
    public function getList()
    {
        $result = $this->downloadPage($this->urlToSearch);
        return json_decode($result, true);
    }

    /**
     * Send a http request and return the response with list podcasts
     * @param string $url
     * @param array $options CURL options
     */
    private function downloadPage($url, array $options = [])
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

        return $http_code == 200 ? $result : null;
    }
}
