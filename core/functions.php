<?php

    //Fuggvények


function getConnection()
{
/**
 * Adatbázis kapcsolat
 */
global $config;
$connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']); //el kell tárolni egy változóban
$connection->set_charset("utf8"); //ez itt fontos
if (!$connection) {
    logMessage("Error", "Failed to connect to MySQL:" . mysqli_connect_error());
    errorPage();
    //die(mysqli_connect_error()); //ha a die-t meghívom akkor megáll az oldal betőltése
}
return $connection;

}







/**
 * Egy route (útvonal) és a hozzá tartozó kezelő függvény hozzáadása a $routes assoc tömbhöz.
 *
 * @global
 * @param   mixed   $route      Útvonal
 * @param   mixed   $callable   Handler function (Controller)
 * @param   string  $method     Default: "GET"
 * @return  void
 */
/*function route($route, $callable, $method = "GET") {
    global $routes;
   // $route = "%^$route$%"; //itt csinál mintát belőle a % -ék a / jel kizárása , A helyett nem itt adjuk hozzá hanem akkor amikor kiveszük a tömből.
    $routes[strtoupper($method)][$route] = $callable;
} */

/**
 * Az aktuális útvonalhoz tartozó kezelő függvény(Controller) kikeresése, meghívása
 *
 * @param [string] $actualRoute Az aktuális útvonal
 * @return [bool] true - találat esetén | false egyébként
 */
function dispatch($actualRoute, $notFound) {
    global $routes;
    $method = $_SERVER["REQUEST_METHOD"];   // POST GET PATH DELETE
    if (key_exists($method, $routes)) { //key_exists -van e az adott kulcs(létezik e)
        foreach ($routes[$method] as $route => $callable) {
            $route = "%^$route$%"; //itt tesszük rá
            if (preg_match($route, $actualRoute, $matches)) { //$matches -kimeneti paraméter
                return $callable($matches);
            }
        }
    }
    return $notFound();
}

function loginUser($connection, $message, $valid)
{
    // a jelszót az összehasonlítás miatt kérdezem le
    $query = "SELECT id,felhasznalonev,jelszo,Jogkorok_id,hozzaferes FROM felhasznalok WHERE felhasznalonev = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "s", $_POST['felhasznalonev']); //bind-hozzákötés"s"-string
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_assoc($result);

        if ($record != null && password_verify($_POST['jelszo'], $record['jelszo']) && $record['hozzaferes']=="engedélyezett") {
            //return $record;
            $_SESSION['id'] = $record['id'];
            $_SESSION['jogkor'] = $record['Jogkorok_id'];
            $_SESSION['felhasznalonev'] = $record['felhasznalonev'];
            $message = 'A bejelentkezés sikeres';
            $valid = true;
        }
        else if($record == null || password_verify($_POST['jelszo'], $record['jelszo']) != true) {
           
           $message = 'A bejelentkezés sikertelen rossz felhasználónév-jelszó páros';
           $valid = false;
        }
        else if ($record['hozzaferes']!="engedélyezett") {

            $valid = false;
            $message = 'Hozzáférés megtagadva.'; 
        }
        return 
        json_encode(
           array(
               'valid' => $valid,
               'message' => $message,
               'felhasznalo_id' => $_SESSION['id'],
               'jogkor' =>  $_SESSION['jogkor']
           )
           );
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }
}

