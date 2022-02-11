<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $podatok=json_decode(file_get_contents("php://input"));
    $idfirma=$podatok->idfirma;
    $objekt=$podatok->objekt;
    echo $db->ZemiPosledenObjekt($objekt,$idfirma);
} else echo json_encode(array("greska" => "greska"));;
?>
