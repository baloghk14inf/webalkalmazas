<div class="container-fluid">
    <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 p-4 m-4 mt-5 border border-info rounded bg-light log-reg-container">
        <h1 class="text-center l-r-cim" >Bejelentkezés</h1>
        <form class="form-container" id="form-login" method="post"  role="form"> 
            <div id="elr">
               <div class="form-group">
                  <label for="">Felhasználónév</label>
                  <input type="text" class="form-control" name="felhasznalonev" id=""  placeholder="Kispista14">
                </div>
                <div class="form-group">
                   <label for="exampleInputPassword1">Jelszó</label>
                   <input type="password" class="form-control" name="jelszo" id="exampleInputPassword1" placeholder="Valami23">
                </div>
                   <input type="submit"  class="btn btn-info" value="Belépés">
                <div class="form-group">
                   <small><label style="display: none" id="uzenet"class="figyelm_uz" ></label></small> 
                </div>
             </div>
         </form>
         <div class="row text-center">
            <a href="/registration">Fiók létrehozása-></a>
         </div>
    </div>
    <div class="col-sm-4"></div>
    </div>
</div>
