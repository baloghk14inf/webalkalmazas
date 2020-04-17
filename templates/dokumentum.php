<div class="container-fluid">
    <div class="row"><!--B-->
        <div class="col-sm-2"></div>
        <div class="col-sm-8 ker-felt-container">
            <div class="col-sm-4 ">
                
                <h4 class="text-center l-r-cim" >Dokumentum adatai</h4>

                <div class="col-sm-1"></div>  
                <div class="col-sm-10 dokumentum-adatok">

                <div class="row">
                <?php if (!empty($atlag_ertekeles)):?>
                    <p><strong  style="padding-right: 36px;">Átlag értékelés: </strong>
                    <a href="https://www.flaticon.com/authors/pixel-perfect" id="atlag_ertekeles" title="Pixel perfect">
                  <?php  for ($i = 0; $i < $atlag_ertekeles; $i++): ?>
                    <img src="/felhasznalt kepek/star-teli.png" class="csillag" alt="">
                  <?php endfor;?>
                  <?php if ($atlag_ertekeles != 5):?>
                        <?php  for ($j = 0; $j < (5-$atlag_ertekeles); $j++): ?>
                        <img src="/felhasznalt kepek/star-ures.png" class="csillag" alt="">
                        <?php endfor;?>
                  <?php endif;?>
                    </a></p>
                <?php endif;?>
                    <p><strong  style="padding-right: 20px;">Dokumentum éve: </strong> <?=$dokumentum_eve?> </p>
                    <p><strong style="padding-right: 67px;">Oldalszám: </strong> <?=$oldalszam?></p>
                    <p><strong  style="padding-right: 100px;">Méret:          </strong> <?=$file_meret?></p>
                    <p><strong style="padding-right: 88px;">Feltöltő:       </strong> <?=$feltolto?></p>
                    <p><strong style="padding-right: 78px;">Feltöltve:   </strong> <?=$feltoltes_datuma?></p>
                    </div>
                
                <?php if ((($kero == $_SESSION['id']) && ($keres_statusza == 6) && ($_SESSION['jogkor'] != 1)) || 
                 (($dokumentum_statusza == 1) && ($_SESSION['id'] != 3 ))):?>
                    <div class="row form-dokumentum-muveletek" id="elr">
                     <form id="form-dokumentum-muveletek" name="form-dokumentum-muveletek">
                     <label><?=($kero == $_SESSION['id']) ? 'Kérésemmel kapcsolatos döntés:' : 'Dokumentummal kapcsolatos döntés:' ?></label>
                        <div class="form-group">
                            <input type="hidden" name="keres_id" id="keres_id" value="<?=$keres_id?>">
                            <input type="hidden" name="dokumentum_id" id="dokumentum_id" value="<?=$id?>">
                            <input type="hidden" name="feltolto" id="feltolto" value="<?=$feltolto?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" id="zold-gomb"  class="btn btn-success "  value="<?=($kero == $_SESSION['id']) ? 'Elfogadom' : 'Elfogad' ?>"">
                            <input type="submit" id="piros-gomb"  class="btn btn-danger "  value="<?=($kero == $_SESSION['id']) ? 'Nem ezt kértem' : 'Törlés' ?>">
                        </div>            
                     </form>
                            
                    </div>
                <?php endif;?>

                    <div class="row  form-dokumentum-muveletek eltuntet" id="d-muvelet-alert">

                    
                    <div class="col-sm-12">
                    <div id='alert-uzenet'></div>
                    <button id="d-muveletek-gomb" type="button" class="btn btn-block"></button>
                    </div>
                    
                    </div>
                </div>
                <div class="col-sm-1"></div>   
            </div>
                
            <div class="col-sm-8 ">
                <div class="row">
                    <h4 class="text-center l-r-cim" ><?=$dokumentum_cime?></h4>  <!-- ide pedig majd a dokumentum címe fog majd kerülni-->
                    <div class="row">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                        <iframe src="/<?=$eleresi_ut?>" type = "application / pdf"></iframe>
                        </div>
                        <div class="col-sm-1"></div>
                        
                    </div>

                    <?php if ($feltolto != $_SESSION['felhasznalonev'] && $dokumentum_statusza != 1 ):?>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-sm-2"></div>
                        <div id='ures' class="col-sm-8"></div>  <!--ez a tartalom allert majd jó lesz ha nincs hozzászólás-->
                        <div class="col-sm-2"></div>
                    </div>
                    <?php endif;?>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10" id="elsodiv"></div> <!--ide meg majd a hozzászólásokat fogom kilistázni kb 6-ot és it is lessz majd pagináció-->
                        <div class="col-sm-1"></div>
                    </div>

                    <div class="row">

                    <?php require_once "paginate.php";?>

                    </div>
                    <?php if ($feltolto != $_SESSION['felhasznalonev'] && $dokumentum_statusza != 1 ):?>
                    <div class="row">
                    <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <form  id="form-ertekeles"  method="post"  role="form"> 

                                    <div class="form-group col-sm-12 col-lg-12 ">
                                        <label>Új értékelés</label>
                                        <textarea rows="5"  class="form-control" name="szovegdoboz" id="szovegdoboz"></textarea>
                                    </div>

                                    <div class="form-group col-sm-12 col-lg-7 ">
                                        
                                        <select class="form-control input-sm" name="pont" id="pont">
                                        <option value="">-- Értékelés kiválasztása --</option>
                                        <option value="1">1-Használhatatlan</option>
                                        <option value="2">2-Talán haszálható</option>
                                        <option value="3">3-Használható</option>
                                        <option value="4">4-Jó</option>
                                        <option value="5">5-Kiváló</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="form-group col-lg-3 ">
                                        <input type="submit"  class="btn btn-primary btn-sm" value="Hozzászólás">
                                    </div>
                                    </div>
                    
                                </form>
                                
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                 <?php endif;?>

                 <div class="col-lg-12">
                    <div id='alert'></div>
                 </div>
                 
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>
