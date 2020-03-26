<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$siteName?> | <?=$title?></title>
    <?php require_once "min.css.html"; ?>
    <link rel="stylesheet" href="style.css">
    <script src='js/feltoltesek.js'></script>

</head>
<body>
    <?php

    /*$query = "SELECT  m.nev mnev, m.route mroute FROM elerheto_menupontok e_m, menupontok m  WHERE e_m ='$jogkor'";
    $result = mysqli_query($connection, $query); */ //ez majd menü kilisttázáasakor esetleg megfelelő lehet

    //var_dump($_SESSION);
    if ($_SESSION['jogkor'] != "") {

        require_once "header.php"; //itt az átirányításnál majd még gondold át

    }
        require_once "$view.php"; //az app.php-ben fog majd eldölni hogy mégis melyik .php nézet fog majd betöltődni
        
    ?>



<?php require_once "templates/min.js.html"; ?>
<script src='js/login.js'></script>
<script src='js/registration.js'></script>

    
</body>
</html>