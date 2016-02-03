<?php

$folderPath = $_POST["path"];
if (substr($folderPath, -1) !== '/') {
    $folderPath = $folderPath . '/';
}

$url = $_POST["url"];

// print $folderPath;
// print $url;
print "<pre>";
print_r($_SERVER);
print "</pre>";
die();

// $_SERVER['HOMEDRIVE'] and $_SERVER['HOMEPATH’] //windows

mkdir($folderPath . date("Y-m-d H:i:s"), 0700);

?>
