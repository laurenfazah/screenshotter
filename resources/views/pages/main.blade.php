@extends('layouts.master')

@section('content')

<header>
    <div class="titles">
        <h1>Screenshotter</h1>
        <h2>Site-wide screenshooting made easy.</h2>
    </div>
</header>

<main>

   <form name="url-input" action="grabShots" class="block" method="post">
        <fieldset class="url">
            <label for="url">Website's root URL:</label>
            <input type="url" name="url" placeholder="http://example.com" required>
        </fieldset>

        <fieldset class="dimensions">
            <p>Which size device would you like to emulate these screenshots on (in pixels)?</p>

            <label><input type="radio" name="device" value="mobile" required> Mobile (414x624)</label>
            <label><input type="radio" name="device" value="tablet" required> Tablet (768x1024)</label>
            <label><input type="radio" name="device" value="desktop" required> Desktop (1280x1024)</label>
            <label><input type="radio" name="device" value="custom" id="custom-dimensions" required> Custom</label>
        </fieldset>

        <fieldset class="custom-dimensions">
            <label for="width">Custom viewport width (px):</label>
            <input type="text" name="width" placeholder="1200" required>

            <label for="height">Custom viewport height (px):</label>
            <input type="text" name="height" placeholder="900" required>
        </fieldset>

        <input type="submit" value="Submit">

        <div class="message">
            <p>Grab a coffee and don't refresh the page!</p>
            <p>This could take a while.</p>
        </div>
    </form>

    <div class="status-message"></div>

</main>
