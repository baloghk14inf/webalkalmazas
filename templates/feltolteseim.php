<div class="container-fluid">
    <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10 ker-felt-container">
    <div class="col-sm-4 p-4 m-4 mt-5">
    <h4 class="text-center l-r-cim" >Dokumentum feltöltése</h4>
        <div class="col-lg-1"></div>
        <?php if (!empty($keres_megnevezese)):?>
        <div class="col-lg-10" id='keres_alert'>
            <div id='k_megenevezes_alert' class='alert alert-info' style="margin-top:15px" role='alert'><?=$keres_megnevezese != "" ? "<strong>Kiválasztott kérés megnevezése:</strong> " .$keres_megnevezese : ""?></div>
        </div>
        <?php endif;?>
        <div class="col-lg-1"></div>
        <form class="form-container" id="form-feltolteseim" method="post"  role="form"> 
            <div id="elr">

                <?php if (!empty($keres)):?>
                <div class="form-group col-sm-12 col-lg-10 feltolteseim-frm" id="keres_input">
                    <input type="hidden" class="form-control input-sm" id="keres" value="<?=$keres?>"">
                </div>
                <?php endif;?>

                <div class="form-group col-sm-12 col-lg-6 feltolteseim-frm">
                    <label for="targy">Tárgy</label>
                    <select class="form-control input-sm" name="targy" id="targy" <?=$targy == "" ? '' : 'disabled'?>>
                    <option value="">-- Kiválaszt --</option>

                    <?php
                    
                    $query = "SELECT id,nev FROM targyak";
                    $record = select_elemek_lekerdezese($connection, $query);   
 
                    foreach ($record as $row):?>

                    <option value="<?=$row['id']?>"<?=$targy == $row['id'] ? 'selected' : ''?>><?=$row['nev']?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-12 col-lg-6 feltolteseim-frm">
                    <label for="kategoria">Kategória</label>
                    <select class="form-control input-sm" name="kategoria" id="kategoria" <?=$kategoria == "" ? '' : 'disabled'?>>
                    <option value="">-- Kiválaszt --</option>

                    <?php
                    $query = "SELECT id,nev FROM kategoriak";
                    $record = select_elemek_lekerdezese($connection, $query);       
                    foreach ($record as $row):?>

                    <option value="<?=$row['id']?>"<?=$kategoria == $row['id'] ? 'selected' : ''?>><?=$row['nev']?></option>
                    <?php endforeach; ?>
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
                    <input type="text" class="form-control input-sm" name="dokumentum_eve" id="dokumentum_eve" placeholder="pl. 2009" <?=$dokumentum_eve != "" ? 'value="'.$dokumentum_eve.'" disabled' : ''?>>
                </div>
                <div class="form-group col-sm-12 col-lg-10 feltolteseim-frm">
                    <label>Forrás</label>
                    <input type="text" class="form-control input-sm" name="forras" id="forras"  placeholder="Létrehozó, terjesztő.">
                </div>
                <div class="form-group col-lg-8 feltolteseim-frm">
                     <input type="file" class="form-control  filestyle" id="file" name="file" data-buttonBefore="true">
                </div>

                <div class="form-group col-lg-4 feltolteseim-frm">
                    <input type="submit"  class="btn btn-primary" value="Feltölt">
                </div>
   
            </form>
            </div>

            <div class="col-lg-12">
                <div id='alert'></div>
            </div>

    </div>
    <div class="col-sm-8 p-4 m-4 mt-5 ">
    <div class="row">
        <h4 class="text-center l-r-cim" >Általam feltöltött dokumentumok</h4>
        <div class="row">
        <form class="form-container" method="get" action=''  role="form" id='lista'> 
                <div class="form-group col-sm-12 col-lg-3 ">
                    <select class="form-control input-sm" name="listazando" id="listazando">
                    <option value="feltoltott">Feltöltéseim</option>
                    <option value="ellenorzendo">Ellenőrzésre várakozó</option>
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
    
    <div class="row">

    <?php require_once "paginate.php";?>
    
    </div>
    
        

    </div>
    </div>
    <div class="col-sm-1"></div>
    </div>
</div>
