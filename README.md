#Podcast Crawler
[![Build Status](https://travis-ci.org/podcastcrawler/podcastcrawler.svg?branch=master)](https://travis-ci.org/podcastcrawler/podcastcrawler)

Podcast Crawler it is a library that enables the search for podcasts to get details and mp3 files through Itunes API.

##Highlights

* Simple API
* Fully documented
* Fully unit tested
* Search for term or Collection ID
* Get details from podcast
* Get list with all episodes

##System Requirements

You need **PHP >= 5.4.0** to use `podcastcrawler/podcastcrawler`, but the latest stable version of PHP is recommended.

Podcast Crawler is verified and tested on PHP 5.4, 5.5, 5.6 and 7.0.

It's necessary have installed [Tidy](http://php.net/manual/pt_BR/book.tidy.php) library.


##Installation

Install `podcastcrawler/podcastcrawler` using Composer:

```
$ composer require podcastcrawler/podcastcrawler
```


##Basic Usage

```php
<?php
// Require the composer auto loader
require 'vendor/autoload.php';

use PodcastCrawler\PodcastCrawler;

$PodcastCrawler = new PodcastCrawler();
$PodcastCrawler->responseJson = false; // Set to response be a json (true) or array (false)

$searchByTerm = $PodcastCrawler->search('nerdcast');
var_dump($searchByTerm);
// returns an array with a podcasts list

$searchById = $PodcastCrawler->search('582573564');
var_dump($searchById);
// returns an array with only one podcast

$getFeed = $PodcastCrawler->feed('381816509');
var_dump($getFeed);
// returns an array with all details of the podcast
```

##API

###Properties

####$PodcastCrawler->responseJson `boolean`

Description: Set to response be a json or array

###Methods

####$PodcastCrawler->search(string|int $value)

Description: Values like `jovem nerd`, `naoouvo`, `criscast` or collection ID of the podcast

Return: `string`|`array`

####$PodcastCrawler->feed(int $id)

Description: Collection ID of the podcast

Return: `string`|`array`

####$PodcastCrawler->getStatusCode()

Description: Return the http status code

Return: `int`

##License

Podcast Crawler is open-sourced software licensed under the MIT License (MIT). Please see [LICENSE](/LICENSE.md) for more information.