
function proba(){ //az aktuális dátum évének kiszámolása hogy ne lehessen nygobb értéket megadni
    var pathArray = window.location.href.split('#');
    alert(pathArray[1]);
}
function aktualis_ev(){ //az aktuális dátum évének kiszámolása hogy ne lehessen nygobb értéket megadni
    var today = new Date();
    var yyyy = today.getFullYear(); 
    today = yyyy;
    return today;
}

$(document).ready(function () {

    
    $('#listazando').on('change', function() {
        $('#lista').submit();
      });
      

    $(":file").filestyle({buttonBefore: true});
    
    $(":file").filestyle('buttonText', 'Kiválaszt');


    $("li.disabled a").click(function() { //ezzel megakadályozom azt hogy ha disabled a nav ellem akkor se lehessen rányomni
        return false;
      });


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
            },//email_end
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
                        message: 'Nem évet adtál meg adtál meg!'
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
    });//bootstrap_validators_end
    


    $('#form-feltolteseim').on('submit', function(event) {

        

        event.preventDefault();

        var fd = new FormData();
        var files = $('#file')[0].files[0];
        
        fd.append('file',files);
        fd.append('targy',$('#targy').val());
        fd.append('kategoria',$('#kategoria').val());
        fd.append('dcim',$('#dcim').val());
        fd.append('oldalszam',$('#oldalszam').val());
        fd.append('dokumentum_eve',$('#dokumentum_eve').val());
        fd.append('forras',$('#forras').val());

        if ($("#form-feltolteseim").data('bootstrapValidator').isValid()) {
            // Use Ajax to submit form data
            
            
            $.ajax({
                url     : 'feltolt.php', //Target URL for JSON file
                type    : 'POST',
                data    : fd,
                contentType: false,
                processData: false,
               // dataType: 'json',
                success : function(result, status, xhr){
                    alert("Sikeres");
                    console.log(result.valid);

                    if (result.valid == true) {

                        $( "#elr" ).empty();
                        $( ".uzenet" ).show( "slow" );
                        
                    }
                    else {
                        $("#uzenet2").html(result.message2);
                        $( "#uzenet2" ).show( "slow" );
                        $("#uzenet3").html(result.message3);
                        $( "#uzenet3" ).show( "slow" );
                    }

                },	// success 
                error : function(xhr, status){
                    alert("Sikertelen");
                    $("#messageText_registration").text(status);

                    $("#form-registration").bootstrapValidator('disableSubmitButtons', false);
                    $("#messageBox_registration").fadeOut();
                }	// error
            }); // $.ajax	
        }	// if
    
    });  // submit
});