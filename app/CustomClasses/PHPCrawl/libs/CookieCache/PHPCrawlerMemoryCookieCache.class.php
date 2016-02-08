<?php
namespace App\CustomClasses\PHPCrawl\libs\CookieCache;

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
 * Class for storing/caching cookies in memory.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerMemoryCookieCache extends PHPCrawlerCookieCacheBase
{
  protected $cookies = array();

  /**
   * Adds a cookie to the cookie-cache.
   *
   * @param PHPCrawlerCookieDescriptor $Cookie The cookie to add.
   */
  public function addCookie(PHPCrawlerCookieDescriptor $Cookie)
  {
    $source_domain = $Cookie->source_domain;
    $cookie_domain = $Cookie->domain;
    $cookie_path = $Cookie->path;
    $cookie_name = $Cookie->name;

    $cookie_hash = md5($cookie_domain."_".$cookie_path."_".$cookie_name);

    $this->cookies[$source_domain][$cookie_hash] = $Cookie;
  }

  /**
   * Adds a bunch of cookies to the cookie-cache.
   *
   * @param array $cookies  Numeric array conatinin the cookies to add as PHPCrawlerCookieDescriptor-objects
   */
  public function addCookies($cookies)
  {
    for ($x=0; $x<count($cookies); $x++)
    {
      $this->addCookie($cookies[$x]);
    }
  }

  /**
   * Returns all cookies from the cache that are adressed to the given URL
   *
   * @param string $target_url The target-URL
   * @return array  Numeric array conatining all matching cookies as PHPCrawlerCookieDescriptor-objects
   */
  public function getCookiesForUrl($target_url)
  {
    $url_parts = PHPCrawlerUtils::splitURL($target_url);

    $target_domain = $url_parts["domain"]; // e.g. acme.com

    $return_cookies = array();

    // Iterate over all cookies of this domain
    @reset($this->cookies[$target_domain]);
    while (list($hash) = @each($this->cookies[$target_domain]))
    {
      $Cookie = $this->cookies[$target_domain][$hash];

      // Does the cookie-domain match?
      // Tail-matching, see http://curl.haxx.se/rfc/cookie_spec.html:
      // A domain attribute of "acme.com" would match host names "anvil.acme.com" as well as "shipping.crate.acme.com"
      // Seems like ".acme.com" should also match "anvil.acme.com", so just remove the dot

      $Cookie->domain = preg_replace("#^.#", "", $Cookie->domain);

      if ($Cookie->domain == $url_parts["host"] || preg_match("#".preg_quote($Cookie->domain)."$#", $url_parts["host"]))
      {
        // Does the path match?
        if (preg_match("#^".preg_quote($Cookie->path)."#", $url_parts["path"]))
        {
          $return_cookies[$Cookie->name] = $Cookie; // Use cookie-name as index to avoid double-cookies
        }
      }
    }

    // Convert to numeric array
    $return_cookies = array_values($return_cookies);

    return $return_cookies;
  }

  /**
   * Cleans up the cache after is it not needed anymore.
   */
  public function cleanup()
  {
    $this->cookies = array();
  }
}
?>
