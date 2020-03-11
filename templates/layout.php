<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require_once "templates/min.css.html"; ?>
</head>
<body>
    <?php

    $id = $_SESSION['id'];
    $jogkor = $_SESSION['jogkor'];

    $query = "SELECT  m.nev mnev, m.route mroute FROM elerheto_menupontok e_m, menupontok m  WHERE e_m ='$jogkor'";
    $result = mysqli_query($connection, $query);

     if ($jogkor == "") {
         
        header("Location: blabla"); //itt az átirányításnál majd még gondold át
     }
    else: ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a href="/" class="navbar-brand">TIMMT</a>
    <ul class="nav navbar-nav ml-auto">
        
    <?php while($row = mysqli_fetch_array($result)):?> <!-- Ez itt elvileg jó de majd otthon inkább ellenőrizd-->

         <li class="nav-item mr-3 "><a href="<?=$row['mnev']?>" class="nav-link text-info"><?=$row['mrout']?></a></li>

    <?php endwhile;?>

    </ul>
    </nav>
<?php endif;?>
    
</body>
</html>