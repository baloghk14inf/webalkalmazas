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
            <h1 class="text-center p-4 " >Regisztráció</h1> 
                <form class="form-container" id="form-registration" method="post"  role="form"> 
                <div id="elr">
                <div class="form-group">
                    <label for="">Felhasználónév</label>
                    <input type="text" class="form-control" name="felhasznalonev" id=""  placeholder="Add meg a felhasználóneved">
                    <label for="" style="display: none" id="uzenet3" ></label>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Nem</label>
                    <select class="form-control" name="nem" id="exampleFormControlSelect1">
                    <option value="">-- Kiválaszt --</option>
                    <option value="nő">Nő</option>
                    <option value="férfi">Férfi</option>
                    <option value="nem megadott">Nem adom meg</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Születési dátum</label>
                    <input type="text" class="form-control" name="szdatum"  placeholder="ÉÉÉÉ HH NN">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Add meg az email címed">
                    <label for="" style="display: none" id="uzenet2" ></label>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Jelszó</label>
                    <input type="password" class="form-control" name="jelszo" id="exampleInputPassword1" placeholder="Add meg a jelszót">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword2">Jelszó ismét</label>
                    <input type="password" class="form-control" name="jelszo_ismet" id="exampleInputPassword2" placeholder="Add meg a jelszót ismét">
                </div>
               <!-- <button type="submit" class="btn btn-info" >Regisztrálok</button> -->
               <input type="submit"  class="btn btn-info" value="Regisztrálok">
               </div>
               <div class="form-group" >
                    <label for="" style="display: none" class="uzenet" >A regisztráció sikeresen megtőrtént, a bejelentkezéshez nyomj a gombra!</label>
                    <input type="submit" style="display: none"   class="btn btn-info btn-block uzenet" value="Tovább a bejelentkezés oldalra"> 
                    
                    
                </div>


                </form>
            

            </div>
            <div class="col-md-3 col-sm-3 col-xs-12"></div>

        </div>
    </div>
    


<?php require_once "templates/min.js.html"; ?>
<script src='js/registration.js'></script>
</body>
</html>