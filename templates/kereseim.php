<div class="container-fluid">
    <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10 ker-felt-container">
    <div class="col-sm-4 p-4 m-4 mt-5">
    <h4 class="text-center l-r-cim" >Új kérés</h4>
        <form class="form-container" id="form-kereseim"  method="post"  role="form"> 
            <div id="elr">
                <div class="form-group col-sm-12 col-lg-6 feltolteseim-frm">
                    <label for="targy">Tárgy</label>
                    <select class="form-control input-sm" name="targy" id="targy">
                    <option value="">-- Kiválaszt --</option>

                    <?php
                    
                    $query = "SELECT id,nev FROM targyak";
                    $record = select_elemek_lekerdezese($connection, $query);   
 
                    foreach ($record as $row):?>

                    <option value="<?=$row['id']?>"><?=$row['nev']?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-12 col-lg-6 feltolteseim-frm">
                    <label for="kategoria">Kategória</label>
                    <select class="form-control input-sm" name="kategoria" id="kategoria">
                    <option value="">-- Kiválaszt --</option>

                    <?php
                    $query = "SELECT id,nev FROM kategoriak";
                    $record = select_elemek_lekerdezese($connection, $query);       
                    foreach ($record as $row):?>

                    <option value="<?=$row['id']?>"><?=$row['nev']?></option>
                    <?php endforeach; ?>
                    </select>

                    </select>
                </div>
                <div class="form-group col-sm-12 col-lg-12 feltolteseim-frm">
                    <label>Dokumentum meghatározása</label>
                    <textarea rows="5"  class="form-control" name="szovegdoboz" id="szovegdoboz"></textarea>
                </div>

                <div class="form-group col-sm-12 col-lg-6 feltolteseim-frm">
                    <label>Dokumentum éve</label>
                    <input type="text" class="form-control input-sm" name="dokumentum_eve" id="dokumentum_eve"  placeholder="pl. 2009">
                </div>
                <div class="col-lg-1"></div>
                <div class="form-group col-lg-3 feltolteseim-frm kereseim-gomb">
                    <input type="submit"  class="btn btn-primary" value="Kérés indítása">
                </div>
   
            </form>
            </div>

            <div class="col-lg-12">
                <div id='alert'></div>
            </div>

    </div>
    <div class="col-sm-8 p-4 m-4 mt-5 ">
    <div class="row">
        <h4 class="text-center l-r-cim" >Saját kérések</h4>
        <div class="row">
        <form class="form-container" method="get" action=''  role="form" id='lista'> 
                <div class="form-group col-sm-12 col-lg-3 ">
                    <select class="form-control input-sm" name="listazando" id="listazando">
                    <option value="eldontendo">Eldöntendő</option>
                    <option value="teljesitesre_varakozo">Teljesítésre várakozó</option>
                    <option value="teljesitett">Teljesített</option>
                    <option value="ellenorzesre_varakozo">Ellenőrzésre várakozó</option>
                    </select>
                </div>
        </form>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div id='ures' class="col-sm-8"></div>
            <div class="col-sm-2"></div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10" id="elsodiv"></div>
            <div class="col-sm-1"></div>
        </div>
    
    <?php

    require_once "paginate.php";
    
    ?>
        

    </div>
    </div>
    <div class="col-sm-1"></div>
    </div>
</div>
