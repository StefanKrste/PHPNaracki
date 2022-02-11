<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $podatok=json_decode(file_get_contents("php://input"));
    $BrMasa=$podatok->BrMasa;
    $idFirma=$podatok->idFirma;
    $SifraObjekt=$podatok->SifraObjekt;
    echo $db->ListaMasaArtikli($BrMasa,$idFirma,$SifraObjekt);
} else echo json_encode(array("error" => "Error: Database connection"));
?>