<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $result = $db->IbrisiArtikl($_POST['IDsmetkainfo'], $_POST['SifraArtikl'], $_POST['Kol'], $_POST['MagacinskaCena'],$_POST['idFirma'],$_POST['SifraObjekt']);
    echo $result;
} else echo "Error: Database connection";
?>