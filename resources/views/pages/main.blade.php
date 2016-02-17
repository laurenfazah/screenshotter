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

        <fieldset class="delay">
            <p>How much of a delay should be built in for each page's screenshot?</p>

            <label><input type="radio" name="delay" value="0" required>None</label>
            <label><input type="radio" name="delay" value="2" required>2 seconds</label>
            <label><input type="radio" name="delay" value="5" required>5 seconds</label>
            <label><input type="radio" name="delay" value="10" required>10 seconds</label>
            <label><input type="radio" name="delay" value="15" required>15 seconds</label>
        </fieldset>

        <fieldset class="dimensions">
            <p>Which size device would you like to emulate these screenshots on (in pixels)?</p>

            <label><input type="radio" name="device" value="mobile" required> Mobile (414x624)</label>
            <label><input type="radio" name="device" value="tablet" required> Tablet (768x1024)</label>
            <label><input type="radio" name="device" value="desktop" required> Desktop (1280x1024)</label>
            <label><input type="radio" name="device" value="custom" id="custom-dimensions" required> Custom</label>
        </fieldset>

        <fieldset class="custom-dimensions">
            <label for="width">Viewport width in pixels (height determined by content) :</label>
            <input type="text" name="width" placeholder="1200">
        </fieldset>

        <input type="submit" value="Submit">

        <div class="message">
            <p>Don't refresh the page - this could take a while.</p>
        </div>
    </form>

    <div class="status-message"></div>

</main>
