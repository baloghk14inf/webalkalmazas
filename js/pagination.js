function pagination(listazando, oldal, targy, kategoria, ev) {

    var listazando = listazando;
    var oldal = oldal;
    var targy = targy;
    var kategoria = kategoria;

    $.ajax({
        url     : '/pagination.php', // a / karakter az a 2. szint miatt kell pl.(/feltoltesek/kivalasztott-keres-68)
        type    : 'POST',
        data    : {
            'listazando': listazando,
            'oldal': oldal,
            'targy': targy,
            'kategoria': kategoria,
            'ev': ev
        },
        cache   : false,
        dataType: 'json',
        async   : true,
        success : function(result, status, xhr){

            console.log(result);

            //ezzel itt eltüntetem a tartalom listázásnál az alert üzenetet.
            $('#alert2').remove();

            //ezzel törlöm az elöbb kreált div-eket, mert ujboli div feltöltéskor nem az elöző adatok helye
            //kerűlne felülírásra hanem  folyamatosan bekerülne a másik a lá a tartalom
            for (var i = 0; i < result.size; i++) {

                $("#div" + i).remove();
                $("#szoveg_d" + i).remove();
                
            }

            if (result.content == "") {
                if (result.listazando == "aktiv-keresek") {

                    $('#ures').html("<div id='alert2' class='alert alert-info' role='alert'> Nem található a feltétel-nek-eknek megfelelő kérés.</div>");

                }
                else if(parseInt(result.listazando)){ //int-é kell alakítani mert str-kénk kerül elküldésre és úgy is jön vissza

                    $('#ures').html("<div id='alert2' class='alert alert-info' role='alert'>Ezt a dokumentumot még nem értékelte senki. Legyél Te az első.</div>");

                }
                else if(result.listazando.split("=")[0] == "?keresendo"){ //int-é kell alakítani mert str-kénk kerül elküldésre és úgy is jön vissza

                    $('#ures').html("<div id='alert2' class='alert alert-info' role='alert'>Nem található a keresésnek megfelelő dokumentum.</div>");

                }
                else { 

                    $('#ures').html("<div id='alert2' class='alert alert-info' role='alert'> Nincs megjeleníthető tartalom.</div>");

                }
                
                $("#pagination").addClass("eltuntet"); // azért van kivül mert minden esetben használatba kell venni
            }
            else {

                if (result.total <= 6) {
                    $("#pagination").addClass("eltuntet");  
                }
                else {
                    $("#pagination").removeClass("eltuntet");
                }
                
            }


           /* $("#a1").removeAttr("href");
            $("#a1").removeClass("leptet");
            $("#a2").removeAttr("href");
            $("#a2").removeClass("leptet");
            $("#a3").removeAttr("href");
            $("#a4").removeAttr("href");
            $("#a4").removeClass("leptet");
            $("#a5").removeAttr("href");
            $("#a5").removeClass("leptet");*/
            


            //it töltöm fel a pagination href elemeit és olykor az a tag szövegét
            $("#a1").attr("href", "#"+ result.listazando + "&#" + (result.page - 1));
            $("#a1").addClass("leptet");
            $("#a2").attr("href", "#"+ result.listazando + "&#" + 1 );
            $("#a2").addClass("leptet");
            $("#a3").attr("href", "#");
            $("#a3").html(result.page);
            $("#a4").attr("href", "#"+ result.listazando + "&#" + (result.lastPage));
            $("#a4").html(result.lastPage);
            $("#a4").addClass("leptet");
            $("#a5").attr("href", "#"+ result.listazando + "&#" + (result.page + 1));
            $("#a5").addClass("leptet");

            
            if (result.page <= 1) {
                $("#nav1").addClass("disabled pointer");
                $("#nav2").addClass("nav-elem-eltuntet");
                $("#a1").removeClass("leptet");
            }
            else {
                $("#nav1").removeClass("disabled pointer");
                $("#nav2").removeClass("nav-elem-eltuntet");
            }
            if (result.page <= 2) {
                $("#nav3").addClass("nav-elem-eltuntet");
            }
            else {
                $("#nav3").removeClass("nav-elem-eltuntet");
            }
            if (result.page >= (result.lastPage - 1)) {
                $("#nav4").addClass("nav-elem-eltuntet");
            }
            else {
                $("#nav4").removeClass("nav-elem-eltuntet");
            }
            if (result.page >= result.lastPage) {
                $("#nav5").addClass("nav-elem-eltuntet");
                $("#nav6").addClass("disabled pointer");
                $("#a5").removeClass("leptet");
            }
            else {
                $("#nav6").removeClass("disabled pointer");
                $("#nav5").removeClass("nav-elem-eltuntet");
            }
     
            if (result.listazando == "feltoltott" || result.listazando == "ellenorzendo" || result.listazando.split("=")[0] == "?keresendo" || result.listazando == "feltoltesek") {


                $meret = ((result.listazando.split("=")[0] == "?keresendo") || (result.listazando == "feltoltesek")) ? "col-sm-12" : "col-sm-5";
                //divek dinamikus feltöltése
                for (var i = 0; i < result.content.length; i++) {
                    
                    $('<div id="div' + i + '" class="'+ $meret +' div-adatok">').appendTo('#elsodiv');
                    $('<a href="/dokumentum/'+ result.content[i].id +'" id="link'+ i +'" class="tartalom-a">').appendTo('#div'+ i);
                    $('<h4 class="tartalom_cim">'+ result.content[i].dokumentum_cime +'</h4>').appendTo('#link'+ i);
                    $('<h6 class="tartalom_alcim">' +"Tantárgy: "+ result.content[i].kategoria +'</h6>').appendTo('#link'+ i);
                    $('<h6 class="tartalom_alcim">' +" Kategória: "+ result.content[i].targy +'</h6>').appendTo('#link'+ i);
                    $('<h6 class="tartalom_alcim">' +"Feltöltés dátuma: "+ result.content[i].feltoltes_datuma +'</h6>').appendTo('#link'+ i); 
                    $('</a>').appendTo('#link'+ i);
                    $('</div>').appendTo('#div'+ i); 
                    //alert('<p id="p' + i +'">'+ result.content[i].dokumentum_cime +'</p>');
                    
                }
            }

            
            if (result.listazando == "eldontendo" || result.listazando == "teljesitett") {

                
                
                //divek dinamikus feltöltése
                for (var i = 0; i < result.content.length; i++) {

                    var dokumentum_datum = result.content[i].dokumentum_eve == null ? "-" : result.content[i].dokumentum_eve;
                    
                    $('<div id="div' + i + '" class="col-sm-5 div-adatok">').appendTo('#elsodiv');
                    $('<a href="/dokumentum/'+ result.content[i].dok_id +'" id="link'+ i +'" class="tartalom-a">').appendTo('#div'+ i);
                    $('<h4 class="tartalom_cim">'+ result.content[i].k_megnev +'</h4>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +"Tantárgy: "+ result.content[i].kategoria +'</h6>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +" Kategória: "+ result.content[i].targy + '</h6>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +"Dokumentum éve: "+ dokumentum_datum + '</h6>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +"Kérés dátuma: "+ result.content[i].keres_d +'</h6>').appendTo('#link'+ i); 
                    $('<h6  class="tartalom_alcim">' +"Teljesítés dátuma: "+ result.content[i].teljesites_d +'</h6>').appendTo('#link'+ i); 
                    $('<h6  class="tartalom_alcim">' +"Feltöltő: "+ result.content[i].teljesito_neve +'</h6>').appendTo('#link'+ i); 
                    $('</a>').appendTo('#link'+ i);
                    $('</div>').appendTo('#div'+ i); 
                    //alert('<p id="p' + i +'">'+ result.content[i].dokumentum_cime +'</p>');
                    
                }
            }


            if (result.listazando == "teljesitesre_varakozo" || result.listazando == "ellenorzesre_varakozo" || result.listazando == "aktiv-keresek") {

                var meretezes = result.listazando == "aktiv-keresek" ? "col-sm-12" : "col-sm-5";
                var szoveg_meretezes = result.listazando != "aktiv-keresek" ? "meretezett-szoveg" : "";

                
                //divek dinamikus feltöltése
                for (var i = 0; i < result.content.length; i++) {

                    var dokumentum_datum = result.content[i].dokumentum_eve == null ? "-" : result.content[i].dokumentum_eve;
                    var query_str = result.listazando == "aktiv-keresek" ? '/feltolteseim/kivalasztott-keres-' + result.content[i].keres_id : "#";
                    
                    $('<div id="div' + i + '" class="'+ meretezes +' div-adatok">').appendTo('#elsodiv');
                    $('<a href="'+query_str+'" id="link'+ i +'" class="tartalom-a">').appendTo('#div'+ i);
                    $('<h4 class="tartalom_cim '+ szoveg_meretezes +'">'+ result.content[i].k_megnev +'</h4>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +"Tantárgy: "+ result.content[i].targy +'</h6>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +" Kategória: "+ result.content[i].kategoria + '</h6>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +"Dokumentum éve: "+ dokumentum_datum + '</h6>').appendTo('#link'+ i);
                    $('<h6  class="tartalom_alcim">' +"Kérés dátuma: "+ result.content[i].keres_d +'</h6>').appendTo('#link'+ i);
                    $('</a>').appendTo('#link'+ i);
                    $('</div>').appendTo('#div'+ i); 
                    //alert('<p id="p' + i +'">'+ result.content[i].dokumentum_cime +'</p>');
                    
                }
            }

            if (parseInt(result.listazando)) {

            
                for (var i = 0; i < result.content.length; i++) {

                    
                    $('<div id="div' + i + '" class="div-hozzaszolas">').appendTo('#elsodiv');
                    $('<a href="https://www.flaticon.com/authors/pixel-perfect" id="link'+ i +'" title="Pixel perfect">').appendTo('#div'+ i);

                    for (var j = 0; j < result.content[i].pont; j++) {

                        $('<img src="/felhasznalt kepek/star-teli.png" class="csillag" alt="">').appendTo('#link'+ i);
                        
                    }
                    if (result.content[i].pont.length != 5 ) {

                        for (var k = 0; k < (5-result.content[i].pont); k++) {

                            $('<img src="/felhasznalt kepek/star-ures.png"  class="csillag" alt="">').appendTo('#link'+ i);
                        }
                            
                    }
                    $('</a>').appendTo('#link'+ i);
                    $('<span  class="hozzaszolo">' + result.content[i].ertekelo +'</span>').appendTo('#div'+ i);
                    $('<button type="button" class=" btn btn-info btn-xs bejelent-gomb" onclick="window.location.href='+"'"+'/bejelentes/hozzaszolas-'+result.content[i].hozzasz_id+"';"+'">Bejelentem</button>').appendTo('#div'+ i);
                    $('<span  class="hozzaszolas-datuma">' + result.content[i].ertekeles_datuma +'</span>').appendTo('#div'+ i);
                    
                   
                    $('</div>').appendTo('#div'+ i); 

                    $('<div id="szoveg_d'+ i + '" class="ertekeles_szovegdoboz">').appendTo('#elsodiv');
                    $('<p>' + result.content[i].megjegyzes + '</p>').appendTo('#szoveg_d'+ i);
                    $('</div>').appendTo('#szoveg_d'+ i); 
                    
                }
            }


        },	// success 
        error : function(xhr, status){
            alert("Sikertelen");

        }	// error
    }); // $.ajax	

}

$(document).on( 'click', '.leptet', function(){

    var feldarabolt_href = this.href.split('#');
    var oldalsz = parseInt(feldarabolt_href[2]);
    var listazando = feldarabolt_href[1].replace('&', '');

    pagination(listazando, oldalsz,"","","");
    
} );

$('#listazando').on('change', function() {
    var listazando = $('#listazando').val();
    pagination(listazando, "","","" , "");
    
});

if (window.location.pathname == "/aktiv-keresek") {
    pagination("aktiv-keresek","","","", "")
}
$('#aktiv_k_targy').on('change', function() {
    
    pagination("aktiv-keresek", "", $('#aktiv_k_targy').val(), $('#aktiv_k_kategoria').val(), "");
    
});
$('#aktiv_k_kategoria').on('change', function() {
    
    pagination("aktiv-keresek", "", $('#aktiv_k_targy').val(), $('#aktiv_k_kategoria').val(), "");
    
});
if (window.location.pathname == "/feltoltesek") {
    pagination("feltoltesek","","","", "")
}

