$(document).ready(function () {

    pagination("eldontendo", "");

    aktualis_ev = aktualis_ev();
    

    $('#form-kereseim').bootstrapValidator({

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
            dokumentum_eve: {
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
            szovegdoboz: {
                validators: {
                    notEmpty: {
                        message: 'Nem határoztad meg a keresendő dokumentumot!'
                    },
                    stringLength: {
                        min: 15,
                        max: 300,
                        message: 'A megadott szöveg min:15 és max:300 karakterből állhat!'
                    }
                }
            }

        }// fields_end
    }).on('success.form.bv', function(e) { //itt végzem el az ürlap küldését
        
        e.preventDefault();

        $.ajax({
            url     : 'keres.php', //Target URL for JSON file
            type    : 'POST',
            data    : $("#form-kereseim :input").serialize(), 
            dataType: 'json',
            async: true, // true a követ kező utasítás végrehalytásra kerül annak ellenére hogy még nem felyződött be.
                        //false szinkron addig nem megy tovább amig be nem fejeződött a kérés
            cache:false,
            success : function(result, status, xhr){

                
                if (result.valid == true) {
                        
                    $("#form-kereseim :input").each(function() {
                        
                        if (this.type != "submit") {
                            $(this).val('');
                        }
                    });

                    $("#form-kereseim").data('bootstrapValidator').resetForm();

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


});