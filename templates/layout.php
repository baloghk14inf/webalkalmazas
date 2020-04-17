<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$siteName?> | <?=$title?></title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css" />
    <link rel="stylesheet" href="/style.css"> <!-- a / a rewriter miatt kell-->

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

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/1.2.3/bootstrap-filestyle.min.js'></script>

<?php

    $feldarabolt_URL = explode('/', $_SERVER['REQUEST_URI']); //ezt itt a query string miatt darabolom pl.(?keres=5)
    $path_name = "/".$feldarabolt_URL[1];

?>

<?php if ($path_name == "/login" | $path_name == "/"):?>
<script src='js/login.js'></script>
<?php endif; ?>
<?php if ($path_name == "/registration"):?>
<script src='js/registration.js'></script>
<?php endif; ?>
<?php if ($path_name == "/kereseim" || $path_name == "/feltolteseim" || $path_name == "/aktiv-keresek" || $path_name == "/dokumentum" || $path_name == "/dokumentum-kereses" || $path_name == "/feltoltesek" ):?>
<script src='/js/pagination.js'></script> <!--A body elé kell beszúrni a pagination kezdeti értékének meghatározása miatt-->
<?php endif; ?>
<?php if ($path_name == "/feltolteseim"):?>
<script src='/js/feltoltesek.js'></script>
<?php endif; ?>
<?php if ($path_name == "/kereseim" || $path_name == "/feltolteseim" || $path_name == "/dokumentum-kereses"):?>
<script src='/js/kozos.js'></script>
<?php endif; ?>
<?php if ($path_name == "/kereseim"):?>
<script src='js/kereseim.js'></script>
<?php endif; ?>
<?php if ($path_name == "/dokumentum" ):?>
<script src='/js/dokumentum.js'></script>
<?php endif; ?>
<?php if ($path_name == "/dokumentum-kereses" ):?>
<script src='/js/dokumentum-kereses.js'></script>
<?php endif; ?>
    
</body>
</html>