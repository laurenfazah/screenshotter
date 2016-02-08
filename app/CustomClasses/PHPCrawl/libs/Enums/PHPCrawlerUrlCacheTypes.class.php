<?php
namespace App\CustomClasses\PHPCrawl\libs\Enums;

use App\CustomClasses\PHPCrawl\libs\PHPCrawler;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerBenchmark;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerCookieDescriptor;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerDNSCache;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerDocumentInfo;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerHTTPRequest;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerLinkFinder;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerProcessReport;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerResponseHeader;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerRobotsTxtParser;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerStatus;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerURLDescriptor;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerURLFilter;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerUrlPartsDescriptor;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerUserSendDataCache;

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

/**
 * Possible cache-types for caching found URLs within the phpcrawl-system.
 *
 * @package phpcrawl.enums
 */
class PHPCrawlerUrlCacheTypes
{
  /**
   * URLs get cached in local RAM. Best performance.
   */
  const URLCACHE_MEMORY = 1;

  /**
   * URLs get cached in a SQLite-database-file. Recommended for spidering huge websites.
   */
  const URLCACHE_SQLITE = 2;
}
