<?php
include '../../config/parameters.php';
include '../../config/dbConnect.php';

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $reqDelete = $dbConnect->prepare('DELETE FROM article WHERE id = :id');
    $reqDelete->bindParam(':id', $_POST['id'], \PDO::PARAM_INT);
    $reqDelete->execute();
    echo 'OK';
}