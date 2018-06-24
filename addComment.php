<?php
include 'config/parameters.php';
include 'config/dbConnect.php';
$data = [];

try {
    $bdd = new PDO("mysql:host=localhost;dbname=$pDB_Name;charset=utf8", "$pDB_UserName", "$pDB_Password");
} catch (Exception $e) {
    $data['error'] = $e->getMessage();
    echo json_encode($data);
    exit();
}

if ($_POST && !empty($_POST)) {
    $author = htmlentities($_POST['author']);
    $comment = htmlentities($_POST['comment']);
    $articleId = is_numeric($_POST['articleId']) ? $_POST['articleId'] : 0;

    if($articleId <= 0 ) {
        $data['error'] = 'The ID of the article can\'t be null !';
        echo json_encode($data);
        exit();
    }
    if(strlen($author) < $pBlog_MinLengthAuthor || strlen($comment) < $pBlog_MinLengthComment) {
        $data['error'] = 'You do not respect the length conditions, try again !';
        echo json_encode($data);
        exit();
    } else {
        $req = $dbConnect->prepare('INSERT INTO comment (id_article, author, comment, date_created) VALUES (:id_article, :author, :comment, now())');
        $req->bindParam(':id_article', $articleId, \PDO::PARAM_INT);
        $req->bindParam(':author', $author, \PDO::PARAM_STR);
        $req->bindParam(':comment', $comment, \PDO::PARAM_STR);
        $data['success'] = true;
        $req->execute();
        echo json_encode($data);
        exit();
    }
}

$data['error'] = "Erreur";
echo json_encode($data) ?>