<?php

namespace PodcastCrawler;

use SimpleXMLElement;
use DateTime;
use Exception;
use Tidy;

class PodcastCrawler
{
    /**
     * @var string $defaultQueryString
     */
    private $defaultQueryString = null;

    /**
     * @var int $requestHttpCode
     */
    private $requestHttpCode = null;

    /**
     * @var boolean $responseJson
     */
    public $responseJson = false;

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
     * @return string|array
     */
    public function search($value)
    {
        try {
            // Request the Itunes API
            $url       = is_int($value) ? self::LOOKUP_URL . "?id={$value}" : self::SEARCH_URL . "?term={$value}";
            $to_search = urldecode($url . '&' . $this->defaultQueryString);
            $result    = $this->request($to_search);

            if (is_null($result)) {
                throw new Exception("Request to Itunes API failed");
            }

            $result   = json_decode($result);
            $response['result_count'] = $result->resultCount;

            foreach($result->results as $value) {
                $response['podcasts'][] = [
                    'itunes_id' => $value->collectionId,
                    'author'    => $value->artistName,
                    'title'     => $value->collectionName,
                    'episodes'  => $value->trackCount,
                    'image'     => $value->artworkUrl100,
                    'rss'       => $value->feedUrl,
                    'genre'     => $value->primaryGenreName,
                ];
            }
        } catch (Exception $except) {
            $response = [
                'code'    => $this->requestHttpCode,
                'message' => $except->getMessage()
            ];
        }

        if ($this->responseJson === false) {
            return $response;
        }

        return $this->responseJson(json_encode($response), $this->requestHttpCode);
    }

    /**
     * Return the podcast details found sought by ID (int)
     * @param int $id The podcast id int you want to details
     * @return string|array
     */
    public function feed($id)
    {
        try {
            // Request the Itunes API
            $url       = self::LOOKUP_URL . "?id={$id}";
            $to_search = urldecode($url . '&' . $this->defaultQueryString);
            $result    = $this->request($to_search);

            if (is_null($result)) {
                throw new Exception("Request to Itunes API failed");
            }

            // Result of request Itunes API
            $result = json_decode($result);

            if (!isset($result->results[0])) {
                throw new Exception("Data response by Itunes are inconsistent");
            }

            // Request the RSS
            $item = $result->results[0];
            $download_feed = $this->request($item->feedUrl);

            if (is_null($download_feed)) {
                throw new Exception("Request to RSS failed");
            }
        } catch (Exception $except) {
            $response = [
                'code'    => $this->requestHttpCode,
                'message' => $except->getMessage()
            ];

            return $this->responseJson(json_encode($response), $this->requestHttpCode);
        }

        libxml_use_internal_errors(true);

        try {
            $feed = new SimpleXMLElement($download_feed, LIBXML_NOCDATA, false);
        } catch (Exception $except) {
            $download_feed = $this->repairXml($download_feed);
            $feed          = new SimpleXMLElement($download_feed, LIBXML_NOCDATA, false);
        }

        $response = [
            'itunes_id'   => $item->collectionId,
            'title'       => (string) $feed->channel->title,
            'description' => (string) $feed->channel->description,
            'image'       => (string) $feed->channel->image->url,
            'links'       => [
                'site'   => (string) $feed->channel->link,
                'rss'    => $item->feedUrl,
                'itunes' => $item->collectionViewUrl
            ],
            'genre'       => $item->primaryGenreName,
            'language'    => (string) $feed->channel->language,
            'episodes'    => (int) count($feed->channel->item)
        ];

        foreach($feed->channel->item as $entry) {
            $published_at = new DateTime($entry->pubDate);
            $published_at = $published_at->format('Y-m-d');

            $response['mp3'][] = [
                'title'        => (string) $entry->title,
                'mp3'          => isset($entry->enclosure) ? (string) $entry->enclosure->attributes()->url : null,
                'description'  => (string) $entry->description,
                'link'         => (string) $entry->link,
                'published_at' => $published_at,
            ];
        }

        if ($this->responseJson === false) {
            return $response;
        }

        return $this->responseJson(json_encode($response), $this->requestHttpCode);
    }

    /**
     * Return the http status code
     * @return int
     */
    public function getStatusCode()
    {
        return $this->requestHttpCode;
    }

    /**
     * Repair a XML string with failures in structure
     * @param string $xml XML string
     * @return string XML repaired
     */
    private function repairXml($xml)
    {
        $config = [
            'indent'     => true,
            'input-xml'  => true,
            'output-xml' => true,
            'wrap'       => false
        ];

        $xml_repaired = new Tidy();
        $xml_repaired->ParseString($xml, $config, 'utf8');
        $xml_repaired->cleanRepair();

        return $xml_repaired;
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

        $result = curl_exec($ch);
        $this->requestHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $result;
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
