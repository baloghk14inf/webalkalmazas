<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require_once "min.css.html"; ?>
</head>
<body>
    <?php

    $id = $_SESSION['id'];
    $jogkor = $_SESSION['jogkor'];

    $query = "SELECT  m.nev mnev, m.route mroute FROM elerheto_menupontok e_m, menupontok m  WHERE e_m ='$jogkor'";
    $result = mysqli_query($connection, $query);

    if ($jogkor == "") {
         
        require_once "header.php"; //itt az átirányításnál majd még gondold át
     }
    else {

        require_once "header.php";
        require_once "$view.php"; 
     }
     if ($view == "/registration") {
        require_once "$view.php"; 
     }
     

    ?>
    
</body>
</html>