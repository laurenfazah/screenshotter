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
/**
 * Simple DNS-cache used by phpcrawl.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerDNSCache
{
  /**
   * Array for caching IPs of the requested hostnames
   *
   * @var array Associative array, keys = hostnames, values = IPs.
   */
  protected $host_ip_array;

  public function __construct()
  {
  }

  /**
   * Returns the IP for the given hostname.
   *
   * @return string The IP-address.
   */
  public function getIP($hostname)
  {
    // If host already was queried
    if (isset($this->host_ip_array[$hostname]))
    {
      return $this->host_ip_array[$hostname];
    }

    // Else do DNS-query
    else
    {
      $ip = gethostbyname($hostname);
      $this->host_ip_array[$hostname] = $ip;
      return $ip;
    }
  }

  /**
   * Checks whether a hostname is already cached.
   *
   * @param string $hostname The hostname
   * @return bool
   */
  public function hostInCache($hostname)
  {
    if (isset($this->host_ip_array[$hostname])) return true;
    else return false;
  }

  /**
   * Checks whether the hostname of the given URL is already cached
   *
   * @param PHPCrawlerURLDescriptor $URL The URL
   * @return bool
   */
  public function urlHostInCache(PHPCrawlerURLDescriptor $URL)
  {
    $url_parts = PHPCrawlerUtils::splitURL($URL->url_rebuild);
    return $this->hostInCache($url_parts["host"]);
  }
}
?>
