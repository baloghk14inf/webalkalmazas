<?php

require_once 'core/config.php';

date_default_timezone_set('Europe/Budapest'); //beálítom az időzónát
header('Content-Type: text/html; charset=utf-8');

/* //FONTOS ezt itt azért kommenteltem ki mert mindig false értéket adott vissza (ha true lett akkor viszont azt maga elé rakta)
$valid = false;
$message ="";
*/
$message ="";

if (isset($_POST['email']) && !empty($_POST['email'])) { //ha az e-mail létezik akkor minden létezik 

	
	//$connection = mysqli_connect($konfig['db_host'], $konfig['db_user'], $konfig['db_pass'], $konfig['db_name']); 
	$felhasznalonev = $_POST['felhasznalonev'];
	$email = $_POST['email'];


	//ITT LEHET A PROBLÉMA //a megadott paraméterekben volt a hiba
   // $query = "SELECT * FROM felhasznalok  WHERE email ='$email'  || felhasznalonev ='$felhasznalonev' ";
   $query1 = "SELECT * FROM felhasznalok  WHERE email ='$email'";
   $query2 = "SELECT * FROM felhasznalok  WHERE felhasznalonev ='$felhasznalonev'";
	
	$result1 = mysqli_query($connection, $query1);
	$result2 = mysqli_query($connection, $query2);

	//var_dump($result);
	
	
	
    
	if (mysqli_num_rows($result1) == 0 && mysqli_num_rows($result2) == 0) {  // ha nincs még ilyen email, akkor OK!
		$cost_of_hash = array('cost' => 11);  // BCrypt erőssége

		
			$nem = $_POST['nem'];
			$szdatum = $_POST['szdatum'];
			$hasznalatipont = 3;
			$jogkor = 3;
			$hozzaf = "engedélyezett";
			$jelszo = password_hash($_POST['jelszo'], PASSWORD_BCRYPT, $cost_of_hash);


		$insert = mysqli_query($connection,"INSERT INTO felhasznalok (felhasznalonev, nem, szuletesi_datum, email, jelszo,hasznalati_pont,Jogkorok_id,hozzaferes,regisztracio_datuma)
		VALUES ('{$felhasznalonev}', '{$nem}', '{$szdatum}', '{$email}','{$jelszo}','{$hasznalatipont}','{$jogkor}','{$hozzaf}', NOW())");
		
		
		if ($insert) {
			
			 $valid = true;
			 $message = "Regisztráció sikeres!";
		}
		/*
		else {
			$valid = false;
			$message = "Probléma adódott a beszúrásnál!";
		} */


		
	}
	if (mysqli_num_rows($result1) > 0) {
		$valid = false;
		$message2 = "Az általad megadott email már használatban van!"; //itt át kell majd alakitani néhány dolgot
	}
	if (mysqli_num_rows($result2) > 0) {
		$valid = false;
		$message3 = "Az általad megadott felhasználónév már használatban van!";
	}
}


// Finally, return a JSON
echo json_encode(
	//és itt volt a probléma
	array(
		'valid' => $valid, 'message' => $message, 'message2' => $message2, 'message3' => $message3
	)
);
