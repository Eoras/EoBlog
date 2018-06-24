<?php
include 'parameters.php';
try {
    $dbConnect = new PDO("mysql:host=localhost;dbname=$pDB_Name;charset=utf8", "$pDB_UserName", "$pDB_Password");
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>