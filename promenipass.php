<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $result = $db->UpdPass($_POST['username'], $_POST['novPas']);
    echo $result;
} else echo "Error: Database connection";
?>