<?php
if(!file_exists('../config/parameters.php')) {
    header('location: /install');
    exit();
}
session_start();
if(!isset($_SESSION['isAdmin']) OR !$_SESSION['isAdmin'] === true) header("location: /login.php");
include '../config/parameters.php';
include '../config/dbConnect.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="icon" type="image/png" href="../assets/favicon.png">

    <script src="../assets/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js.js"></script>

    <title><?= $pBlog_Mark . ' - ' . $pBlog_Title ?></title>
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-dark bg-danger">
    <a class="navbar-brand ex" href="/"><i class="fa fa-home" aria-hidden="true"></i> <?= $pBlog_Mark ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav">
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Articles</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/admin/"><i class="fa fa-list" aria-hidden="true"></i> All articles</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/admin/article.php"><i class="fa fa-plus" aria-hidden="true"></i> Add article</a>
                </div>
            </li>
        </ul>
        <div class="ml-auto">
                <a class="ml-2 btn btn-sm border-light text-light bg-danger" href="/admin/logout.php" data-tooltips="tooltip"
                   data-placement="right" title="Log out">
                    <i class="fa fa-unlock" aria-hidden="true"></i>
                </a>
        </div>
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

