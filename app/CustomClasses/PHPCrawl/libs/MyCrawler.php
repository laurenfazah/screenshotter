<?php
namespace App\CustomClasses\PHPCrawl\libs;

use App\CustomClasses\PHPCrawl\libs\CookieCache\PHPCrawlerCookieCacheBase;
use App\CustomClasses\PHPCrawl\libs\CookieCache\PHPCrawlerMemoryCookieCache;
use App\CustomClasses\PHPCrawl\libs\CookieCache\PHPCrawlerSQLiteCookieCache;

use App\CustomClasses\PHPCrawl\libs\Enums\PHPCrawlerAbortReasons;
use App\CustomClasses\PHPCrawl\libs\Enums\PHPCrawlerHTTPProtocols;
use App\CustomClasses\PHPCrawl\libs\Enums\PHPCrawlerLinkSearchDocumentSections;
use App\CustomClasses\PHPCrawl\libs\Enums\PHPCrawlerMultiProcessModes;
use App\CustomClasses\PHPCrawl\libs\Enums\PHPCrawlerRequestErrors;
use App\CustomClasses\PHPCrawl\libs\Enums\PHPCrawlerUrlCacheTypes;

use App\CustomClasses\PHPCrawl\libs\ProcessCommunication\PHPCrawlerDocumentInfoQueue;
use App\CustomClasses\PHPCrawl\libs\ProcessCommunication\PHPCrawlerProcessHandler;
use App\CustomClasses\PHPCrawl\libs\ProcessCommunication\PHPCrawlerStatusHandler;

use App\CustomClasses\PHPCrawl\libs\UrlCache\PHPCrawlerMemoryURLCache;
use App\CustomClasses\PHPCrawl\libs\UrlCache\PHPCrawlerSQLiteURLCache;
use App\CustomClasses\PHPCrawl\libs\UrlCache\PHPCrawlerURLCacheBase;

use App\CustomClasses\PHPCrawl\libs\Utils\PHPCrawlerEncodingUtils;
use App\CustomClasses\PHPCrawl\libs\Utils\PHPCrawlerUtils;

class MyCrawler extends PHPCrawler
{
  public function handleDocumentInfo($DocInfo)
  {
    // Just detect linebreak for output ("\n" in CLI-mode, otherwise "<br>").
    if (PHP_SAPI == "cli") $lb = "\n";
    else $lb = "<br />";
    // Print the URL and the HTTP-status-Code
    echo "Page requested: ".$DocInfo->url." (".$DocInfo->http_status_code.")".$lb;

    // Print the refering URL
    echo "Referer-page: ".$DocInfo->referer_url.$lb;

    // Print if the content of the document was be recieved or not
    if ($DocInfo->received == true)
      echo "Content received: ".$DocInfo->bytes_received." bytes".$lb;
    else
      echo "Content not received".$lb;

    // Now you should do something with the content of the actual
    // received page or file ($DocInfo->source), we skip it in this example

    echo $lb;

        // print "<pre>";
        // print($DocInfo);
        // print "</pre>";
        // die();

    flush();
  }
}
