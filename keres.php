<?php
session_start();
require_once "core/functions.php";
require_once "core/controllers.php";
require_once "core/config.php"; //alap beállítások helye

 date_default_timezone_set('Europe/Budapest'); //beállítjuk az időzónát

 $message="";
 $valid = false;
 $connection = getConnection();

 echo uj_keres($connection, $message,$valid);
