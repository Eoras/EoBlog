<?php
if(!file_exists('config/parameters.php')) {
    header('location: /install');
    exit();
}
include 'config/parameters.php';
include 'config/dbConnect.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" type="image/png" href="assets/favicon.png">

    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js.js"></script>

    <title><?= $pBlog_Mark . ' - ' . $pBlog_Title ?></title>
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="/"><?= $pBlog_Mark ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Blog</a>
            </li>
        </ul>
        <a class="ml-2 btn btn-sm border-info bg-dark text-info" href="/admin" data-tooltips="tooltip"
           data-placement="right" title="Admin">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </a>
    </div>
</nav>

<div class="container py-3">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h1><?= $pBlog_Title ?></h1>
                <h3 class=""><?= $title ?? '' ?></h3>
            </div>
        </div>
    </div>