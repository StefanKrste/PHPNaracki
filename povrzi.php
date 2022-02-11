<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    echo $db->povrzi($_POST['username'], $_POST['kod_povrzuvanje']);
} else echo "Error: Database connection";
?>