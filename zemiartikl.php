<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $podatok=json_decode(file_get_contents("php://input"));
    $SifraArtikl=$podatok->SifraArtikl;
    $Kol=$podatok->Kol;
    $NegativnaKol=$podatok->NegativnaKol;
    $SifraObjekt=$podatok->SifraObjekt;
    $idFirma=$podatok->idFirma;
    $BrMasa=$podatok->BrMasa;
    echo $db->ZemiArtikl($SifraArtikl, $Kol,$NegativnaKol,$SifraObjekt,$idFirma,$BrMasa);
} else echo json_encode(array("error" => "Error: Database connection"));
?>