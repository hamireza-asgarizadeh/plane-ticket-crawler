<?php

namespace App\Observers;

use App\Models\PathMain;
use DOMDocument;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CustomCrawlerObserver extends CrawlObserver {

    private $content;

    public function __construct() {
        $this->content = NULL;
    }
    /**
     * Called when the crawler will crawl the url.
     *
     * @param UriInterface $url
     * @param string|null $linkText
     */
    public function willCrawl(UriInterface $url, ?string $linkText): void
    {
        Log::info('willCrawl',['url'=>$url]);
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     * @param string|null $linkText
     */
    public function crawled(
        UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null, string $linkText = null): void
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());
        //# save HTML
        $content = $doc->saveHTML();
        //# convert encoding
        $content1 = mb_convert_encoding($content,'UTF-8',mb_detect_encoding($content,'UTF-8, ISO-8859-1',true));
        //# strip all javascript
        $content2 = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content1);
        //# strip all style
        $content3 = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content2);
        //# strip tags
        $content4 = str_replace('<',' <',$content3);
        $content5 = strip_tags($content4);
        $content6 = str_replace( '  ', ' ', $content5 );
        //# strip white spaces and line breaks
        $content7 = preg_replace('/\s+/S', " ", $content6);
        //# html entity decode - ö was shown as &ouml;
        $html = html_entity_decode($content7);
        //# append
        $this->content .= $html;
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     * @param string|null $linkText
     */
    public function crawlFailed(
        UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null, string $linkText = null): void
    {
        Log::error('crawlFailed',['url'=>$url,'error'=>$requestException->getMessage()]);
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
        Log::info($this->content);
        $mainPath=PathMain::where('info',null)->first();
        $kilometerSTRLocation = strpos($this->content,'kilometers');
        $kilometerString=substr($this->content,$kilometerSTRLocation-5,4);
        $mainPath->info=$kilometerString;

        $mainPath->save();
//        add info to excel
        //# store $this->content in DB
        //# Add logic here
    }
}
