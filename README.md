# Podcast Crawler
[![Build Status](https://travis-ci.org/podcastcrawler/podcastcrawler.svg?branch=master)](https://travis-ci.org/podcastcrawler/podcastcrawler)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/7823d6165f3244f196c5df469b3be5d6)](https://www.codacy.com/app/doriansampaioneto/podcastcrawler?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=podcastcrawler/podcastcrawler&amp;utm_campaign=Badge_Grade)
[![Packagist](https://img.shields.io/packagist/v/podcastcrawler/podcastcrawler.svg?maxAge=2592000)](https://packagist.org/packages/podcastcrawler/podcastcrawler)
[![Packagist](https://img.shields.io/packagist/dt/podcastcrawler/podcastcrawler.svg?maxAge=2592000)](https://packagist.org/packages/podcastcrawler/podcastcrawler)
[![Packagist](https://img.shields.io/packagist/l/podcastcrawler/podcastcrawler.svg?maxAge=2592000)](https://packagist.org/packages/podcastcrawler/podcastcrawler)

Podcast Crawler is a library that enables the search for podcasts to get details and mp3 files through Itunes and Digital Podcast API.

## Table of Contents
- [Highlights](#highlights)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [API](#api)
- [License](#license)

### Highlights

* Simple API
* Fully documented
* Fully unit tested
* Search for podcasts by Term
* Search podcasts on multiple APIs
* Get details and mp3 files through from Collection ID

### System Requirements

You need **PHP >= 5.4.0** to use `podcastcrawler/podcastcrawler`, but the latest stable version of PHP is recommended.

Podcast Crawler is verified and tested on PHP 5.4, 5.5, 5.6 and 7.0.

It's necessary have installed [Tidy](http://php.net/manual/pt_BR/book.tidy.php) library.

### Installation

Install `podcastcrawler/podcastcrawler` using Composer:

```
$ composer require podcastcrawler/podcastcrawler
```

### Basic Usage

```php
<?php
// Require the composer auto loader
require 'vendor/autoload.php';

use PodcastCrawler\PodcastCrawler;
use PodcastCrawler\Provider\Itunes;

$provider = new Itunes();
$PodcastCrawler = new PodcastCrawler($provider);

$PodcastCrawler->get('nerdcast');
//=> Returns an array with search result (result count and a list with podcasts)

$PodcastCrawler->limit(1)->get('a');
//=> Returns an array with 1 (one) item. Result count is also included.

$PodcastCrawler->find(381816509);
//=> Returns an array with the podcast's detail and episodes with its mp3 files.
```

### API

See the full API through this [link](http://api.podcastcrawler.com/v1/).

### License

Podcast Crawler is open-sourced software licensed under the MIT License (MIT). Please see [LICENSE](/LICENSE.md) for more information.
