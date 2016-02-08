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
 * Describes an HTTP response-header within the phpcrawl-system.
 *
 * @package phpcrawl
 */
class PHPCrawlerResponseHeader
{
  /**
   * Initiates an new PHPCrawlerResponseHeader.
   *
   * @param string $header_string A complete response-header as it was send by the server
   * @param string $source_url    The URL of the website the header was recevied from.
   * @internal
   */
  public function __construct($header_string, $source_url)
  {
    $this->header_raw = $header_string;
    $this->source_url = $source_url;

    $this->http_status_code = PHPCrawlerUtils::getHTTPStatusCode($header_string);
    $this->content_type = strtolower(PHPCrawlerUtils::getHeaderValue($header_string, "content-type"));
    $this->content_length = strtolower(PHPCrawlerUtils::getHeaderValue($header_string, "content-length"));
    $this->cookies = PHPCrawlerUtils::getCookiesFromHeader($header_string, $source_url);
    $this->transfer_encoding = strtolower(PHPCrawlerUtils::getHeaderValue($header_string, "transfer-encoding"));
    $this->content_encoding = strtolower(PHPCrawlerUtils::getHeaderValue($header_string, "content-encoding"));
  }

  /**
   * The raw HTTP-header as it was send by the server
   *
   * @var string
   */
  public $header_raw;

  /**
   * The HTTP-statuscode
   *
   * @var int
   */
  public $http_status_code;

  /**
   * The content-type
   *
   * @var string
   */
  public $content_type;

  /**
   * The content-length as stated in the header.
   *
   * @var int
   */
  public $content_length;

  /**
   * The content-encoding as stated in the header.
   *
   * @var string
   */
  public $content_encoding;

  /**
   * The transfer-encoding as stated in the header.
   *
   * @var string
   */
  public $transfer_encoding;

  /**
   * All cookies found in the header
   *
   * @var array Numeric array containing all cookies as {@link PHPCrawlerCookieDescriptor}-objects
   */
  public $cookies = array();

  /**
   * The URL of the website the header was recevied from.
   *
   * @var string
   */
  public $source_url;
}
?>
