<?php
require "DataBase.php";
$db = new DataBase();
if ($db->dbConnect()) {
    $podatok=json_decode(file_get_contents("php://input"));
    $vrednost=$podatok->idFirma;
    echo $db->zemiObjekti( $vrednost );
} else echo json_encode(array("greska" => "greska"));
?>