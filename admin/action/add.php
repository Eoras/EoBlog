<?php
include '../../config/parameters.php';
include '../../config/dbConnect.php';

if (isset($_POST) && !empty($_POST)) {
    $visibility = isset($_POST['visibility']) ? true : false;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $pseudo = $_POST['pseudo'];
    var_dump($visibility, $title, $content, $pseudo, $_POST);

    $req = $dbConnect->prepare('INSERT INTO article VALUE (null, :pseudo, :title, :content, now(), null, :visibility)');
    $req->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
    $req->bindParam(':title', $title, \PDO::PARAM_STR);
    $req->bindParam(':content', $content, \PDO::PARAM_STR);
    $req->bindParam(':visibility', $visibility, \PDO::PARAM_BOOL);
    $req->execute();

    header('location: /admin');
}