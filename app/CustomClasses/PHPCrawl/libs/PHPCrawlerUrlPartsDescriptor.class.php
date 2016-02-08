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
 * Describes the single parts of an URL.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerUrlPartsDescriptor
{
  public $protocol;

  public $host;

  public $path;

  public $file;

  public $domain;

  public $port;

  public $auth_username;

  public $auth_password;

  /**
   * Returns the PHPCrawlerUrlPartsDescriptor-object for the given URL.
   *
   * @return PHPCrawlerUrlPartsDescriptor
   */
  public static function fromURL($url)
  {
    $parts = PHPCrawlerUtils::splitURL($url);

    $tmp = new PHPCrawlerUrlPartsDescriptor();

    $tmp->protocol = $parts["protocol"];
    $tmp->host = $parts["host"];
    $tmp->path = $parts["path"];
    $tmp->file = $parts["file"];
    $tmp->domain = $parts["domain"];
    $tmp->port = $parts["port"];
    $tmp->auth_username = $parts["auth_username"];
    $tmp->auth_password = $parts["auth_password"];

    return $tmp;
  }

  public function toArray()
  {
    return get_object_vars($this);
  }
}
?>
