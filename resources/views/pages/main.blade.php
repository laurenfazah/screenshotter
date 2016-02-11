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

        <label for="height">Viewport height (px):</label>
        <input type="text" name="height" placeholder="2000" required>

        <label for="width">Viewport width (px):</label>
        <input type="text" name="width" placeholder="1400" required>

        <input type="submit" value="Submit">

        <div class="message">
            <p>Hang tight and don't refresh!</p>
        </div>
    </form>

    <div class="status-message"></div>

</main>
