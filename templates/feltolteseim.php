<div class="container-fluid">
    <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 ker-felt-container">
    <div class="col-sm-6 p-4 m-4 mt-5">
    <h4 class="text-center l-r-cim" >Dokumentum feltöltése</h4>
        <form class="form-container" id="form-feltolteseim" method="post"  role="form"> 
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
                <div class="form-group col-sm-12 col-lg-10 feltolteseim-frm">
                    <label>Dokumentum címe</label>
                    <input type="text" class="form-control input-sm"  name="dcim" id="dcim" placeholder="pl. A Szózat részletes elemzése">
                </div>
                <div class="form-group col-sm-12 col-lg-6  feltolteseim-frm">
                    <label>Oldalszám</label>
                    <input type="text" class="form-control input-sm"  name="oldalszam" id="oldalszam" min="1" placeholder="pl. 10">
                </div>
                <div class="form-group col-sm-12 col-lg-6 feltolteseim-frm">
                    <label>Dokumentum éve</label>
                    <input type="text" class="form-control input-sm" name="dokumentum_eve" id="dokumentum_eve" min="1950" max="aktualis_ev()"  placeholder="pl. 2009">
                </div>
                <div class="form-group col-sm-12 col-lg-10 feltolteseim-frm">
                    <label>Forrás</label>
                    <input type="text" class="form-control input-sm" name="forras" id="forras"  placeholder="Létrehozó, terjesztő.">
                </div>
                <div class="form-group col-lg-8 feltolteseim-frm">
                     <input type="file" class="form-control  filestyle" id="file" name="file" data-buttonBefore="true">
                </div>

                <div class="form-group col-lg-4 feltolteseim-frm">
                    <input type="submit"  class="btn btn-info" value="Feltölt">
                </div>
               </div>
            </form>

            <div class="form-group  col-lg-12">
                <h4 class="text-center l-r-cim" >Ellenőrzésre várakozó feltöltések</h4>
            </div>
    </div>
    <div class="col-sm-6 p-4 m-4 mt-5 ">
        <h4 class="text-center l-r-cim" >Feltöltéseim</h4>
        

    </div>
    </div>
    <div class="col-sm-2"></div>
    </div>
</div>