function registrationUser($connection,$message, $message2, $message3, $valid)
{
    $query = "SELECT felhasznalonev, email FROM felhasznalok WHERE felhasznalonev = ? OR email = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "ss", $_POST['felhasznalonev'],$_POST['email']); //bind-hozzákötés"s"-string
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_all($result, MYSQLI_ASSOC); //FONTOS az egész tömböt beleteszem a rekordba és ez alapján már lehetővé válik mind a 3 esetben a vizsgálat

        if ($record == null) {

            $cost_of_hash = array('cost' => 11);  //11- bcrypt erőssége
            $jelszo = password_hash($_POST['jelszo'], PASSWORD_BCRYPT, $cost_of_hash);

            $insert = mysqli_query($connection,"INSERT INTO felhasznalok (
                felhasznalonev,
                nem,
                szuletesi_datum,
                email,
                jelszo,
                hasznalati_pont,
                Jogkorok_id,
                hozzaferes)
            VALUES ('{$_POST["felhasznalonev"]}',
                '{$_POST["nem"]}', 
                '{$_POST["szdatum"]}', 
                '{$_POST["email"]}', 
                '{$jelszo}', 
                 3, 
                 3, 
                'engedélyezett')"); //itt majd javítsd

            if ($insert) {
                        
                $valid = true;
                $message = "A regisztráció sikeresen megtőrtént!";
            }

        }
        foreach ($record as $row) {
            if ($row['felhasznalonev'] == $_POST['felhasznalonev']) { //azáltal hogy itt a $_POST tartalmával vetem össze így nem kell mégegyszer lekérdezni
                $valid = false;
                $message2 = "Az általad megadott felhasználónév már használatban van!";
            }
            if ($row['email'] == $_POST['email']) {
                $valid = false;
                $message3 = "Az általad megadott email már használatban van!";
            }
        }

        return
        json_encode(
            //és itt volt a probléma
            array(
                'valid' => $valid, 
                'message' => $message, 
                'message2' => $message2, 
                'message3' => $message3
            )
        );
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }
}
function menupontok_feltoltese($connection)
{
    //a query-t lehet hogy inkább inner joinnal kéne csinálni
    $query = "SELECT m.nev mnev, m.route mroute  FROM elerheto_menupontok em,menupontok m WHERE em.Jogkorok_id = ? AND em.Menupontok_id = m.id ";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "i", $_SESSION['jogkor']); //bind-hozzákötés"s"-string
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $record;
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }
}

