<div class="container-fluid">
    <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 p-4 m-4 mt-5 border border-info rounded bg-light log-reg-container" >
        <h1 class="text-center l-r-cim" >Regisztráció</h1>
        <form class="form-container" id="form-registration" method="post"  role="form"> 
                <div id="elr">
                <div class="form-group">
                    <label for="">Felhasználónév</label>
                    <input type="text" class="form-control" name="felhasznalonev" id=""  placeholder="kispista15">
                    <small><label for="" style="display: none" id="uzenet2" class="figyelm_uz"></label></small>
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
                    <input type="text" class="form-control" name="szdatum"  placeholder="ÉÉÉÉ-HH-NN">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="kispista23@gmail.com">
                    <small><label for="" style="display: none" id="uzenet3" class="figyelm_uz" ></label></small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Jelszó</label>
                    <input type="password" class="form-control" name="jelszo" id="exampleInputPassword1" placeholder="Valami24">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword2">Jelszó ismét</label>
                    <input type="password" class="form-control" name="jelszo_ismet" id="exampleInputPassword2" placeholder="Valami24">
                </div>
               <input type="submit"  class="btn btn-info" value="Regisztrálok">
               </div>
               <div class="form-group" >
                    <label for="" style="display: none" class="uzenet figyelm_uz" >A regisztráció sikeresen megtőrtént, a bejelentkezéshez nyomj a gombra!</label> 
                    <button type="button" style="display: none" class="btn btn-info btn-block uzenet" onclick="location.href='/login'">Tovább a bejelentkezés oldalra</button>
                </div>
                </form>
    </div>
    <div class="col-sm-4"></div>
    </div>
</div>