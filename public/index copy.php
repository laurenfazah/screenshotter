<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>Screenshotter</title>

        <meta name="twitter:card" content="">
        <meta name="twitter:site" content="">
        <meta name="twitter:title" content="">
        <meta name="twitter:description" content="">
        <meta name="twitter:image:src" content="">

        <meta property="og:title" content=""/>
        <meta property="og:url" content=""/>
        <meta property="og:image" content=""/>
        <meta property="og:site_name" content=""/>
        <meta property="og:description" content=""/>

        <link rel="stylesheet" href="stylesheets/style.css">

        <script src="js/vendor/modernizr.js"></script>
    </head>
    <body>
        <div class="container">
            <form name="url-input" action="folder.php" class="block" method="post">
                <label for="path">Path to save folder will be saved:</label>
                <input type="text" name="path" placeholder="i.e. on Mac: /Users/first.last/Desktop/" required>

                <label for="url">Website's root URL:</label>
                <input type="url" name="url" placeholder="http://example.com" required>

                <input type="submit">
            </form>

            <div class="status-message"></div>

        </div>

        <script>
            // google analytics
        </script>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
