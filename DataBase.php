<?php
require "DataBaseConfig.php";

class DataBase{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    public function __construct(){
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }

    function dbConnect(){
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data){
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($username, $password){
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $this->sql = "select * from akaunti where username = '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbusername = $row['username'];
            $dbpassword = $row['password'];
            if ($dbusername == $username && $password == $dbpassword) {
                $array = array();
                $array[] = $row;
                header('Content-Type: application/json');
                return json_encode(array("akaunt" => $array));
            }
        }
        return json_encode(array("greska" => "greska"));
    }

    function signUp($table, $username, $password, $mail, $ime, $prezime, $tel){
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $mail = $this->prepareData($mail);
        $ime = $this->prepareData($ime);
        $prezime = $this->prepareData($prezime);
        $tel = $this->prepareData($tel);
        $tip = $this->prepareData("kelner");
        $this->sql = "SELECT username, mail FROM " . $table . " WHERE username = '" . $username . "' OR mail = '" . $mail . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbusername = $row['username'];
            $dbmaiil = $row['mail'];
            if ($dbusername===$username) {
                return "Username exists";
            }else if($dbmaiil===$mail){
                return "Mail exists";
            }
        }
        //$password = password_hash($password, PASSWORD_DEFAULT);
        $this->sql = "INSERT INTO " . $table . " (username, password, mail, ime, prezime, tel, tip) VALUES ('" . $username . "','" . $password . "','" . $mail . "','" . $ime . "','" . $prezime . "','" . $tel . "','" . $tip . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return "Sign Up Success";
        } else return "Sign up Failed";
    }

    function povrzi($username, $kod){
        $username = $this->prepareData($username);
        $kod = $this->prepareData($kod);
        $this->sql = "SELECT id FROM firma WHERE kod_povrzuvanje = '" . $kod . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbidFirma = $row['id'];

            $this->sql = "UPDATE akaunti SET id_firma= '" . $dbidFirma . "' WHERE username= '" . $username . "'";
            $result1 = mysqli_query($this->connect, $this->sql);

            if($result1) {
                $kod= $this->prepareData("");
                $this->sql = "UPDATE firma SET kod_povrzuvanje= '" . $kod . "' WHERE id= '" . $dbidFirma . "'";
                mysqli_query($this->connect, $this->sql);
            }else{
                return 0;
            }

            return $dbidFirma;
        } else return 0;
    }

    function zemiObjekti($id_firma){
        $tabela = $id_firma."objekti";
        $tip = "Угостителски објект";
        $this->sql = "SELECT * FROM " . $tabela . " WHERE tip_na_objekt = '" . $tip . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $array = array();
        header('Content-Type: application/json');
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)){
                $array[] = $row;
            }
            return json_encode(array("objekti"=>$array));
        }
        return json_encode(array("greska" => "greska"));
    }

    function nazivFirma($id_firma){
        $id_firma = $this->prepareData($id_firma);
        $this->sql = "SELECT ime FROM firma WHERE id = '" . $id_firma . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
                $dbNazivFirma = $row['ime'];
        }
        return $dbNazivFirma;
    }

    function prekinivrska($username){
        $username = $this->prepareData($username);
        $this->sql = "UPDATE akaunti SET id_firma= '" . "" . "' , posleden_objekt= '" . "" . "', od_masa= '" . "" . "', do_masa= '" . "" . "' WHERE username= '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    function postaviObjekt($username, $objekt){
        $username = $this->prepareData($username);
        $objekt = $this->prepareData($objekt);
        $this->sql = "UPDATE akaunti SET posleden_objekt= '" . "$objekt" . "' WHERE username= '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    function ZemiPosledenObjekt($objekt, $idFirma){
        $tabela = $idFirma."objekti";
        $this->sql = "SELECT * FROM " . $tabela . " WHERE id= '" . $objekt . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $array = array();
        header('Content-Type: application/json');
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)){
                $array[] = $row;
            }
            return json_encode(array("objekt"=>$array));
        }
        return json_encode(array("greska" => "greska"));
    }

    function ListaArtikli($idFirma){
        $tabela = $idFirma."artikli";
        $this->sql = "SELECT sifra_na_artikl,naziv_na_atikl FROM " . $tabela . "";
        $result = mysqli_query($this->connect, $this->sql);
        $array = array();
        header('Content-Type: application/json');
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)){
                $array[] = $row;
            }
            return json_encode(array("artikli"=>$array));
        }
        return json_encode(array("greska" => "greska"));
    }

    function UpdOdMasa($username, $odMasa){
        $username = $this->prepareData($username);
        $odMasa = $this->prepareData($odMasa);
        $this->sql = "UPDATE akaunti SET od_masa= '" . "$odMasa" . "' WHERE username= '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    function UpdDoMasa($username, $doMasa){
        $username = $this->prepareData($username);
        $doMasa = $this->prepareData($doMasa);
        $this->sql = "UPDATE akaunti SET do_masa= '" . "$doMasa" . "' WHERE username= '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    function UpdTel($username, $tel){
        $username = $this->prepareData($username);
        $tel = $this->prepareData($tel);
        $this->sql = "UPDATE akaunti SET tel= '" . "$tel" . "' WHERE username= '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    function UpdPass($username, $password)
    {
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $this->sql = "UPDATE akaunti SET password= '" . "$password" . "' WHERE username= '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    function ZemiArtikl($SifraArtikl, $Kol, $NegativnaKol,$SifraObjekt,$idFirma,$BrMasa){
        $SifraArtikl = $this->prepareData($SifraArtikl);
        $Kol = $this->prepareData($Kol);
        $NegativnaKol = $this->prepareData($NegativnaKol);
        $SifraObjekt = $this->prepareData($SifraObjekt);
        $BrMasa = $this->prepareData($BrMasa);
        $KolonaKol = "kol_".$SifraObjekt;
        $KolonaMagacinska ="magacinska_cena_".$SifraObjekt;
        $TabelaArtikli = $idFirma."artikli";
        $TabelaSmetkiInfo = $idFirma."_".$SifraObjekt."smetkiinfo";
        $UspesnoUpd = false;
        header('Content-Type: application/json');
        $this->connect->autocommit(false);
        try {
            $this->sql = "SELECT sifra_na_artikl,naziv_na_atikl,edinica_merka,maloprodazna_cena,danocna_tarifa,mk_proizvod,`{$KolonaKol}`,`{$KolonaMagacinska}`  FROM " . $TabelaArtikli . " WHERE sifra_na_artikl= '" . $SifraArtikl . "'";
            $result = mysqli_query($this->connect, $this->sql);
            $array = array();
            if (mysqli_num_rows($result) != 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $array[] = $row;
                }
                $razlika = $array[0][$KolonaKol]-$Kol;
                if ($NegativnaKol > 0 || $razlika >= 0) {
                    $this->sql = "UPDATE `{$TabelaArtikli}` SET `{$KolonaKol}`=`{$KolonaKol}`-'$Kol' WHERE sifra_na_artikl= '" . $SifraArtikl . "'";
                    $result = mysqli_query($this->connect, $this->sql);
                    if ($result) {
                        $this->sql = "INSERT INTO " . $TabelaSmetkiInfo . " (broj_na_masa_kasa,sifra,naziv_artikl,ed_merka,kol,maloprodazna,magacinska_cena,mk_proizvod) VALUES ('".$BrMasa."','".$array[0]['sifra_na_artikl']."','".$array[0]['naziv_na_atikl']."','".$array[0]['edinica_merka']."','".$Kol."','".$array[0]['maloprodazna_cena']."','".$array[0][$KolonaMagacinska]."','".$array[0]['mk_proizvod']."')";
                        if (mysqli_query($this->connect, $this->sql) === TRUE) {
                            $last_id = $this->connect->insert_id;
                            $array[0]["id"] = $last_id;
                            $UspesnoUpd=true;
                        }
                    }
                }
            }
            $this->connect->autocommit(true);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($this->connect);
        }
        if($UspesnoUpd){
            return json_encode(array("artikl" => $array));
        }else {
            return json_encode(array("greska" => "greska"));
        }
    }

    function IbrisiArtikl ($IDsmetkainfo,$SifraArtikl,$Kol,$MagacinskaCena,$idFirma,$SifraObjekt){
        $IDsmetkainfo = $this->prepareData($IDsmetkainfo);
        $SifraArtikl = $this->prepareData($SifraArtikl);
        $Kol = $this->prepareData($Kol);
        $MagacinskaCena = $this->prepareData($MagacinskaCena);
        $SifraObjekt = $this->prepareData($SifraObjekt);
        $TabelaArtikli = $idFirma."artikli";
        $TabelaSmetkiInfo = $idFirma."_".$SifraObjekt."smetkiinfo";
        $KolonaKol = "kol_".$SifraObjekt;
        $KolonaMagacinska ="magacinska_cena_".$SifraObjekt;
        $UspesnoUpd = false;

        $this->connect->autocommit(false);
        try {
            $this->sql = "DELETE FROM " . $TabelaSmetkiInfo . " WHERE id='" . $IDsmetkainfo . "'";
            $result = mysqli_query($this->connect, $this->sql);
            if($result){
                $this->sql = "SELECT `{$KolonaKol}`,`{$KolonaMagacinska}` FROM " . $TabelaArtikli . " WHERE sifra_na_artikl= '" . $SifraArtikl . "'";
                $result = mysqli_query($this->connect, $this->sql);
                $row = mysqli_fetch_assoc($result);
                if (mysqli_num_rows($result) != 0) {
                    $SQLKol = $row[$KolonaKol];
                    $SQLMagacinska = $row[$KolonaMagacinska];
                    $NovaKol = $SQLKol+$Kol;
                    if($SQLKol>0) {
                        $NovaMagacinska = (($SQLKol * $SQLMagacinska) + ($Kol * $MagacinskaCena)) / $NovaKol;
                    }else{
                        $NovaMagacinska = $MagacinskaCena;
                    }
                    $this->sql = "UPDATE `{$TabelaArtikli}` SET `{$KolonaKol}`='" . $NovaKol . "',`{$KolonaMagacinska}`='" . $NovaMagacinska . "' WHERE sifra_na_artikl= '" . $SifraArtikl . "'";
                    $result = mysqli_query($this->connect, $this->sql);
                    if ($result){
                        $UspesnoUpd=true;
                    }
                }
            }
            $this->connect->autocommit(true);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($this->connect);
        }
        if($UspesnoUpd){
            echo "Success";
        }else {
            echo "Error";
        }
    }

    function ListaMasaArtikli ($BrMasa,$idFirma,$SifraObjekt){
        $BrMasa = $this->prepareData($BrMasa);
        $idFirma = $this->prepareData($idFirma);
        $SifraObjekt = $this->prepareData($SifraObjekt);
        $TabelaSmetkiInfo = $idFirma."_".$SifraObjekt."smetkiinfo";
        header('Content-Type: application/json');

        $this->sql = "SELECT id,sifra,naziv_artikl,ed_merka,kol,maloprodazna,magacinska_cena,mk_proizvod FROM " . $TabelaSmetkiInfo . " WHERE broj_na_masa_kasa= '" . $BrMasa . "' AND id_smetka= '" . "" . "' ";
        $result = mysqli_query($this->connect, $this->sql);
        $array = array();
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)){
                $array[] = $row;
            }
            return json_encode(array("MArtikli"=>$array));
        }
        return json_encode(array("prazno"=>"prazno"));
    }

    function IsprintajSmetka($NizaID,$ImePrezime,$SifraObjekt,$idFirma,$Vkupno,$BrMasa){
        $array = json_decode($NizaID, true);
        $ImePrezime = $this->prepareData($ImePrezime);
        $SifraObjekt = $this->prepareData($SifraObjekt);
        $idFirma = $this->prepareData($idFirma);
        $Vkupno = $this->prepareData($Vkupno);
        $BrMasa = $this->prepareData($BrMasa);
        $TabelaSmetkiInfo = $idFirma."_".$SifraObjekt."smetkiinfo";
        $TabelaSmetki = $idFirma."_".$SifraObjekt."smetki";
        date_default_timezone_set('Europe/Skopje');
        $datum = date('d/m/Y');
        $vreme = date("h:i:s");
        header('Content-Type: application/json');

        $this->connect->autocommit(false);
        try {
            $this->sql = "SELECT broj,status FROM " . $TabelaSmetki . " ORDER BY id DESC LIMIT 1;";
            $result = mysqli_query($this->connect, $this->sql);
            $row = mysqli_fetch_assoc($result);

            if(isset($row['broj'])) {
                $SmetkaBroj = 1;
                $Status = 0;
            } else {
                $SmetkaBroj = $row['broj'];
                $Status = $row['status'];
                if ($Status === "1") {
                    $SmetkaBroj = "1";
                } else {
                    $SmetkaBroj = $SmetkaBroj + 1;
                }
            }

            $this->sql = "INSERT INTO " . $TabelaSmetki . " (broj,broj_na_masa_kasa,datum,vreme,vkupno,ispecatil,status) VALUES ('" . $SmetkaBroj . "','" . $BrMasa . "','" . $datum . "','" . $vreme . "','" . $Vkupno . "','" . $ImePrezime . "','" . $Status . "')";
            if (mysqli_query($this->connect, $this->sql) === TRUE) {
                $last_id = $this->connect->insert_id;
                for ($i = 0; $i < count($array); $i++) {
                    $this->sql = "UPDATE `{$TabelaSmetkiInfo}` SET id_smetka='" . "$last_id" . "' WHERE id= '" . $array[$i] . "'";
                    $result = mysqli_query($this->connect, $this->sql);
                    if ($result) {
                        $UspesnoUpd = true;
                    }
                }
            }

            $this->connect->autocommit(true);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($this->connect);
        }
        if($UspesnoUpd){
            return json_encode(array("idSmetka"=>$last_id));
        }else {
            return json_encode(array("greska"=>"greska"));
        }
    }
}
?>
