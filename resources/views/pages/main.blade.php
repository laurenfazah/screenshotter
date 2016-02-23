@extends('layouts.master')

@section('content')



<main>
    <header>
        <div class="titles">
            <h1>hh snaps</h1>
        </div>
    </header>
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

        <div class="all-dimensions">
            <fieldset class="dimensions">
                <p>Which size device would you like to capture these screenshots with (in pixels)?</p>

                <label><input type="radio" name="device" value="mobile" required> Mobile (414x624)</label>
                <label><input type="radio" name="device" value="tablet" required> Tablet (768x1024)</label>
                <label><input type="radio" name="device" value="desktop" required> Desktop (1280x1024)</label>
                <label><input type="radio" name="device" value="custom" id="custom-dimensions" required> Custom</label>
            </fieldset>

            <fieldset class="custom-dimensions">
                <label for="width">Viewport width in pixels (height determined by content):</label>
                <input type="text" name="width" placeholder="1200">
            </fieldset>
        </div>

        <fieldset class="auth-check">
            <label for="auth">Does this site require server authentication?</label>
            <label><input type="radio" name="auth" value="yes" id="auth" class="auth-field"> Yes </label>
            <label><input type="radio" name="auth" value="no" class="auth-field"> No </label>
        </fieldset>

        <fieldset class="auth">
            <label for="username" class="auth-field">Username:<input type="text" name="username" placeholder="username"></label>
            <label for="password" class="auth-field">Password:<input type="password" name="password" placeholder="password"></label>
        </fieldset>

        <input type="submit" value="Submit">

        <div class="message">
            <p>Don't refresh the page - this could take a while.</p>
        </div>
    </form>

    <div class="status-message"></div>

</main>
