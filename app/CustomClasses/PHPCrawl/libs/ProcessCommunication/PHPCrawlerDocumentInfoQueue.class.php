<?php
namespace App\CustomClasses\PHPCrawl\libs\ProcessCommunication;

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
 * Queue for PHPCrawlerDocumentInfo-objects
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerDocumentInfoQueue
{
  protected $PDO;

  protected $sqlite_db_file;

  protected $prepared_statements_created = false;

  /**
   * Prepared statement for inserting PHPCrawlerDocumentInfo-objects
   */
  protected $preparedInsertStatement;

  /**
   * Prepared statement for selecting/fetching PHPCrawlerDocumentInfo-objects
   */
  protected $preparedSelectStatement;

  protected $working_directory = null;

  protected $queue_max_size = 50;

  /**
   * Initiates a PHPCrawlerDocumentInfoQueue
   *
   * @param string $file            The SQLite-fiel to use.
   * @param  bool  $create_tables   Defines whether all necessary tables should be created
   */
  public function __construct($file, $create_tables = false)
  {
    $this->sqlite_db_file = $file;
    $this->working_directory = dirname($file)."/";
    $this->openConnection($create_tables);
  }

  /**
   * Returns the current number of PHPCrawlerDocumentInfo-objects in the queue
   */
  public function getDocumentInfoCount()
  {
    $Result = $this->PDO->query("SELECT count(id) as sum FROM document_infos;");
    $row = $Result->fetch(PDO::FETCH_ASSOC);
    $Result->closeCursor();

    return $row["sum"];
  }

  /**
   * Adds a PHPCrawlerDocumentInfo-object to the queue
   */
  public function addDocumentInfo(PHPCrawlerDocumentInfo $DocInfo)
  {
    // If queue is full -> wait a little
    while ($this->getDocumentInfoCount() >= $this->queue_max_size)
    {
      usleep(500000);
    }

    $this->createPreparedStatements();

    $ser = serialize($DocInfo);

    $this->PDO->exec("BEGIN EXCLUSIVE TRANSACTION");
    $this->preparedInsertStatement->bindParam(1, $ser, PDO::PARAM_LOB);
    $this->preparedInsertStatement->execute();
    $this->preparedSelectStatement->closeCursor();
    $this->PDO->exec("COMMIT");
  }

   /**
   * Returns a PHPCrawlerDocumentInfo-object from the queue
   */
  public function getNextDocumentInfo()
  {
    $this->createPreparedStatements();

    $this->preparedSelectStatement->execute();
    $this->preparedSelectStatement->bindColumn("document_info", $doc_info, PDO::PARAM_LOB);
    $this->preparedSelectStatement->bindColumn("id", $id);
    $row = $this->preparedSelectStatement->fetch(PDO::FETCH_BOUND);
    $this->preparedSelectStatement->closeCursor();

    if ($id == null)
    {
      return null;
    }

    $this->PDO->exec("DELETE FROM document_infos WHERE id = ".$id.";");

    $DocInfo = unserialize($doc_info);

    return $DocInfo;
  }

  /**
   * Creates all prepared statemenst
   */
  protected function createPreparedStatements()
  {
    if ($this->prepared_statements_created == false)
    {
      $this->preparedInsertStatement = $this->PDO->prepare("INSERT INTO document_infos (document_info) VALUES (?);");
      $this->preparedSelectStatement = $this->PDO->prepare("SELECT * FROM document_infos limit 1;");

      $this->prepared_statements_created = true;
    }
  }

  /**
   * Creates the sqlite-db-file and opens connection to it.
   *
   * @param bool $create_tables Defines whether all necessary tables should be created
   */
  protected function openConnection($create_tables = false)
  {
    // Open sqlite-file
    try
    {
      $this->PDO = new PDO("sqlite:".$this->sqlite_db_file);
    }
    catch (Exception $e)
    {
      throw new Exception("Error creating SQLite-cache-file, ".$e->getMessage().", try installing sqlite3-extension for PHP.");
    }

    $this->PDO->exec("PRAGMA journal_mode = OFF");

    $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $this->PDO->setAttribute(PDO::ATTR_TIMEOUT, 100);

    if ($create_tables == true)
    {
      $this->PDO->exec("CREATE TABLE IF NOT EXISTS document_infos (id integer PRIMARY KEY AUTOINCREMENT,
                                                                   document_info blob);");
      $this->PDO->exec("ANALYZE;");
    }
  }
}
?>
