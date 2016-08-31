<?php

namespace PodcastCrawler\Tests;

use PodcastCrawler\Tests\PodcastCrawlerBaseTest as Base;
use PodcastCrawler\Xml;
use DOMDocument;

class XmlTest extends Base
{
    /**
     * Test the repair of the a dirty xml string
     */
    public function testRepairXml()
    {
        $clean_xml    = '<?xml version="1.0"?><catalog><book><title>XML Developer</title></book></catalog>';
        $dirty_xml    = '<catalog><book><title>XML Developer</title>';
        $repaired_xml = Xml::repair($dirty_xml);

        $expected = new DOMDocument();
        $expected->loadXML($clean_xml);

        $actual = new DOMDocument();
        $actual->loadXML($repaired_xml);

        $this->assertEqualXMLStructure($expected->firstChild, $actual->firstChild, true);
        $this->assertXmlStringEqualsXmlString($clean_xml, $actual->saveXML());
    }
}
