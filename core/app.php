<?php

 $uri= null;

// az utvonal lekérdezése
if ($_SESSION['jogkor'] == "") {
  
  $uri = '/login'; //ha üres a session változó akkor a login.php töltse majd be
}
else {
  
  $uri = $_SERVER["REQUEST_URI"] ?? '/'; //lehet hogy egy nem létező változóra hivatkozunk// '/'- a home-ot jelenti
  var_dump($uri);
}
if ($_SERVER["REQUEST_URI"]== '/registration') {
  
  $uri = '/registration'; //ha üres a session változó akkor a login.php töltse majd be
}


$cleaned = explode("?", $uri) [0];



//dispatch() fv.meghívása, ami kiválasztja az adott utvonalhoz tartozó controllert

list($view, $data) = dispatch($cleaned, 'notFoundController');

if (preg_match("%^redirect\:(?<route>.*)$%", $view, $matches)) {//i tt történik meg majd az átirányítás
  $redirectTarget = $matches['route'];
  header('Location:' . $redirectTarget);
  die();
}


extract($data); //visszaadja a $data tartalmát



