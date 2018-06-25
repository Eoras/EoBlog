<?php
include '../../config/parameters.php';
include '../../config/dbConnect.php';
session_start();
unset($_SESSION['errors']);
if (isset($_POST) && !empty($_POST)) {
    $editMode = false;
    if (isset($_POST['article_id'])) {
        $editMode = true;
        $articleId = $_POST['article_id'];
    }
    $visibility = $_POST['visibility'] ?? false;
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);

    if(empty($title)) $_SESSION['errors']['title'] = "Title can't be empty !";
    if(empty($content)) $_SESSION['errors']['content'] = "Content can't be empty !";
    if(empty($author)) $_SESSION['errors']['author'] = "Author can't be empty !";

    if(empty($_SESSION['errors'])) {
        if ($editMode) {
            $query = $dbConnect->prepare('UPDATE article SET author = :author, title = :title, content = :content, date_updated = now(), visibility = :visibility WHERE id = :id');
            $query->bindParam(':author', $author, \PDO::PARAM_STR);
            $query->bindParam(':title', $title, \PDO::PARAM_STR);
            $query->bindParam(':content', $content, \PDO::PARAM_STR);
            $query->bindParam(':visibility', $visibility, \PDO::PARAM_BOOL);
            $query->bindParam(':id', $articleId, \PDO::PARAM_INT);
            $query->execute();
            header('location: /admin');
            exit();

        } else {
            $query = $dbConnect->prepare('INSERT INTO article VALUE (null, :author, :title, :content, now(), null, :visibility)');
            $query->bindParam(':author', $author, \PDO::PARAM_STR);
            $query->bindParam(':title', $title, \PDO::PARAM_STR);
            $query->bindParam(':content', $content, \PDO::PARAM_STR);
            $query->bindParam(':visibility', $visibility, \PDO::PARAM_BOOL);
            $query->execute();
            header('location: /admin');
            exit();
        }
    } else {
        $_SESSION['POST'] = $_POST;
        header("location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }
}