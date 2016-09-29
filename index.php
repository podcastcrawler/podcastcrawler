<?php
require 'vendor/autoload.php';

use PodcastCrawler\PodcastCrawler;
use PodcastCrawler\Provider\Itunes;
use PodcastCrawler\Provider\DigitalPodcast;

// $PodcastCrawler = new PodcastCrawler(new Itunes);

// $list_by_term = $PodcastCrawler->get('jovem');
// echo '<pre>';
// var_dump($list_by_term);
// echo '</pre>';

$PodcastCrawler = new PodcastCrawler(new DigitalPodcast);

$list_by_term = $PodcastCrawler->get('jovem nerd');
echo '<pre>';
var_dump($list_by_term);
echo '</pre>';

// $feed = $PodcastCrawler->find('http://feeds.feedburner.com/djanthonygarcia');
// echo '<pre>';
// var_dump($feed);
// echo '</pre>';

// 1103141552
// 381816509
// 1111319839
// 1013472013
// 601457993
// 635644349
// 1017846896
// 1083394658