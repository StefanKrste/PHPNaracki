<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    echo $db->prekinivrska($_POST['username']);
} else echo "Error: Database connection";
?>