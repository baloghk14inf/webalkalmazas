$(document).ready(function () {


    pagination(window.location.pathname.split("/")[2], "", "","", ""); // a dokumentum id-jét admo át amit az url-ből szerzek meg
    
    $('#form-ertekeles').bootstrapValidator({

        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            szovegdoboz: {
                validators: {
                    notEmpty: {
                        message: 'Nem fogalmaztad meg értékelésed!'
                    },
                    stringLength: {
                        min: 15,
                        max: 500,
                        message: 'A megadott szöveg min:15 és max:500 karakterből állhat.'
                    }
                }
            },
            pont: {

                validators: { notEmpty: { message : 'Az értékelésnek megfelelő pontot kötelező kiválasztani.'}}
            }

        }// fields_end
    }).on('success.form.bv', function(e) { //itt végzem el az ürlap küldését
        
        e.preventDefault();

        $.ajax({
            url     : '/ertekeles.php', //Target URL for JSON file
            type    : 'POST',
            data    : {
                'szoveg': $("#szovegdoboz").val(),
                'pont': $("#pont").val(),
                'dokumentum_id': window.location.pathname.split("/")[2],

            }, 
            dataType: 'json',
            async: true, // true a követ kező utasítás végrehalytásra kerül annak ellenére hogy még nem felyződött be.
                        //false szinkron addig nem megy tovább amig be nem fejeződött a kérés
            cache:false,
            success : function(result, status, xhr){

                
                if (result.valid == true) {
                    
                    pagination(window.location.pathname.split("/")[2], "", "","", "");

                    $("#form-ertekeles :input").each(function() {
                        
                        if (this.type != "submit") {
                            $(this).val('');
                        }
                    });

                    $("#form-ertekeles").data('bootstrapValidator').resetForm();

                    $('#alert').html("<div class='alert alert-success' role='alert'>"+ result.message +"</div>");
                    $('#alert').show();
                    
                }
                else {
                    
                    $('#alert').html("<div class='alert alert-danger' role='alert'>"+ result.message +"</div>");
                    $('#alert').show();
                }
 
               

            },	// success 
            error : function(xhr, status){
                alert("Sikertelen");

            }	// error
        }); // $.ajax	
    });

    $("#zold-gomb").on('click',function (event){
        
        dokumentum_muveletek($(this).val());

    });

    $("#piros-gomb").on('click',function (event){

        dokumentum_muveletek($(this).val());

    });


    function dokumentum_muveletek(gomb) {

        $('#form-dokumentum-muveletek').submit( function(event){ //az adott id-jü formnak a submit típusu gomb lenyomására történjen meg az esemény
                
            event.preventDefault();

            $("#zold-gomb").attr('disabled','disabled'); //ezt itt azért csinlom mert a háttérben valamiért töbszöri kattintásra kerül sor
            $("#piros-gomb").attr('disabled','disabled');

            console.log(gomb);

            var vegzendo_muvelet = gomb;
            
                $.ajax ({
                    url: '/dokumentum-muveletek.php', //kinek      //xml struktúrát épít fel és azt küldi tovább a register.php-nek az url-el,type-val és,dataType-vel eggyütt
                    //url: 'login.php', //kinek
                    
                    type: 'POST', //küldönk
                    data:
                    { "gomb_nev": vegzendo_muvelet,
                      "keres_id": $("#keres_id").val(),
                      "dokumentum_id": $("#dokumentum_id").val(),
                      "feltolto": $("#feltolto").val()}, 
                    cache: false, //false esetén azoonal küldi a szerver felé az adatokat, true esetén pedig a merevlemezen tárolja a kérést és majd valamikor megy tovább a szerver felé
                    dataType: 'json',
                    async: true, //ha false akkor várni fog, az az itt meg fog akadni  //ne fagyjon le a várásban
                    //a fölötte lévő az csak küldés
                    //is innetnől lefele pedig a válasz ellenörzés
                    success: function(result, status, xhr) { // a succes ág akkor fog lefutni ha a php-ben nincs hiba pl (csatlakozási)
        

                        if (result.message != "") {

                            $( "#elr" ).addClass("eltuntet");
                            $("#d-muvelet-alert").removeClass("eltuntet");

                            $('#alert-uzenet').html("<div class='alert alert-info' role='alert'>"+ result.message +"</div>");
                            $('#alert-uzenet').show();

                            if (vegzendo_muvelet == "Elfogadom" || vegzendo_muvelet == "Nem ezt kértem") {
                                $("#d-muveletek-gomb").html("Vissza a kéréseimhez");
                                $("#d-muveletek-gomb").attr("onclick", "location.href='/kereseim'");
                            }
                            if (vegzendo_muvelet == "Elfogad" || vegzendo_muvelet == "Törlés") {
                                $("#d-muveletek-gomb").html("Vissza a feltöltésekhez");
                                $("#d-muveletek-gomb").attr("onclick", "location.href='/feltoltesek'");
                            }

                            
                            
                    }
                        
                    },
                    error: function(xhr, status) { //ez pedig akkor ha pl csatlakozási hiba vagy programkodban van hib
                     alert("Sikertelen");
        
                    }
        
                });
            
        
        });
    }

  /*  $('#zold-gomb').on('click', function(e) {
        
        dokumentum_muveletek($(this).val());

    });
    $('#piros-gomb').on('click', function(e) {

        dokumentum_muveletek($(this).val());
    });

    */

  /*  $(function() {
        var submitter
      $('input:submit').click(function() {
          submitter = $(this).val()
      })
        $('form').submit(function(e) {
          if (submitter === 'Save & Exit') {
            alert('Saved and exiting')
        } else {
            alert('Saved and continuing')
        }
        return false
      })
    })*/



    /* function dokumentum_muveletek(gomb) {

        
        $('#form-dokumentum-muveletek').submit(function(e) {
            e.preventDefault();
            alert(gomb);
            console.log($(this).closest('#form-dokumentum-muveletek').serialize());
          
        })
      }*/


});