<?php
 
// az utvonal lekérdezése
switch ($_SESSION['jogkor']) {
  case '':
    $uri = $_SERVER["REQUEST_URI"] ?? '/login'; //ha üres a session változó akkor a login.php töltse majd be
    break;
  case 3:
    $uri = $_SERVER["REQUEST_URI"] ?? '/'; //lehet hogy egy nem létező változóra hivatkozunk// '/'- a home-ot jelenti
    break;
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


