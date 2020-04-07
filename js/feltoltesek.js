$(document).ready(function () {


    pagination("feltoltott","","",""); //itt hívom meg hogy a függvényt többszőr is meg tudjam majd hívni

    

    aktualis_ev = aktualis_ev();

    $("li.disabled a").on(function() { //ezzel megakadályozom azt hogy ha disabled a nav ellem akkor se lehessen rányomni
        return false;
      });
    
      
    $(":file").filestyle({buttonBefore: true});
    
    $(":file").filestyle('buttonText', 'Kiválaszt');


    $('#form-feltolteseim').bootstrapValidator({

        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            targy: {

                validators: { notEmpty: { message : 'A tárgyat kötelező megadni!'}}
            },
            kategoria: {

                validators: { notEmpty: { message : 'A kategóriát kötelező megadni!'}}
            },
            dcim: {
                validators: {
                    notEmpty: {
                        message: 'A dokumentum címét kötelező megadni!'
                    }
                }
            },
            forras: {
                validators: {
                    notEmpty: {
                        message: 'A dokumentum forrást köteleő megadni!'
                    }
                }
            },
            file: {
                validators: {
                    file: {
                        extension: 'pdf', //itt ezzel gondoskodom róla hogy a kiválasztott file pdf formátumu legyen
                        message: 'A kiválasztott fájl nem pdf formátumu!'
                    },
                    notEmpty: {
                        message: 'Nem választottad ki a feltöltendő dokumentumot!'
                    }
                }
            },
            dokumentum_eve : {
                validators: {
                    integer: {
                        message: 'Nem évet adtál meg!'
                    },
                    between: {
                        min: 1950,
                        max: aktualis_ev,
                        message: 'A megadandó évnek 1950-'+aktualis_ev+' közé kell esnie!'
                    }
                }
            },
            oldalszam: {
                validators: {
                    integer: {
                        message: 'Nem számot adtál meg!'
                    }
                }
            }

        
        }// fields_end
    }).on('success.form.bv', function(e) { //itt végzem el az ürlap küldését
        
        e.preventDefault();


        var fd = new FormData();
        var files = $('#file')[0].files[0];
        
        fd.append('file',files);
        fd.append('targy',$('#targy').val());
        fd.append('kategoria',$('#kategoria').val());
        fd.append('dcim',$('#dcim').val());
        fd.append('oldalszam',$('#oldalszam').val());
        fd.append('dokumentum_eve',$('#dokumentum_eve').val());
        fd.append('forras',$('#forras').val());
        fd.append('keres',$('#keres').val());  //átadom a keres_id-t

        $.ajax({
            url     : 'feltolt.php', //Target URL for JSON file
            type    : 'POST',
            data    : fd,
            async: true,           //Ezzel itt kikapcsoltam azt hogy 2 üzenet térjen vissza
            contentType: false,
            processData: false,
           // dataType: 'json',
            success : function(result, status, xhr){
                alert("Sikeres");
 
                var valasz = JSON.parse(result);

                if (valasz.valid == true) {
                    
                    $("#form-feltolteseim :input").each(function() {
                        
                        if (this.type != "submit") {
                            $(this).val('');
                        }
                    });

                    //alaphelyzetbe állítom a bootstrap validátort
                    $("#form-feltolteseim").data('bootstrapValidator').resetForm(); 

                    //sikeres kérés teljesítés után törlöm a kéréshez köthető tartalmat az oldalról
                    $("#keres_alert").remove();
                    $("#keres_input").remove();
                    $("#targy").removeAttr('disabled');
                    $("#kategoria").removeAttr('disabled');

                    //ezzel itt megszüntetem a lehetőséget hogy az adott teljesítő ismét töltsön fel az adott kéérésnek
                    //(az url modosításával megszüntetem a query stringet)
                    window.history.replaceState('data to be passed', 'Feltöltéseim', '/feltolteseim');

                    $('#alert').html("<div class='alert alert-success' role='alert'>"+ valasz.message +"</div>");
                    $('#alert').show();
                    
                }
                else {
                    
                    $('#alert').html("<div class='alert alert-danger' role='alert'>"+ valasz.message +"</div>");
                    $('#alert').show();
                }

            },	// success 
            error : function(xhr, status){
                alert("Sikertelen");
                $("#messageText_registration").text(status);

                $("#form-registration").bootstrapValidator('disableSubmitButtons', false);
                $("#messageBox_registration").fadeOut();
            }	// error
        }); // $.ajax	
    });//bootstrap_validators_end
    
    
});