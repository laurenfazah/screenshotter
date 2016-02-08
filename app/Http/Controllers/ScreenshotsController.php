<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
// use App\CustomClasses\Sitemap;
// use App\CustomClasses\Spider;
// use App\CustomClasses\RecursiveCrawler;
use App\CustomClasses\GrabzItClient;
use App\CustomClasses\Crawler\WebCrawler as WebCrawler;
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

        print "<pre>";
        print($dir);
        print "</pre>";
        die();
    }

    public function crawlSite($site, $filepath)
    {
        // set_time_limit(3000);
        // $allLinks = array();
        // $crawler = WebCrawler::getInstance();
        // //Crawler for aplopio
        // $crawler->beginCrawl($site);
        // $aplopio = $crawler->getWebPages();
        // foreach ($aplopio as $url => $properties) {
        //     array_push($allLinks, $url);
        // }

        // print "<pre>";
        // print_r($allLinks);
        // print "</pre>";
        // die();

    }

    public function takeScreenshots($site, $filepath)
    {
        $grabzIt = new GrabzItClient("MTRmOTdkZDJmNWYyNDU2MmI4NDBkYzYzYTIxODdhNzg=", "M1c/Pz9sPz9VJT8/XBY/Jn0dPysAPz8/PxpBRWwpNX8=");
        // To take a image screenshot
        $grabzIt->SetImageOptions($site);
        $grabzIt->SaveTo($filepath);
    }

    public function grabShots()
    {

        //*/////////////////////////////////////////////////
        // create new folder based on user input
        //*/////////////////////////////////////////////////

        $userURL = $_POST["url"];                               // user input
        $uploadPath = base_path() . '/public/uploads/';         // path to uploads folder on server
        $parsedUrl = parse_url($userURL);                       // parsing user input
        $domain = $parsedUrl["host"];                           // grabbing just domain from user input
        $newDir = $uploadPath . $domain . '_' . date('Y-m-d'.'-'.'H:i:s') . '/';  // new unique folder name
        $filepath = $uploadPath . $domain . '_' . date('Y-m-d'.'-'.'H:i:s') . '/'. $domain . '.png';  // new unique file name

        File::makeDirectory($newDir, 0777);                     // make new directory for screenshots


        //*/////////////////////////////////////////////////
        // crawl site for all possible links
        //*/////////////////////////////////////////////////

        // $this->crawlSite($userURL, $filepath);

        // $this->createXMLSitemap($userURL, $newDir);  // to create xml file

        // print "<pre>";
        // print($newDir);
        // print "</pre>";
        // die();



        //*/////////////////////////////////////////////////
        // take screenshots of site
        //*/////////////////////////////////////////////////

        $this->takeScreenshots($userURL, $filepath);


        //*/////////////////////////////////////////////////
        // return user back to homepage
        //*/////////////////////////////////////////////////

        return redirect()->back();

    }
}
?>
