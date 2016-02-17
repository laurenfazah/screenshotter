@extends('layouts.master')

@section('content')

<section class="report">

    <div class="container">

        <a id="download" class="download-link" href="<?php echo $data["ziplink"] ?>" download>
            <p>Download your screenshots zipfile for <?php echo $data["domain"] ?></p>
            <img src="img/cloudDL.png" alt="download">
        </a>


    </div>

    <div class="return">
        <a href="/">
            <p>Return Home</p>
        </a>
    </div>

</section>
