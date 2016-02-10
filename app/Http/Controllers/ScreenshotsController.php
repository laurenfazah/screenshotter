<?php
namespace App\Http\Controllers;

ini_set('display_errors', 1);

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CustomClasses\PHPCrawl\libs\PHPCrawler;
use App\CustomClasses\PHPCrawl\libs\PHPCrawlerDocumentInfo;
use JonnyW\PhantomJs\Client;
use Response;
use View;
use Input;
use Validator;
use Redirect;
use File;
use Session;
use DOMDocument;

class ScreenshotsController extends Controller
{

    public function takeScreenshots($site, $newDir, $dimensions)
    {

        $urlHost = parse_url($site)["host"];
        $urlPath = isset(parse_url($site)['path']) ? parse_url($site)['path'] : '';
        $urlPathName = str_replace("/", "",$urlPath);

        $filepath = $newDir . $urlHost . "_" . $urlPathName . ".jpg";

        print $filepath . "<br>";

        $client = Client::getInstance();

        $client->getEngine()->setPath(base_path().'/bin/phantomjs');

        $width  = $dimensions["width"];
        $height = $dimensions["height"];
        $top    = 0;
        $left   = 0;

        /**
         * @see JonnyW\PhantomJs\Message\CaptureRequest
         **/
        $request = $client->getMessageFactory()->createCaptureRequest($site, 'GET');
        $request->setOutputFile($filepath);
        $request->setViewportSize($width, $height);
        $request->setCaptureDimensions($width, $height, $top, $left);

        /**
         * @see JonnyW\PhantomJs\Message\Response
         **/
        $response = $client->getMessageFactory()->createResponse();

        // Send the request
        $client->send($request, $response);

    }

    public function crawlSite($site, $newDir, $dimensions)
    {

        $crawler = new PHPCrawler();                                // set new class instances
        $crawlerInfo = new PHPCrawlerDocumentInfo();

        $crawler->setURL($site);                                    // URL to crawl

        $crawler->addContentTypeReceiveRule("#text/html#");         // only receive content-type "text/html"

        $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");    // ignore & don't request pics

        $crawler->enableCookieHandling(true);                       // store and send cookie-data like a browser does

        $crawler->setTrafficLimit(1000 * 1024);                     // limiting traffic (for dev)

        $crawler->go();                                             // all info in, good to go

        $allLinks = $crawler->all_links_found;

        $siteLinks = [];

        foreach ($allLinks as $link) {                             // only grab 200s
            if ($link["status_code"] === 200){
                $siteLinks[] = $link["url"];
            }
        }

        $uniqueLinks = array_unique($siteLinks);                    // clear out duplicates

        //*/////////////////////////////////////////////////
        // take screenshots of site
        //*/////////////////////////////////////////////////

        foreach ($uniqueLinks as $link) {
            $this->takeScreenshots($link, $newDir, $dimensions);  // follow through to take screenshots
        }

    }

    public function grabShots()
    {

        //*/////////////////////////////////////////////////
        // gather user input
        //*/////////////////////////////////////////////////

        $dimensions = array();
        $dimensions["height"] = $_POST["height"];
        $dimensions["width"] = $_POST["width"];
        $userURL = $_POST["url"];

        //*/////////////////////////////////////////////////
        // create new folder based on user input
        //*/////////////////////////////////////////////////

        $uploadPath = base_path() . '/public/uploads/';         // path to uploads folder on server
        $parsedUrl = parse_url($userURL);                       // parsing user input
        $domain = $parsedUrl["host"];                           // grabbing just domain from user input
        $newDir = $uploadPath . $domain . '_' . date('Y-m-d'.'-'.'H:i:s') . '/';  // new unique folder name

        File::makeDirectory($newDir, 0777);                     // make new directory for screenshots


        //*/////////////////////////////////////////////////
        // crawl site for all possible links
        //*/////////////////////////////////////////////////

        $this->crawlSite($userURL, $newDir, $dimensions);


        //*/////////////////////////////////////////////////
        // return user back to homepage
        //*/////////////////////////////////////////////////
        die();
        return redirect()->back();

    }
}
?>
