<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $result = $db->UpdTel($_POST['username'], $_POST['tel']);
    echo $result;
} else echo "Error: Database connection";
?>