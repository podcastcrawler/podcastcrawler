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

use Tidy;
use Exception;

/**
 * Class Podcastcrawler\Xml
 *
 * @version v0.15.1-beta
 * @link https://github.com/podcastcrawler/podcastcrawler
 * @license https://github.com/podcastcrawler/podcastcrawler/blob/master/LICENSE.md MIT
 * @copyright 2016 Podcast Crawler
 * @author Dorian Neto <doriansampaioneto@gmail.com>
 */
class Xml
{
    /**
     * Repair a XML string with failures in structure
     *
     * @param string $xml XML string
     * @return string XML repaired
     * @throws Exception
     */
    public static function repair($xml)
    {
        if (class_exists("Tidy") === false) {
            throw new Exception("Tidy Class not found", 500);
        }

        $config = [
            'indent'     => true,
            'input-xml'  => true,
            'output-xml' => true,
            'wrap'       => false
        ];

        $xml_repaired = new Tidy();
        $xml_repaired->parseString($xml, $config, 'utf8');
        $xml_repaired->cleanRepair();

        return $xml_repaired;
    }
}
