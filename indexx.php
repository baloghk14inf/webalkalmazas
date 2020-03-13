<?php
    session_start();

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