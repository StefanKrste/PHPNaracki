<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $result = $db->UpdOdMasa($_POST['username'], $_POST['od_masa']);
    echo $result;
} else echo "Error: Database connection";
?>