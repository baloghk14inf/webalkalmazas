<?php

    //Fuggvények


function getConnection()
{
/**
 * Adatbázis kapcsolat
 */
global $config;
$connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']); //el kell tárolni egy változóban
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
    $query = "SELECT id,jelszo,Jogkorok_id FROM felhasznalok WHERE felhasznalonev = ?";
    if ($statment = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($statment, "s", $_POST['felhasznalonev']); //bind-hozzákötés"s"-string
        mysqli_stmt_execute($statment);
        $result = mysqli_stmt_get_result($statment);
        $record = mysqli_fetch_assoc($result);
        if ($record != null && password_verify($_POST['jelszo'], $record['jelszo'])) {
            //return $record;
            $_SESSION['id'] = $record['id'];
            $_SESSION['jogkor'] = $record['Jogkorok_id'];
            $message = 'A bejelentkezés sikeres';
            $valid = true;
            return 
             json_encode(
                array(
                    'valid' => $valid,
                    'message' => $message,
                    'felhasznalo_id' => $_SESSION['id'],
                    'jogkor' =>  $_SESSION['jogkor']
                )
                );

        }
        else {
           // return null;
           $message = 'A bejelentkezés sikertelen rossz felhasználónév-jelszó páros';
           $valid = false;
           $_SESSION['id'] = '';
           $_SESSION['jogkor'] = '';
                return
                 json_encode(
                    array(
                        'valid' => $valid,
                        'message' => $message,
                        'felhasznalo_id' => $_SESSION['id'],
                        'jogkor' =>  $_SESSION['jogkor']
                    )
                    );
        }
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
    $filenev = $_FILES['file']['name'];
    $file_hash = md5_file($_FILES['file']['tmp_name']);
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
            
                
                if (move_uploaded_file($_FILES['file']['tmp_name'],$helymegh)) { // a paraméterben tárolt file-t a $location-ben található

                    
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
                        file_hash)
                    VALUES (
                        '{$_SESSION["id"]}',
                        '{$_POST["kategoria"]}', 
                        '{$_POST["targy"]}', 
                        '{$_POST["dcim"]}',
                        '{$_POST["oldalszam"]}',
                        '{$_POST["dokumentum_eve"]}',
                        '{$_POST["forras"]}',
                        '{$helymegh}',
                            1,
                        '{$file_hash}')");

                    if ($insert) {
                        $valid = true;
                        $message = "A feltöltés sikeresen megtörtént";
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






