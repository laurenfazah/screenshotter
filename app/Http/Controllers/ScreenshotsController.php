<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CustomClasses\PHPCrawl\libs\PHPCrawler;
use App\CustomClasses\PHPCrawl\libs\MyCrawler;
use App\CustomClasses\GrabzItClient;
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

    public function crawlSite($site, $filepath)
    {

        // $spiderScript = "wget --spider -r http://www.laurenfazah.com 2>&1 | grep '^--' | awk '{ print $3 }' | grep -v '\.\(css\|js\|png\|gif\|jpg\|JPG\)$' > /Users/lauren.fazah/Desktop/testshots/test/urls.txt";


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
        // $crawler->setTrafficLimit(5242880);
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


        print "<pre>";
        print_r ($report);
        print "</pre>";
        die();

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

        $this->crawlSite($userURL, $filepath);

        // $this->createXMLSitemap($userURL, $newDir);  // to create xml file

        // print "<pre>";
        // print($newDir);
        // print "</pre>";
        // die();



        //*/////////////////////////////////////////////////
        // take screenshots of site
        //*/////////////////////////////////////////////////

        // $this->takeScreenshots($userURL, $filepath);


        //*/////////////////////////////////////////////////
        // return user back to homepage
        //*/////////////////////////////////////////////////

        return redirect()->back();

    }
}
?>
