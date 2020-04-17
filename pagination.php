<?php
session_start();
require_once "core/functions.php";
require_once "core/controllers.php";
require_once "core/config.php"; //alap beállítások helye
date_default_timezone_set('Europe/Budapest'); //beállítjuk az időzónát

$query_total = "";
$query_content = "";
$message="alma";
$valid = false;
$listazando ="";
$id = $_SESSION['id'];
$tomb = array();
$dokumentum_kereses = explode('=',urldecode($_POST['listazando']));


if (isset($_POST['listazando'],$_POST['oldal'])) {
    $listazando = $_POST['listazando'];
}
//var_dump($_POST);


if ($listazando == "feltoltott" || $listazando == "ellenorzendo") {

    $statusz_id = $listazando == "feltoltott" ? 5 : 1; //ha nem feltöltött akkor csak ellenörzött lehet 5: ellenörzött , 1:ellenörzésre várakozó

    $query_total = "SELECT count(*) AS count FROM dokumentumok d WHERE d.Felhasznalok_id = '{$id}' AND d.Statuszok_id = '{$statusz_id}'"  ; //FONTOS it csak az ellenorzot dokumentumok legyenek
    $query_content = "SELECT d.id id, t.nev targy, k.nev kategoria, d.dokumentum_cime, d.feltoltes_datuma FROM dokumentumok d 
                     INNER JOIN targyak t ON d.Targyak_id = t.id INNER JOIN kategoriak k ON d.Kategoriak_id = k.id
                     WHERE d.Felhasznalok_id = '{$id}' AND d.Statuszok_id = '{$statusz_id}' ORDER BY d.feltoltes_datuma DESC
                     LIMIT ?, ?"; // itt is az ellenorzott
}

if ($listazando == "teljesitett" || $listazando == "eldontendo") {

    $statusz_id = $listazando == "teljesitett" ? 3 : 6; //ha nem teljesített akkor csak eldontendo lehet 3:teljesített , 6:eldöntendő

    $query_total = "SELECT count(*) AS count FROM keresek k WHERE k.Felhasznalok_id = '{$id}' AND k.Statuszok_id = '{$statusz_id}'" ; //FONTOS itt pedig csak az ellenörzetlen
    $query_content = "SELECT t.nev targy, kat.nev kategoria, k.keres_megnevezese k_megnev,k.ev dokumentum_eve, k.keres_datuma keres_d, 
                        k.teljesites_datuma teljesites_d, tk.Dokumentumok_id dok_id, f.felhasznalonev teljesito_neve
                        FROM keresek k 
                        INNER JOIN targyak t ON k.Targyak_id = t.id INNER JOIN kategoriak kat ON k.Kategoriak_id = kat.id 
                        INNER JOIN teljesitett_keresek tk ON k.id = tk.Keresek_id INNER JOIN dokumentumok d ON d.id = tk.Dokumentumok_id
                        INNER JOIN felhasznalok f ON f.id = d.Felhasznalok_id
                        WHERE k.Felhasznalok_id = '{$id}' AND k.Statuszok_id = '{$statusz_id}' ORDER BY k.keres_datuma DESC
                        LIMIT ?, ?"; // itt is az ellenorzott
}

if ($listazando == "ellenorzesre_varakozo" || $listazando == "teljesitesre_varakozo" || $listazando == "aktiv-keresek") {

    $statusz_id = $listazando == "ellenorzesre_varakozo" ? 1 : 2; //ha nem  ellenorzesre_varakozo akkor teljesítéser_várakozó vagy aktiv-keres lehet
                                                                  // 1:ellenörzésre várakozó , 2: teljesítésre várakozó
                                                                  // a teljesítésre_v valamint az aktiv_k státusza 2 tehát ezért nem vizsgálom külön kölön őket

    //FONTOS  itt tarottam a wher hozzáfűzést még meg kell oldani  $_POST ok vizsgálatával
    $tantargy = !empty($_POST['targy']) ? 'AND k.Targyak_id = ' .'"' . $_POST['targy']. '"'  : '';
    $kategoria = !empty($_POST['kategoria']) ? ' AND k.Kategoriak_id =' .'"' . $_POST['kategoria']. '"' : '';
    $id_operator = $listazando == "aktiv-keresek" ? ' != ' :  ' = ';
    $keres_id = $listazando == "aktiv-keresek" ? ' k.id keres_id, ' :  '';

   

    $query_total = "SELECT count(*) AS count FROM keresek k WHERE k.Felhasznalok_id ".$id_operator." '{$id}' AND k.Statuszok_id = '{$statusz_id}'" . $tantargy . $kategoria ; //FONTOS itt pedig csak az ellenörzetlen
    $query_content = "SELECT". $keres_id ." t.nev targy, kat.nev kategoria, k.keres_megnevezese k_megnev,k.ev dokumentum_eve, k.keres_datuma keres_d
                        FROM keresek k 
                        INNER JOIN targyak t ON k.Targyak_id = t.id INNER JOIN kategoriak kat ON k.Kategoriak_id = kat.id 
                        WHERE k.Felhasznalok_id".$id_operator."'{$id}' AND k.Statuszok_id = '{$statusz_id}' " .$tantargy . $kategoria . " ORDER BY k.keres_datuma DESC
                        LIMIT ?, ?"; // itt is az ellenorzott
}



