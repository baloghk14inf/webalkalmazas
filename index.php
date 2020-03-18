<?php
    session_start();

    date_default_timezone_set('Europe/Budapest');

    if ($_SESSION == null) {        //ez a feltétel a kezdeti bejelentkezéshez valamint a kijelentkezés után szükséges
        $_SESSION['jogkor'] = "";   //ha nem lenne akkor a session változók folyton üresek lennének az az nem tudnánk neki átani semmit
        $_SESSION['id'] = "";
    }
    //session_destroy();            //ezt pedig teszteléshez használtam hogy a $_SESSION szuperg tömböt teljesen kiürítsem

    $message="";
    $message2="";
    $message3="";
    $valid = false;

    /*error_reporting(E_ALL); //hibakezelés
    ini_set("display_errosrs", "On");
    ini_set("log_errors", "On");
    ini_set("error_log", "php_error.log"); */

    require_once "core/functions.php";
    require_once "core/controllers.php";
    require_once "core/config.php"; //alap beállítások helye
    require_once "core/app.php"; //algoritmusok helye
    require_once "templates/layout.php";
    

?>