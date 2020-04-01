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


if (isset($_POST['listazando'],$_POST['oldal'])) {
    $listazando = $_POST['listazando'];
}
//var_dump($_POST);


if ($listazando == "feltoltott") {
    $query_total = "SELECT count(*) AS count FROM dokumentumok d WHERE d.Felhasznalok_id = '{$id}' AND d.Statuszok_id = 5" ; //FONTOS it csak az ellenorzot dokumentumok legyenek
    $query_content = "SELECT d.id id, t.nev targy, k.nev kategoria, d.dokumentum_cime, d.feltoltes_datuma FROM dokumentumok d 
                     INNER JOIN targyak t ON d.Targyak_id = t.id INNER JOIN kategoriak k ON d.Kategoriak_id = k.id
                     WHERE d.Felhasznalok_id = '{$id}' AND d.Statuszok_id = 5 ORDER BY d.feltoltes_datuma DESC
                     LIMIT ?, ?"; // itt is az ellenorzott
}
if ($listazando == "ellenorzendo") {
    $query_total = "SELECT count(*) AS count FROM dokumentumok d WHERE d.Felhasznalok_id = '{$id}' AND d.Statuszok_id = 1" ; //FONTOS itt pedig csak az ellenörzetlen
    $query_content = "SELECT d.id id, t.nev targy, k.nev kategoria, d.dokumentum_cime, d.feltoltes_datuma FROM dokumentumok d 
                        INNER JOIN targyak t ON d.Targyak_id = t.id INNER JOIN kategoriak k ON d.Kategoriak_id = k.id
                        WHERE d.Felhasznalok_id = '{$id}' AND d.Statuszok_id = 1 ORDER BY d.feltoltes_datuma DESC
                        LIMIT ?, ?"; // itt is az ellenorzott
}

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