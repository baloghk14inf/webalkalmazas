$(document).ready(function()
{

    $('#form-login').bootstrapValidator({

        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            felhasznalonev: {

                validators: {
                     notEmpty: {
                          message : 'A felhasználónév megadása kötelező!'
                        },
                     different: {
                            field: 'jelszo',
                            message: 'Nem lehet ugyan az a felhasználónév és a jelszó!'
                        }
            }
            },//vezeteknev_end
            jelszo: {
                validators: {
                    notEmpty: {
                        message: 'A jelszó kötelező és nem lehet üres'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'A jelszónak 6 és 30 karakter hosszuságunak kell lennie'
                    }

                }

            }


        
        }// fields_end
    }).on('success.form.bv', function(e) { //itt végzem el az ürlap küldését
        
        e.preventDefault();

        $.ajax({
            url     : 'login.ajax.php', //Target URL for JSON file
            type    : 'POST',
            data    : $("#form-login :input").serialize(),
            cache   : false,
            dataType: 'json',
            async   : true,
            success : function(result, status, xhr){
                alert("Sikeres");
                

                if (result.valid == false) {

                    $('#alert').html("<div class='alert alert-danger' role='alert'>"+ result.message +"</div>");
                    $('#alert').show();
                }
                else {
                    //location.reload();
                    window.location.replace('/registration'); //ez jó lessz makd igy bejelentkezés után és igy már nem is tud majd visszalépni a login oldalára
                }
               
            },	// success 
            error : function(xhr, status){
                alert("Sikertelen");

            }	// error
        }); // $.ajax	
    });//bootstrap_validators_end


});// document_ready

