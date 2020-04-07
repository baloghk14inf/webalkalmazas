<?php


    $siteName = "TIMMT";

    /**
     * Adatbázis kapcsolodáshoz szükséges adat
     */
    
    $config['db_host'] = 'localhost';
    $config['db_user'] = 'root';
    $config['db_pass'] = '';
    $config['db_name'] = 'timmt';


    /*$connection = mysqli_connect($konfig['db_host'], $konfig['db_user'], $konfig['db_pass'], $konfig['db_name']); 


    $connection -> set_charset("utf8");
    $connection ->query("SET COLLATION_CONNECTION='utf8_hungarian_ci'");
    $connection ->query("SET NAMES 'UTF8'");
    $connection->query("SET CHARACTER SET UTF8"); */  //FONTOS ezt lehet hogy image-galery-with-esen fogom megcsinálni


    $routes = [];
    // utvonalak felvétele a $routes tömbbe
    //$routes['GET']['/'] = 'homeController'; //ez miatt hibába ötközött a betöltés mert alapértelmezetten a localhost = /
    $routes['GET']['/login'] = 'loginFormController'; //Itt ez még csak az hogy irányítson a login formra
    $routes['GET']['/registration'] = 'registrationFormController'; //Ez a login formra fog irányítani
    $routes['GET']['/feltolteseim'] = 'feltolteseimController'; //Ez a login formra fog irányítani
    $routes['GET']['/kereseim'] = 'kereseimController'; //Ez a login formra fog irányítani
    $routes['GET']['/aktiv-keresek'] = 'aktiv_keresekController'; //Ez a login formra fog irányítani

    //$routes['GET']['/feltolteseim/(?<id>[\d]+)'] = 'registrationController';

    $routes['GET']['/logout'] = 'logoutController';
    
    //$routes['GET']['/login'] = 'loginFormController'; //ez pedig az hogy ha csak simán a bejelentkezés formra szeretnénk menni
    //$routes['POST']['/login'] = 'loginSubmitController'; // ez figja majd meghatározni a miros figyelmeztetést (tehát ha hiba lenne)
    //$routes['GET']['/logout'] = 'logoutSubmitController'; //about route létrehozása , már előre létre volt hozva
    
        /**
     * Adatbázis kapcsolodáshoz szükséges adat
     */
   /* $konfig['db_host'] = 'localhost';
    //$konfig['db_user'] = 'phpalapok';
    //$konfig['db_pass'] = 'phpalapok';
    $konfig['db_user'] = 'root';
    $konfig['db_pass'] = '';
    $konfig['db_name'] = 'timmt';*/


    $connection = getConnection();


    $connection -> set_charset("utf8");
    $connection ->query("SET COLLATION_CONNECTION='utf8_hungarian_ci'");
    $connection ->query("SET NAMES 'UTF8'");
    $connection->query("SET CHARACTER SET UTF8"); 


 