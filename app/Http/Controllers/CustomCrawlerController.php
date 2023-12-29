<?php

namespace App\Http\Controllers;

use App\Models\PathMain;
use App\Observers\CustomCrawlerObserver;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Spatie\Crawler\Crawler;
use GuzzleHttp\RequestOptions;

class CustomCrawlerController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Crawl the website content.
     * @return true
     */
    public function fetchContent($id)
    {
        $temp=PathMain::findOrFail($id);
            $url = 'https://www.airportdistancecalculator.com/flight-' . $temp->origin . '-to-' . $temp->destination . '.html';
            # initiate crawler
            Crawler::create([RequestOptions::ALLOW_REDIRECTS => true, RequestOptions::TIMEOUT => 30])
                ->acceptNofollowLinks()
                ->ignoreRobots()
                // ->setParseableMimeTypes(['text/html', 'text/plain'])
                ->setCrawlObserver(new CustomCrawlerObserver())
                ->setCrawlProfile(new CrawlInternalUrls($url))
                ->setMaximumResponseSize(10 * 10 * 2) // 2 MB maximum
                ->setTotalCrawlLimit(1) // limit defines the maximal count of URLs to crawl
                // ->setConcurrency(1) // all urls will be crawled one by one
                ->setDelayBetweenRequests(0)
                ->startCrawling($url);
            return redirect('/fetch/'.$id+1);
        }
}
