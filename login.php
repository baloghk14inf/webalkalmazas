<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <?php require_once "templates/min.css.html"; ?>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12"></div><!--A kéernyőméreteket majd nézd át-->
            <div class="col-md-6 col-sm-6 col-xs-12 " >
            <h1 class="text-center p-4 " >Bejelentkezés</h1> 
                <form class="form-container" id="form-login" method="post"  role="form"> 
                <div id="elr">
                <div class="form-group">
                    <label for="">Felhasználónév</label>
                    <input type="text" class="form-control" name="felhasznalonev" id=""  placeholder="Add meg a felhasználóneved">
                    <label for="" style="display: none" id="uzenet3" ></label>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Jelszó</label>
                    <input type="password" class="form-control" name="jelszo" id="exampleInputPassword1" placeholder="Add meg a jelszót">
                </div>
               <input type="submit"  class="btn btn-info" value="Belépés">
               </div>
                </form>
            

            </div>
            <div class="col-md-3 col-sm-3 col-xs-12"></div>

        </div>
    </div>
    


<?php require_once "templates/min.js.html"; ?>
<script src='js/login.js'></script>
</body>
</html>