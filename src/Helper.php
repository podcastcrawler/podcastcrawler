<?php

namespace PodcastCrawler;

use Tidy;
use Exception;

class Helper
{
    /**
     * Repair a XML string with failures in structure
     * @param string $xml XML string
     * @return string XML repaired
     */
    public static function repairXml($xml)
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
        $xml_repaired->ParseString($xml, $config, 'utf8');
        $xml_repaired->cleanRepair();

        return $xml_repaired;
    }
}