if (intval($listazando)) { //ha a listázandó szám (dokumentum esetében)


    $query_total = "SELECT count(*) AS count FROM ertekelesek e WHERE e.Dokumentumok_id = '{$listazando}'"  ; //FONTOS it csak az ellenorzot dokumentumok legyenek
    $query_content = "SELECT e.id hozzasz_id, f.felhasznalonev ertekelo, e.pont pont, e.megjegyzes megjegyzes, e.ertekeles_datuma ertekeles_datuma 
                      FROM ertekelesek e INNER JOIN felhasznalok f ON f.id = e.Felhasznalok_id 
                      WHERE e.Dokumentumok_id = '{$listazando}' ORDER BY e.ertekeles_datuma DESC
                      LIMIT ?, ?"; // itt is az ellenorzott
}

if ($dokumentum_kereses[0] == "?keresendo") { //ha a listázandó szám (dokumentum esetében)

    $keresendo = !empty($dokumentum_kereses[1]) ? "AND binary d.dokumentum_cime  LIKE " ."'%" . $dokumentum_kereses[1]. "%'"  : '';
    $tantargy = !empty($_POST['targy']) ? 'AND d.Targyak_id = ' .'"' . $_POST['targy']. '"'  : '';
    $kategoria = !empty($_POST['kategoria']) ? ' AND d.Kategoriak_id = ' .'"' . $_POST['kategoria']. '"' : '';
    $ev = !empty($_POST['ev']) ? ' AND d.dokumentum_eve = ' .'"' . $_POST['ev']. '"' : '';

    $query_total = "SELECT count(*) AS count FROM dokumentumok d WHERE d.Statuszok_id = 5 ".$keresendo. $tantargy. $kategoria. $ev ; //FONTOS it csak az ellenorzot dokumentumok legyenek
    $query_content = "SELECT d.id id, t.nev targy, k.nev kategoria, d.dokumentum_cime dokumentum_cime, d.feltoltes_datuma feltoltes_datuma  FROM dokumentumok d 
                     INNER JOIN targyak t ON d.Targyak_id = t.id INNER JOIN kategoriak k ON d.Kategoriak_id = k.id
                     WHERE d.Statuszok_id = 5 ".$keresendo. $tantargy. $kategoria. $ev." ORDER BY d.feltoltes_datuma DESC
                     LIMIT ?, ?"; // itt is az ellenorzott
}

if ($listazando == "feltoltesek") {

    $query_total = "SELECT count(*) AS count FROM dokumentumok d WHERE d.Felhasznalok_id != '{$id}' AND d.Statuszok_id = 1 "  ; //FONTOS it csak az ellenorzot dokumentumok legyenek
    $query_content = "SELECT d.id id, t.nev targy, k.nev kategoria, d.dokumentum_cime dokumentum_cime, d.feltoltes_datuma feltoltes_datuma FROM dokumentumok d 
                     INNER JOIN targyak t ON d.Targyak_id = t.id INNER JOIN kategoriak k ON d.Kategoriak_id = k.id
                     WHERE d.Felhasznalok_id != '{$id}' AND d.Statuszok_id = 1 ORDER BY d.feltoltes_datuma DESC
                     LIMIT ?, ?"; // itt is az ellenorzott
}

//echo $query_content . "<br>";
//echo $query_total . "<br>";

//var_dump($_POST);



$result = lepteto($listazando, $_POST['oldal'], $query_total, $query_content);
//var_dump($result['content']);




echo json_encode(

array(
            'content' => $result['content'],
            'total' =>$result['total'],
            'size' => $result['size'],
            'page' => $result['page'],
            'lastPage' => $result['lastPage'],
            'listazando' => $result['listazando']
    )

);