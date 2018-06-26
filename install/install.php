<?php
include 'params.php';

$data = [];
$dbTable = [
    "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS comment",
    "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS article",
    "CREATE TABLE IF NOT EXISTS article (
      id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      author varchar(255) NOT NULL,
      title varchar(255) NOT NULL,
      content text NOT NULL,
      date_created datetime NOT NULL,
      date_updated datetime NULL,
      visibility tinyint(1) NOT NULL DEFAULT '1'
      );",
    "CREATE TABLE IF NOT EXISTS comment (
      id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      id_article int(11) NOT NULL,
      author varchar(255) NOT NULL,
      comment text NOT NULL,
      date_created datetime NOT NULL,
      validated tinyint(1) NOT NULL DEFAULT '0',
      unvalidated tinyint(1) NOT NULL DEFAULT '0'
    );",
    "ALTER TABLE comment
      ADD CONSTRAINT fk_comment FOREIGN KEY (id_article) REFERENCES article (id) ON DELETE CASCADE ON UPDATE NO ACTION;
      COMMIT;",
    "ALTER TABLE comment
      ADD KEY fk_comment (id_article);",
    "INSERT INTO article (id, author, title, content, date_created, date_updated, visibility)
      VALUES (NULL, 'Eoras', 'My first article NOT visible', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2018-06-18 07:55:11', NULL, '0');",
    "INSERT INTO article (id, author, title, content, date_created, date_updated, visibility)
      VALUES (NULL, 'Eoras', 'My second article visible', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', '2018-06-20 14:55:11', NULL, '1');",
    "INSERT INTO comment (id, id_article, author, comment, date_created, validated)
      VALUES
      (NULL, '1', 'Loan', 'Good work ;)', '2018-06-19 12:32:00', '1'),
      (NULL, '1', 'Alexandre', 'Have to go, cya', '2018-06-20 14:02:15', '0'),
      (NULL, '2', 'Melissa', 'Nice have fun !', '2018-06-20 15:07:08', '0');"
];
if(isset($_POST) && !empty($_POST)) {
    $pDB_Name = (!empty($_POST['pDB_Name']) ? htmlentities($_POST['pDB_Name']) : 'EoBlog_DB');
    $pDB_UserName = $_POST['pDB_UserName'];
    $pDB_Password = $_POST['pDB_Password'];

    // Ensure reporting is setup correctly
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $mysqli = new mysqli('localhost',$pDB_UserName,$pDB_Password);
        try {
            $mysqli->query("CREATE DATABASE $pDB_Name;");
            $db = new PDO("mysql:host=localhost;dbname=$pDB_Name;charset=utf8",
                "$pDB_UserName",
                "$pDB_Password", [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

            foreach ($dbTable as $query) {
                if($db->exec($query) === false) {
                    $data['error'] = 'Error creating tables, maybe they already exist.';
                    echo json_encode($data); exit();
                }
            }
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
            echo json_encode($data); exit();
        }
    } catch (mysqli_sql_exception $e) {
        $data['error'] = $e->getMessage();
        echo json_encode($data); exit();
    }

    try {
        $parametersFile = fopen("parameters.php.dist", "w");
        $first = true;
        foreach ($parameters as $keyName => $param) {
            if ($first) {
                try {
                    fwrite($parametersFile, "<?php\n"); $first = false;
                } catch (Exception $e) {
                    $data['error'] = 'Unable to write the file parameter.php in the folder';
                    echo json_encode($data); exit();
                }
            }
            try {
                fwrite($parametersFile, "$" . $keyName . " = " . (key_exists($keyName, $_POST) ? ($param['type'] === 'INT' ? $_POST[$keyName] : "\"" . $_POST[$keyName] . "\"") : ($param['type'] === 'INT' ? $param['value'] : "\"" . $param['value'] . "\"")) . ";\n");
            } catch (Exception $e) {
                $data['error'] = 'Unable to write the file parameter.php in the folder';
                echo json_encode($data); exit();
            }
        }
        try {
            copy("parameters.php.dist", '../config/parameters.php');
            unlink("parameters.php.dist");
        } catch (Exception $e) {
            $data['error'] = 'Could not move the file';
            echo json_encode($data); exit();
        }
        $data['success'] = true;
        echo json_encode($data); exit();
    } catch (Exception $e) {
        $data['error'] = 'Unable to open the file parameter.php in the folder';
        echo json_encode($data); exit();
    }
}
echo json_encode($data);