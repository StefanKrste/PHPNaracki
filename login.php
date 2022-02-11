<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $podatok=json_decode(file_get_contents("php://input"));
    $username=$podatok->username;
    $password=$podatok->password;
    echo $db->logIn($username, $password);
} else echo json_encode(array("error" => "Error: Database connection"));
?>