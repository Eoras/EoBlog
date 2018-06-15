<?php

include 'config/config.php';

try {
    $bdd = new PDO("mysql:host=localhost;dbname=$configBddNameBdd;charset=utf8", "$configBddUserName", "$configBddPassword");
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css">
    <link rel="icon" type="image/png" href="assets/favicon.png">

    <title><?= $configMarque . ' - ' . $configTitreDuBlog ?></title>
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php"><?= $configMarque ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Accueil</a>
            </li>
        </ul>
        <a class="btn btn-sm border-info bg-dark text-info" href="#">Ajouter un article</a>
    </div>
</nav>

<div class="container py-3">
    <h1><?= $configTitreDuBlog ?></h1>