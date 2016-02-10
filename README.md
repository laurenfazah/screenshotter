# Screenshotter

## A tool intended to aid with the screenshotting of an entire site's sitemap (individual screenshots for each page of a website)

### Intended use flow:
1. Drop in site's URL
2. Site is scanned for all internal pages
3. Folder is created on server
4. Script opens each internal page and saves a screenshot of each to said folder
5. Folder's contents are zipped up
6. Users are prompted to download zipfile

### Libraries in use:
* [PHPCrawl](http://phpcrawl.cuab.de/) for sitemapping
* [PHP-PhantomJS](http://jonnnnyw.github.io/php-phantomjs/4.0/) for screenshotting

### Current Status:
Screenshotter is currently able to scrape a site for all internal pages, screenshot each page, and create a zipfile containing all screenshots available for download.
