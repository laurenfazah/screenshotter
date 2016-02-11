@extends('layouts.master')

@section('content')

<div class="report">

    <a class="download-link" href="<?php echo $data["ziplink"] ?>" download>
        <p>Download screenshots zipfile for <?php echo $data["domain"] ?></p>
        <img src="img/cloudDL.png" alt="download">
    </a>

</div>
