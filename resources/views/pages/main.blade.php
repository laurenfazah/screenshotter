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
        <label for="url">Website's root URL:</label>
        <input type="url" name="url" placeholder="http://example.com" required>

        <label for="device">Which size device would you like to emulate these screenshots on?</label>
        <input type="radio" name="device" value="mobile" required> Mobile
        <input type="radio" name="device" value="tablet" required> Tablet
        <input type="radio" name="device" value="Desktop" required> Desktop
        <input type="radio" name="device" value="Custom" id="custom-dimensions" required> Custom

        <div class="custom-dimensions">
            <label for="width">Custom viewport width (px):</label>
            <input type="text" name="width" placeholder="1200" required>
        </div>

        <input type="submit" value="Submit">

        <div class="message">
            <p>Grab a coffee and don't refresh the page!</p>
            <p>This could take a while.</p>
        </div>
    </form>

    <div class="status-message"></div>

</main>
