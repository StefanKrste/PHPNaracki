<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $result = $db->signUp("akaunti", $_POST['username'], $_POST['password'], $_POST['mail'], $_POST['ime'], $_POST['prezime'], $_POST['tel']);
    echo $result;
} else echo "Error: Database connection";
?>
