<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $podatok=json_decode(file_get_contents("php://input"));
    $idfirma=$podatok->idfirma;
    echo $db->ListaArtikli( $idfirma );
} else echo json_encode(array("greska" => "greska"));
?>