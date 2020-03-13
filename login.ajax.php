<?php
require_once 'core/config.php';
session_start();

 date_default_timezone_set('Europe/Budapest'); //beállítjuk az időzónát

    
$valid = false;

$email = "";
$password = "";
$row= "";
$message = "";
	
if (isset($_POST['felhasznalonev'])) {
    
	$felhasznalonev = $_POST['felhasznalonev'];
	$password = $_POST['jelszo'];		
}
/*else 
if (isset($argv)) {
	$email = $argv[1];
	$password = $argv[2];	
}*/

//$atiranyitas = "";

 if ($felhasznalonev != "" ) {

   // echo "belépett";
    $query = "SELECT * FROM felhasznalok  WHERE felhasznalonev ='$felhasznalonev' ";
     
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result)  != 0) {

        
        $row = mysqli_fetch_row($result);
       $_SESSION['jogkor'] = $row[11]; //ez miatt mutatja hogy jogkor = 2 mert az elöbb sikeresen beléptem az adminnal
       $_SESSION['id'] = $row[0];
        
        
        $valid = password_verify($password, $row[7]); //összehasonlítja a két paramétert
        
        if ($valid) {
            $message = 'A bejelentkezés sikeres';
			//$atiranyitas = $result[0]->atiranyitas; //FONTOS erre majd szükség lessz !!!!! //index.php 
        }

    }
    else {
        $message = 'A bejelentkezés sikertelen rossz felhasználónév jelszó páros';
    }
}

echo json_encode(
    array(
        'valid' => $valid, 'message' => $message, 'felhasznalo_id' => $_SESSION['id'], 'jogkor' =>  $_SESSION['jogkor']
    )
);