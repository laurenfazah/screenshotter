<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CustomClasses\PHPCrawl\libs\PHPCrawler;
use App\CustomClasses\PHPCrawl\libs\MyCrawler;
use App\CustomClasses\GrabzItClient;
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

    public function createXMLSitemap($site, $dir)
    {
        $sitemap = new Sitemap($site);

        $sitemap->setPath($dir);                            // path to save xml files

        // add sitemap urls to xml file
        // $sitemap->addItem('/', '1.0', 'daily', 'Today');
        // $sitemap->addItem('/about', '0.8', 'monthly', 'Jun 25');
        // $sitemap->addItem('/contact', '0.6', 'yearly', '14-12-2009');
        // $sitemap->addItem('/otherpage');

        $sitemap->createSitemapIndex($site, 'Today');

        // print "<pre>";
        // print($dir);
        // print "</pre>";
        // die();
    }

    public function takeScreenshots($site, $filepath, $dimensions)
    {

        // die();
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

    public function crawlSite($site, $filepath)
    {

        $crawler = new PHPCrawler();

        // URL to crawl
        $crawler->setURL($site);

        // $crawler->setWorkingDirectory($filepath);

        // Only receive content of files with content-type "text/html"
        $crawler->addContentTypeReceiveRule("#text/html#");

        // Ignore links to pictures, dont even request pictures
        $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");

        // Store and send cookie-data like a browser does
        $crawler->enableCookieHandling(true);

        // Set the traffic-limit to 1 MB (in bytes,
        // for testing we dont want to "suck" the whole site)
        // $crawler->setTrafficLimit(1000 * 1024);

        // Thats enough, now here we go
        $crawler->go();

        // At the end, after the process is finished, we print a short
        // report (see method getProcessReport() for more information)
        $report = $crawler->getProcessReport();

        if (PHP_SAPI == "cli") $lb = "\n";
        else $lb = "<br />";

        echo "Summary:".$lb;
        echo "Links followed: ".$report->links_followed.$lb;
        echo "Documents received: ".$report->files_received.$lb;
        echo "Bytes received: ".$report->bytes_received." bytes".$lb;
        echo "Process runtime: ".$report->process_runtime." sec".$lb;

        $this->takeScreenshots($data, $filepath);

    }

    public function grabShots()
    {

        //*/////////////////////////////////////////////////
        // create new folder based on user input
        //*/////////////////////////////////////////////////

        $dimensions = array();
        $dimensions["height"] = $_POST["height"];
        $dimensions["width"] = $_POST["width"];

        $userURL = $_POST["url"];                               // user input
        $uploadPath = base_path() . '/public/uploads/';         // path to uploads folder on server
        $parsedUrl = parse_url($userURL);                       // parsing user input
        $domain = $parsedUrl["host"];                           // grabbing just domain from user input
        $newDir = $uploadPath . $domain . '_' . date('Y-m-d'.'-'.'H:i:s') . '/';  // new unique folder name
        $filepath = $uploadPath . $domain . '_' . date('Y-m-d'.'-'.'H:i:s') . '/'. $domain . '.jpg';  // new unique file name

        File::makeDirectory($newDir, 0777);                     // make new directory for screenshots


        //*/////////////////////////////////////////////////
        // crawl site for all possible links
        //*/////////////////////////////////////////////////

        // $this->crawlSite($userURL, $filepath, $dimensions);


        //*/////////////////////////////////////////////////
        // take screenshots of site
        //*/////////////////////////////////////////////////

        $this->takeScreenshots($userURL, $filepath, $dimensions);

        //*/////////////////////////////////////////////////
        // return user back to homepage
        //*/////////////////////////////////////////////////

        return redirect()->back();

    }
}
?>
