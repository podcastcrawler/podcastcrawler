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

use SimpleXMLElement;
use DateTime;

/**
 * Abstract class PodcastCrawler\Provider\AbstractProvider
 *
 * @version   v1.0.0
 * @link      https://github.com/podcastcrawler/podcastcrawler
 * @license   https://github.com/podcastcrawler/podcastcrawler/blob/master/LICENSE.md MIT
 * @copyright 2016 Podcast Crawler
 * @author    Dorian Neto <doriansampaioneto@gmail.com>
 */
abstract class AbstractProvider
{
    /**
     * Structuring the response from API through the feed
     *
     * @param  SimpleXMLElement $feed The feed after converting (XML -> Object)
     * @return array                  The array with feed data filtered and built
     */
    public function buildFeed(SimpleXMLElement $feed)
    {
        $output = [
            'title'          => (string) utf8_decode($feed->channel->title),
            'description'    => (string) utf8_decode($feed->channel->description),
            'image'          => (string) $feed->channel->image->url,
            'site'           => (string) $feed->channel->link,
            'language'       => (string) $feed->channel->language,
            'episodes_total' => (int) count($feed->channel->item)
        ];

        foreach($feed->channel->item as $value) {
            $published_at = new DateTime();
            $published_at->setTimestamp(strtotime($value->pubDate));
            $published_at = $published_at->format('Y-m-d');

            $output['episodes'][] = [
                'title'        => (string) utf8_decode($value->title),
                'mp3'          => isset($value->enclosure) ? (string) $value->enclosure->attributes()->url : null,
                'description'  => (string) utf8_decode($value->description),
                'link'         => (string) $value->link,
                'published_at' => $published_at,
            ];
        }

        return $output;
    }
}
