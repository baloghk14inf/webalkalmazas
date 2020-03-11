<?php

    /**
     * Adatbázis kapcsolodáshoz szükséges adat
     */
    $konfig['db_host'] = 'localhost';
    //$konfig['db_user'] = 'phpalapok';
    //$konfig['db_pass'] = 'phpalapok';
    $konfig['db_user'] = 'root';
    $konfig['db_pass'] = '';
    $konfig['db_name'] = 'timmt';


    $connection = mysqli_connect($konfig['db_host'], $konfig['db_user'], $konfig['db_pass'], $konfig['db_name']); 


    $connection -> set_charset("utf8");
    $connection ->query("SET COLLATION_CONNECTION='utf8_hungarian_ci'");
    $connection ->query("SET NAMES 'UTF8'");
    $connection->query("SET CHARACTER SET UTF8");

 