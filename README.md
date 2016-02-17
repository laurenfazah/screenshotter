# Screenshotter

## A tool intended to aid with the screenshotting of an entire site's sitemap (individual screenshots for each page of a website)

### User flow:
1. Drop in site's URL
2. Site is scanned for all internal pages
3. New folder is created on server
4. Script opens each internal page and saves a screenshot of each to said folder
5. Folder's contents are zipped up
6. Users are prompted to download zipfile

### Libraries in use:
* [PHPCrawl](http://phpcrawl.cuab.de/) for sitemapping
* [PHP-PhantomJS](http://jonnnnyw.github.io/php-phantomjs/4.0/) for screenshotting
* [Jens Segers Agent Parser](https://github.com/jenssegers/agent) for device detection
* Built with [Laravel](https://laravel.com/)

### Current Status:

Screenshotter is currently able to:
* scrape a site for all internal pages
* screenshot each page at custom dimensions
* create a zipfile containing all screenshots available for download
* pages returning a 404 are screenshot with "404" appended to their filename

**Screenshotter will only work on desktop**

