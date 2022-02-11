<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $result = $db->UpdDoMasa($_POST['username'], $_POST['do_masa']);
    echo $result;
} else echo "Error: Database connection";
?>