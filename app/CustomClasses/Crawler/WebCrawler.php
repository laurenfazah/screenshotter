<?php

namespace App\CustomClasses\Crawler;

/**
 * Singleton Class responsible to
 * all transactions
 * ACTS LIKE A CONTROLLER
 *
 * PHP version 5.3+
 *
 * @author    Ravi Shanker B
 * @package   WebCrawler
 */

use \Exception as Exception;
use App\CustomClasses\Crawler\Request\RequestRegular;
use App\CustomClasses\Crawler\WebPage\WebPage as WebPage;

class WebCrawler {

    protected static $instance = null;

    /*
     * Number of times the
     * crawler must recurse
     */
    protected $depth = -1;

    /*
     * Number of times the
     * crawler must recurse
     */
    protected $linksAllowed = 20;

    /*
     * variable storing each crawler/Request object
     *
     * @access private
     */
    private $webPages = array();

    /*
     * Use this incase webCrawler needs to
     * have Static instancce and store crawls
     *
     * @return WebCrawler
     */
    public static function getInstance(){
        if (self::$instance==null)
            self::$instance = new WebCrawler();
        return self::$instance;
    }

    /*
     * @return webPage
     */
    public function getWebPages(){
        return $this->webPages;
    }

    /*
     * Sets all the pages' details as array
     *
     * @param  Request $request
     */
    protected function setWebPages(WebPage $WebPage){
        $this->webPages[$WebPage->getUrl()] = $WebPage;
    }

    /*
     * Sets web crawler
     *
     * @param string $uri   URL to begin the crawler
     */
    public function beginCrawl($uri){

        $CrawlerRequest = RequestRegular::getInstance();

        //Get the links
        if($this->checkLimits() === true){

            $urls = $CrawlerRequest->getLinks($uri);

            foreach($urls as $url){

                //break if limit is hit
                if($this->linksAllowed != -1){
                    if($this->linksAllowed <= count($this->getWebPages()) )
                        return false;
                }

                $WebPage = new WebPage($url);
                if($WebPage->getUrl() != null){
                    if($this->depth!=-1){
                        $this->depth--;
                    }

                    //check if uri has been checked
                    if(!array_key_exists($url, $this->getWebPages())){
                        $this->setWebPages($WebPage);
                        $this->beginCrawl($url);
                    }

                }
                else{
                    $key = array_search($url, $urls);
                    unset($urls[$key]);
                }

            }

        }

    }

    /*
     * @return Depth
     */
    public function getDepth(){
        return $this->depth;
    }

    /*
     * @param int $depth
     */
    public function setDepth($depth){
        if(!is_numeric($depth))
            throw new Exception("Depth needs to be Numeric");
        $this->depth = $depth;
    }

    /*
     * @return webPage
     */
    public function getLinksAllowed(){
        return $this->linksAllowed;
    }

    public function setLinksAllowed($linksAllowed){
        if(!is_numeric($linksAllowed))
            throw new Exception("Links Allowed needs to be Numeric");
        $this->linksAllowed = $linksAllowed;
    }

    /*
     * Note:- Negative value is
     *        considered as no limits
     * @return bool
     */
    public function checkLimits(){
        if($this->depth != -1){
            if($this->depth <= 0)
                return false;
        }
        if($this->linksAllowed != -1){
            if($this->linksAllowed <= count($this->getWebPages()) )
                return false;
        }
        return true;
    }
}
