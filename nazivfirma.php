<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    echo $db->nazivFirma($_POST['idFirma']);
} else echo "Error: Database connection";
?>