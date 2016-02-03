@extends('layouts.master')

@section('content')

<form name="url-input" action="folder.php" class="block" method="post">
    <label for="url">Website's root URL:</label>
    <input type="url" name="url" placeholder="http://example.com" required>
    <input type="submit">
</form>

<div class="status-message"></div>
