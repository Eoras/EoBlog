<?php
include '../../config/parameters.php';
include '../../config/dbConnect.php';
$data = [];

if (isset($_POST['id']) && !empty($_POST['id'])) {
    try {
        try {
            $reqToggleVisibility = $dbConnect->prepare('UPDATE article SET visibility = !visibility WHERE id = :id');
            $reqToggleVisibility->bindParam(':id', $_POST['id'], \PDO::PARAM_INT);
            $reqToggleVisibility->execute();
            $reqToggleVisibility->closeCursor();
        } catch (\Exception $e) {
            $data['error'] = $e;
            echo json_encode($data);
            exit();
        }

        try {
            $reqShowStatus = $dbConnect->prepare('SELECT visibility FROM article WHERE id = :id');
            $reqShowStatus->bindParam(':id', $_POST['id'], \PDO::PARAM_INT);
            $reqShowStatus->execute();
            $visibility = $reqShowStatus->fetch()['visibility'] ? true : false;
            $reqShowStatus->closeCursor();
        } catch (\Exception $e) {
            $data['error'] = $e;
            echo json_encode($data);
            exit();
        }
        $data['visibility'] = $visibility;
        $data['success'] = true;
        echo json_encode($data);

    } catch (\Exception $e) {
        $data['error'] = $e;
        echo json_encode($data);
    }
}