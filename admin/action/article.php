<?php
include '../../config/parameters.php';
include '../../config/dbConnect.php';

if (isset($_POST) && !empty($_POST)) {
    $editMode = false;
    if (isset($_POST['article_id'])) {
        $editMode = true;
        $articleId = $_POST['article_id'];
    }
    $visibility = isset($_POST['visibility']) ? true : false;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    if ($editMode) {
        $query = $dbConnect->prepare('UPDATE article SET author = :author, title = :title, content = :content, date_updated = now(), visibility = :visibility WHERE id = :id');
        $query->bindParam(':author', $author, \PDO::PARAM_STR);
        $query->bindParam(':title', $title, \PDO::PARAM_STR);
        $query->bindParam(':content', $content, \PDO::PARAM_STR);
        $query->bindParam(':visibility', $visibility, \PDO::PARAM_BOOL);
        $query->bindParam(':id', $articleId, \PDO::PARAM_INT);
        $query->execute();
        header('location: /admin');

    } else {
        $query = $dbConnect->prepare('INSERT INTO article VALUE (null, :author, :title, :content, now(), null, :visibility)');
        $query->bindParam(':author', $author, \PDO::PARAM_STR);
        $query->bindParam(':title', $title, \PDO::PARAM_STR);
        $query->bindParam(':content', $content, \PDO::PARAM_STR);
        $query->bindParam(':visibility', $visibility, \PDO::PARAM_BOOL);
        $query->execute();
        header('location: /admin');
    }
}