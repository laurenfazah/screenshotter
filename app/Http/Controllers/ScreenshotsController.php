<?php
namespace App\Http\Controllers;

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
        if ($site["status_code"] === 200) {

            $url = $site["url"];
            $filepath = $newDir . $url . ".jpg";

            $client = Client::getInstance();

            $client->getEngine()->setPath(base_path().'/bin/phantomjs');

            $width  = $dimensions["width"];
            $height = $dimensions["height"];
            $top    = 0;
            $left   = 0;

            /**
             * @see JonnyW\PhantomJs\Message\CaptureRequest
             **/
            $request = $client->getMessageFactory()->createCaptureRequest($url, 'GET');
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

        $siteLinks = $crawler->all_links_found;

        //*/////////////////////////////////////////////////
        // take screenshots of site
        //*/////////////////////////////////////////////////

        foreach ($siteLinks as $link) {
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

        return redirect()->back();

    }
}
?>
