<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use View;
use Input;
use Validator;
use Redirect;
use Session;

class ScreenshotsController extends Controller
{
    public function grabShots()
    {

        $url = $_POST["url"];

        print $url;
        print "<pre>";
        print_r($_SERVER);
        print "</pre>";
        die();

        // $_SERVER['HOMEDRIVE'] and $_SERVER['HOMEPATH’] //windows

        // mkdir($folderPath . date("Y-m-d H:i:s"), 0700);
    }
}
?>
