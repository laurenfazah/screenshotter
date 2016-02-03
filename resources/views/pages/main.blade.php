@extends('layouts.master')

@section('content')

<form name="url-input" action="folder.php" class="block" method="post">
    <label for="path">Path to save folder will be saved:</label>
    <input type="text" name="path" placeholder="i.e. on Mac: /Users/first.last/Desktop/" required>

    <label for="url">Website's root URL:</label>
    <input type="url" name="url" placeholder="http://example.com" required>

    <input type="submit">
</form>

<div class="status-message"></div>
