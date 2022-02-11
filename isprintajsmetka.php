<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $podatok=json_decode(file_get_contents("php://input"));
    $NizaID=$podatok->NizaID;
    $ImePrezime=$podatok->ImePrezime;
    $SifraObjekt=$podatok->SifraObjekt;
    $idFirma=$podatok->idFirma;
    $Vkupno=$podatok->Vkupno;
    $BrMasa=$podatok->BrMasa;
    echo $db->IsprintajSmetka($NizaID,$ImePrezime,$SifraObjekt,$idFirma,$Vkupno,$BrMasa);
} else echo json_encode(array("error" => "Error: Database connection"));
?>