function select_elemek_lekerdezese($connection, $query) {
    
    if ($result = mysqli_query($connection, $query)) {

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
         
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

function file_feltoltes($connection,$message, $valid)
{
    $oldalsz = (empty($_POST["oldalszam"]) ? "NULL" : $_POST['oldalszam']);
    $dokumentum_eve = (empty($_POST["dokumentum_eve"]) ? "NULL" : $_POST['dokumentum_eve']);
    $filenev = $_FILES['file']['name'];
    $dokumentum = $_FILES['file']['tmp_name'];
    $file_meret = formatSizeUnits(filesize($dokumentum)); //file_size visszaadja a fájl méretét byte-ban
    $file_hash = md5_file($dokumentum);
    $datum = date("Y M");
    $gyoker_mappa = "dokumentumok/";
    $mappa = $gyoker_mappa . $datum;
    $helymegh = $mappa."/".$filenev;
    $FileTipus = pathinfo($helymegh,PATHINFO_EXTENSION); //file nevéről a  kiterjesztés kigyüjtése változóba
    $ervenyes_kiterjesztes = "pdf"; //álltalunk elfogadott file- kiterjesztés

    if (strtolower($FileTipus) != $ervenyes_kiterjesztes){ //a file kiterjesztése az elfogadott listájában megtalálható
        
        $valid = false;
        $message = "A kiválasztott dokumentum nem .pdf kiterjesztésü!";

        return
        json_encode(
            //és itt volt a probléma
            array(
                'valid' => $valid, 
                'message' => $message
            )
        );
    }

    $query = "SELECT file_hash FROM dokumentumok WHERE file_hash = ? ";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "s",$file_hash); //bind-hozzákötés"s"-string
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_assoc($result);

        if ($record == null) {

            
            if (!file_exists($mappa)) {
                mkdir($mappa, 0777, true);
            }

            if (!file_exists($helymegh)) {
            
                    
                    $insert = mysqli_query($connection,"INSERT INTO dokumentumok (
                        Felhasznalok_id,
                        Kategoriak_id,
                        Targyak_id,
                        dokumentum_cime,
                        oldalszam,
                        dokumentum_eve,
                        forras,
                        dokumentum,
                        Statuszok_id,
                        file_hash,
                        file_meret)
                    VALUES (
                        '{$_SESSION["id"]}',
                        '{$_POST["kategoria"]}', 
                        '{$_POST["targy"]}', 
                        '{$_POST["dcim"]}',".
                         $oldalsz.",".      //itt hozzáfűztem , mert ha csak simán paraméterként adom át és üres a változó akkor nem lessz sikeres a beillesztés
                         $dokumentum_eve . ",
                        '{$_POST["forras"]}',
                        '{$helymegh}',
                            1,
                        '{$file_hash}',
                        '{$file_meret}')");

                    if ($insert) {

                        move_uploaded_file($_FILES['file']['tmp_name'],$helymegh); // a paraméterben tárolt file-t a $location-ben található
                        $valid = true;
                        $message = "A feltöltés sikeresen megtörtént. A feltöltés hamarosan ellenőrzés alá kerül.";

                        //itt ellenörzöm hogy kérés teljesítés történik-e
                        //ha igen akkor megkapjuk a kérés id-jét, ha nem akkor pedig egy karaktersorozatot pl. ebben az esetben: "undefined" 
                        // $_POST stringet ad vissza még akor is ha számot adunk át az űrlappal
                        //tehát az intval() átalakítja ebben az esetben a $_POST['keres'] változót egész számmá
                        //ha sikeres vissza adja a postban található számot ha nem akkor pedig 0 át ad vissza
                        if (intval($_POST['keres'])) { 
                            
                            $update_keres_statusz = mysqli_query($connection, "UPDATE keresek SET Statuszok_id= 1 WHERE id='{$_POST["keres"]}'");
                            
                            $utoljara_felt_dok = mysqli_fetch_assoc(mysqli_query($connection, "SELECT MAX(id) AS id FROM dokumentumok WHERE Felhasznalok_id='{$_SESSION["id"]}'"));
                            $insert_telj_keresek = mysqli_query($connection, "INSERT INTO teljesitett_keresek (Keresek_id, Dokumentumok_id)
                                                                              VALUE ('" .$_POST['keres']."', '".$utoljara_felt_dok['id']."')");
                            
                            $message.= " A kérést sikeresen teljesítetted.";

                        }
                        
                    }

            
            }
            else {
                $valid = false;
                $message = "Nevezd át a fetöltendő dokumentumot és utána probáld meg újra!";
            }
        }
        else {
            $valid = false;
            $message = "A feltöltendő dokumentumot már létezik!";
        }


        return
        json_encode(
            array(
                'valid' => $valid, 
                'message' => $message
            )
        );
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }
}


/**
 * getTotal() a képek számának meghatározása
 *
 * @param [type] $connection MySQL kapcsolat
 * @return [int] A képek száma
 */
function getTotal($connection, $query) {

    if ($result = mysqli_query($connection, $query)) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

/**
 * Egy oldalnyi képet ad vissza az adatbázisból, a lapméret és az eltolás alapján.
 *
 * @param [type] $connection MySQL kapcsolat
 * @param [int] $size Lapméret
 * @param [int] $offset Eltolás
 * @return void MYSQLI_ASSOC
 */
function getContentPaginated($connection, $size, $offset, $query) {
    
    if ($statment = mysqli_prepare($connection, $query)) { //előkészítés
        mysqli_stmt_bind_param($statment, "ii", $offset, $size); // itt kerül behelyettesítésre a kérdőjelek helyére a változó ii- integer and integer
        mysqli_stmt_execute($statment); //végrehajtás
        $result = mysqli_stmt_get_result($statment); //eredménymegszerzés
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}




function lepteto($listazando, $oldal, $query_total, $query_content) {

        /**
     * Query string változók: $_GET[]
     * PHP 7 új operátora: null coalescing operator
     * A ternary operátor (felt ? true : false) és az isset() fv. együttes használatát helyettesíti.
     * A null coalescing operator az első (bal oldali) operandusát adja vissza, ha az létezik és nem null, különben a másodikat (jobb oldalit)
     * Az isset() fv. igazat ad vissza, ha a paraméterül adott változó létezik és nem null (gyakran használatos a $_GET-ben levő változók ellenőrzésére).
     * Tehát az
     *   isset($_GET["size"]) ? $pageSize = $_GET["size"] : $pageSize = 10;
     * helyettesíthető ezzel:
     *   $pageSize = $_GET["size"] ??  10;
     * ami lényegesen tömörebb...
     */
    $size = 6;    // $size: lapozási oldalméret
    $page = !empty($oldal)? (int)$oldal : 1;     // $page: oldalszám
    $listazando = $listazando;
    $query_total = $query_total;
    $query_content = $query_content;


 
    $connection = getConnection();
    // $total: a képek számának meghatározása
    $total = getTotal($connection, $query_total);
 
    // $offset: eltolás kiszámítása
    $offset = ($page - 1) * $size;
 
    // $content: egy oldalnyi kép
    $content = getContentPaginated($connection, $size, $offset, $query_content);

    $lastPage = $total % $size == 0 ? intdiv($total, $size) : intdiv($total, $size) + 1;

    return array(
            'content' => $content,
            'total' => $total,
            'size' => $size,
            'page' => $page,
            'lastPage' => $lastPage,
            'listazando' => $listazando
    );
    
}

function uj_keres($connection, $message, $valid)
{
    $ev = (empty($_POST["dokumentum_eve"]) ? "NULL" : $_POST['dokumentum_eve']);
    $query = "SELECT hasznalati_pont FROM felhasznalok WHERE id = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "i", $_SESSION['id']); //bind-hozzákötés"s"-string
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_assoc($result);
        if ($record != null && $record['hasznalati_pont'] >= 1) {

            $insert = mysqli_query($connection,"INSERT INTO keresek (
                Felhasznalok_id,
                keres_megnevezese,
                ev,
                Kategoriak_id,
                Targyak_id,
                Statuszok_id)
            VALUES (
                '{$_SESSION["id"]}',
                '{$_POST["szovegdoboz"]}',".
                 $ev .", 
                '{$_POST["kategoria"]}', 
                '{$_POST["targy"]}', 
                   2 )");  // 2-teljesítésre várakozó

            if ($insert) {

                $uj_egyenleg = $record['hasznalati_pont']-1; //fent már kialakítottam hogy ne legyen nullánál kissebb a szám(egyenleg)
                $update = mysqli_query($connection, "UPDATE felhasznalok SET hasznalati_pont='{$uj_egyenleg}' WHERE id='{$_SESSION["id"]}'");

                $valid = true;
                $message = "A dokumentum kérés sikeresen megörtént.";
            
            }


        }
        else {
           $valid = false;
           $message = 'Nincs elég pontod hogy új kérést kezdeményezz. A pontszerzéshez tölts fel dokumentumot ,vagy teljesíts kérést!';
           
        }

        return
        json_encode(
            //és itt volt a probléma
            array(
                'valid' => $valid, 
                'message' => $message
            )
        );
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }
}
function ujra_felhasznalhato_lekerdezes($connection, $query) {

    if ($result = mysqli_query($connection, $query)) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }  
}

function formatSizeUnits($bytes) 
{
    if ($bytes >= 1073741824)
    {
        //number_format(1,2): 1-formázandó szám 2. tizedes számjegy
        //elosztom az átadott file méretet és elosztom méretnek megfelelően.
        //A 2. paraméterrel pedig a , után megjelenítendő karakterek számát határozzuk meg
        $bytes = number_format($bytes / 1073741824, 1) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 1) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 1) . ' KB';
    }
    elseif ($bytes >= 130)  //elvileg pdf esetében a legkissebb fájlméret 130 b
    {
        $bytes = $bytes . ' bytes';
    }
    /*
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }*/

    return $bytes;
}

