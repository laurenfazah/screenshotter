@extends('layouts.master')

@section('content')

<section class="report">

    <div class="container">

        <a id="download" class="download-link" href="<?php echo $data["ziplink"] ?>" download>
            <p>Download your screenshots zipfile for <?php echo $data["domain"] ?></p>
            <img src="img/cloudDL.png" alt="download">
        </a>


        @if ($data["exceptions"] !== [])
            <div class="exceptions">
                <h4>We had trouble rendering these links found:</h4>
                <ul>
                @foreach ($data["exceptions"] as $exception)
                    <li>
                        <p>{{ $exception }}</p>
                    </li>
                @endforeach
                </ul>
            </div>
        @endif

    </div>

    <div class="return">
        <a href="/">
            <p>Return Home</p>
        </a>
    </div>

</section>
