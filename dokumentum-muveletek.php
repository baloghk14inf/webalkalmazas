<?php
session_start();
require_once "core/functions.php";
require_once "core/controllers.php";
require_once "core/config.php"; //alap beállítások helye

 date_default_timezone_set('Europe/Budapest'); //beállítjuk az időzónát

 $message="";
 $valid = false;
 $connection = getConnection();
// var_dump($_POST);

if ($_POST['gomb_nev'] == "Elfogadom") {
  mysqli_query($connection,"UPDATE keresek SET Statuszok_id = 3 WHERE id='{$_POST["keres_id"]}'");
  mysqli_query($connection,"UPDATE felhasznalok f  SET hasznalati_pont = hasznalati_pont + 1  WHERE felhasznalonev='{$_POST["feltolto"]}'");

  $message="Elfogadtad a számodra feltöltött dokumentumot, ezáltal sikeresen teljesült a kérésed.";
}
elseif ($_POST['gomb_nev'] == "Nem ezt kértem") {
    mysqli_query($connection,"DELETE FROM teljesitett_keresek WHERE Keresek_id='{$_POST["keres_id"]}'");
    mysqli_query($connection,"UPDATE keresek SET Statuszok_id = 2 WHERE id='{$_POST["keres_id"]}'");

    $message= "Nem felelt meg a számodra feltöltött dokumentum, ezért kérésed ismételten teljesíthetővé vált.";
}
elseif ($_POST['gomb_nev'] == "Elfogad") {

    mysqli_query($connection,"UPDATE dokumentumok SET Statuszok_id = 5 WHERE id='{$_POST["dokumentum_id"]}'");
    
    mysqli_query($connection,"UPDATE felhasznalok f  SET hasznalati_pont = hasznalati_pont + 1  WHERE felhasznalonev='{$_POST["feltolto"]}'");

    if (!empty($_POST["keres_id"])) {
        mysqli_query($connection,"UPDATE keresek SET Statuszok_id = 6 WHERE id='{$_POST["keres_id"]}'");
    }

    $message = "A feltöltött dokumentum elfogadásra került, mostantól elérhetővé vált a felhasználók számára.";
}
elseif ($_POST['gomb_nev'] == "Törlés") {
    if (!empty($_POST["keres_id"])) {
        mysqli_query($connection,"DELETE FROM teljesitett_keresek WHERE Keresek_id='{$_POST["keres_id"]}'");
        mysqli_query($connection,"UPDATE keresek SET Statuszok_id = 2 WHERE id='{$_POST["keres_id"]}'");
    }

    $eleresi_ut_lekerdezese = mysqli_query($connection,"SELECT dokumentum FROM dokumentumok  WHERE id='{$_POST["dokumentum_id"]}'");
    $eleresi_ut = mysqli_fetch_assoc($eleresi_ut_lekerdezese);
    
    unlink($eleresi_ut['dokumentum']); //az unlinke törlöm az adot dokumentumot

    mysqli_query($connection,"DELETE FROM dokumentumok WHERE id='{$_POST["dokumentum_id"]}'");

    $message="A feltöltött dokumentum nem felelt meg az elvártaknak, ezáltal törlésre került.";

}


echo json_encode(
    //és itt volt a probléma
    array(
        'message' => $message 
    )
);


 //echo dokumentum_ertekeles($connection, $message,$valid);