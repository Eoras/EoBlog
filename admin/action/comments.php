<?php
include '../../config/parameters.php';
include '../../config/dbConnect.php';

$data = [];

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $action = $_POST['action'];
    $commentId = $_POST['id'];
    if($action === 'valide') {
        $query = $dbConnect->prepare('UPDATE comment SET validated = !validated WHERE id = :id');
        $query->bindParam(':id', $commentId, \PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
        $data['success'] = true;
        $data['id'] = $commentId;
        $data['action'] = $action;
        echo json_encode($data);
    } elseif ($action === 'unvalide') {
        $query = $dbConnect->prepare('UPDATE comment SET unvalidated = !unvalidated WHERE id = :id');
        $query->bindParam(':id', $commentId, \PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
        $data['success'] = true;
        $data['id'] = $commentId;
        $data['action'] = $action;
        echo json_encode($data);
    } elseif ($action === 'delete') {
        $query = $dbConnect->prepare('DELETE FROM comment WHERE id = :id');
        $query->bindParam(':id', $commentId, \PDO::PARAM_INT);
        $query->execute();
        $query->closeCursor();
        $data['success'] = true;
        $data['id'] = $commentId;
        $data['action'] = $action;
        echo json_encode($data);
    }
} else{
    $data['action'] = "ppopoj";
    echo json_encode($data);
}