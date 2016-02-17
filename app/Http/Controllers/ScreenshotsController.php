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
use ZipArchive;
use Redirect;
use File;
use Session;
use DOMDocument;

class ScreenshotsController extends Controller
{

    public function takeScreenshots($site, $newDir, $stats)
    {

        $delay = $stats["delay"];

        $urlHost = parse_url($site)["host"];
        $urlPath = isset(parse_url($site)['path']) ? parse_url($site)['path'] : '';
        $urlPathName = str_replace("/", "",$urlPath);

        $filepath = $newDir . $urlHost . "_" . $urlPathName . ".jpg";

        $client = Client::getInstance();

        $client->getEngine()->setPath(base_path().'/bin/phantomjs');

        $width  = $stats["width"];
        $height = $stats["height"];
        $top    = 0;
        $left   = 0;

        /**
         * @see JonnyW\PhantomJs\Message\CaptureRequest
         **/
        $request = $client->getMessageFactory()->createCaptureRequest($site, 'GET');
        $request->setDelay($delay);
        $request->setOutputFile($filepath);
        $request->setViewportSize($width, $height);

        /**
         * @see JonnyW\PhantomJs\Message\Response
         **/
        $response = $client->getMessageFactory()->createResponse();

        // Send the request
        $client->send($request, $response);

    }

    public function crawlSite($site, $newDir, $stats)
    {

        $crawler = new PHPCrawler();                                // set new class instances

        $crawler->setURL($site);                                    // URL to crawl

        $crawler->addContentTypeReceiveRule("#text/html#");         // only receive content-type "text/html"

        $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png|js|svg|css|ico|pdf|mp3|mp4|webm)$# i");    // ignore & don't request pics

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
            $this->takeScreenshots($link, $newDir, $stats);  // follow through to take screenshots
        }

    }

    public function zipIt($newDir, $domain)
    {
        // Choose a name for the archive.
        $zipFileName = $domain . ".zip";

        // Create zipfile in public directory of project.
        $zip = new ZipArchive;
        if ($zip->open(public_path() . '/uploads/' . $zipFileName, ZipArchive::CREATE) === TRUE)
        {

            // Copy all the files from the folder and place them in the archive.
            foreach (glob($newDir . '/*') as $fileName) {
                    $file = basename($fileName);
                    $zip->addFile($fileName, $file);
                }
                $zip->close();

                $headers = array(
                    'Content-Type' => 'application/octet-stream',
                );

            // Download .zip file.
            return Response::download(public_path() . '/uploads/' . $zipFileName, $zipFileName, $headers);
        } else {
            echo 'failed';
        }
    }

    public function grabShots(Request $request)
    {

        //*/////////////////////////////////////////////////
        // gather user input
        //*/////////////////////////////////////////////////

        $stats = [];

        if ($request->input("device") === "mobile"){
            $stats["height"] = "624";
            $stats["width"] = "414";
        } elseif ($request->input("device") === "tablet") {
            $stats["height"] = "1024";
            $stats["width"] = "768";
        } elseif ($request->input("device") === "desktop") {
            $stats["height"] = "1024";
            $stats["width"] = "1280";
        } elseif ($request->input("device") === "custom") {
            $stats["height"] = $request->input("height");
            $stats["width"] = $request->input("width");
        }

        $stats["delay"] = $request->input("delay");

        $userURL = $request->input("url");

        //*/////////////////////////////////////////////////
        // create new folder based on user input
        //*/////////////////////////////////////////////////

        $uploadPath = base_path() . '/public/uploads/';         // path to uploads folder on server
        $parsedUrl = parse_url($userURL);                       // parsing user input
        $domain = $parsedUrl["host"];                           // grabbing just domain from user input
        $uniqueFolder = $domain . '_' . time();                 // new unique folder name
        $newDir = $uploadPath . $uniqueFolder . '/';

        File::makeDirectory($newDir, 0777);                     // make new directory for screenshots

        //*/////////////////////////////////////////////////
        // crawl site for all possible links
        //*/////////////////////////////////////////////////

        $this->crawlSite($userURL, $newDir, $stats);

        //*/////////////////////////////////////////////////
        // zip up site folder with assets
        //*/////////////////////////////////////////////////

        $this->zipIt($newDir, $uniqueFolder);

        //*/////////////////////////////////////////////////
        // return user back to homepage
        //*/////////////////////////////////////////////////

        $data = [];
        $data["domain"] = $domain;
        $data["ziplink"] = "/uploads/" . $uniqueFolder . ".zip";

        return view('pages.report')->with('data', $data);

    }
}
?>
