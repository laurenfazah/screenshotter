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

    public function takeScreenshots($site, $newDir, $stats, $siteStatus, $auth)
    {

        $delay = $stats["delay"];

        $urlHost = parse_url($site)["host"];
        $urlPath = isset(parse_url($site)['path']) ? parse_url($site)['path'] : '';
        $urlPathName = str_replace("/", "",$urlPath);

        if ($siteStatus === "OK") {
            $filepath = $newDir . $urlHost . "_" . $urlPathName . ".jpg";
        } elseif ($siteStatus === "404") {
            $filepath = $newDir . $urlHost . "_" . $urlPathName . "_" . $siteStatus  . ".jpg";
        }

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
        if ($auth["status"] == true){
            $request->addHeader('Authorization', 'Basic '. base64_encode($auth["username"].":".$auth["password"]));
        }
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

    public function crawlSite($site, $newDir, $stats, $auth)
    {
        $trimmedSite = rtrim($site,"/");
        $escapedSite = addslashes($trimmedSite);

        $crawler = new PHPCrawler();                                // set new class instances

        $crawler->setURL($site);                                    // URL to crawl

        $crawler->addContentTypeReceiveRule("#text/html#");         // only receive content-type "text/html"

        if ($auth["status"] == true){
            $crawler->addBasicAuthentication("#". $trimmedSite ."#", $auth["username"], $auth["password"]);
        }

        $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png|js|svg|css|ico|pdf|mp3|mp4|webm)$# i");    // ignore & don't request pics

        $crawler->enableCookieHandling(true);                       // store and send cookie-data like a browser does

        // $crawler->setTrafficLimit(1000 * 1024);                     // limiting traffic (for dev)

        $crawler->go();                                             // all info in, good to go

        $allLinks = $crawler->all_links_found;

        $siteLinks = [];
        $deadLinks = [];

        // dead and live links kept separate in case of a rollback to just 200s
        foreach ($allLinks as $link) {
            if ($link["status_code"] === 200){
                $siteLinks[] = $link["url"];
            } elseif ($link["status_code"] === 404) {
                $deadLinks[] = $link["url"];
            }
        }

        $uniqueLinks = array_unique($siteLinks);                    // clear out duplicates
        $uniqueDeadLinks = array_unique($deadLinks);                // clear out duplicates

        //*/////////////////////////////////////////////////
        // take screenshots of site
        //*/////////////////////////////////////////////////

        foreach ($uniqueLinks as $link) {
            $this->takeScreenshots($link, $newDir, $stats, "OK", $auth);   // follow through to take screenshots
        }

        foreach ($uniqueDeadLinks as $link) {
            $this->takeScreenshots($link, $newDir, $stats, "404", $auth);  // follow through to take screenshots
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
            return;
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
            $stats["height"] = "900";  // custom viewport height not necessary
            $stats["width"] = $request->input("width");
        }

        $stats["delay"] = $request->input("delay");

        $userURL = $request->input("url");

        $auth = [];
        if ($request->input("auth") == "yes"){
            $auth["status"] = true;
            $auth["username"] = $request->input("username");
            $auth["password"] = $request->input("password");
        } else {
            $auth["status"] = false;
        }


        //*/////////////////////////////////////////////////
        // create new folder based on user input
        //*/////////////////////////////////////////////////

        $uploadPath = base_path() . '/public/uploads/';         // path to uploads folder on server
        $parsedUrl = parse_url($userURL);                       // parsing user input
        $domain = $parsedUrl["host"];                           // grabbing just domain from user input
        $uniqueFolder = $domain . '_' . time();
        $newDir = $uploadPath . $domain . '_' . time() . '/';   // new unique folder name

        File::makeDirectory($newDir, 0777);                     // make new directory for screenshots

        //*/////////////////////////////////////////////////
        // crawl site for all possible links
        //*/////////////////////////////////////////////////

        $this->crawlSite($userURL, $newDir, $stats, $auth);

        //*/////////////////////////////////////////////////
        // zip up site folder with assets
        //*/////////////////////////////////////////////////

        $this->zipIt($newDir, $uniqueFolder);

        //*/////////////////////////////////////////////////
        // return user back to homepage
        //*/////////////////////////////////////////////////

        $ziplink = "/uploads/" . $uniqueFolder . ".zip";

        $data = [];
        $data["domain"] = $domain;
        $data["ziplink"] = $ziplink;

        if (File::exists($_SERVER['DOCUMENT_ROOT'].$ziplink)) {
            return view('pages.report')->with('data', $data);
        } else {
            return redirect()->route('error');
        }


    }
}
?>
