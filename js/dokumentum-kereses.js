$(document).ready(function () {

    
    pagination(window.location.search, "",$("#dokumentum_k_targy").val(),$("#dokumentum_k_kategoria").val(),$("#datum").val()); //az egész query stringet továbbítom hogy tudjom hogy mégis  melyik lapnak listázom

    aktualis_ev = aktualis_ev();
    

    $('#form-dokumentum-listazas').bootstrapValidator({

        fields: {
            datum: {
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
            }
        }// fields_end
    }).change( function(e) { //itt végzem el az ürlap küldését
        
        e.preventDefault();


        pagination(window.location.search, "",$("#dokumentum_k_targy").val(),$("#dokumentum_k_kategoria").val(),$("#datum").val());



    });


    $('#form-dokumentum-listazas').submit(function() { //ezzel itt megoldom hogy amikor megadom a dátumot akkor enter leütésre ne nyújtsa be a formot
        return false;
      });


});