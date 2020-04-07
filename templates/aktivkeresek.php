<div class="container-fluid">
    <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6 ker-felt-container">
    <div class="col-sm-12 p-4 m-4 mt-5 ">
    <div class="row">
        <h4 class="text-center l-r-cim" >Teljesíthető kérések</h4>
        <div class="row">
            <form class="form-container" method="get" action=''  role="form" id='lista'> 
                    <div class="form-group col-sm-12 col-lg-3 feltolteseim-frm" >
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
                    <div class="form-group col-sm-12 col-lg-3 feltolteseim-frm" >
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
                    </div>
            </form>
        </div>
        <div class="row">
            <div class="col-lg-2"></div>
            <div id='ures' class="col-lg-8"></div>
            <div class="col-sm-2"></div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10" id="elsodiv"></div>
            <div class="col-sm-1"></div>
        </div>
    </div>
    <div class="row">

    <?php require_once "paginate.php";?>
    
    </div>
    
        

    </div>
    </div>
    <div class="col-sm-3"></div>
    </div>
</div>
