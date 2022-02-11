<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    echo $db->postaviObjekt($_POST['username'],$_POST['objekt']);
} else echo "Error: Database connection";
?>