function tobb_sort_visszaado_lekerdezes($connection, $query) {

    if ($result = mysqli_query($connection, $query)) {
        $record =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $record;
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }   
}

function dokumentum_ertekeles($connection, $message, $valid)
{
    
    $query = "SELECT id FROM dokumentumok  WHERE id = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "i", $_POST['dokumentum_id']); //bind-hozzákötés"s"-string
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_assoc($result);
        if ($record != null) {

            $insert = mysqli_query($connection,"INSERT INTO ertekelesek (
                Dokumentumok_id,
                Felhasznalok_id,
                pont,
                megjegyzes)
            VALUES (
                '{$_POST['dokumentum_id']}',
                '{$_SESSION["id"]}',
                '{$_POST['pont']}', 
                '{$_POST["szoveg"]}')"); 

            if ($insert) {

                $valid = true;
                $message = "A dokumentum értékelése sikeresen megörtént.";
            
            }


        }
        else {
           $valid = false;
           $message = 'A dokumentum értékelése sikertelen, mert a dokumentum, törlésre került.';
           
        }

        return
        json_encode(
            //és itt volt a probléma
            array(
                'valid' => $valid, 
                'message' => $message
            )
        );
    } else {
        logMessage("ERROR", 'Query error: ' . mysqli_error($connection));
        errorPage();
    }
}




